<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    protected $table = 'laporan_kesehatan';

    protected $primaryKey = 'idLaporan';

    protected $fillable = [
        'idTernak',
        'idPeternak',
        'idPenyuluh',
        'tanggalLaporan',
        'kondisi',
        'catatan',
    ];


    public function ternak()
    {
        return $this->belongsTo(Ternak::class, 'idTernak');
    }


    public function peternak()
    {
        return $this->belongsTo(User::class, 'idPeternak');
    }


    public function penyuluh()
    {
        return $this->belongsTo(User::class, 'idPenyuluh');
    }
}
