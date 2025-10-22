<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            // Redirect based on role
            $user = Auth::user();
            switch ($user->role) {
                case User::ROLE_ADMIN:
                    return redirect()->intended('/admin/dashboard');
                case User::ROLE_PETUGAS:
                    return redirect()->intended('/petugas/dashboard');
                case User::ROLE_GURU:
                    return redirect()->intended('/guru/dashboard');
                case User::ROLE_SISWA:
                    return redirect()->intended('/siswa/dashboard');
                default:
                    return redirect()->intended('/');
            }
        }

        return back()->withErrors([
            'username' => 'Username atau password salah.',
        ])->onlyInput('username');
    }

    public function register(Request $request)
    {
        $request->validate([
            'username' => ['required', 'string', 'unique:user'],
            'password' => ['required', 'confirmed', Password::min(8)],
            'nama_pengguna' => ['required', 'string', 'max:200'],
        ]);

        $user = User::create([
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'nama_pengguna' => $request->nama_pengguna,
            'role' => User::ROLE_SISWA, // Default role for new registrations
        ]);

        Auth::login($user);

        return redirect('/dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}