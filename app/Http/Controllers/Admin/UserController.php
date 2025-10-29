<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('petugas');
        
        // Filter by role if requested
        if ($request->filled('role') && $request->role !== 'all') {
            $query->where('role', $request->role);
        }
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_pengguna', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%");
            });
        }
        
        $users = $query->orderBy('id_user', 'desc')->paginate(10);
        
        // Count by role for statistics
        $statistics = [
            'total' => User::count(),
            'admin' => User::where('role', 'admin')->count(),
            'petugas' => User::where('role', 'petugas')->count(),
            'pengguna' => User::where('role', 'pengguna')->count(),
        ];
        
        return view('admin.users.index', compact('users', 'statistics'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_pengguna' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:user',
            'password' => 'required|string|min:6',
            'role' => ['required', Rule::in(['admin', 'petugas', 'pengguna'])],
            'pekerjaan' => 'required_if:role,petugas|in:CS,Teknisi,Administrasi,Supervisor',
        ], [
            'nama_pengguna.required' => 'Nama lengkap wajib diisi',
            'username.required' => 'Username wajib diisi',
            'username.unique' => 'Username sudah digunakan',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 6 karakter',
            'role.required' => 'Role wajib dipilih',
            'pekerjaan.required_if' => 'Pekerjaan wajib diisi untuk petugas',
        ]);

        $user = User::create([
            'nama_pengguna' => $validated['nama_pengguna'],
            'username' => $validated['username'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);

        // Jika role adalah petugas, buat data petugas
        if ($validated['role'] === 'petugas') {
            $user->petugas()->create([
                'nama' => $validated['nama_pengguna'],
                'gender' => 'L', // Default value
                'telp' => '', // Default value
                'pekerjaan' => $validated['pekerjaan'],
            ]);
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil ditambahkan');
    }

    public function edit(User $user)
    {
        $user->load('petugas');
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'nama_pengguna' => 'required|string|max:255',
            'username' => ['required', 'string', 'max:255', Rule::unique('user')->ignore($user->id_user, 'id_user')],
            'password' => 'nullable|string|min:6',
            'role' => ['required', Rule::in(['admin', 'petugas', 'pengguna'])],
            'pekerjaan' => 'required_if:role,petugas|in:CS,Teknisi,Administrasi,Supervisor',
        ], [
            'nama_pengguna.required' => 'Nama lengkap wajib diisi',
            'username.required' => 'Username wajib diisi',
            'username.unique' => 'Username sudah digunakan',
            'password.min' => 'Password minimal 6 karakter',
            'role.required' => 'Role wajib dipilih',
            'pekerjaan.required_if' => 'Pekerjaan wajib diisi untuk petugas',
        ]);

        $user->nama_pengguna = $validated['nama_pengguna'];
        $user->username = $validated['username'];
        if ($validated['password']) {
            $user->password = Hash::make($validated['password']);
        }
        $user->role = $validated['role'];
        $user->save();

        // Jika role adalah petugas
        if ($validated['role'] === 'petugas') {
            // Cek apakah sudah ada data petugas
            if ($user->petugas) {
                // Update data petugas
                $user->petugas->update([
                    'nama' => $validated['nama_pengguna'],
                    'pekerjaan' => $validated['pekerjaan'],
                ]);
            } else {
                // Buat data petugas baru
                $user->petugas()->create([
                    'nama' => $validated['nama_pengguna'],
                    'gender' => 'L', // Default value
                    'telp' => '', // Default value
                    'pekerjaan' => $validated['pekerjaan'],
                ]);
            }
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil diperbarui');
    }

    public function destroy(User $user)
    {
        // Prevent deleting own account
        if ($user->id_user === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Tidak dapat menghapus akun sendiri');
        }
        
        $user->delete();
        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil dihapus');
    }
}