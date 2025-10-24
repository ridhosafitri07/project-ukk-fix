<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Ambil semua user dengan role petugas
        $petugasUsers = DB::table('user')->where('role', 'petugas')->get();
        
        foreach ($petugasUsers as $user) {
            // Cek apakah user sudah punya data petugas
            $existingPetugas = DB::table('petugas')
                ->where('id_user', $user->id_user)
                ->first();
            
            // Jika belum ada, buat data petugas baru
            if (!$existingPetugas) {
                // Cek juga berdasarkan nama (fallback untuk data lama)
                $existingByName = DB::table('petugas')
                    ->where('nama', $user->nama_pengguna)
                    ->whereNull('id_user')
                    ->first();
                
                if ($existingByName) {
                    // Update data petugas yang sudah ada dengan id_user
                    DB::table('petugas')
                        ->where('id_petugas', $existingByName->id_petugas)
                        ->update(['id_user' => $user->id_user]);
                } else {
                    // Buat data petugas baru
                    DB::table('petugas')->insert([
                        'nama' => $user->nama_pengguna,
                        'gender' => 'L', // Default
                        'telp' => '0800000000', // Default, bisa diubah manual
                        'id_user' => $user->id_user,
                    ]);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Tidak perlu rollback, karena data sudah dibuat
        // dan mungkin sudah digunakan
    }
};
