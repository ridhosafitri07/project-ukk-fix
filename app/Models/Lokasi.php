<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lokasi extends Model
{
    protected $table = 'lokasi';
    protected $primaryKey = 'id_lokasi';
    public $timestamps = true;
    
    protected $fillable = [
        'nama_lokasi',
        'kategori'
    ];

    protected $casts = [
        'kategori' => 'string',
    ];

    // Accessor untuk badge warna kategori
    public function getKategoriBadgeAttribute()
    {
        $badges = [
            'kelas' => ['color' => 'blue', 'icon' => 'fa-chalkboard', 'label' => 'Kelas'],
            'lab' => ['color' => 'purple', 'icon' => 'fa-flask', 'label' => 'Laboratorium'],
            'kantor' => ['color' => 'green', 'icon' => 'fa-building', 'label' => 'Kantor'],
            'umum' => ['color' => 'gray', 'icon' => 'fa-door-open', 'label' => 'Umum'],
            'area_luar' => ['color' => 'yellow', 'icon' => 'fa-tree', 'label' => 'Area Luar'],
        ];
        
        return $badges[$this->kategori] ?? $badges['umum'];
    }

    public function listLokasi()
    {
        return $this->hasMany(ListLokasi::class, 'id_lokasi', 'id_lokasi');
    }

    public function items()
    {
        return $this->belongsToMany(Item::class, 'list_lokasi', 'id_lokasi', 'id_item');
    }

    // Accessor untuk jumlah barang
    public function getJumlahBarangAttribute()
    {
        return $this->items()->count();
    }
}