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

    protected $casts = [
        'tanggalLahir' => 'date',
    ];

    public function getUmurTextAttribute()
    {
        if (!$this->tanggalLahir) return 'Belum diketahui';

        $diff   = $this->tanggalLahir->diff(now());
        $years  = $diff->y;
        $months = $diff->m;

        if ($years < 1) {
            return ($years === 0 && $months === 0) ? '0 bulan' : $months . ' bulan';
        }
        return $years . ' tahun' . ($months ? ' ' . $months . ' bulan' : '');
    }

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
