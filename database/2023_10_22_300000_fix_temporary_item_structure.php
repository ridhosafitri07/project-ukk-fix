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
        // Drop kolom yang mungkin sudah ada
        Schema::table('temporary_item', function (Blueprint $table) {
            $table->dropColumn(['status_permintaan', 'tanggal_permintaan', 'tanggal_persetujuan', 'catatan_admin', 'catatan_petugas']);
        });

        // Tambah kolom baru dengan definisi yang benar
        DB::statement("ALTER TABLE temporary_item ADD COLUMN status_permintaan ENUM('Menunggu Persetujuan', 'Disetujui', 'Ditolak') NOT NULL DEFAULT 'Menunggu Persetujuan' AFTER alasan_penggantian");
        
        Schema::table('temporary_item', function (Blueprint $table) {
            $table->timestamp('tanggal_permintaan')->useCurrent()->after('status_permintaan');
            $table->timestamp('tanggal_persetujuan')->nullable()->after('tanggal_permintaan');
            $table->text('catatan_admin')->nullable()->after('tanggal_persetujuan');
            $table->text('catatan_petugas')->nullable()->after('catatan_admin');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('temporary_item', function (Blueprint $table) {
            $table->dropColumn([
                'status_permintaan',
                'tanggal_permintaan',
                'tanggal_persetujuan',
                'catatan_admin',
                'catatan_petugas'
            ]);
        });
    }
};