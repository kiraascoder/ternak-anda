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
        'tanggalRekomendasi',
        'jenisPakan',
        'kategori',
        'jumlah',
        'satuan',
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
