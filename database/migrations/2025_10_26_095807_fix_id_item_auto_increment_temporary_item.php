<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Cek apakah id_item sudah auto increment
        $result = DB::select("SHOW COLUMNS FROM temporary_item WHERE Field = 'id_item'");
        
        if (!empty($result)) {
            $extra = $result[0]->Extra;
            
            // Jika belum auto_increment, ubah
            if (strpos($extra, 'auto_increment') === false) {
                DB::statement('ALTER TABLE temporary_item MODIFY COLUMN id_item BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY');
            }
        }
    }

    public function down()
    {
        DB::statement('ALTER TABLE temporary_item MODIFY COLUMN id_item BIGINT UNSIGNED NOT NULL');
    }
};