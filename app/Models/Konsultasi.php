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
        'tanggalKonsultasi',
        'keluhan',
        'respon',
    ];
}
