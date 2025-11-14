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
            $table->dropColumn([
                'status_permintaan',
                'tanggal_persetujuan', 
                'catatan_admin',
                'catatan_petugas'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('temporary_item', function (Blueprint $table) {
            $table->enum('status_permintaan', ['Menunggu Persetujuan', 'Disetujui', 'Ditolak'])->default('Menunggu Persetujuan');
            $table->timestamp('tanggal_persetujuan')->nullable();
            $table->text('catatan_admin')->nullable();
            $table->text('catatan_petugas')->nullable();
        });
    }
};
