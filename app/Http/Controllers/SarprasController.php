<?php

namespace App\Http\Controllers;

use App\Models\TemporaryItem;
use App\Models\Pengaduan;
use Illuminate\Http\Request;

class SarprasController extends Controller
{
    public function index()
    {
        $totalPendingItems = TemporaryItem::count();
        $pendingPengaduan = Pengaduan::where('status', 'Pending')->count();
        
        $data = [
            'total_permintaan' => $totalPendingItems,
            'menunggu_persetujuan' => $totalPendingItems, // All items are pending
            'disetujui' => 0, // Approved items moved to master table
            'ditolak' => 0, // Rejected items deleted
            'permintaan_terbaru' => TemporaryItem::with(['pengaduan'])
                ->orderBy('tanggal_permintaan', 'desc')
                ->take(5)
                ->get(),
            // Notification data for sarpras context
            'notifications' => [
                [
                    'type' => 'sarpras',
                    'icon' => 'fas fa-box',
                    'color' => 'blue',
                    'title' => 'Permintaan Barang',
                    'message' => $totalPendingItems > 0 ? "Ada {$totalPendingItems} permintaan barang menunggu approval" : 'Tidak ada permintaan barang baru',
                    'time' => 'Baru saja',
                    'count' => $totalPendingItems
                ],
                [
                    'type' => 'pengaduan', 
                    'icon' => 'fas fa-clipboard-list',
                    'color' => 'green',
                    'title' => 'Pengaduan Pending',
                    'message' => $pendingPengaduan > 0 ? "Ada {$pendingPengaduan} pengaduan menunggu review" : 'Semua pengaduan telah diproses',
                    'time' => '5 menit lalu',
                    'count' => $pendingPengaduan
                ]
            ]
        ];

        return view('admin.sarpras.index', $data);
    }

    public function permintaanList()
    {
        // Redirect to pengaduan system - pure transit concept implementation
        return redirect()->route('admin.pengaduan.index')
            ->with('info', 'Managemen permintaan barang baru sekarang dikelola melalui sistem pengaduan.');
    }

    public function showPermintaan($id)
    {
        // Try to find the related pengaduan for this temporary item
        $tempItem = TemporaryItem::find($id);
        
        if ($tempItem && $tempItem->id_pengaduan) {
            return redirect()->route('admin.pengaduan.show', $tempItem->id_pengaduan)
                ->with('info', 'Permintaan barang baru dikelola melalui detail pengaduan.');
        }
        
        // If no related pengaduan found, redirect to pengaduan list
        return redirect()->route('admin.pengaduan.index')
            ->with('warning', 'Data permintaan tidak ditemukan. Silakan kelola melalui sistem pengaduan.');
    }

    public function updateStatus(Request $request, $id)
    {
        // DISABLED: This method uses deleted columns (status_permintaan, catatan_admin, tanggal_persetujuan)
        // Use AdminPengaduanController::approveTemporaryItem and rejectTemporaryItem instead
        
        return redirect()->back()->with('error', 'Please use the pengaduan approval system to manage item requests.');
        
        /* ORIGINAL CODE - DISABLED DUE TO COLUMN REMOVAL
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
        */
    }
}