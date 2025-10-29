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
        'id_petugas',
        'nama_barang_baru',
        'lokasi_barang_baru',
        'alasan_permintaan',
        'foto_kerusakan',
        'status_permintaan',
        'tanggal_permintaan',
        'tanggal_persetujuan',
        'catatan_admin',
        'catatan_petugas',
    ];

    // TAMBAHKAN INI:
    protected $casts = [
        'tanggal_permintaan' => 'datetime',
        'tanggal_persetujuan' => 'datetime',
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