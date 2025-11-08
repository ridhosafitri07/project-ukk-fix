<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lokasi;
use Illuminate\Http\Request;

class MasterLokasiController extends Controller
{
    /**
     * Display a listing of lokasi
     */
    public function index(Request $request)
    {
        $query = Lokasi::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_lokasi', 'like', "%{$search}%")
                  ->orWhere('kategori', 'like', "%{$search}%");
            });
        }

        // Filter by kategori
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        $lokasis = $query->orderBy('nama_lokasi', 'asc')->paginate(15);
        
        // Get unique categories for filter
        $categories = Lokasi::select('kategori')
            ->distinct()
            ->whereNotNull('kategori')
            ->pluck('kategori');
        
        // Statistics
        $statistics = [
            'total' => Lokasi::count(),
            'kelas' => Lokasi::where('kategori', 'Kelas')->count(),
            'lab' => Lokasi::where('kategori', 'Lab')->count(),
            'kantor' => Lokasi::where('kategori', 'Kantor')->count(),
            'umum' => Lokasi::where('kategori', 'Umum')->count(),
            'area_luar' => Lokasi::where('kategori', 'Area Luar')->count(),
        ];

        return view('admin.master-lokasi.index', compact('lokasis', 'categories', 'statistics'));
    }

    /**
     * Show the form for creating a new lokasi
     */
    public function create()
    {
        return view('admin.master-lokasi.create');
    }

    /**
     * Store a newly created lokasi
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_lokasi' => 'required|string|max:100|unique:lokasi,nama_lokasi',
            'kategori' => 'nullable|string|max:50'
        ], [
            'nama_lokasi.required' => 'Nama lokasi harus diisi',
            'nama_lokasi.unique' => 'Nama lokasi sudah ada',
            'nama_lokasi.max' => 'Nama lokasi maksimal 100 karakter',
            'kategori.max' => 'Kategori maksimal 50 karakter'
        ]);

        Lokasi::create($validated);

        return redirect()
            ->route('admin.master-lokasi.index')
            ->with('success', 'Lokasi berhasil ditambahkan');
    }

    /**
     * Display the specified lokasi
     */
    public function show(Lokasi $masterLokasi)
    {
        // Load items related to this lokasi
        $masterLokasi->load(['items']);
        
        return view('admin.master-lokasi.show', compact('masterLokasi'));
    }

    /**
     * Show the form for editing the specified lokasi
     */
    public function edit(Lokasi $masterLokasi)
    {
        return view('admin.master-lokasi.edit', compact('masterLokasi'));
    }

    /**
     * Update the specified lokasi
     */
    public function update(Request $request, Lokasi $masterLokasi)
    {
        $validated = $request->validate([
            'nama_lokasi' => 'required|string|max:100|unique:lokasi,nama_lokasi,' . $masterLokasi->id_lokasi . ',id_lokasi',
            'kategori' => 'nullable|string|max:50'
        ], [
            'nama_lokasi.required' => 'Nama lokasi harus diisi',
            'nama_lokasi.unique' => 'Nama lokasi sudah ada',
            'nama_lokasi.max' => 'Nama lokasi maksimal 100 karakter',
            'kategori.max' => 'Kategori maksimal 50 karakter'
        ]);

        $masterLokasi->update($validated);

        return redirect()
            ->route('admin.master-lokasi.index')
            ->with('success', 'Lokasi berhasil diperbarui');
    }

    /**
     * Remove the specified lokasi
     */
    public function destroy(Lokasi $masterLokasi)
    {
        try {
            // Check if lokasi has related items
            if ($masterLokasi->items()->count() > 0) {
                return back()->with('error', 'Lokasi tidak dapat dihapus karena masih memiliki item terkait');
            }

            $masterLokasi->delete();
            
            return redirect()
                ->route('admin.master-lokasi.index')
                ->with('success', 'Lokasi berhasil dihapus');
                
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus lokasi: ' . $e->getMessage());
        }
    }
}
