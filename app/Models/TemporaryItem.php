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
        'nama_barang_baru',
        'lokasi_barang_baru'
    ];

    public function item()
    {
        return $this->belongsTo(Item::class, 'id_item', 'id_item');
    }
}