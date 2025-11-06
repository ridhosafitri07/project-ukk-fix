<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\ListLokasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BarangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Item::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('nama_item', 'LIKE', "%{$search}%")
                  ->orWhere('deskripsi', 'LIKE', "%{$search}%");
        }

        // Get items with lokasi count
        $items = $query->withCount('lokasis')
                      ->orderBy('id_item', 'desc')
                      ->paginate(10)
                      ->appends($request->all());

        // Statistics
        $statistics = [
            'total' => Item::count(),
            'dengan_lokasi' => Item::has('lokasis')->count(),
            'tanpa_lokasi' => Item::doesntHave('lokasis')->count(),
        ];

        return view('admin.master-barang.index', compact('items', 'statistics'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.master-barang.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_item' => 'required|string|max:200|unique:items,nama_item',
            'deskripsi' => 'nullable|string',
        ], [
            'nama_item.required' => 'Nama barang harus diisi',
            'nama_item.max' => 'Nama barang maksimal 200 karakter',
            'nama_item.unique' => 'Nama barang sudah terdaftar',
        ]);

        try {
            Item::create([
                'nama_item' => $validated['nama_item'],
                'deskripsi' => $validated['deskripsi'] ?? null,
            ]);

            return redirect()
                ->route('admin.master-barang.index')
                ->with('success', '✅ Barang berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', '❌ Gagal menambahkan barang: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $masterBarang = Item::with(['lokasis.listLokasi'])
            ->findOrFail($id);

        return view('admin.master-barang.show', compact('masterBarang'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $masterBarang = Item::findOrFail($id);
        
        return view('admin.master-barang.edit', compact('masterBarang'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $masterBarang = Item::findOrFail($id);

        $validated = $request->validate([
            'nama_item' => 'required|string|max:200|unique:items,nama_item,' . $id . ',id_item',
            'deskripsi' => 'nullable|string',
        ], [
            'nama_item.required' => 'Nama barang harus diisi',
            'nama_item.max' => 'Nama barang maksimal 200 karakter',
            'nama_item.unique' => 'Nama barang sudah terdaftar',
        ]);

        try {
            $masterBarang->update([
                'nama_item' => $validated['nama_item'],
                'deskripsi' => $validated['deskripsi'] ?? null,
            ]);

            return redirect()
                ->route('admin.master-barang.index')
                ->with('success', '✅ Barang berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', '❌ Gagal memperbarui barang: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $masterBarang = Item::findOrFail($id);

            // Check if item has relations in list_lokasi
            $hasRelations = ListLokasi::where('id_item', $id)->exists();
            
            if ($hasRelations) {
                return redirect()
                    ->back()
                    ->with('error', '❌ Tidak dapat menghapus barang yang masih memiliki relasi dengan lokasi!');
            }

            // Check if item has pengaduan
            if ($masterBarang->pengaduans()->exists()) {
                return redirect()
                    ->back()
                    ->with('error', '❌ Tidak dapat menghapus barang yang memiliki riwayat pengaduan!');
            }

            $masterBarang->delete();

            return redirect()
                ->route('admin.master-barang.index')
                ->with('success', '✅ Barang berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', '❌ Gagal menghapus barang: ' . $e->getMessage());
        }
    }

    /**
     * Export data to PDF
     */
    public function exportPdf()
    {
        // TODO: Implement PDF export
        return redirect()
            ->back()
            ->with('error', '🚧 Fitur export PDF sedang dalam pengembangan');
    }

    /**
     * Export data to Excel
     */
    public function exportExcel()
    {
        // TODO: Implement Excel export
        return redirect()
            ->back()
            ->with('error', '🚧 Fitur export Excel sedang dalam pengembangan');
    }
}
