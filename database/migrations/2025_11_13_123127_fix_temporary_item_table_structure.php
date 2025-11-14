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
        // Drop table if exists and recreate with correct structure
        Schema::dropIfExists('temporary_item');
        
        Schema::create('temporary_item', function (Blueprint $table) {
            $table->id('id_item');
            $table->integer('id_pengaduan')->nullable();
            $table->integer('id_petugas')->nullable();
            $table->string('nama_barang_baru', 255)->nullable();
            $table->string('lokasi_barang_baru', 255)->nullable();
            $table->text('alasan_permintaan')->nullable();
            $table->string('foto_kerusakan', 255)->nullable();
            $table->enum('status_permintaan', ['Menunggu Persetujuan', 'Disetujui', 'Ditolak'])->default('Menunggu Persetujuan');
            $table->timestamp('tanggal_permintaan')->useCurrent();
            $table->timestamp('tanggal_persetujuan')->nullable();
            $table->text('catatan_admin')->nullable();
            $table->text('catatan_petugas')->nullable();
            
            // Foreign key
            $table->foreign('id_pengaduan')->references('id_pengaduan')->on('pengaduan')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('temporary_item');
    }
};
