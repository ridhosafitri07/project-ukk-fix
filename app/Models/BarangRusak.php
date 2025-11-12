<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BarangRusak extends Model
{
    protected $table = 'barang_rusak';
    protected $primaryKey = 'id_barang_rusak';

    protected $fillable = [
        'id_item',
        'nama_item',
        'lokasi',
        'keterangan',
        'id_petugas',
        'tanggal_lapor',
        'status'
    ];

    protected $dates = ['tanggal_lapor'];

    public function item()
    {
        return $this->belongsTo(Item::class, 'id_item', 'id_item');
    }

    public function petugas()
    {
        return $this->belongsTo(Petugas::class, 'id_petugas', 'id_petugas');
    }
}
