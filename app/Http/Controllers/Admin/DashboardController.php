<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Item;
use App\Models\Pengaduan;
use App\Models\TemporaryItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Get total counts
        $totalUsers = User::count();
        $totalItems = Item::count();
        $totalPengaduan = Pengaduan::count();
        $pendingItems = TemporaryItem::where('status_permintaan', 'Menunggu Persetujuan')->count();

        // Get monthly pengaduan statistics
        $pengaduanStats = Pengaduan::select(
            DB::raw('MONTH(tgl_pengajuan) as month'),
            DB::raw('COUNT(*) as total')
        )
        ->whereYear('tgl_pengajuan', date('Y'))
        ->groupBy('month')
        ->orderBy('month')
        ->get()
        ->pluck('total', 'month')
        ->toArray();

        // Get item status statistics
        $itemStats = TemporaryItem::select('status_permintaan', DB::raw('COUNT(*) as total'))
            ->groupBy('status_permintaan')
            ->get()
            ->pluck('total', 'status_permintaan')
            ->toArray();

        // Get recent activities
        $recentActivities = collect();

        // Get recent pengaduan
        $recentPengaduan = Pengaduan::with(['user'])
            ->orderBy('tgl_pengajuan', 'desc')
            ->take(5)
            ->get()
            ->map(function ($pengaduan) {
                return (object)[
                    'type' => 'pengaduan',
                    'description' => "Pengaduan baru dari {$pengaduan->user->nama_pengguna}",
                    'created_at' => $pengaduan->tgl_pengajuan
                ];
            });
        $recentActivities = $recentActivities->concat($recentPengaduan);

        // Get recent item approvals
        $recentApprovals = TemporaryItem::where('status_permintaan', 'Disetujui')
            ->orderBy('tanggal_persetujuan', 'desc')
            ->take(5)
            ->get()
            ->map(function ($item) {
                return (object)[
                    'type' => 'approval',
                    'description' => "Item {$item->nama_barang_baru} telah disetujui",
                    'created_at' => $item->tanggal_persetujuan
                ];
            });
        $recentActivities = $recentActivities->concat($recentApprovals)
            ->sortByDesc('created_at')
            ->take(5);

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalItems',
            'totalPengaduan',
            'pendingItems',
            'pengaduanStats',
            'itemStats',
            'recentActivities'
        ));
    }
}