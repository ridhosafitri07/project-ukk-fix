<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TemporaryItem extends Model
{
    protected $table = 'temporary_item';
    protected $primaryKey = 'id_temporary';
    public $timestamps = false;
    
    protected $fillable = [
        'id_item',
        'id_pengaduan',
        'nama_barang_baru',
        'lokasi_barang_baru',
        'status_permintaan',
        'tanggal_permintaan',
        'tanggal_persetujuan',
        'catatan_admin',
        'catatan_petugas'
    ];

    protected $dates = [
        'tanggal_permintaan',
        'tanggal_persetujuan'
    ];

    public function item()
    {
        return $this->belongsTo(Item::class, 'id_item', 'id_item');
    }

    public function pengaduan()
    {
        return $this->belongsTo(Pengaduan::class, 'id_pengaduan', 'id_pengaduan');
    }
}