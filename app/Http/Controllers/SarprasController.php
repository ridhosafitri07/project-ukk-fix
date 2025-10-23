<?php

namespace App\Http\Controllers;

use App\Models\TemporaryItem;
use App\Models\Pengaduan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SarprasController extends Controller
{
    public function index()
    {
        $data = [
            'total_permintaan' => TemporaryItem::count(),
            'menunggu_persetujuan' => TemporaryItem::where('status_permintaan', 'Menunggu Persetujuan')->count(),
            'disetujui' => TemporaryItem::where('status_permintaan', 'Disetujui')->count(),
            'ditolak' => TemporaryItem::where('status_permintaan', 'Ditolak')->count(),
            'permintaan_terbaru' => TemporaryItem::with('pengaduan')
                ->orderBy('tanggal_permintaan', 'desc')
                ->take(5)
                ->get()
        ];

        return view('admin.sarpras.index', $data);
    }

    public function permintaanList()
    {
        $permintaan = TemporaryItem::with('pengaduan')
            ->orderBy('tanggal_permintaan', 'desc')
            ->paginate(10);

        return view('admin.sarpras.permintaan-list', compact('permintaan'));
    }

    public function showPermintaan($id)
    {
        $permintaan = TemporaryItem::with('pengaduan')->findOrFail($id);
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

        // Update status pengaduan terkait
        $pengaduan = Pengaduan::find($permintaan->id_pengaduan);
        if ($pengaduan) {
            $pengaduan->status = $request->status === 'Disetujui' ? 'Diproses' : 'Ditolak';
            $pengaduan->save();
        }

        return redirect()
            ->route('admin.sarpras.permintaan-list')
            ->with('success', 'Status permintaan berhasil diperbarui');
    }

    public function history()
    {
        $history = TemporaryItem::with('pengaduan')
            ->whereIn('status_permintaan', ['Disetujui', 'Ditolak'])
            ->orderBy('tanggal_persetujuan', 'desc')
            ->paginate(10);

        return view('admin.sarpras.history', compact('history'));
    }

    public function exportHistory()
    {
        $data = TemporaryItem::with('pengaduan')
            ->whereIn('status_permintaan', ['Disetujui', 'Ditolak'])
            ->orderBy('tanggal_persetujuan', 'desc')
            ->get();

        // Logic untuk export ke Excel/PDF bisa ditambahkan di sini

        return back()->with('info', 'Fitur export akan segera tersedia');
    }
}