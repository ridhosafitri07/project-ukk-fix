<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lokasi extends Model
{
    protected $table = 'lokasi';
    protected $primaryKey = 'id_lokasi';
    public $timestamps = false;
    
    protected $fillable = [
        'nama_lokasi',
        'kategori'
    ];
    
    protected $appends = ['kategori_badge'];
    
    protected $withCount = ['items'];

    public function listLokasi()
    {
        return $this->hasMany(ListLokasi::class, 'id_lokasi', 'id_lokasi');
    }

    public function items()
    {
        return $this->belongsToMany(Item::class, 'list_lokasi', 'id_lokasi', 'id_item');
    }
    
    public function getKategoriBadgeAttribute()
    {
        $badges = [
            'Kelas' => ['color' => 'blue', 'icon' => 'fa-chalkboard', 'label' => 'Kelas'],
            'Lab' => ['color' => 'purple', 'icon' => 'fa-flask', 'label' => 'Lab'],
            'Kantor' => ['color' => 'green', 'icon' => 'fa-building', 'label' => 'Kantor'],
            'Umum' => ['color' => 'gray', 'icon' => 'fa-door-open', 'label' => 'Umum'],
            'Area Luar' => ['color' => 'yellow', 'icon' => 'fa-tree', 'label' => 'Area Luar'],
        ];
        
        return $badges[$this->kategori] ?? ['color' => 'gray', 'icon' => 'fa-map-marker-alt', 'label' => $this->kategori ?? 'Lainnya'];
    }
}