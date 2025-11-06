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
        Schema::table('lokasi', function (Blueprint $table) {
            $table->enum('kategori', ['kelas', 'lab', 'kantor', 'umum', 'area_luar'])
                  ->default('umum')
                  ->after('nama_lokasi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lokasi', function (Blueprint $table) {
            $table->dropColumn(['kategori', 'created_at', 'updated_at']);
        });
    }
};
