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
            $table->dropColumn([
                'ditangani_admin',
                'nama_admin', 
                'ditolak_oleh',
                'tgl_ditolak',
                'diproses_oleh'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengaduan', function (Blueprint $table) {
            $table->boolean('ditangani_admin')->default(false);
            $table->string('nama_admin', 200)->nullable();
            $table->string('ditolak_oleh', 200)->nullable();
            $table->timestamp('tgl_ditolak')->nullable();
            $table->string('diproses_oleh', 200)->nullable();
        });
    }
};
