<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ternak extends Model
{
    protected $table = 'ternak';

    protected $primaryKey = 'idTernak';

    protected $fillable = [
        'idPemilik',
        'namaTernak',
        'tanggalLahir',
        'berat',
        'fotoTernak',
    ];

    public function pemilik()
    {
        return $this->belongsTo(User::class, 'idPemilik');
    }
}
