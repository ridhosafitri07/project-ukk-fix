<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixTemporaryItemAndPengaduanStructure extends Migration
{
    public function up()
    {
        Schema::dropIfExists('temporary_item');

        Schema::create('temporary_item', function (Blueprint $table) {
            $table->increments('id_temporary');
            $table->integer('id_item');
            $table->bigInteger('id_pengaduan')->unsigned()->nullable();
            $table->string('nama_barang_baru', 50)->nullable();
            $table->string('lokasi_barang_baru', 50)->nullable();
            $table->enum('status_permintaan', ['Menunggu Persetujuan', 'Disetujui', 'Ditolak'])
                  ->default('Menunggu Persetujuan');
            $table->timestamp('tanggal_permintaan')->useCurrent();
            $table->timestamp('tanggal_persetujuan')->nullable();
            $table->text('catatan_admin')->nullable();
            $table->text('catatan_petugas')->nullable();
            
            $table->foreign('id_item')
                  ->references('id_item')
                  ->on('items')
                  ->onDelete('cascade');

            $table->index('id_item', 'idx_id_item');
        });
    }

    public function down()
    {
        Schema::dropIfExists('temporary_item');
    }
}