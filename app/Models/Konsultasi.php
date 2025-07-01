<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Konsultasi extends Model
{
    protected $table = 'konsultasi_kesehatan';

    protected $primaryKey = 'idKonsultasi';

    protected $fillable = [
        'idPeternak',
        'idPenyuluh',
        'idTernak',
        'judul_konsultasi',
        'kategori',
        'deskripsi',
        'status',
        'foto_ternak',
    ];
    public function penyuluh()
    {
        return $this->belongsTo(User::class, 'idPenyuluh', 'idUser');
    }

    public function ternak()
    {
        return $this->belongsTo(Ternak::class, 'idTernak', 'idTernak');
    }
    public function peternak()
    {
        return $this->belongsTo(User::class, 'idPeternak', 'idUser');
    }
}
