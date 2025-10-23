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
            if (!Schema::hasColumn('pengaduan', 'tgl_verifikasi')) {
                $table->timestamp('tgl_verifikasi')->nullable()->after('tgl_pengajuan');
            }
            if (!Schema::hasColumn('pengaduan', 'catatan_admin')) {
                $table->text('catatan_admin')->nullable()->after('status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengaduan', function (Blueprint $table) {
            $table->dropColumn(['tgl_verifikasi', 'catatan_admin']);
        });
    }
};