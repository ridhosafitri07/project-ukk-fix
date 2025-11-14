<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Pengaduan;
use App\Models\Petugas;
use App\Models\TemporaryItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PetugasController extends Controller
{
    private function getPetugasId()
    {
        $user = Auth::user();
        $petugas = Petugas::where('id_user', $user->id_user)->first();
        
        if (!$petugas) {
            $petugas = Petugas::where('nama', $user->nama_pengguna)->first();
        }
        
        return $petugas ? $petugas->id_petugas : null;
    }
    
    public function dashboard()
    {
        $petugasId = $this->getPetugasId();
        
        if (!$petugasId) {
            return redirect()->back()->with('error', 'Akun petugas tidak ditemukan. Hubungi administrator.');
        }
        
        $totalTugas = Pengaduan::where('id_petugas', $petugasId)
            ->whereIn('status', ['Disetujui', 'Diproses', 'Selesai'])
            ->count();
            
        $tugasAktif = Pengaduan::where('id_petugas', $petugasId)
            ->where('status', 'Diproses')
            ->count();
            
        $tugasSelesai = Pengaduan::where('id_petugas', $petugasId)
            ->where('status', 'Selesai')
            ->whereMonth('tgl_selesai', date('m'))
            ->whereYear('tgl_selesai', date('Y'))
            ->count();
            
        $perluTindakan = Pengaduan::where('id_petugas', $petugasId)
            ->where('status', 'Disetujui')
            ->count();
        
        $totalAll = $tugasAktif + $tugasSelesai;
        $completionRate = $totalAll > 0 ? round(($tugasSelesai / $totalAll) * 100) : 0;
        
        $tugasTerbaru = Pengaduan::with('user')
            ->where('id_petugas', $petugasId)
            ->whereIn('status', ['Disetujui', 'Diproses'])
            ->orderBy('tgl_pengajuan', 'desc')
            ->take(5)
            ->get();
        
        return view('petugas.dashboard', compact(
            'totalTugas',
            'tugasAktif',
            'tugasSelesai',
            'perluTindakan',
            'completionRate',
            'tugasTerbaru'
        ));
    }
    
    public function pengaduanIndex()
    {
        $petugasId = $this->getPetugasId();
        
        if (!$petugasId) {
            return redirect()->back()->with('error', 'Akun petugas tidak ditemukan. Hubungi administrator.');
        }
        
        // Petugas should only see complaints assigned to them (Pengaduan Saya)
        $pengaduanSaya = Pengaduan::with('user')
            ->where('id_petugas', $petugasId)
            ->whereIn('status', ['Disetujui', 'Diproses'])
            ->orderBy('tgl_pengajuan', 'desc')
            ->paginate(10);

        $statistics = [
            'tugas_saya' => Pengaduan::where('id_petugas', $petugasId)
                ->whereIn('status', ['Disetujui', 'Diproses'])
                ->count(),
            'diproses' => Pengaduan::where('id_petugas', $petugasId)
                ->where('status', 'Diproses')
                ->count(),
            'selesai' => Pengaduan::where('id_petugas', $petugasId)
                ->where('status', 'Selesai')
                ->count(),
        ];

        return view('petugas.pengaduan.index', compact('pengaduanSaya', 'statistics'));
    }
    
    public function pengaduanShow(Pengaduan $pengaduan)
    {
        $petugasId = $this->getPetugasId();
        
    // Petugas dapat mengakses pengaduan ketika:
    // - Sudah Disetujui dan belum ada petugas yang mengambilnya (tersedia untuk diambil)
    // - Sudah ditugaskan ke petugas tersebut
    $canAccess = ($pengaduan->status === 'Disetujui' && !$pengaduan->id_petugas) ||
             ($pengaduan->id_petugas == $petugasId);
        
        if (!$canAccess) {
            return redirect()->route('petugas.pengaduan.index')
                ->with('error', 'Anda tidak memiliki akses ke pengaduan ini.');
        }
        
        $pengaduan->load(['user', 'temporary_items']);
        return view('petugas.pengaduan.show', compact('pengaduan'));
    }
    
    public function updateStatus(Request $request, Pengaduan $pengaduan)
    {
        $petugasId = $this->getPetugasId();
        
        // Validasi transisi status
        $currentStatus = $pengaduan->status;
        $newStatus = $request->status;
        
        // Aturan transisi status untuk petugas:
        // Petugas TIDAK BISA menyetujui atau menolak aduan yang berstatus 'Diajukan'.
        // Petugas hanya boleh:
        // Disetujui -> Diproses/Selesai
        // Diproses -> Selesai

        if ($currentStatus === 'Diajukan') {
            // Petugas cannot act on 'Diajukan' status
            return back()->with('error', 'Anda tidak dapat menyetujui atau menolak pengaduan. Silakan menunggu keputusan Admin.');
        }
        
        if ($currentStatus === 'Disetujui' && !in_array($newStatus, ['Diproses', 'Selesai'])) {
            return back()->with('error', 'Pengaduan yang sudah disetujui hanya bisa dimulai proses atau diselesaikan!');
        }
        
        if ($currentStatus === 'Diproses' && $newStatus !== 'Selesai') {
            return back()->with('error', 'Pengaduan yang sedang diproses hanya bisa diselesaikan!');
        }
        
        // Untuk Diproses/Selesai, harus cek ownership
        if (in_array($newStatus, ['Diproses', 'Selesai']) && 
            $pengaduan->id_petugas && 
            $pengaduan->id_petugas != $petugasId) {
            return redirect()->route('petugas.pengaduan.index')
                ->with('error', 'Anda tidak memiliki akses ke pengaduan ini.');
        }
        
        $request->validate([
            'status' => 'required|in:Diproses,Selesai',
            'saran_petugas' => 'nullable|string|max:500'
        ], [
            'status.required' => 'Status harus dipilih',
            'status.in' => 'Status tidak valid',
            'saran_petugas.max' => 'Saran petugas maksimal 500 karakter',
        ]);
        
        DB::beginTransaction();
        try {
            if ($request->saran_petugas) {
                $pengaduan->saran_petugas = $request->saran_petugas;
            }
            
            // STAGE 2: Mulai Proses (dari status Disetujui)
            elseif ($newStatus === 'Diproses') {
                $pengaduan->status = 'Diproses';
                $pengaduan->tgl_mulai_proses = now();
                
                // PENTING: Kolom petugas baru diisi saat klik "Mulai Proses"
                $pengaduan->id_petugas = $petugasId;
                
                $petugasNama = Auth::user()->nama_pengguna;
                $message = "Proses perbaikan telah dimulai oleh {$petugasNama}. Pengaduan sekarang ditangani oleh Anda.";
            }
            
            // STAGE 2: Selesai (dari status Disetujui atau Diproses)
            elseif ($newStatus === 'Selesai') {
                $pengaduan->status = 'Selesai';
                $pengaduan->tgl_selesai = now();
                
                // Jika langsung selesai dari Disetujui (tanpa proses), isi kolom petugas
                if ($currentStatus === 'Disetujui' && !$pengaduan->id_petugas) {
                    $pengaduan->id_petugas = $petugasId;
                }
                
                $message = 'Pengaduan telah diselesaikan.';
            }
            
            $pengaduan->save();
            
            DB::commit();
            return redirect()
                ->route('petugas.pengaduan.show', $pengaduan)
                ->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat memperbarui status: ' . $e->getMessage());
        }
    }
    
    // ===== FITUR BARU: ITEM REQUEST =====
    
    public function showItemRequestForm(Pengaduan $pengaduan)
    {
        $petugasId = $this->getPetugasId();
        
        if ($pengaduan->id_petugas != $petugasId) {
            return redirect()->route('petugas.pengaduan.index')
                ->with('error', 'Anda tidak memiliki akses ke pengaduan ini.');
        }
        
        return view('petugas.item-request.create', compact('pengaduan'));
    }
    
    public function storeItemRequest(Request $request, Pengaduan $pengaduan)
    {
        $petugasId = $this->getPetugasId();
        
        if ($pengaduan->id_petugas != $petugasId) {
            return redirect()->route('petugas.pengaduan.index')
                ->with('error', 'Anda tidak memiliki akses ke pengaduan ini.');
        }
        
        $request->validate([
            'nama_barang_baru' => 'required|string|max:200',
            'lokasi_barang_baru' => 'required|string|max:200',
            'alasan_permintaan' => 'required|string',
            'foto_kerusakan' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);
        
        DB::beginTransaction();
        try {
            // Upload foto
            $fotoPath = null;
            if ($request->hasFile('foto_kerusakan')) {
                $fotoPath = $request->file('foto_kerusakan')->store('public/item-requests');
                $fotoPath = str_replace('public/', '', $fotoPath);
            }
            
            // Create item request
            TemporaryItem::create([
                'id_pengaduan' => $pengaduan->id_pengaduan,
                'nama_barang_baru' => $request->nama_barang_baru,
                'lokasi_barang_baru' => $request->lokasi_barang_baru,
                'alasan_permintaan' => $request->alasan_permintaan,
                'deskripsi_barang_baru' => $request->alasan_permintaan
            ]);
            
            DB::commit();
            return redirect()
                ->route('petugas.pengaduan.show', $pengaduan)
                ->with('success', 'Permintaan barang berhasil diajukan dan menunggu persetujuan admin');
        } catch (\Exception $e) {
            DB::rollBack();
            if ($fotoPath) {
                Storage::delete('public/' . $fotoPath);
            }
            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    public function riwayatIndex()
    {
        $petugasId = $this->getPetugasId();
        
        if (!$petugasId) {
            return redirect()->back()->with('error', 'Akun petugas tidak ditemukan. Hubungi administrator.');
        }
        
        // Apply optional filters from query string
        $query = Pengaduan::with('user')
            ->where('id_petugas', $petugasId)
            ->where('status', 'Selesai');

        // Date range filter: date_from, date_to (format: yyyy-mm-dd)
        if ($dateFrom = request()->get('date_from')) {
            $query->whereDate('tgl_selesai', '>=', $dateFrom);
        }
        if ($dateTo = request()->get('date_to')) {
            $query->whereDate('tgl_selesai', '<=', $dateTo);
        }

        // Lokasi filter (partial match)
        if ($lokasi = request()->get('lokasi')) {
            $query->where('lokasi', 'like', "%{$lokasi}%");
        }

        // Keyword search in judul or pengadu name
        if ($q = request()->get('q')) {
            $query->where(function ($sub) use ($q) {
                $sub->where('nama_pengaduan', 'like', "%{$q}%")
                    ->orWhereHas('user', function ($u) use ($q) {
                        $u->where('nama_pengguna', 'like', "%{$q}%");
                    });
            });
        }

        $riwayat = $query->orderBy('tgl_selesai', 'desc')->paginate(10)->appends(request()->query());
        
        return view('petugas.riwayat.index', compact('riwayat'));
    }

    /**
     * Export filtered riwayat as CSV
     */
    public function riwayatExport()
    {
        $petugasId = $this->getPetugasId();
        $query = Pengaduan::with('user')
            ->where('id_petugas', $petugasId)
            ->where('status', 'Selesai');

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
            $query->where(function ($sub) use ($q) {
                $sub->where('nama_pengaduan', 'like', "%{$q}%")
                    ->orWhereHas('user', function ($u) use ($q) {
                        $u->where('nama_pengguna', 'like', "%{$q}%");
                    });
            });
        }

        $items = $query->orderBy('tgl_selesai', 'desc')->get();

        $filename = 'riwayat_pengaduan_' . date('Ymd_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($items) {
            $out = fopen('php://output', 'w');
            // Header
            fputcsv($out, ['ID', 'Tgl Pengajuan', 'Pengadu', 'Judul', 'Lokasi', 'Tgl Selesai', 'Petugas']);

            foreach ($items as $item) {
                $row = [
                    $item->id_pengaduan,
                    $item->tgl_pengajuan ? date('Y-m-d', strtotime($item->tgl_pengajuan)) : '',
                    $item->user->nama_pengguna ?? '',
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
    
    public function riwayatShow(Pengaduan $pengaduan)
    {
        $petugasId = $this->getPetugasId();
        
        if ($pengaduan->id_petugas != $petugasId) {
            return redirect()->route('petugas.riwayat.index')
                ->with('error', 'Anda tidak memiliki akses ke pengaduan ini.');
        }
        
        if ($pengaduan->status !== 'Selesai') {
            return redirect()->route('petugas.riwayat.index')
                ->with('error', 'Pengaduan ini belum selesai');
        }
        
        $pengaduan->load('user');
        return view('petugas.riwayat.show', compact('pengaduan'));
    }
}