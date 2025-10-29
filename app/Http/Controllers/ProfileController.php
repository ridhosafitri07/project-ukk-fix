<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Models\Pengaduan;

class ProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Load relasi berdasarkan role
        if ($user->role === 'petugas') {
            $user->load('petugas.pengaduans');
        } elseif ($user->role === 'pengguna') {
            $user->load('pengaduans');
        }
        
        // Hitung statistik berdasarkan role
        $statistics = $this->getUserStatistics($user);
        
        return view('profile.index', compact('user', 'statistics'));
    }
    
    public function edit()
    {
        $user = auth()->user();
        
        if ($user->role === 'petugas') {
            $user->load('petugas');
        }
        
        return view('profile.edit', compact('user'));
    }
    
    public function update(Request $request)
    {
        $user = auth()->user();
        
        $validated = $request->validate([
            'nama_pengguna' => 'required|string|max:255',
            'username' => ['required', 'string', 'max:255', Rule::unique('user')->ignore($user->id_user, 'id_user')],
            'telp_user' => 'nullable|string|max:20',
            'bio' => 'nullable|string|max:500',
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'nama_pengguna.required' => 'Nama lengkap wajib diisi',
            'username.required' => 'Username wajib diisi',
            'username.unique' => 'Username sudah digunakan',
            'foto_profil.image' => 'File harus berupa gambar',
            'foto_profil.max' => 'Ukuran foto maksimal 2MB',
        ]);
        
        // Handle foto profil
        if ($request->hasFile('foto_profil')) {
            // Hapus foto lama jika ada
            if ($user->foto_profil) {
                Storage::disk('public')->delete($user->foto_profil);
            }
            
            $validated['foto_profil'] = $request->file('foto_profil')->store('profile-photos', 'public');
        }
        
        $user->update($validated);
        
        // Update data petugas jika role petugas
        if ($user->role === 'petugas' && $user->petugas) {
            $user->petugas->update([
                'nama' => $validated['nama_pengguna'],
                'telp' => $validated['telp_user'] ?? $user->petugas->telp,
            ]);
        }
        
        return redirect()->route('profile.index')
            ->with('success', 'Profil berhasil diperbarui');
    }
    
    public function changePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'current_password.required' => 'Password lama wajib diisi',
            'password.required' => 'Password baru wajib diisi',
            'password.min' => 'Password minimal 6 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
        ]);
        
        $user = auth()->user();
        
        // Cek password lama
        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()->with('error', 'Password lama tidak sesuai');
        }
        
        // Update password
        $user->update([
            'password' => Hash::make($validated['password'])
        ]);
        
        return back()->with('success', 'Password berhasil diubah');
    }
    
    public function deletePhoto()
    {
        $user = auth()->user();
        
        if ($user->foto_profil) {
            Storage::disk('public')->delete($user->foto_profil);
            $user->update(['foto_profil' => null]);
        }
        
        return back()->with('success', 'Foto profil berhasil dihapus');
    }
    
    private function getUserStatistics($user)
    {
        $statistics = [
            'total_pengaduan' => 0,
            'pengaduan_selesai' => 0,
            'pengaduan_diproses' => 0,
            'pengaduan_pending' => 0,
        ];
        
        if ($user->role === 'petugas' && $user->petugas) {
            $pengaduans = Pengaduan::where('id_petugas', $user->petugas->id_petugas);
            $statistics['total_pengaduan'] = $pengaduans->count();
            $statistics['pengaduan_selesai'] = $pengaduans->where('status', 'Selesai')->count();
            $statistics['pengaduan_diproses'] = $pengaduans->where('status', 'Diproses')->count();
            $statistics['pengaduan_pending'] = $pengaduans->whereIn('status', ['Diajukan', 'Disetujui'])->count();
        } elseif ($user->role === 'pengguna') {
            $pengaduans = Pengaduan::where('id_user', $user->id_user);
            $statistics['total_pengaduan'] = $pengaduans->count();
            $statistics['pengaduan_selesai'] = $pengaduans->where('status', 'Selesai')->count();
            $statistics['pengaduan_diproses'] = $pengaduans->whereIn('status', ['Disetujui', 'Diproses'])->count();
            $statistics['pengaduan_pending'] = $pengaduans->where('status', 'Diajukan')->count();
        } elseif ($user->role === 'admin') {
            $statistics['total_users'] = User::count();
            $statistics['total_pengaduan'] = Pengaduan::count();
            $statistics['pengaduan_selesai'] = Pengaduan::where('status', 'Selesai')->count();
            $statistics['pengaduan_pending'] = Pengaduan::where('status', 'Diajukan')->count();
        }
        
        return $statistics;
    }
}
