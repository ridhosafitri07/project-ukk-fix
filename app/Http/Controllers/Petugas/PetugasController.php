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
        
        $pengaduan = Pengaduan::with('user')
            ->where('id_petugas', $petugasId)
            ->whereIn('status', ['Disetujui', 'Diproses'])
            ->orderBy('tgl_pengajuan', 'desc')
            ->paginate(10);
        
        $statistics = [
            'total' => Pengaduan::where('id_petugas', $petugasId)
                ->whereIn('status', ['Disetujui', 'Diproses'])
                ->count(),
            'disetujui' => Pengaduan::where('id_petugas', $petugasId)
                ->where('status', 'Disetujui')
                ->count(),
            'diproses' => Pengaduan::where('id_petugas', $petugasId)
                ->where('status', 'Diproses')
                ->count(),
        ];
        
        return view('petugas.pengaduan.index', compact('pengaduan', 'statistics'));
    }
    
    public function pengaduanShow(Pengaduan $pengaduan)
    {
        $petugasId = $this->getPetugasId();
        
        if ($pengaduan->id_petugas != $petugasId) {
            return redirect()->route('petugas.pengaduan.index')
                ->with('error', 'Anda tidak memiliki akses ke pengaduan ini.');
        }
        
        $pengaduan->load(['user', 'temporary_items']);
        return view('petugas.pengaduan.show', compact('pengaduan'));
    }
    
    public function updateStatus(Request $request, Pengaduan $pengaduan)
    {
        $petugasId = $this->getPetugasId();
        
        if ($pengaduan->id_petugas != $petugasId) {
            return redirect()->route('petugas.pengaduan.index')
                ->with('error', 'Anda tidak memiliki akses ke pengaduan ini.');
        }
        
        $request->validate([
            'status' => 'required|in:Diproses,Selesai',
            'saran_petugas' => 'nullable|string|max:255'
        ]);
        
        DB::beginTransaction();
        try {
            $pengaduan->status = $request->status;
            if ($request->saran_petugas) {
                $pengaduan->saran_petugas = $request->saran_petugas;
            }
            
            if ($request->status === 'Selesai') {
                $pengaduan->tgl_selesai = now();
            }
            
            $pengaduan->save();
            
            DB::commit();
            return redirect()
                ->route('petugas.pengaduan.show', $pengaduan)
                ->with('success', 'Status pengaduan berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat memperbarui status');
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
                'id_petugas' => $petugasId,
                'nama_barang_baru' => $request->nama_barang_baru,
                'lokasi_barang_baru' => $request->lokasi_barang_baru,
                'alasan_permintaan' => $request->alasan_permintaan,
                'foto_kerusakan' => $fotoPath,
                'status_permintaan' => 'Menunggu Persetujuan',
                'tanggal_permintaan' => now()
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
        
        $riwayat = Pengaduan::with('user')
            ->where('id_petugas', $petugasId)
            ->where('status', 'Selesai')
            ->orderBy('tgl_selesai', 'desc')
            ->paginate(10);
        
        return view('petugas.riwayat.index', compact('riwayat'));
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