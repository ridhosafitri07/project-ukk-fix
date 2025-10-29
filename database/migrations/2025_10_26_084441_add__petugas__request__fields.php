<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('temporary_item', function (Blueprint $table) {
            // Add id_petugas for tracking who requested
            $table->integer('id_petugas')->nullable()->after('id_pengaduan');
            
            // Add alasan field
            $table->text('alasan_permintaan')->nullable()->after('lokasi_barang_baru');
            
            // Add foto bukti kerusakan
            $table->string('foto_kerusakan')->nullable()->after('alasan_permintaan');
        });
    }

    public function down(): void
    {
        Schema::table('temporary_item', function (Blueprint $table) {
            $table->dropColumn(['id_petugas', 'alasan_permintaan', 'foto_kerusakan']);
        });
    }
};