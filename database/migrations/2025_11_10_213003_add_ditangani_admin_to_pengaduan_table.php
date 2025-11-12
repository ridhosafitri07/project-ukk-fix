<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pengaduan', function (Blueprint $table) {
            // Tambah kolom untuk tracking apakah admin yang menangani
            $table->boolean('ditangani_admin')->default(false)->after('id_petugas');
            
            // Tambah kolom untuk nama admin yang menangani (opsional)
            $table->string('nama_admin', 100)->nullable()->after('ditangani_admin');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengaduan', function (Blueprint $table) {
            $table->dropColumn(['ditangani_admin', 'nama_admin']);
        });
    }
};
