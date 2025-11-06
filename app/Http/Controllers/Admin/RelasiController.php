<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ListLokasi;
use App\Models\Lokasi;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RelasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = ListLokasi::with(['lokasi', 'item']);

        // Filter by lokasi
        if ($request->filled('id_lokasi')) {
            $query->where('id_lokasi', $request->id_lokasi);
        }

        // Search by nama barang or lokasi
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('item', function($q) use ($search) {
                $q->where('nama_item', 'LIKE', "%{$search}%");
            })->orWhereHas('lokasi', function($q) use ($search) {
                $q->where('nama_lokasi', 'LIKE', "%{$search}%");
            });
        }

        $relasis = $query->orderBy('id_list', 'desc')
                        ->paginate(15)
                        ->appends($request->all());

        // Statistics
        $statistics = [
            'total' => ListLokasi::count(),
            'total_lokasi' => Lokasi::count(),
            'total_barang' => Item::count(),
        ];

        // Get all lokasi for filter dropdown
        $lokasis = Lokasi::orderBy('nama_lokasi')->get();

        return view('admin.relasi.index', compact('relasis', 'statistics', 'lokasis'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $lokasis = Lokasi::orderBy('nama_lokasi')->get();
        $items = Item::orderBy('nama_item')->get();

        return view('admin.relasi.create', compact('lokasis', 'items'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_lokasi' => 'required|exists:lokasi,id_lokasi',
            'id_item' => 'required|exists:items,id_item',
        ], [
            'id_lokasi.required' => 'Lokasi harus dipilih',
            'id_lokasi.exists' => 'Lokasi tidak valid',
            'id_item.required' => 'Barang harus dipilih',
            'id_item.exists' => 'Barang tidak valid',
        ]);

        try {
            // Check if relation already exists
            $exists = ListLokasi::where('id_lokasi', $validated['id_lokasi'])
                               ->where('id_item', $validated['id_item'])
                               ->exists();

            if ($exists) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', '❌ Relasi barang dan lokasi ini sudah ada!');
            }

            ListLokasi::create($validated);

            return redirect()
                ->route('admin.relasi.index')
                ->with('success', '✅ Relasi barang-ruangan berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', '❌ Gagal menambahkan relasi: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $relasi = ListLokasi::with(['lokasi', 'item'])->findOrFail($id);
        $lokasis = Lokasi::orderBy('nama_lokasi')->get();
        $items = Item::orderBy('nama_item')->get();

        return view('admin.relasi.edit', compact('relasi', 'lokasis', 'items'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $relasi = ListLokasi::findOrFail($id);

        $validated = $request->validate([
            'id_lokasi' => 'required|exists:lokasi,id_lokasi',
            'id_item' => 'required|exists:items,id_item',
        ], [
            'id_lokasi.required' => 'Lokasi harus dipilih',
            'id_lokasi.exists' => 'Lokasi tidak valid',
            'id_item.required' => 'Barang harus dipilih',
            'id_item.exists' => 'Barang tidak valid',
        ]);

        try {
            // Check if new relation already exists (excluding current record)
            $exists = ListLokasi::where('id_lokasi', $validated['id_lokasi'])
                               ->where('id_item', $validated['id_item'])
                               ->where('id_list', '!=', $id)
                               ->exists();

            if ($exists) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', '❌ Relasi barang dan lokasi ini sudah ada!');
            }

            $relasi->update($validated);

            return redirect()
                ->route('admin.relasi.index')
                ->with('success', '✅ Relasi barang-ruangan berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', '❌ Gagal memperbarui relasi: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $relasi = ListLokasi::findOrFail($id);
            $relasi->delete();

            return redirect()
                ->route('admin.relasi.index')
                ->with('success', '✅ Relasi barang-ruangan berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', '❌ Gagal menghapus relasi: ' . $e->getMessage());
        }
    }

    /**
     * Bulk delete relasi
     */
    public function bulkDelete(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:list_lokasi,id_list',
        ]);

        try {
            ListLokasi::whereIn('id_list', $validated['ids'])->delete();

            return redirect()
                ->route('admin.relasi.index')
                ->with('success', '✅ Berhasil menghapus ' . count($validated['ids']) . ' relasi!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', '❌ Gagal menghapus relasi: ' . $e->getMessage());
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
