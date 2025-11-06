<?php

namespace App\Http\Controllers;

use App\Models\Pengaduan;
use App\Models\Lokasi;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PengaduanController extends Controller
{
    public function index(Request $request)
    {
        $query = Pengaduan::where('id_user', Auth::id());
        
        // Filter by status if provided
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        
        $pengaduans = $query->orderBy('tgl_pengajuan', 'desc')->get();
        
        return view('pengguna.pengaduan.index', compact('pengaduans'));
    }

    public function create()
    {
        $lokasis = Lokasi::all();
        return view('pengguna.pengaduan.create', compact('lokasis'));
    }

    public function getItemsByLokasi($id_lokasi)
    {
        try {
            \Log::info("Fetching items for lokasi ID: {$id_lokasi}");
            
            $lokasi = Lokasi::with('items')->find($id_lokasi);
            
            if (!$lokasi) {
                \Log::warning("Lokasi not found: {$id_lokasi}");
                return response()->json([
                    'success' => false,
                    'message' => 'Lokasi tidak ditemukan',
                    'items' => []
                ], 404);
            }
            
            // Get items and fix encoding issues
            $items = $lokasi->items->map(function($item) {
                return [
                    'id_item' => $item->id_item,
                    'nama_item' => mb_convert_encoding($item->nama_item ?? '', 'UTF-8', 'UTF-8'),
                    'deskripsi' => mb_convert_encoding($item->deskripsi ?? '', 'UTF-8', 'UTF-8'),
                    'lokasi' => mb_convert_encoding($item->lokasi ?? '', 'UTF-8', 'UTF-8'),
                ];
            });
            
            \Log::info("Found {$items->count()} items for lokasi {$id_lokasi}");
            
            return response()->json([
                'success' => true,
                'items' => $items,
                'lokasi_nama' => mb_convert_encoding($lokasi->nama_lokasi ?? '', 'UTF-8', 'UTF-8')
            ]);
            
        } catch (\Exception $e) {
            \Log::error("Error fetching items for lokasi {$id_lokasi}: " . $e->getMessage());
            \Log::error($e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memuat data',
                'items' => [],
                'error_detail' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_pengaduan' => 'required|string|max:200',
            'deskripsi' => 'required|string',
            'id_lokasi' => 'required|exists:lokasi,id_lokasi',
            'id_item' => 'required|exists:items,id_item',
            'foto' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ], [
            'nama_pengaduan.required' => 'Judul pengaduan wajib diisi',
            'deskripsi.required' => 'Deskripsi wajib diisi',
            'id_lokasi.required' => 'Lokasi wajib dipilih',
            'id_lokasi.exists' => 'Lokasi tidak valid',
            'id_item.required' => 'Item/Barang wajib dipilih',
            'id_item.exists' => 'Item/Barang tidak valid',
            'foto.required' => 'Foto wajib diunggah',
            'foto.image' => 'File harus berupa gambar',
            'foto.mimes' => 'Format foto harus JPG, JPEG, atau PNG',
            'foto.max' => 'Ukuran foto maksimal 2MB'
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            
            // Generate unique filename
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            
            // Store with public visibility
            $fotoPath = $file->storeAs('pengaduan', $fileName, 'public');
        }

        // Get nama lokasi untuk field lokasi
        $lokasi = Lokasi::find($validated['id_lokasi']);

        Pengaduan::create([
            'nama_pengaduan' => $validated['nama_pengaduan'],
            'deskripsi' => $validated['deskripsi'],
            'lokasi' => $lokasi->nama_lokasi,
            'id_item' => $validated['id_item'],
            'foto' => $fotoPath,
            'status' => 'Diajukan',
            'id_user' => Auth::id(),
            'tgl_pengajuan' => now()
        ]);

        return redirect()->route('pengaduan.index')
            ->with('success', 'Pengaduan berhasil diajukan');
    }

    public function show(Pengaduan $pengaduan)
    {
        if ($pengaduan->id_user !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
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

        $lokasis = Lokasi::all();
        
        // Get current lokasi id dari nama lokasi
        $currentLokasi = Lokasi::where('nama_lokasi', $pengaduan->lokasi)->first();
        
        return view('pengguna.pengaduan.edit', compact('pengaduan', 'lokasis', 'currentLokasi'));
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
            'id_lokasi' => 'required|exists:lokasi,id_lokasi',
            'id_item' => 'required|exists:items,id_item',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ], [
            'nama_pengaduan.required' => 'Judul pengaduan wajib diisi',
            'deskripsi.required' => 'Deskripsi wajib diisi',
            'id_lokasi.required' => 'Lokasi wajib dipilih',
            'id_lokasi.exists' => 'Lokasi tidak valid',
            'id_item.required' => 'Item/Barang wajib dipilih',
            'id_item.exists' => 'Item/Barang tidak valid',
            'foto.image' => 'File harus berupa gambar',
            'foto.mimes' => 'Format foto harus JPG, JPEG, atau PNG',
            'foto.max' => 'Ukuran foto maksimal 2MB'
        ]);

        // Get nama lokasi untuk field lokasi
        $lokasi = Lokasi::find($validated['id_lokasi']);
        
        // Update basic fields
        $pengaduan->nama_pengaduan = $validated['nama_pengaduan'];
        $pengaduan->deskripsi = $validated['deskripsi'];
        $pengaduan->lokasi = $lokasi->nama_lokasi;
        $pengaduan->id_item = $validated['id_item'];

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
}