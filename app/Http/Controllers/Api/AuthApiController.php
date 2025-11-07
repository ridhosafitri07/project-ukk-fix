<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthApiController extends Controller
{
    /**
     * Login API
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::where('username', $request->username)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Username atau password salah'
            ], 401);
        }

        // Create token
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil',
            'data' => [
                'user' => [
                    'id_user' => $user->id_user,
                    'username' => $user->username,
                    'nama_pengguna' => $user->nama_pengguna,
                    'role' => $user->role,
                    'foto_profil' => $user->foto_profil ? url('storage/' . $user->foto_profil) : null,
                    'bio' => $user->bio,
                    'telp_user' => $user->telp_user,
                ],
                'token' => $token,
                'token_type' => 'Bearer'
            ]
        ], 200);
    }

    /**
     * Register API (Khusus Pengguna)
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|unique:user,username|min:3|max:50',
            'password' => 'required|string|min:6|confirmed',
            'nama_pengguna' => 'required|string|max:100',
            'telp_user' => 'nullable|string|max:15',
            'bio' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Create user
        $user = User::create([
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'nama_pengguna' => $request->nama_pengguna,
            'role' => 'pengguna', // Default role
            'telp_user' => $request->telp_user,
            'bio' => $request->bio,
        ]);

        // Create token
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Registrasi berhasil',
            'data' => [
                'user' => [
                    'id_user' => $user->id_user,
                    'username' => $user->username,
                    'nama_pengguna' => $user->nama_pengguna,
                    'role' => $user->role,
                    'foto_profil' => null,
                    'bio' => $user->bio,
                    'telp_user' => $user->telp_user,
                ],
                'token' => $token,
                'token_type' => 'Bearer'
            ]
        ], 201);
    }

    /**
     * Logout API
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        // Revoke current token
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil'
        ], 200);
    }

    /**
     * Get authenticated user
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function user(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'success' => true,
            'data' => [
                'id_user' => $user->id_user,
                'username' => $user->username,
                'nama_pengguna' => $user->nama_pengguna,
                'role' => $user->role,
                'foto_profil' => $user->foto_profil ? url('storage/' . $user->foto_profil) : null,
                'bio' => $user->bio,
                'telp_user' => $user->telp_user,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ]
        ], 200);
    }
}
