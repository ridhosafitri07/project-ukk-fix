<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class UserProfileApiController extends Controller
{
    /**
     * Get user profile
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request)
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

    /**
     * Update user profile
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'nama_pengguna' => 'nullable|string|max:100',
            'bio' => 'nullable|string|max:500',
            'telp_user' => 'nullable|string|max:15',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        if ($request->has('nama_pengguna')) {
            $user->nama_pengguna = $request->nama_pengguna;
        }
        if ($request->has('bio')) {
            $user->bio = $request->bio;
        }
        if ($request->has('telp_user')) {
            $user->telp_user = $request->telp_user;
        }

        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Profile berhasil diupdate',
            'data' => [
                'id_user' => $user->id_user,
                'username' => $user->username,
                'nama_pengguna' => $user->nama_pengguna,
                'role' => $user->role,
                'foto_profil' => $user->foto_profil ? url('storage/' . $user->foto_profil) : null,
                'bio' => $user->bio,
                'telp_user' => $user->telp_user,
            ]
        ], 200);
    }

    /**
     * Update profile photo
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updatePhoto(Request $request)
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'foto_profil' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Delete old photo
        if ($user->foto_profil) {
            Storage::disk('public')->delete($user->foto_profil);
        }

        // Upload new photo
        $foto = $request->file('foto_profil');
        $filename = time() . '_' . $user->username . '.' . $foto->getClientOriginalExtension();
        $fotoPath = $foto->storeAs('profile', $filename, 'public');

        $user->foto_profil = $fotoPath;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Foto profil berhasil diupdate',
            'data' => [
                'foto_profil' => url('storage/' . $user->foto_profil)
            ]
        ], 200);
    }

    /**
     * Change password
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changePassword(Request $request)
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Verify current password
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Password lama tidak sesuai'
            ], 401);
        }

        // Update password
        $user->password = Hash::make($request->new_password);
        $user->save();

        // Revoke all tokens
        $user->tokens()->delete();

        // Create new token
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Password berhasil diubah',
            'data' => [
                'token' => $token,
                'token_type' => 'Bearer'
            ]
        ], 200);
    }
}
