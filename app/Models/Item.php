<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $table = 'items';
    protected $primaryKey = 'id_item';
    public $timestamps = false;
    
    protected $fillable = [
        'nama_item',
        'lokasi',
        'deskripsi',
        'foto'
    ];

    public function lokasis()
    {
        return $this->belongsToMany(Lokasi::class, 'list_lokasi', 'id_item', 'id_lokasi');
    }

    public function temporaryItems()
    {
        return $this->hasMany(TemporaryItem::class, 'id_item', 'id_item');
    }

    public function pengaduans()
    {
        return $this->hasMany(Pengaduan::class, 'id_item', 'id_item');
    }
}