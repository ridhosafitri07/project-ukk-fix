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
        Schema::create('temporary_item', function (Blueprint $table) {
            $table->id('id_item');
            $table->foreignId('id_pengaduan')->constrained('pengaduan', 'id_pengaduan')->onDelete('cascade');
            $table->string('nama_barang');
            $table->text('spesifikasi')->nullable();
            $table->integer('jumlah');
            $table->text('alasan_penggantian');
            $table->enum('status_permintaan', ['Menunggu Persetujuan', 'Disetujui', 'Ditolak'])->default('Menunggu Persetujuan');
            $table->timestamp('tanggal_permintaan')->useCurrent();
            $table->timestamp('tanggal_persetujuan')->nullable();
            $table->text('catatan_admin')->nullable();
            $table->text('catatan_petugas')->nullable();
            $table->timestamps();
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