<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lokasi extends Model
{
    protected $table = 'lokasi';
    protected $primaryKey = 'id_lokasi';
    public $timestamps = false;
    
    protected $fillable = [
        'nama_lokasi'
    ];

    public function listLokasi()
    {
        return $this->hasMany(ListLokasi::class, 'id_lokasi', 'id_lokasi');
    }

    public function items()
    {
        return $this->belongsToMany(Item::class, 'list_lokasi', 'id_lokasi', 'id_item');
    }
}