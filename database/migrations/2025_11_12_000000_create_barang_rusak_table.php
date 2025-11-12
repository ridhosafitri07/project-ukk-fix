<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('barang_rusak', function (Blueprint $table) {
            $table->id('id_barang_rusak');
            $table->unsignedBigInteger('id_item')->nullable();
            $table->string('nama_item')->nullable();
            $table->string('lokasi')->nullable();
            $table->text('keterangan')->nullable();
            $table->unsignedBigInteger('id_petugas')->nullable();
            $table->timestamp('tanggal_lapor')->nullable();
            $table->string('status')->default('Terdata');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('barang_rusak');
    }
};
