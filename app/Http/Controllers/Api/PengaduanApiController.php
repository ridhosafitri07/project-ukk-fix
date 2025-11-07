<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pengaduan;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class PengaduanApiController extends Controller
{
    /**
     * Get all pengaduan by authenticated user
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $user = $request->user();
        
        $pengaduans = Pengaduan::where('id_user', $user->id_user)
            ->with(['item', 'petugas.user'])
            ->orderBy('tgl_pengajuan', 'desc')
            ->get()
            ->map(function($pengaduan) {
                return $this->formatPengaduan($pengaduan);
            });

        return response()->json([
            'success' => true,
            'data' => $pengaduans
        ], 200);
    }

    /**
     * Get pengaduan by status
     * 
     * @param Request $request
     * @param string $status
     * @return \Illuminate\Http\JsonResponse
     */
    public function getByStatus(Request $request, $status)
    {
        $user = $request->user();
        
        $validStatus = ['pending', 'proses', 'selesai', 'ditolak'];
        
        if (!in_array($status, $validStatus)) {
            return response()->json([
                'success' => false,
                'message' => 'Status tidak valid'
            ], 400);
        }

        $pengaduans = Pengaduan::where('id_user', $user->id_user)
            ->where('status', $status)
            ->with(['item', 'petugas.user'])
            ->orderBy('tgl_pengajuan', 'desc')
            ->get()
            ->map(function($pengaduan) {
                return $this->formatPengaduan($pengaduan);
            });

        return response()->json([
            'success' => true,
            'data' => $pengaduans
        ], 200);
    }

    /**
     * Show single pengaduan
     * 
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, $id)
    {
        $user = $request->user();
        
        $pengaduan = Pengaduan::where('id_pengaduan', $id)
            ->where('id_user', $user->id_user)
            ->with(['item', 'petugas.user'])
            ->first();

        if (!$pengaduan) {
            return response()->json([
                'success' => false,
                'message' => 'Pengaduan tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $this->formatPengaduan($pengaduan)
        ], 200);
    }

    /**
     * Create new pengaduan
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Bersihkan input foto jika string kosong (untuk support JSON request)
        if ($request->has('foto') && empty($request->foto)) {
            $request->request->remove('foto');
        }

        $validator = Validator::make($request->all(), [
            'nama_pengaduan' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'lokasi' => 'required|string|max:255',
            'id_item' => 'required|exists:items,id_item',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();
        
        // Handle file upload
        $fotoPath = null;
        if ($request->hasFile('foto') && $request->file('foto')->isValid()) {
            $foto = $request->file('foto');
            $filename = time() . '_' . $foto->getClientOriginalName();
            $fotoPath = $foto->storeAs('pengaduan', $filename, 'public');
        }

        // Create pengaduan
        $pengaduan = Pengaduan::create([
            'nama_pengaduan' => $request->nama_pengaduan,
            'deskripsi' => $request->deskripsi,
            'lokasi' => $request->lokasi,
            'id_item' => $request->id_item,
            'foto' => $fotoPath,
            'id_user' => $user->id_user,
            'status' => 'pending',
            'tgl_pengajuan' => now(),
        ]);

        $pengaduan->load(['item', 'petugas.user']);

        return response()->json([
            'success' => true,
            'message' => 'Pengaduan berhasil dibuat',
            'data' => $this->formatPengaduan($pengaduan)
        ], 201);
    }

    /**
     * Update pengaduan
     * 
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $user = $request->user();
        
        $pengaduan = Pengaduan::where('id_pengaduan', $id)
            ->where('id_user', $user->id_user)
            ->first();

        if (!$pengaduan) {
            return response()->json([
                'success' => false,
                'message' => 'Pengaduan tidak ditemukan'
            ], 404);
        }

        // Only allow update if status is pending
        if ($pengaduan->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Pengaduan yang sudah diproses tidak dapat diubah'
            ], 403);
        }

        // Bersihkan input foto jika string kosong
        if ($request->has('foto') && empty($request->foto)) {
            $request->request->remove('foto');
        }

        $validator = Validator::make($request->all(), [
            'nama_pengaduan' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
            'lokasi' => 'nullable|string|max:255',
            'id_item' => 'nullable|exists:items,id_item',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Update fields
        if ($request->has('nama_pengaduan')) {
            $pengaduan->nama_pengaduan = $request->nama_pengaduan;
        }
        if ($request->has('deskripsi')) {
            $pengaduan->deskripsi = $request->deskripsi;
        }
        if ($request->has('lokasi')) {
            $pengaduan->lokasi = $request->lokasi;
        }
        if ($request->has('id_item')) {
            $pengaduan->id_item = $request->id_item;
        }

        // Handle file upload
        if ($request->hasFile('foto')) {
            // Delete old file
            if ($pengaduan->foto) {
                Storage::disk('public')->delete($pengaduan->foto);
            }

            $foto = $request->file('foto');
            $filename = time() . '_' . $foto->getClientOriginalName();
            $fotoPath = $foto->storeAs('pengaduan', $filename, 'public');
            $pengaduan->foto = $fotoPath;
        }

        $pengaduan->save();
        $pengaduan->load(['item', 'petugas.user']);

        return response()->json([
            'success' => true,
            'message' => 'Pengaduan berhasil diupdate',
            'data' => $this->formatPengaduan($pengaduan)
        ], 200);
    }

    /**
     * Delete pengaduan
     * 
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, $id)
    {
        $user = $request->user();
        
        $pengaduan = Pengaduan::where('id_pengaduan', $id)
            ->where('id_user', $user->id_user)
            ->first();

        if (!$pengaduan) {
            return response()->json([
                'success' => false,
                'message' => 'Pengaduan tidak ditemukan'
            ], 404);
        }

        // Only allow delete if status is pending
        if ($pengaduan->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Pengaduan yang sudah diproses tidak dapat dihapus'
            ], 403);
        }

        // Delete file if exists
        if ($pengaduan->foto) {
            Storage::disk('public')->delete($pengaduan->foto);
        }

        $pengaduan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Pengaduan berhasil dihapus'
        ], 200);
    }

    /**
     * Format pengaduan data
     * 
     * @param Pengaduan $pengaduan
     * @return array
     */
    private function formatPengaduan($pengaduan)
    {
        return [
            'id_pengaduan' => $pengaduan->id_pengaduan,
            'nama_pengaduan' => $pengaduan->nama_pengaduan,
            'deskripsi' => $pengaduan->deskripsi,
            'lokasi' => $pengaduan->lokasi,
            'foto' => $pengaduan->foto ? url('storage/' . $pengaduan->foto) : null,
            'status' => $pengaduan->status,
            'tgl_pengajuan' => $pengaduan->tgl_pengajuan,
            'tgl_verifikasi' => $pengaduan->tgl_verifikasi,
            'tgl_selesai' => $pengaduan->tgl_selesai,
            'catatan_admin' => $pengaduan->catatan_admin,
            'saran_petugas' => $pengaduan->saran_petugas,
            'item' => $pengaduan->item ? [
                'id_item' => $pengaduan->item->id_item,
                'nama_item' => $pengaduan->item->nama_item,
            ] : null,
            'petugas' => $pengaduan->petugas ? [
                'id_petugas' => $pengaduan->petugas->id_petugas,
                'nama_petugas' => $pengaduan->petugas->user->nama_pengguna ?? null,
            ] : null,
        ];
    }
}
