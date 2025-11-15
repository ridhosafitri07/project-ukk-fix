<?php

namespace App\Services;

use App\Models\Pengaduan;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class NotificationService
{
    /**
     * Get notifications for current user
     * Shows the latest status changes of their pengaduan
     */
    public static function getUserNotifications($limit = 10)
    {
        $user = Auth::user();
        if (!$user) {
            return [];
        }

        // Get pengaduan status changes for this user
        $pengaduans = Pengaduan::where('id_user', $user->id_user)
            ->orderBy('tgl_verifikasi', 'desc')
            ->orderBy('tgl_mulai_proses', 'desc')
            ->orderBy('tgl_selesai', 'desc')
            ->orderBy('tgl_pengajuan', 'desc')
            ->limit($limit)
            ->get();

        $notifications = [];

        foreach ($pengaduans as $pengaduan) {
            $notification = self::createNotificationFromPengaduan($pengaduan);
            if ($notification) {
                $notifications[] = $notification;
            }
        }

        return $notifications;
    }

    /**
     * Get count of unread notifications
     * We'll consider notifications from last 7 days as potentially unread
     */
    public static function getUnreadCount()
    {
        $user = Auth::user();
        if (!$user) {
            return 0;
        }

        $sevenDaysAgo = Carbon::now()->subDays(7);
        
        // Count pengaduan with recent updates
        $count = Pengaduan::where('id_user', $user->id_user)
            ->where(function ($query) use ($sevenDaysAgo) {
                $query->where('tgl_verifikasi', '>=', $sevenDaysAgo)
                    ->orWhere('tgl_mulai_proses', '>=', $sevenDaysAgo)
                    ->orWhere('tgl_selesai', '>=', $sevenDaysAgo);
            })
            ->whereIn('status', ['Disetujui', 'Diproses', 'Selesai', 'Ditolak'])
            ->count();

        return $count;
    }

    /**
     * Create a notification array from Pengaduan object
     */
    private static function createNotificationFromPengaduan(Pengaduan $pengaduan)
    {
        $status = $pengaduan->status;
        $timeago = '';
        $icon = 'fas fa-info-circle';
        $color = 'blue';
        $title = '';
        $message = '';

        // Determine notification type based on status and timestamps
        if ($pengaduan->status === 'Diajukan') {
            $title = 'Pengaduan Diajukan';
            $message = "'{$pengaduan->nama_pengaduan}' telah berhasil diajukan";
            $icon = 'fas fa-paper-plane';
            $color = 'blue';
            $timeago = self::timeAgo($pengaduan->tgl_pengajuan);
        } elseif ($pengaduan->status === 'Disetujui' && $pengaduan->tgl_verifikasi) {
            $title = 'Pengaduan Disetujui ✓';
            $message = "Pengaduan '{$pengaduan->nama_pengaduan}' telah disetujui oleh admin";
            $icon = 'fas fa-check-circle';
            $color = 'green';
            $timeago = self::timeAgo($pengaduan->tgl_verifikasi);
        } elseif ($pengaduan->status === 'Diproses' && $pengaduan->tgl_mulai_proses) {
            $title = 'Pengaduan Diproses 🔧';
            $message = "Pengaduan '{$pengaduan->nama_pengaduan}' sedang diproses oleh petugas";
            $icon = 'fas fa-cog';
            $color = 'amber';
            $timeago = self::timeAgo($pengaduan->tgl_mulai_proses);
        } elseif ($pengaduan->status === 'Selesai' && $pengaduan->tgl_selesai) {
            $title = 'Pengaduan Selesai! 🎉';
            $message = "Pengaduan '{$pengaduan->nama_pengaduan}' telah selesai ditangani";
            $icon = 'fas fa-flag-checkered';
            $color = 'emerald';
            $timeago = self::timeAgo($pengaduan->tgl_selesai);
        } elseif ($pengaduan->status === 'Ditolak') {
            $title = 'Pengaduan Ditolak';
            $message = "Pengaduan '{$pengaduan->nama_pengaduan}' telah ditolak. " . 
                      ($pengaduan->catatan_admin ? 'Alasan: ' . substr($pengaduan->catatan_admin, 0, 50) . '...' : '');
            $icon = 'fas fa-times-circle';
            $color = 'red';
            $timeago = self::timeAgo($pengaduan->tgl_pengajuan);
        } else {
            return null;
        }

        return [
            'id' => $pengaduan->id_pengaduan,
            'title' => $title,
            'message' => $message,
            'icon' => $icon,
            'color' => $color,
            'time' => $timeago,
            'status' => $status,
            'pengaduan_name' => $pengaduan->nama_pengaduan
        ];
    }

    /**
     * Calculate time ago string
     */
    private static function timeAgo($date)
    {
        if (!$date) {
            return 'Baru saja';
        }

        $carbon = Carbon::parse($date);
        $now = Carbon::now();
        $diff = $now->diffInSeconds($carbon);

        if ($diff < 60) {
            return 'Baru saja';
        } elseif ($diff < 3600) {
            $minutes = floor($diff / 60);
            return $minutes . ' menit lalu';
        } elseif ($diff < 86400) {
            $hours = floor($diff / 3600);
            return $hours . ' jam lalu';
        } elseif ($diff < 604800) {
            $days = floor($diff / 86400);
            return $days . ' hari lalu';
        } else {
            return $carbon->format('d M Y');
        }
    }
}
