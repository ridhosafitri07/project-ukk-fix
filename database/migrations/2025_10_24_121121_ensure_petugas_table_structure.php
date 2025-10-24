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
        // Cek apakah kolom id_user sudah ada
        if (!Schema::hasColumn('petugas', 'id_user')) {
            Schema::table('petugas', function (Blueprint $table) {
                $table->integer('id_user')->nullable()->after('telp');
                $table->foreign('id_user')->references('id_user')->on('user')->onDelete('set null');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('petugas', 'id_user')) {
            Schema::table('petugas', function (Blueprint $table) {
                $table->dropForeign(['id_user']);
                $table->dropColumn('id_user');
            });
        }
    }
};
