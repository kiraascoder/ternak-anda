<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KonsultasiSaran extends Model
{
    use HasFactory;

    protected $table = 'konsultasi_sarans';

    protected $primaryKey = 'id';

    protected $fillable = [
        'idKonsultasi',
        'idPenyuluh',
        'judul',
        'isi',
    ];

    /**
     * Relasi ke Konsultasi
     */
    public function konsultasi()
    {
        return $this->belongsTo(Konsultasi::class, 'konsultasi_id');
    }

    /**
     * Relasi ke User (penyuluh)
     */
    public function penyuluh()
    {
        return $this->belongsTo(User::class, 'idPenyuluh');
    }
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'idPenyuluh', 'idUser')
            ->select(['idUser', 'nama']);
    }
}
