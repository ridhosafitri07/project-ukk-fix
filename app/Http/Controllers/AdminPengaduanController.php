<?php

namespace App\Http\Controllers;

use App\Models\Pengaduan;
use App\Models\Petugas;
use App\Models\TemporaryItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class AdminPengaduanController extends Controller
{
    public function index(Request $request)
    {
        $query = Pengaduan::with('user', 'petugas');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('tanggal_dari')) {
            $query->whereDate('tgl_pengajuan', '>=', $request->tanggal_dari);
        }
        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('tgl_pengajuan', '<=', $request->tanggal_sampai);
        }

        // Filter by lokasi
        if ($request->filled('lokasi')) {
            $query->where('lokasi', 'LIKE', '%' . $request->lokasi . '%');
        }

        // Filter by petugas  
        if ($request->filled('petugas')) {
            $petugasFilter = $request->petugas;
            
            if ($petugasFilter === 'belum_ditugaskan') {
                // Belum ditugaskan: tidak ada petugas  
                $query->whereNull('id_petugas');
            } elseif (str_starts_with($petugasFilter, 'petugas_')) {
                // Filter by petugas
                $idPetugas = str_replace('petugas_', '', $petugasFilter);
                $query->where('id_petugas', $idPetugas);
            }
        }

        $pengaduan = $query->orderBy('tgl_pengajuan', 'desc')->paginate(10);

        $statistics = [
            'total' => Pengaduan::count(),
            'diajukan' => Pengaduan::where('status', 'Diajukan')->count(),
            'diproses' => Pengaduan::whereIn('status', ['Disetujui', 'Diproses'])->count(),
            'selesai' => Pengaduan::where('status', 'Selesai')->count(),
            'ditolak' => Pengaduan::where('status', 'Ditolak')->count(),
        ];

        // Data untuk filter dropdown
        $lokasis = Pengaduan::distinct()->pluck('lokasi')->filter()->sort()->values();
        $petugas = Petugas::orderBy('nama')->get();

        return view('admin.pengaduan.index', compact('pengaduan', 'statistics', 'lokasis', 'petugas'));
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

    /**
     * Approve a temporary item and promote it to master items.
     */
    public function approveTemporaryItem(Request $request, $id)
    {
        $temp = TemporaryItem::findOrFail($id);

        DB::beginTransaction();
        try {
            // Create new master item
            $newItem = \App\Models\Item::create([
                'nama_item' => $temp->nama_barang_baru,
                'lokasi' => $temp->lokasi_barang_baru,
                'deskripsi' => $temp->nama_barang_baru . ' - Item baru berdasarkan permintaan pengaduan',
                'foto' => $temp->foto_kerusakan
            ]);

            // Link pengaduan to new item
            $pengaduan = Pengaduan::find($temp->id_pengaduan);
            if ($pengaduan) {
                $pengaduan->id_item = $newItem->id_item;
                $pengaduan->catatan_admin = $request->input('catatan_admin', 'Permintaan barang baru disetujui dan ditambahkan ke master items');
                $pengaduan->save();
            }

            // Delete temporary item since it's now in master items
            $temp->delete();

            DB::commit();
            return back()->with('success', 'Temporary item disetujui dan ditambahkan ke master barang.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyetujui temporary item: ' . $e->getMessage());
        }
    }

    public function rejectTemporaryItem(Request $request, $id)
    {
        $temp = TemporaryItem::findOrFail($id);

        DB::beginTransaction();
        try {
            // Save rejection reason to pengaduan
            $pengaduan = Pengaduan::find($temp->id_pengaduan);
            if ($pengaduan) {
                $alasanPenolakan = $request->input('catatan_admin', 'Permintaan barang baru ditolak oleh admin');
                $pengaduan->catatan_admin = $pengaduan->catatan_admin 
                    ? $pengaduan->catatan_admin . "\n\nPenolakan Barang Baru: " . $alasanPenolakan
                    : "Penolakan Barang Baru: " . $alasanPenolakan;
                $pengaduan->save();
            }

            // Delete temporary item completely (it's just transit data)
            $temp->delete();

            DB::commit();
            return back()->with('success', 'Permintaan barang baru ditolak dan dihapus dari sistem.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menolak temporary item: ' . $e->getMessage());
        }
    }

    public function updateStatus(Request $request, Pengaduan $pengaduan)
    {
        // Validasi berdasarkan status yang diminta
        $validationRules = [
            'status' => 'required|in:Disetujui,Ditolak,Diproses,Selesai',
            'catatan_admin' => 'required|string|max:500'
        ];
        
        // Validasi transisi status
        $currentStatus = $pengaduan->status;
        $newStatus = $request->status;
        
        // Aturan transisi status:
        // Diajukan -> Disetujui/Ditolak
        // Disetujui -> Diproses/Selesai (atau tetap Ditolak)
        // Diproses -> Selesai
        
        if ($currentStatus === 'Diajukan' && !in_array($newStatus, ['Disetujui', 'Ditolak'])) {
            return back()->with('error', 'Pengaduan yang baru diajukan hanya bisa Disetujui atau Ditolak!');
        }
        
        if ($currentStatus === 'Disetujui' && !in_array($newStatus, ['Diproses', 'Selesai', 'Ditolak'])) {
            return back()->with('error', 'Pengaduan yang sudah disetujui hanya bisa dimulai proses atau diselesaikan!');
        }
        
        if ($currentStatus === 'Diproses' && $newStatus !== 'Selesai') {
            return back()->with('error', 'Pengaduan yang sedang diproses hanya bisa diselesaikan!');
        }
        
        // Validasi id_petugas hanya untuk status Disetujui (opsional)
        if ($newStatus === 'Disetujui' && $request->filled('id_petugas')) {
            $validationRules['id_petugas'] = 'exists:petugas,id_petugas';
        }
        
        $request->validate($validationRules, [
            'status.required' => 'Status harus dipilih',
            'status.in' => 'Status tidak valid',
            'catatan_admin.required' => 'Catatan admin wajib diisi',
            'catatan_admin.max' => 'Catatan admin maksimal 500 karakter',
            'id_petugas.exists' => 'Petugas yang dipilih tidak valid',
        ]);

        DB::beginTransaction();
        try {
            $pengaduan->catatan_admin = $request->catatan_admin;
            
            // STAGE 1: Setujui atau Tolak (dari status Diajukan)
            if ($newStatus === 'Disetujui') {
                $pengaduan->status = 'Disetujui';
                $pengaduan->tgl_verifikasi = now();
                
                // Admin bisa assign ke petugas tertentu atau tidak (tetap available untuk semua petugas)
                if ($request->filled('id_petugas')) {
                    $pengaduan->id_petugas = $request->id_petugas;
                    $petugasNama = Petugas::find($request->id_petugas)->nama;
                    $message = "Pengaduan berhasil disetujui dan ditugaskan ke {$petugasNama}. Petugas dapat memulai proses perbaikan.";
                } else {
                    // Tidak assign ke petugas tertentu - semua petugas bisa mengambil
                    $pengaduan->id_petugas = null;
                    $message = 'Pengaduan berhasil disetujui. Semua petugas dapat melihat dan memulai proses perbaikan.';
                }
                
                // Update temporary items jika ada (pure transit concept)
                if ($pengaduan->temporary_items->count() > 0) {
                    foreach ($pengaduan->temporary_items as $item) {
                        // Items remain in temporary table until explicitly approved/rejected
                        // No status tracking needed in pure transit concept
                    }
                }
            }
            
            // STAGE 1: Tolak
            elseif ($newStatus === 'Ditolak') {
                $pengaduan->status = 'Ditolak';
                $pengaduan->tgl_verifikasi = now();
                
                $adminUser = auth()->user();
                $message = 'Pengaduan telah ditolak oleh Admin ' . $adminUser->nama_pengguna . '.';
                
                // Update temporary items jika ada (pure transit concept)
                if ($pengaduan->temporary_items->count() > 0) {
                    foreach ($pengaduan->temporary_items as $item) {
                        // Items remain in temporary table until explicitly approved/rejected
                        // No status tracking needed in pure transit concept
                    }
                }
            }
            
            // Invalid status transition
            else {
                DB::rollBack();
                return back()->with('error', 'Status transition tidak valid untuk admin.');
            }
            
            $pengaduan->save();
            DB::commit();
            
            return redirect()
                ->route('admin.pengaduan.show', $pengaduan)
                ->with('success', $message);
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Export pengaduan to Excel (mengikuti filter)
     */
    public function exportExcel(Request $request)
    {
        $query = Pengaduan::with('user', 'petugas');

        // Apply same filters as index
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('tanggal_dari')) {
            $query->whereDate('tgl_pengajuan', '>=', $request->tanggal_dari);
        }
        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('tgl_pengajuan', '<=', $request->tanggal_sampai);
        }
        if ($request->filled('lokasi')) {
            $query->where('lokasi', 'LIKE', '%' . $request->lokasi . '%');
        }
        if ($request->filled('petugas')) {
            $petugasFilter = $request->petugas;
            if ($petugasFilter === 'belum_ditugaskan') {
                $query->whereNull('id_petugas')->where('ditangani_admin', false);
            } elseif (str_starts_with($petugasFilter, 'petugas_')) {
                $idPetugas = str_replace('petugas_', '', $petugasFilter);
                $query->where('id_petugas', $idPetugas);
            } elseif (str_starts_with($petugasFilter, 'admin_')) {
                $namaAdmin = str_replace('admin_', '', $petugasFilter);
                $query->where('nama_admin', $namaAdmin);
            }
        }

        $pengaduan = $query->orderBy('tgl_pengajuan', 'desc')->get();

        // Generate CSV content (Excel-compatible)
        $filename = 'Laporan_Pengaduan_' . date('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $callback = function() use ($pengaduan, $request) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for Excel UTF-8 support
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Title and metadata
            fputcsv($file, ['LAPORAN DATA PENGADUAN SAPRAS']);
            fputcsv($file, ['Tanggal Export: ' . date('d/m/Y H:i:s')]);
            fputcsv($file, []); // Empty row
            
            // Filter info
            if ($request->filled('status')) {
                fputcsv($file, ['Filter Status: ' . $request->status]);
            }
            if ($request->filled('tanggal_dari') || $request->filled('tanggal_sampai')) {
                $dari = $request->tanggal_dari ?? '...';
                $sampai = $request->tanggal_sampai ?? '...';
                fputcsv($file, ["Filter Tanggal: {$dari} s/d {$sampai}"]);
            }
            if ($request->filled('lokasi')) {
                fputcsv($file, ['Filter Lokasi: ' . $request->lokasi]);
            }
            
            fputcsv($file, []); // Empty row
            
            // Headers
            fputcsv($file, [
                'No',
                'ID Pengaduan',
                'Tanggal',
                'Nama Pengadu',
                'Judul Pengaduan',
                'Lokasi',
                'Ditangani Oleh',
                'Status'
            ]);
            
            // Data rows
            $no = 1;
            foreach ($pengaduan as $item) {
                $handler = 'Belum ditugaskan';
                if ($item->petugas) {
                    $handler = 'Petugas: ' . $item->petugas->nama;
                    if ($item->petugas->pekerjaan) {
                        $handler .= ' (' . $item->petugas->pekerjaan . ')';
                    }
                }
                
                fputcsv($file, [
                    $no++,
                    $item->id_pengaduan,
                    date('d/m/Y', strtotime($item->tgl_pengajuan)),
                    $item->user->nama_pengguna,
                    $item->nama_pengaduan,
                    $item->lokasi,
                    $handler,
                    $item->status
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export pengaduan to PDF (mengikuti filter)
     */
    public function exportPdf(Request $request)
    {
        $query = Pengaduan::with('user', 'petugas');

        // Apply same filters as index
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('tanggal_dari')) {
            $query->whereDate('tgl_pengajuan', '>=', $request->tanggal_dari);
        }
        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('tgl_pengajuan', '<=', $request->tanggal_sampai);
        }
        if ($request->filled('lokasi')) {
            $query->where('lokasi', 'LIKE', '%' . $request->lokasi . '%');
        }
        if ($request->filled('petugas')) {
            $petugasFilter = $request->petugas;
            if ($petugasFilter === 'belum_ditugaskan') {
                $query->whereNull('id_petugas')->where('ditangani_admin', false);
            } elseif (str_starts_with($petugasFilter, 'petugas_')) {
                $idPetugas = str_replace('petugas_', '', $petugasFilter);
                $query->where('id_petugas', $idPetugas);
            } elseif (str_starts_with($petugasFilter, 'admin_')) {
                $namaAdmin = str_replace('admin_', '', $petugasFilter);
                $query->where('nama_admin', $namaAdmin);
            }
        }

        $pengaduan = $query->orderBy('tgl_pengajuan', 'desc')->get();

        $data = [
            'pengaduan' => $pengaduan,
            'tanggal_export' => date('d/m/Y H:i:s'),
            'filters' => [
                'status' => $request->status,
                'tanggal_dari' => $request->tanggal_dari,
                'tanggal_sampai' => $request->tanggal_sampai,
                'lokasi' => $request->lokasi,
            ]
        ];

        $pdf = Pdf::loadView('admin.pengaduan.export-pdf', $data);
        $pdf->setPaper('A4', 'landscape');
        
        return $pdf->download('Laporan_Pengaduan_' . date('Y-m-d') . '.pdf');
    }
}
