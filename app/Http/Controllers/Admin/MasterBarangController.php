<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Lokasi;
use Illuminate\Http\Request;

class MasterBarangController extends Controller
{
    /**
     * Display a listing of items
     */
    public function index(Request $request)
    {
        $query = Item::with('lokasis');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_item', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        // Filter by lokasi
        if ($request->filled('lokasi')) {
            $query->whereHas('lokasis', function($q) use ($request) {
                $q->where('lokasi.id_lokasi', $request->lokasi);
            });
        }

        $items = $query->orderBy('nama_item', 'asc')->paginate(15);
        
        // Get all lokasi for filter
        $lokasis = Lokasi::orderBy('nama_lokasi')->get();
        
        // Statistics
        $statistics = [
            'total' => Item::count(),
            'dengan_lokasi' => Item::has('lokasis')->count(),
            'tanpa_lokasi' => Item::doesntHave('lokasis')->count(),
        ];

        return view('admin.master-barang.index', compact('items', 'lokasis', 'statistics'));
    }

    /**
     * Show the form for creating a new item
     */
    public function create()
    {
        $lokasis = Lokasi::orderBy('nama_lokasi')->get();
        return view('admin.master-barang.create', compact('lokasis'));
    }

    /**
     * Store a newly created item
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_item' => 'required|string|max:200',
            'deskripsi' => 'nullable|string',
        ], [
            'nama_item.required' => 'Nama item harus diisi',
        ]);

        // Create item
        Item::create([
            'nama_item' => $validated['nama_item'],
            'deskripsi' => $validated['deskripsi']
        ]);

        return redirect()
            ->route('admin.master-barang.index')
            ->with('success', 'Item berhasil ditambahkan. Silakan atur lokasinya di menu Relasi.');
    }

    /**
     * Display the specified item
     */
    public function show(Item $masterBarang)
    {
        $masterBarang->load(['lokasis']);
        return view('admin.master-barang.show', compact('masterBarang'));
    }

    /**
     * Show the form for editing the specified item
     */
    public function edit(Item $masterBarang)
    {
        return view('admin.master-barang.edit', compact('masterBarang'));
    }

    /**
     * Update the specified item
     */
    public function update(Request $request, Item $masterBarang)
    {
        $validated = $request->validate([
            'nama_item' => 'required|string|max:200',
            'deskripsi' => 'nullable|string',
        ], [
            'nama_item.required' => 'Nama item harus diisi',
        ]);

        // Update item
        $masterBarang->update([
            'nama_item' => $validated['nama_item'],
            'deskripsi' => $validated['deskripsi']
        ]);

        return redirect()
            ->route('admin.master-barang.index')
            ->with('success', 'Item berhasil diperbarui');
    }

    /**
     * Remove the specified item
     */
    public function destroy(Item $masterBarang)
    {
        try {
            // Check if item has related pengaduan
            if ($masterBarang->pengaduans()->count() > 0) {
                return back()->with('error', 'Item tidak dapat dihapus karena masih memiliki pengaduan terkait');
            }

            // Detach all lokasi relationships
            $masterBarang->lokasis()->detach();
            
            // Delete item
            $masterBarang->delete();
            
            return redirect()
                ->route('admin.master-barang.index')
                ->with('success', 'Item berhasil dihapus');
                
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus item: ' . $e->getMessage());
        }
    }
}
