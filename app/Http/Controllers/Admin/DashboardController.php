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
        $pendingItems = TemporaryItem::count(); // All temporary items are pending in pure transit concept

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

        // Get item status statistics (pure transit concept)
        $totalPendingItems = TemporaryItem::count();
        $itemStats = [
            'Menunggu Persetujuan' => $totalPendingItems
        ];

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

        // Recent item approvals disabled - using pure transit concept
        // Items are approved through pengaduan workflow, not tracked in temporary table
        
        // Sort and limit recent activities
        $recentActivities = $recentActivities->sortByDesc('created_at')->take(5);

        // Notifications for dashboard context
        $pendingPengaduan = Pengaduan::where('status', 'Pending')->count();
        $notifications = [
            [
                'type' => 'dashboard',
                'icon' => 'fas fa-tachometer-alt',
                'color' => 'purple',
                'title' => 'Dashboard Update',
                'message' => 'Sistem berjalan normal, semua metrik terupdate',
                'time' => 'Baru saja',
                'count' => 0
            ],
            [
                'type' => 'pengaduan', 
                'icon' => 'fas fa-clipboard-list',
                'color' => 'blue',
                'title' => 'Pengaduan Pending',
                'message' => $pendingPengaduan > 0 ? "Ada {$pendingPengaduan} pengaduan menunggu review" : 'Semua pengaduan telah diproses',
                'time' => '2 menit lalu',
                'count' => $pendingPengaduan
            ]
        ];

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalItems',
            'totalPengaduan',
            'pendingItems',
            'pengaduanStats',
            'itemStats',
            'recentActivities',
            'notifications'
        ));
    }
}