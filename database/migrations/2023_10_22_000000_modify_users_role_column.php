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
        // First modify the column to allow NULL temporarily
        DB::statement("ALTER TABLE user MODIFY COLUMN role ENUM('admin', 'petugas', 'pengguna', 'guru', 'siswa') NULL");
        
        // Then update the roles
        DB::statement("UPDATE user SET role = 'pengguna' WHERE role IN ('guru', 'siswa')");
        
        // Finally, set the column to the new ENUM with NOT NULL
        DB::statement("ALTER TABLE user MODIFY COLUMN role ENUM('admin', 'petugas', 'pengguna') NOT NULL DEFAULT 'pengguna'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE user MODIFY COLUMN role ENUM('admin', 'petugas', 'guru', 'siswa') NOT NULL DEFAULT 'siswa'");
    }
};