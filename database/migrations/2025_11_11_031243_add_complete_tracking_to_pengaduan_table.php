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
            // Tracking untuk setiap action
            $table->string('ditolak_oleh', 100)->nullable()->after('nama_admin')->comment('Nama user yang menolak');
            $table->timestamp('tgl_ditolak')->nullable()->after('ditolak_oleh')->comment('Tanggal ditolak');
            $table->string('diproses_oleh', 100)->nullable()->after('tgl_ditolak')->comment('Nama user yang memproses');
            $table->timestamp('tgl_mulai_proses')->nullable()->after('diproses_oleh')->comment('Tanggal mulai proses');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengaduan', function (Blueprint $table) {
            $table->dropColumn(['ditolak_oleh', 'tgl_ditolak', 'diproses_oleh', 'tgl_mulai_proses']);
        });
    }
};
