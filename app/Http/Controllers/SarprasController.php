<?php

namespace App\Http\Controllers;

use App\Models\TemporaryItem;
use App\Models\Pengaduan;
use Illuminate\Http\Request;

class SarprasController extends Controller
{
    public function index()
    {
        $data = [
            'total_permintaan' => TemporaryItem::count(),
            'menunggu_persetujuan' => TemporaryItem::where('status_permintaan', 'Menunggu Persetujuan')->count(),
            'disetujui' => TemporaryItem::where('status_permintaan', 'Disetujui')->count(),
            'ditolak' => TemporaryItem::where('status_permintaan', 'Ditolak')->count(),
            'permintaan_terbaru' => TemporaryItem::with(['pengaduan', 'pengaduan.petugas'])
                ->orderBy('tanggal_permintaan', 'desc')
                ->take(5)
                ->get()
        ];

        return view('admin.sarpras.index', $data);
    }

    public function permintaanList()
    {
        $permintaan = TemporaryItem::with(['pengaduan', 'pengaduan.petugas'])
            ->orderBy('tanggal_permintaan', 'desc')
            ->paginate(10);

        return view('admin.sarpras.permintaan-list', compact('permintaan'));
    }

    public function showPermintaan($id)
    {
        $permintaan = TemporaryItem::with(['pengaduan', 'pengaduan.petugas', 'pengaduan.user'])
            ->findOrFail($id);
        return view('admin.sarpras.show-permintaan', compact('permintaan'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Disetujui,Ditolak',
            'catatan_admin' => 'required|string|max:255'
        ]);

        $permintaan = TemporaryItem::findOrFail($id);
        $permintaan->status_permintaan = $request->status;
        $permintaan->catatan_admin = $request->catatan_admin;
        $permintaan->tanggal_persetujuan = now();
        $permintaan->save();

        // Jika disetujui, update status pengaduan terkait menjadi Diproses
        if ($request->status === 'Disetujui' && $permintaan->id_pengaduan) {
            $pengaduan = Pengaduan::find($permintaan->id_pengaduan);
            if ($pengaduan && $pengaduan->status == 'Disetujui') {
                $pengaduan->status = 'Diproses';
                $pengaduan->save();
            }
        }

        return redirect()
            ->route('admin.sarpras.show-permintaan', ['id' => $id])
            ->with('success', 'Status permintaan berhasil diperbarui');
    }
}