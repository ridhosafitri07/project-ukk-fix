<?php

namespace App\Http\Controllers;

use App\Models\Pengaduan;
use App\Models\Lokasi;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class PengaduanController extends Controller
{
    public function index()
    {
        $pengaduans = Pengaduan::where('id_user', Auth::id())
            ->with('temporary_items')  // Load temporary items untuk badge di index
            ->orderBy('tgl_pengajuan', 'desc')
            ->get();
        return view('pengguna.pengaduan.index', compact('pengaduans'));
    }

    public function create()
    {
        // Ambil semua lokasi
        $lokasis = Lokasi::orderBy('nama_lokasi', 'asc')->get();
        
        // Ambil semua item beserta relasi lokasi-nya
        $items = Item::with('lokasis')->orderBy('nama_item', 'asc')->get();
        
        // Buat mapping item per lokasi untuk JavaScript
        $itemsByLokasi = [];
        foreach ($items as $item) {
            foreach ($item->lokasis as $lokasi) {
                if (!isset($itemsByLokasi[$lokasi->id_lokasi])) {
                    $itemsByLokasi[$lokasi->id_lokasi] = [];
                }
                $itemsByLokasi[$lokasi->id_lokasi][] = [
                    'id_item' => $item->id_item,
                    'nama_item' => $item->nama_item,
                    'deskripsi' => $item->deskripsi ?? ''
                ];
            }
        }
        
        return view('pengguna.pengaduan.create', compact('lokasis', 'items', 'itemsByLokasi'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_pengaduan' => 'required|string|max:200',
            'deskripsi' => 'required|string',
            'id_lokasi' => 'required|exists:lokasi,id_lokasi',
            'id_item' => 'nullable|exists:items,id_item',
            'nama_barang_baru' => 'nullable|string|max:200',
            'foto' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ], [
            'nama_pengaduan.required' => 'Judul pengaduan wajib diisi',
            'deskripsi.required' => 'Deskripsi wajib diisi',
            'id_lokasi.required' => 'Lokasi wajib diisi',
            'id_lokasi.exists' => 'Lokasi yang dipilih tidak valid',
            'id_item.exists' => 'Item/Barang yang dipilih tidak valid',
            'nama_barang_baru.max' => 'Nama barang baru maksimal 200 karakter',
            'foto.required' => 'Foto wajib diunggah',
            'foto.image' => 'File harus berupa gambar',
            'foto.mimes' => 'Format foto harus JPG, JPEG, atau PNG',
            'foto.max' => 'Ukuran foto maksimal 2MB'
        ]);

        // Either existing item or new item name must be provided
        if (empty($validated['id_item']) && empty(trim($validated['nama_barang_baru'] ?? ''))) {
            return back()->withInput()->withErrors(['id_item' => 'Pilih item yang tersedia atau isi Nama Barang Baru']);
        }

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $fotoPath = $file->storeAs('pengaduan', $fileName, 'public');
        }

        // Get lokasi name from id_lokasi
        $lokasi = Lokasi::find($validated['id_lokasi']);

        DB::beginTransaction();
        try {
            $pengaduan = Pengaduan::create([
                'nama_pengaduan' => $validated['nama_pengaduan'],
                'deskripsi' => $validated['deskripsi'],
                'lokasi' => $lokasi->nama_lokasi,
                'foto' => $fotoPath,
                'status' => 'Diajukan',
                'id_user' => Auth::id(),
                'id_item' => $validated['id_item'] ?? null,
                'tgl_pengajuan' => now()
            ]);

            if (empty($validated['id_item']) && !empty(trim($validated['nama_barang_baru'] ?? ''))) {
                \App\Models\TemporaryItem::create([
                    'id_pengaduan' => $pengaduan->id_pengaduan,
                    'nama_barang_baru' => $validated['nama_barang_baru'],
                    'lokasi_barang_baru' => $lokasi->nama_lokasi,
                    'alasan_permintaan' => $validated['deskripsi'],
                    'deskripsi_barang_baru' => $validated['deskripsi']
                ]);
            }

            DB::commit();
            return redirect()->route('pengaduan.index')
                ->with('success', 'Pengaduan berhasil diajukan');
        } catch (\Exception $e) {
            DB::rollBack();
            if ($fotoPath) {
                Storage::disk('public')->delete($fotoPath);
            }
            return back()->withInput()->with('error', 'Terjadi kesalahan saat menyimpan pengaduan: ' . $e->getMessage());
        }
    }

    public function show(Pengaduan $pengaduan)
    {
        if ($pengaduan->id_user !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        // Load temporary items untuk ditampilkan di view
        $pengaduan->load('temporary_items');
        
        return view('pengguna.pengaduan.show', compact('pengaduan'));
    }

    public function edit(Pengaduan $pengaduan)
    {
        if ($pengaduan->id_user !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        if ($pengaduan->status !== 'Diajukan') {
            return redirect()->route('pengaduan.show', $pengaduan)
                ->with('error', 'Pengaduan yang sudah diproses tidak dapat diubah.');
        }

        return view('pengguna.pengaduan.edit', compact('pengaduan'));
    }

    public function update(Request $request, Pengaduan $pengaduan)
    {
        if ($pengaduan->id_user !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        if ($pengaduan->status !== 'Diajukan') {
            return redirect()->route('pengaduan.show', $pengaduan)
                ->with('error', 'Pengaduan yang sudah diproses tidak dapat diubah.');
        }

        $validated = $request->validate([
            'nama_pengaduan' => 'required|string|max:200',
            'deskripsi' => 'required|string',
            'lokasi' => 'required|string|max:200',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ], [
            'nama_pengaduan.required' => 'Judul pengaduan wajib diisi',
            'deskripsi.required' => 'Deskripsi wajib diisi',
            'lokasi.required' => 'Lokasi wajib diisi',
            'foto.image' => 'File harus berupa gambar',
            'foto.mimes' => 'Format foto harus JPG, JPEG, atau PNG',
            'foto.max' => 'Ukuran foto maksimal 2MB'
        ]);

        // Update basic fields
        $pengaduan->nama_pengaduan = $validated['nama_pengaduan'];
        $pengaduan->deskripsi = $validated['deskripsi'];
        $pengaduan->lokasi = $validated['lokasi'];

        // Handle photo update
        if ($request->hasFile('foto')) {
            // Delete old photo if exists
            if ($pengaduan->foto && Storage::disk('public')->exists($pengaduan->foto)) {
                Storage::disk('public')->delete($pengaduan->foto);
            }
            
            // Store new photo
            $file = $request->file('foto');
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $fotoPath = $file->storeAs('pengaduan', $fileName, 'public');
            $pengaduan->foto = $fotoPath;
        }

        $pengaduan->save();

        return redirect()->route('pengaduan.show', $pengaduan)
            ->with('success', 'Pengaduan berhasil diperbarui');
    }

    public function destroy(Pengaduan $pengaduan)
    {
        if ($pengaduan->id_user !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        if ($pengaduan->status !== 'Diajukan') {
            return redirect()->route('pengaduan.show', $pengaduan)
                ->with('error', 'Pengaduan yang sudah diproses tidak dapat dihapus.');
        }

        // Delete photo if exists
        if ($pengaduan->foto && Storage::disk('public')->exists($pengaduan->foto)) {
            Storage::disk('public')->delete($pengaduan->foto);
        }

        $pengaduan->delete();

        return redirect()->route('pengaduan.index')
            ->with('success', 'Pengaduan berhasil dihapus');
    }

    // Riwayat for pengguna: show all completed and processed pengaduan with filters and export
    public function riwayatIndex()
    {
        $userId = Auth::id();

        // Show pengaduan with status: Diproses, Selesai, Ditolak (exclude Diajukan dan Disetujui)
        $query = Pengaduan::where('id_user', $userId)
            ->whereIn('status', ['Diproses', 'Selesai', 'Ditolak'])
            ->with('item', 'petugas'); // Eager load item dan petugas

        if ($dateFrom = request()->get('date_from')) {
            $query->whereDate('tgl_selesai', '>=', $dateFrom);
        }
        if ($dateTo = request()->get('date_to')) {
            $query->whereDate('tgl_selesai', '<=', $dateTo);
        }
        if ($lokasi = request()->get('lokasi')) {
            $query->where('lokasi', 'like', "%{$lokasi}%");
        }
        if ($q = request()->get('q')) {
            $query->where('nama_pengaduan', 'like', "%{$q}%");
        }

        $riwayat = $query->orderBy('tgl_selesai', 'desc')->paginate(10)->appends(request()->query());
        return view('pengguna.riwayat.index', compact('riwayat'));
    }

    public function riwayatShow(Pengaduan $pengaduan)
    {
        if ($pengaduan->id_user !== Auth::id()) {
            abort(403);
        }
        // Allow viewing pengaduan yang sudah diproses atau selesai
        if (!in_array($pengaduan->status, ['Diproses', 'Selesai', 'Ditolak'])) {
            return redirect()->route('pengguna.riwayat.index')->with('error', 'Pengaduan belum diproses');
        }
        $pengaduan->load('user', 'temporary_items');
        return view('pengguna.riwayat.show', compact('pengaduan'));
    }

    public function riwayatExport()
    {
        $userId = Auth::id();
        $query = Pengaduan::where('id_user', $userId)->whereIn('status', ['Diproses', 'Selesai', 'Ditolak']);

        if ($dateFrom = request()->get('date_from')) {
            $query->whereDate('tgl_selesai', '>=', $dateFrom);
        }
        if ($dateTo = request()->get('date_to')) {
            $query->whereDate('tgl_selesai', '<=', $dateTo);
        }
        if ($lokasi = request()->get('lokasi')) {
            $query->where('lokasi', 'like', "%{$lokasi}%");
        }
        if ($q = request()->get('q')) {
            $query->where('nama_pengaduan', 'like', "%{$q}%");
        }

        $items = $query->orderBy('tgl_selesai', 'desc')->get();

        $filename = 'riwayat_pengaduan_user_' . date('Ymd_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($items) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['ID', 'Tgl Pengajuan', 'Judul', 'Lokasi', 'Tgl Selesai', 'Petugas']);
            foreach ($items as $item) {
                $row = [
                    $item->id_pengaduan,
                    $item->tgl_pengajuan ? date('Y-m-d', strtotime($item->tgl_pengajuan)) : '',
                    $item->nama_pengaduan ?? '',
                    $item->lokasi ?? '',
                    $item->tgl_selesai ? date('Y-m-d', strtotime($item->tgl_selesai)) : '',
                    optional($item->petugas)->nama ?? '',
                ];
                fputcsv($out, $row);
            }
            fclose($out);
        };

        return response()->stream($callback, 200, $headers);
    }
}