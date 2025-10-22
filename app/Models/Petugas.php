<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Petugas extends Model
{
    protected $table = 'petugas';
    protected $primaryKey = 'id_petugas';
    public $timestamps = false;
    
    protected $fillable = [
        'nama',
        'gender',
        'telp'
    ];

    public function pengaduans()
    {
        return $this->hasMany(Pengaduan::class, 'id_petugas', 'id_petugas');
    }
}