<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Ternak extends Model
{
    protected $table = 'ternak';

    protected $primaryKey = 'idTernak';

    protected $fillable = [
        'idPemilik',
        'namaTernak',
        'tanggalLahir',
        'jenis',
        'jenis_kelamin',
        'berat',
        'status',
        'asal',
        'fotoTernak',
        'keterangan'
    ];

    public function pemilik()
    {
        return $this->belongsTo(User::class, 'idPemilik');
    }
    public function getUmurAttribute()
    {
        if ($this->tanggalLahir) {
            return Carbon::parse($this->tanggalLahir)->age;
        }
        return null;
    }
}
