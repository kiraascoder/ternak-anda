<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pakan extends Model
{
    protected $table = 'rekomendasi_pakan';

    protected $primaryKey = 'idRekomendasi';

    protected $fillable = [
        'idTernak',
        'idPenyuluh',
        'tanggalPakan',
        'jenisPakan',
        'jumlah',
        'saran',
    ];


    public function ternak()
    {
        return $this->belongsTo(Ternak::class, 'idTernak');
    }


    public function penyuluh()
    {
        return $this->belongsTo(User::class, 'idPenyuluh');
    }
}
