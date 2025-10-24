<?php

namespace App\Http\Controllers;

use App\Models\Pengaduan;
use App\Models\Petugas;
use App\Models\TemporaryItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminPengaduanController extends Controller
{
    public function index()
    {
        $pengaduan = Pengaduan::with('user', 'petugas')
            ->orderBy('tgl_pengajuan', 'desc')
            ->paginate(10);

        $statistics = [
            'total' => Pengaduan::count(),
            'diajukan' => Pengaduan::where('status', 'Diajukan')->count(),
            'diproses' => Pengaduan::whereIn('status', ['Disetujui', 'Diproses'])->count(),
            'selesai' => Pengaduan::where('status', 'Selesai')->count(),
            'ditolak' => Pengaduan::where('status', 'Ditolak')->count(),
        ];

        return view('admin.pengaduan.index', compact('pengaduan', 'statistics'));
    }

    public function show(Pengaduan $pengaduan)
    {
        $pengaduan->load(['user', 'petugas', 'temporary_items']);
        
        // Ambil petugas dari tabel user dan join dengan tabel petugas
        $petugasList = Petugas::join('user', 'petugas.id_user', '=', 'user.id_user')
            ->where('user.role', 'petugas')
            ->select('petugas.*', 'user.username', 'user.nama_pengguna', 'user.role')
            ->orderBy('petugas.nama', 'asc')
            ->get();
        
        return view('admin.pengaduan.show', compact('pengaduan', 'petugasList'));
    }

    public function updateStatus(Request $request, Pengaduan $pengaduan)
    {
        $validationRules = [
            'status' => 'required|in:Disetujui,Ditolak,Diproses,Selesai',
            'catatan_admin' => 'required|string|max:255'
        ];
        
        if ($request->status === 'Disetujui') {
            $validationRules['id_petugas'] = 'required|exists:petugas,id_petugas';
            
            // Validasi tambahan untuk memastikan petugas yang dipilih sesuai
            $petugas = Petugas::find($request->id_petugas);
            if (!$petugas) {
                return back()->with('error', 'Petugas tidak ditemukan!');
            }
        }
        
        $request->validate($validationRules);

        DB::beginTransaction();
        try {
            $pengaduan->status = $request->status;
            $pengaduan->catatan_admin = $request->catatan_admin;
            
            if ($request->status === 'Disetujui') {
                $pengaduan->tgl_verifikasi = now();
                $pengaduan->id_petugas = $request->id_petugas;
            } elseif ($request->status === 'Selesai') {
                $pengaduan->tgl_selesai = now();
            }
            
            $pengaduan->save();

            if ($request->status === 'Disetujui' && $pengaduan->temporary_items->count() > 0) {
                foreach ($pengaduan->temporary_items as $item) {
                    $item->status_permintaan = 'Disetujui';
                    $item->tanggal_persetujuan = now();
                    $item->catatan_admin = $request->catatan_admin;
                    $item->save();
                }
            }

            DB::commit();
            
            $message = 'Status pengaduan berhasil diperbarui';
            if ($request->status === 'Disetujui') {
                $petugas = Petugas::find($request->id_petugas);
                $message .= ' dan ditugaskan ke ' . $petugas->nama;
            }
            
            return redirect()
                ->route('admin.pengaduan.show', $pengaduan)
                ->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat memperbarui status: ' . $e->getMessage());
        }
    }
}
