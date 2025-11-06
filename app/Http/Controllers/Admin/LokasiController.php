<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lokasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LokasiController extends Controller
{
    public function index(Request $request)
    {
        $query = Lokasi::withCount('items');

        // Search
        if ($request->has('search') && $request->search != '') {
            $query->where('nama_lokasi', 'like', '%' . $request->search . '%');
        }

        // Filter by kategori
        if ($request->has('kategori') && $request->kategori != '') {
            $query->where('kategori', $request->kategori);
        }

        $lokasis = $query->orderBy('nama_lokasi', 'asc')->paginate(15);

        // Statistics
        $statistics = [
            'total' => Lokasi::count(),
            'kelas' => Lokasi::where('kategori', 'kelas')->count(),
            'lab' => Lokasi::where('kategori', 'lab')->count(),
            'kantor' => Lokasi::where('kategori', 'kantor')->count(),
            'umum' => Lokasi::where('kategori', 'umum')->count(),
            'area_luar' => Lokasi::where('kategori', 'area_luar')->count(),
        ];

        return view('admin.master-lokasi.index', compact('lokasis', 'statistics'));
    }

    public function create()
    {
        return view('admin.master-lokasi.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_lokasi' => 'required|string|max:200|unique:lokasi,nama_lokasi',
            'kategori' => 'required|in:kelas,lab,kantor,umum,area_luar',
        ], [
            'nama_lokasi.required' => 'Nama lokasi wajib diisi',
            'nama_lokasi.unique' => 'Nama lokasi sudah ada',
            'kategori.required' => 'Kategori wajib dipilih',
            'kategori.in' => 'Kategori tidak valid',
        ]);

        try {
            Lokasi::create($validated);
            
            return redirect()->route('admin.master-lokasi.index')
                ->with('success', 'Lokasi berhasil ditambahkan');
        } catch (\Exception $e) {
            Log::error('Error creating lokasi: ' . $e->getMessage());
            return back()->withInput()
                ->with('error', 'Gagal menambahkan lokasi: ' . $e->getMessage());
        }
    }

    public function show(Lokasi $masterLokasi)
    {
        $masterLokasi->load('items');
        
        // Get items with full details
        $barangs = $masterLokasi->items()->get();
        
        return view('admin.master-lokasi.show', compact('masterLokasi', 'barangs'));
    }

    public function edit(Lokasi $masterLokasi)
    {
        return view('admin.master-lokasi.edit', compact('masterLokasi'));
    }

    public function update(Request $request, Lokasi $masterLokasi)
    {
        $validated = $request->validate([
            'nama_lokasi' => 'required|string|max:200|unique:lokasi,nama_lokasi,' . $masterLokasi->id_lokasi . ',id_lokasi',
            'kategori' => 'required|in:kelas,lab,kantor,umum,area_luar',
        ], [
            'nama_lokasi.required' => 'Nama lokasi wajib diisi',
            'nama_lokasi.unique' => 'Nama lokasi sudah ada',
            'kategori.required' => 'Kategori wajib dipilih',
            'kategori.in' => 'Kategori tidak valid',
        ]);

        try {
            $masterLokasi->update($validated);
            
            return redirect()->route('admin.master-lokasi.index')
                ->with('success', 'Lokasi berhasil diperbarui');
        } catch (\Exception $e) {
            Log::error('Error updating lokasi: ' . $e->getMessage());
            return back()->withInput()
                ->with('error', 'Gagal memperbarui lokasi: ' . $e->getMessage());
        }
    }

    public function destroy(Lokasi $masterLokasi)
    {
        try {
            // Check if lokasi has items
            if ($masterLokasi->items()->count() > 0) {
                return back()->with('error', 'Tidak dapat menghapus lokasi yang masih memiliki barang. Hapus relasi barang terlebih dahulu.');
            }

            $masterLokasi->delete();
            
            return redirect()->route('admin.master-lokasi.index')
                ->with('success', 'Lokasi berhasil dihapus');
        } catch (\Exception $e) {
            Log::error('Error deleting lokasi: ' . $e->getMessage());
            return back()->with('error', 'Gagal menghapus lokasi: ' . $e->getMessage());
        }
    }

    public function exportPdf(Lokasi $masterLokasi)
    {
        // TODO: Implement PDF export
        return back()->with('info', 'Fitur export PDF akan segera tersedia');
    }

    public function exportExcel(Lokasi $masterLokasi)
    {
        // TODO: Implement Excel export
        return back()->with('info', 'Fitur export Excel akan segera tersedia');
    }
}
