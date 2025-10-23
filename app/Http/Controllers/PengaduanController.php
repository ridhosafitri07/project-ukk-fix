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
        $request->validate([
            'nama_pengaduan' => 'required|string|max:200',
            'deskripsi' => 'required|string',
            'lokasi' => 'required|string|max:200',
            'foto' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('public/pengaduan');
            $fotoPath = str_replace('public/', '', $fotoPath);
        }

        Pengaduan::create([
            'nama_pengaduan' => $request->nama_pengaduan,
            'deskripsi' => $request->deskripsi,
            'lokasi' => $request->lokasi,
            'foto' => $fotoPath,
            'status' => 'Diajukan',
            'id_user' => Auth::id(),
            'tgl_pengajuan' => now()
        ]);

        return redirect()->route('pengaduan.index')->with('success', 'Pengaduan berhasil diajukan');
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

        $request->validate([
            'nama_pengaduan' => 'required|string|max:200',
            'deskripsi' => 'required|string',
            'lokasi' => 'required|string|max:200',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        if ($request->hasFile('foto')) {
            // Delete old photo
            if ($pengaduan->foto) {
                Storage::delete('public/' . $pengaduan->foto);
            }
            
            // Store new photo
            $fotoPath = $request->file('foto')->store('public/pengaduan');
            $fotoPath = str_replace('public/', '', $fotoPath);
            $pengaduan->foto = $fotoPath;
        }

        $pengaduan->update([
            'nama_pengaduan' => $request->nama_pengaduan,
            'deskripsi' => $request->deskripsi,
            'lokasi' => $request->lokasi
        ]);

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
        if ($pengaduan->foto) {
            Storage::delete('public/' . $pengaduan->foto);
        }

        $pengaduan->delete();

        return redirect()->route('pengaduan.index')
            ->with('success', 'Pengaduan berhasil dihapus');
    }
}