<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Lokasi;
use App\Models\ListLokasi;
use Illuminate\Http\Request;

class RelasiController extends Controller
{
    /**
     * Display a listing of item-lokasi relations
     */
    public function index(Request $request)
    {
        $query = ListLokasi::with(['item', 'lokasi']);

        // Search by item name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('item', function($q) use ($search) {
                $q->where('nama_item', 'like', "%{$search}%");
            })->orWhereHas('lokasi', function($q) use ($search) {
                $q->where('nama_lokasi', 'like', "%{$search}%");
            });
        }

        // Filter by item
        if ($request->filled('id_item')) {
            $query->where('id_item', $request->id_item);
        }

        // Filter by lokasi
        if ($request->filled('id_lokasi')) {
            $query->where('id_lokasi', $request->id_lokasi);
        }

        $relasis = $query->orderBy('id_lokasi')->orderBy('id_item')->paginate(15);
        
        // Get all items and lokasi for filter
        $items = Item::orderBy('nama_item')->get();
        $lokasis = Lokasi::orderBy('nama_lokasi')->get();
        
        // Statistics
        $totalRelations = \DB::table('list_lokasi')->count();
        $statistics = [
            'total' => $totalRelations,
            'items_distributed' => Item::has('lokasis')->count(),
            'items_not_distributed' => Item::doesntHave('lokasis')->count(),
            'total_lokasi' => Lokasi::count(),
            'total_barang' => Item::count(),
        ];

        return view('admin.relasi.index', compact('relasis', 'items', 'lokasis', 'statistics'));
    }

    /**
     * Show the form for creating a new relation
     */
    public function create()
    {
        $items = Item::orderBy('nama_item')->get();
        $lokasis = Lokasi::orderBy('nama_lokasi')->get();
        
        return view('admin.relasi.create', compact('items', 'lokasis'));
    }

    /**
     * Store a newly created relation
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_item' => 'required|exists:items,id_item',
            'lokasi' => 'required|array|min:1',
            'lokasi.*' => 'exists:lokasi,id_lokasi'
        ], [
            'id_item.required' => 'Item harus dipilih',
            'id_item.exists' => 'Item tidak valid',
            'lokasi.required' => 'Minimal pilih 1 lokasi',
            'lokasi.*.exists' => 'Lokasi tidak valid'
        ]);

        $item = Item::findOrFail($validated['id_item']);
        
        // Attach new lokasi (without detaching existing ones)
        foreach ($validated['lokasi'] as $lokasiId) {
            // Check if relation already exists
            if (!$item->lokasis()->where('lokasi.id_lokasi', $lokasiId)->exists()) {
                $item->lokasis()->attach($lokasiId);
            }
        }

        return redirect()
            ->route('admin.relasi.index')
            ->with('success', 'Relasi berhasil ditambahkan');
    }

    /**
     * Show the form for editing relation (edit all lokasi for specific item)
     */
    public function edit($id)
    {
        // id format: {id_item}_{id_lokasi} OR just {id_item}
        $ids = explode('_', $id);
        $itemId = $ids[0];
        
        $item = Item::with('lokasis')->findOrFail($itemId);
        $lokasis = Lokasi::orderBy('nama_lokasi')->get();
        
        return view('admin.relasi.edit', compact('item', 'lokasis'));
    }

    /**
     * Update relations for an item
     */
    public function update(Request $request, $id)
    {
        // id format: {id_item}_{id_lokasi} OR just {id_item}
        $ids = explode('_', $id);
        $itemId = $ids[0];
        
        $item = Item::findOrFail($itemId);
        
        $validated = $request->validate([
            'lokasi' => 'required|array|min:1',
            'lokasi.*' => 'exists:lokasi,id_lokasi'
        ], [
            'lokasi.required' => 'Minimal pilih 1 lokasi',
            'lokasi.*.exists' => 'Lokasi tidak valid'
        ]);

        // Sync lokasi (remove old, add new)
        $item->lokasis()->sync($validated['lokasi']);

        return redirect()
            ->route('admin.relasi.index')
            ->with('success', 'Relasi berhasil diperbarui');
    }

    /**
     * Remove the specified relation
     */
    public function destroy($id)
    {
        try {
            // id format: {id_item}_{id_lokasi}
            $ids = explode('_', $id);
            
            if (count($ids) != 2) {
                return back()->with('error', 'Format ID tidak valid');
            }

            $itemId = $ids[0];
            $lokasiId = $ids[1];

            $item = Item::findOrFail($itemId);
            
            // Detach specific lokasi
            $item->lokasis()->detach($lokasiId);

            return redirect()
                ->route('admin.relasi.index')
                ->with('success', 'Relasi berhasil dihapus');
                
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus relasi: ' . $e->getMessage());
        }
    }
}
