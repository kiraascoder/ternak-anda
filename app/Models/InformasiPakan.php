<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InformasiPakan extends Model
{
    protected $table = 'pakan';

    protected $primaryKey = 'idPakan';

    protected $fillable = [
        'idPakan',
        'judul',
        'deskripsi',
        'foto',
        'sumber',
        'jenis_pakan',
        'is_published',
        'created_at',
        'updated_at'
    ];
}
