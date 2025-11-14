<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengaduan extends Model
{
    protected $table = 'pengaduan';
    protected $primaryKey = 'id_pengaduan';
    public $timestamps = false;
    
    protected $fillable = [
        'nama_pengaduan',
        'deskripsi',
        'lokasi',
        'foto',
        'status',
        'id_user',
        'id_petugas',
        'id_item',
        'tgl_pengajuan',
        'tgl_selesai',
        'tgl_verifikasi',
        'catatan_admin',
        'saran_petugas',
        'tgl_mulai_proses'
    ];

    protected $dates = [
        'tgl_pengajuan',
        'tgl_selesai',
        'tgl_verifikasi',
        'tgl_mulai_proses'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function petugas()
    {
        return $this->belongsTo(Petugas::class, 'id_petugas', 'id_petugas');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'id_item', 'id_item');
    }

    public function temporary_items()
    {
        return $this->hasMany(TemporaryItem::class, 'id_pengaduan', 'id_pengaduan');
    }
}