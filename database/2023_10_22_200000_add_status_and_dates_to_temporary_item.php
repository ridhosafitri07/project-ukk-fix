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
        Schema::table('temporary_item', function (Blueprint $table) {
            if (!Schema::hasColumn('temporary_item', 'status_permintaan')) {
                $table->enum('status_permintaan', ['Menunggu Persetujuan', 'Disetujui', 'Ditolak'])
                    ->default('Menunggu Persetujuan')
                    ->after('alasan_penggantian');
            }
            
            if (!Schema::hasColumn('temporary_item', 'tanggal_permintaan')) {
                $table->timestamp('tanggal_permintaan')->useCurrent()->after('status_permintaan');
            }
            
            if (!Schema::hasColumn('temporary_item', 'tanggal_persetujuan')) {
                $table->timestamp('tanggal_persetujuan')->nullable()->after('tanggal_permintaan');
            }
            
            if (!Schema::hasColumn('temporary_item', 'catatan_admin')) {
                $table->text('catatan_admin')->nullable()->after('tanggal_persetujuan');
            }
            
            if (!Schema::hasColumn('temporary_item', 'catatan_petugas')) {
                $table->text('catatan_petugas')->nullable()->after('catatan_admin');
            }
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