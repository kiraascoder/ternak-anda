<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Perawatan extends Model
{
    protected $table = 'perawatan';

    protected $primaryKey = 'idPerawatan';

    protected $fillable = [
        'idTernak',
        'idPenyuluh',
        'tanggalPerawatan',
        'jenisPerawatan',
        'deskripsi',
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
