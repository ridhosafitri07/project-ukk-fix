<?php

namespace App\Http\Controllers;

use App\Models\Pengaduan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PengaduanController extends Controller
{
    public function index()
    {
        $pengaduans = Pengaduan::where('id_user', Auth::id())
            ->orderBy('tgl_pengajuan', 'desc')
            ->get();
        return view('pengguna.pengaduan.index', compact('pengaduans'));
    }

    public function create()
    {
        return view('pengguna.pengaduan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_pengaduan' => 'required|string|max:200',
            'deskripsi' => 'required|string',
            'lokasi' => 'required|string|max:200',
            'foto' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ], [
            'nama_pengaduan.required' => 'Judul pengaduan wajib diisi',
            'deskripsi.required' => 'Deskripsi wajib diisi',
            'lokasi.required' => 'Lokasi wajib diisi',
            'foto.required' => 'Foto wajib diunggah',
            'foto.image' => 'File harus berupa gambar',
            'foto.mimes' => 'Format foto harus JPG, JPEG, atau PNG',
            'foto.max' => 'Ukuran foto maksimal 2MB'
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            
            // Generate unique filename
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            
            // Store with public visibility
            $fotoPath = $file->storeAs('pengaduan', $fileName, 'public');
        }

        Pengaduan::create([
            'nama_pengaduan' => $validated['nama_pengaduan'],
            'deskripsi' => $validated['deskripsi'],
            'lokasi' => $validated['lokasi'],
            'foto' => $fotoPath,
            'status' => 'Diajukan',
            'id_user' => Auth::id(),
            'tgl_pengajuan' => now()
        ]);

        return redirect()->route('pengaduan.index')
            ->with('success', 'Pengaduan berhasil diajukan');
    }

    public function show(Pengaduan $pengaduan)
    {
        if ($pengaduan->id_user !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        return view('pengguna.pengaduan.show', compact('pengaduan'));
    }

    public function edit(Pengaduan $pengaduan)
    {
        if ($pengaduan->id_user !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        if ($pengaduan->status !== 'Diajukan') {
            return redirect()->route('pengaduan.show', $pengaduan)
                ->with('error', 'Pengaduan yang sudah diproses tidak dapat diubah.');
        }

        return view('pengguna.pengaduan.edit', compact('pengaduan'));
    }

    public function update(Request $request, Pengaduan $pengaduan)
    {
        if ($pengaduan->id_user !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        if ($pengaduan->status !== 'Diajukan') {
            return redirect()->route('pengaduan.show', $pengaduan)
                ->with('error', 'Pengaduan yang sudah diproses tidak dapat diubah.');
        }

        $validated = $request->validate([
            'nama_pengaduan' => 'required|string|max:200',
            'deskripsi' => 'required|string',
            'lokasi' => 'required|string|max:200',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ], [
            'nama_pengaduan.required' => 'Judul pengaduan wajib diisi',
            'deskripsi.required' => 'Deskripsi wajib diisi',
            'lokasi.required' => 'Lokasi wajib diisi',
            'foto.image' => 'File harus berupa gambar',
            'foto.mimes' => 'Format foto harus JPG, JPEG, atau PNG',
            'foto.max' => 'Ukuran foto maksimal 2MB'
        ]);

        // Update basic fields
        $pengaduan->nama_pengaduan = $validated['nama_pengaduan'];
        $pengaduan->deskripsi = $validated['deskripsi'];
        $pengaduan->lokasi = $validated['lokasi'];

        // Handle photo update
        if ($request->hasFile('foto')) {
            // Delete old photo if exists
            if ($pengaduan->foto && Storage::disk('public')->exists($pengaduan->foto)) {
                Storage::disk('public')->delete($pengaduan->foto);
            }
            
            // Store new photo
            $file = $request->file('foto');
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $fotoPath = $file->storeAs('pengaduan', $fileName, 'public');
            $pengaduan->foto = $fotoPath;
        }

        $pengaduan->save();

        return redirect()->route('pengaduan.show', $pengaduan)
            ->with('success', 'Pengaduan berhasil diperbarui');
    }

    public function destroy(Pengaduan $pengaduan)
    {
        if ($pengaduan->id_user !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        if ($pengaduan->status !== 'Diajukan') {
            return redirect()->route('pengaduan.show', $pengaduan)
                ->with('error', 'Pengaduan yang sudah diproses tidak dapat dihapus.');
        }

        // Delete photo if exists
        if ($pengaduan->foto && Storage::disk('public')->exists($pengaduan->foto)) {
            Storage::disk('public')->delete($pengaduan->foto);
        }

        $pengaduan->delete();

        return redirect()->route('pengaduan.index')
            ->with('success', 'Pengaduan berhasil dihapus');
    }
}