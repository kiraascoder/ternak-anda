<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Informasi extends Model
{

    protected $primaryKey = 'idInformasi';

    protected $fillable = [
        'judul',
        'deskripsi',
        'tanggal_kegiatan',
        'lokasi',
        'kategori',
        'idPenyuluh',
        'foto'
    ];
    
}
