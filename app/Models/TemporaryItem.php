<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TemporaryItem extends Model
{
    protected $table = 'temporary_item';
    protected $primaryKey = 'id_item';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'id_pengaduan',
        'nama_barang_baru',
        'lokasi_barang_baru',
        'alasan_permintaan',
        'deskripsi_barang_baru'
    ];

    // Cast tanggal_permintaan to datetime if it exists
    protected $casts = [
        'tanggal_permintaan' => 'datetime'
    ];

    public function pengaduan()
    {
        return $this->belongsTo(Pengaduan::class, 'id_pengaduan', 'id_pengaduan');
    }

    public function petugas()
    {
        return $this->belongsTo(User::class, 'id_petugas', 'id_pengguna');
    }
}