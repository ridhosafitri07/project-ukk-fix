<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengaduan;
use App\Models\TemporaryItem;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->get('type', 'pengaduan');
        
        if ($type == 'pengaduan') {
            $query = Pengaduan::with(['user', 'petugas'])
                ->whereIn('status', ['Proses', 'Selesai']);
            
            // Filter by date range
            if ($request->filled('tanggal_dari')) {
                $query->whereDate('tgl_pengajuan', '>=', $request->tanggal_dari);
            }
            if ($request->filled('tanggal_sampai')) {
                $query->whereDate('tgl_pengajuan', '<=', $request->tanggal_sampai);
            }
            
            // Filter by status
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
            
            $data = $query->orderBy('tgl_pengajuan', 'desc')->paginate(15);
            
            // Statistics
            $stats = [
                'total_pengaduan' => Pengaduan::whereIn('status', ['Proses', 'Selesai'])->count(),
                'total_permintaan' => TemporaryItem::whereIn('status_permintaan', ['Disetujui', 'Ditolak'])->count(),
                'disetujui' => Pengaduan::where('status', 'Selesai')->count(),
                'ditolak' => 0, // Pengaduan tidak ada status ditolak
            ];
            
        } else {
            $query = TemporaryItem::with(['pengaduan.user', 'pengaduan.petugas'])
                ->whereIn('status_permintaan', ['Disetujui', 'Ditolak']);
            
            // Filter by date range
            if ($request->filled('tanggal_dari')) {
                $query->whereDate('tanggal_persetujuan', '>=', $request->tanggal_dari);
            }
            if ($request->filled('tanggal_sampai')) {
                $query->whereDate('tanggal_persetujuan', '<=', $request->tanggal_sampai);
            }
            
            // Filter by status
            if ($request->filled('status')) {
                $query->where('status_permintaan', $request->status);
            }
            
            $data = $query->orderBy('tanggal_persetujuan', 'desc')->paginate(15);
            
            // Statistics
            $stats = [
                'total_pengaduan' => Pengaduan::whereIn('status', ['Proses', 'Selesai'])->count(),
                'total_permintaan' => TemporaryItem::whereIn('status_permintaan', ['Disetujui', 'Ditolak'])->count(),
                'disetujui' => TemporaryItem::where('status_permintaan', 'Disetujui')->count(),
                'ditolak' => TemporaryItem::where('status_permintaan', 'Ditolak')->count(),
            ];
        }
        
        return view('admin.laporan.index', compact('data', 'type', 'stats'));
    }
    
    public function export(Request $request)
    {
        $type = $request->get('type', 'pengaduan');
        
        if ($type == 'pengaduan') {
            $query = Pengaduan::with(['user', 'petugas'])
                ->whereIn('status', ['Proses', 'Selesai']);
            
            // Apply filters
            if ($request->filled('tanggal_dari')) {
                $query->whereDate('tgl_pengajuan', '>=', $request->tanggal_dari);
            }
            if ($request->filled('tanggal_sampai')) {
                $query->whereDate('tgl_pengajuan', '<=', $request->tanggal_sampai);
            }
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
            
            $data = $query->orderBy('tgl_pengajuan', 'desc')->get();
            
            // Generate CSV
            $filename = 'laporan_pengaduan_' . date('Y-m-d_His') . '.csv';
            $headers = [
                'Content-Type' => 'text/csv; charset=utf-8',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];
            
            $callback = function() use ($data) {
                $file = fopen('php://output', 'w');
                
                // Add BOM for Excel UTF-8 support
                fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
                
                // Header CSV
                fputcsv($file, ['ID', 'Tanggal Pengajuan', 'Pelapor', 'Isi Laporan', 'Status', 'Petugas', 'Tanggal Selesai']);
                
                // Data rows
                foreach ($data as $item) {
                    fputcsv($file, [
                        $item->id_pengaduan,
                        $item->tgl_pengajuan,
                        $item->user->nama_pengguna ?? '-',
                        $item->isi_laporan,
                        $item->status,
                        $item->petugas->nama_lengkap ?? '-',
                        $item->tgl_selesai ?? '-',
                    ]);
                }
                
                fclose($file);
            };
            
        } else {
            $query = TemporaryItem::with(['pengaduan.user', 'pengaduan.petugas'])
                ->whereIn('status_permintaan', ['Disetujui', 'Ditolak']);
            
            // Apply filters
            if ($request->filled('tanggal_dari')) {
                $query->whereDate('tanggal_persetujuan', '>=', $request->tanggal_dari);
            }
            if ($request->filled('tanggal_sampai')) {
                $query->whereDate('tanggal_persetujuan', '<=', $request->tanggal_sampai);
            }
            if ($request->filled('status')) {
                $query->where('status_permintaan', $request->status);
            }
            
            $data = $query->orderBy('tanggal_persetujuan', 'desc')->get();
            
            // Generate CSV
            $filename = 'laporan_permintaan_barang_' . date('Y-m-d_His') . '.csv';
            $headers = [
                'Content-Type' => 'text/csv; charset=utf-8',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];
            
            $callback = function() use ($data) {
                $file = fopen('php://output', 'w');
                
                // Add BOM for Excel UTF-8 support
                fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
                
                // Header CSV
                fputcsv($file, ['ID', 'Tanggal Permintaan', 'Barang', 'Lokasi', 'Alasan', 'Pemohon', 'Status', 'Tanggal Persetujuan', 'Disetujui Oleh']);
                
                // Data rows
                foreach ($data as $item) {
                    fputcsv($file, [
                        $item->id_item,
                        $item->tanggal_permintaan ?? '-',
                        $item->nama_barang_baru ?? '-',
                        $item->lokasi_barang_baru ?? '-',
                        $item->alasan_permintaan ?? '-',
                        $item->pengaduan->user->nama_pengguna ?? '-',
                        $item->status_permintaan,
                        $item->tanggal_persetujuan ?? '-',
                        $item->pengaduan->petugas->nama_lengkap ?? '-',
                    ]);
                }
                
                fclose($file);
            };
        }
        
        return response()->stream($callback, 200, $headers);
    }
}
