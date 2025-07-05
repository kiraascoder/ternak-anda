<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Laporan extends Model
{
    use HasFactory;

    protected $table = 'laporan_kesehatan';

    protected $fillable = [
        'idPenyuluh',
        'idPeternak',
        'idTernak',
        'tanggal_pemeriksaan',
        'berat_badan',
        'suhu_tubuh',
        'nafsu_makan',
        'pernafasan',
        'kulit_bulu',
        'mata_hidung',
        'feses',
        'aktivitas',
        'tindakan',
        'rekomendasi',
        'status_kesehatan'
    ];

    protected $casts = [
        'tanggal_pemeriksaan' => 'datetime',
        'berat_badan' => 'decimal:2',
        'suhu_tubuh' => 'decimal:1',
    ];

    /**
     * Relationship dengan User (Penyuluh)
     * Foreign key: idPenyuluh -> references: idUser on users table
     */
    public function penyuluh()
    {
        return $this->belongsTo(User::class, 'idPenyuluh', 'idUser');
    }

    /**
     * Relationship dengan User (Peternak)
     * Foreign key: idPeternak -> references: idUser on users table
     */
    public function peternak()
    {
        return $this->belongsTo(User::class, 'idPeternak', 'idUser');
    }

    /**
     * Relationship dengan Ternak
     * Foreign key: idTernak -> references: idTernak on ternaks table
     */
    public function ternak()
    {
        return $this->belongsTo(Ternak::class, 'idTernak', 'idTernak');
    }

    /**
     * Scope untuk filter berdasarkan penyuluh
     */
    public function scopeByPenyuluh($query, $penyuluhId)
    {
        return $query->where('idPenyuluh', $penyuluhId);
    }

    /**
     * Scope untuk filter berdasarkan peternak
     */
    public function scopeByPeternak($query, $peternakId)
    {
        return $query->where('idPeternak', $peternakId);
    }

    /**
     * Scope untuk filter berdasarkan ternak
     */
    public function scopeByTernak($query, $ternakId)
    {
        return $query->where('idTernak', $ternakId);
    }

    /**
     * Scope untuk filter berdasarkan status kesehatan
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status_kesehatan', $status);
    }

    /**
     * Scope untuk filter berdasarkan periode
     */
    public function scopeByPeriod($query, $startDate, $endDate)
    {
        return $query->whereBetween('tanggal_pemeriksaan', [$startDate, $endDate]);
    }

    /**
     * Scope untuk laporan terbaru
     */
    public function scopeLatest($query)
    {
        return $query->orderBy('tanggal_pemeriksaan', 'desc');
    }

    /**
     * Accessor untuk format tanggal pemeriksaan
     */
    public function getFormattedTanggalPemeriksaanAttribute()
    {
        return $this->tanggal_pemeriksaan->format('d F Y, H:i');
    }

    /**
     * Accessor untuk format tanggal pemeriksaan pendek
     */
    public function getShortTanggalPemeriksaanAttribute()
    {
        return $this->tanggal_pemeriksaan->format('d/m/Y');
    }

    /**
     * Accessor untuk status kesehatan dalam bahasa Indonesia
     */
    public function getStatusKesehatanTextAttribute()
    {
        $statuses = [
            'sehat' => 'Sehat',
            'perlu_perhatian' => 'Perlu Perhatian',
            'sakit' => 'Sakit'
        ];

        return $statuses[$this->status_kesehatan] ?? 'Tidak Diketahui';
    }

    /**
     * Accessor untuk CSS class status kesehatan
     */
    public function getStatusKesehatanClassAttribute()
    {
        $classes = [
            'sehat' => 'bg-green-100 text-green-800',
            'perlu_perhatian' => 'bg-yellow-100 text-yellow-800',
            'sakit' => 'bg-red-100 text-red-800'
        ];

        return $classes[$this->status_kesehatan] ?? 'bg-gray-100 text-gray-800';
    }

    /**
     * Accessor untuk nafsu makan dalam bahasa Indonesia
     */
    public function getNafsuMakanTextAttribute()
    {
        $texts = [
            'baik' => 'Baik',
            'menurun' => 'Menurun',
            'tidak_ada' => 'Tidak Ada'
        ];

        return $texts[$this->nafsu_makan] ?? 'Tidak Diketahui';
    }

    /**
     * Accessor untuk pernafasan dalam bahasa Indonesia
     */
    public function getPernafasanTextAttribute()
    {
        $texts = [
            'normal' => 'Normal',
            'cepat' => 'Cepat',
            'lambat' => 'Lambat',
            'sesak' => 'Sesak'
        ];

        return $texts[$this->pernafasan] ?? 'Tidak Diketahui';
    }

    /**
     * Accessor untuk kulit bulu dalam bahasa Indonesia
     */
    public function getKulitBuluTextAttribute()
    {
        $texts = [
            'normal' => 'Normal',
            'kusam' => 'Kusam',
            'luka' => 'Ada Luka',
            'parasit' => 'Ada Parasit'
        ];

        return $texts[$this->kulit_bulu] ?? 'Tidak Diketahui';
    }

    /**
     * Accessor untuk mata hidung dalam bahasa Indonesia
     */
    public function getMataHidungTextAttribute()
    {
        $texts = [
            'normal' => 'Normal',
            'berair' => 'Berair',
            'bengkak' => 'Bengkak',
            'bernanah' => 'Bernanah'
        ];

        return $texts[$this->mata_hidung] ?? 'Tidak Diketahui';
    }

    /**
     * Accessor untuk feses dalam bahasa Indonesia
     */
    public function getFesesTextAttribute()
    {
        $texts = [
            'normal' => 'Normal',
            'encer' => 'Encer',
            'keras' => 'Keras',
            'berdarah' => 'Berdarah'
        ];

        return $texts[$this->feses] ?? 'Tidak Diketahui';
    }

    /**
     * Accessor untuk aktivitas dalam bahasa Indonesia
     */
    public function getAktivitasTextAttribute()
    {
        $texts = [
            'aktif' => 'Aktif',
            'lesu' => 'Lesu',
            'gelisah' => 'Gelisah',
            'lemas' => 'Lemas'
        ];

        return $texts[$this->aktivitas] ?? 'Tidak Diketahui';
    }

    /**
     * Method untuk mendapatkan indikator suhu
     */
    public function getSuhuIndicatorAttribute()
    {
        if ($this->suhu_tubuh >= 37.5 && $this->suhu_tubuh <= 39.5) {
            return [
                'status' => 'normal',
                'text' => 'Normal',
                'class' => 'text-green-600'
            ];
        } elseif ($this->suhu_tubuh < 37.5) {
            return [
                'status' => 'rendah',
                'text' => 'Rendah (Hipotermia)',
                'class' => 'text-blue-600'
            ];
        } else {
            return [
                'status' => 'tinggi',
                'text' => 'Tinggi (Demam)',
                'class' => 'text-red-600'
            ];
        }
    }

    /**
     * Method untuk menghitung skor kesehatan
     */
    public function getHealthScoreAttribute()
    {
        $score = 0;
        $maxScore = 7;

        // Suhu normal
        if ($this->suhu_tubuh >= 37.5 && $this->suhu_tubuh <= 39.5) $score++;

        // Kondisi normal lainnya
        if ($this->nafsu_makan === 'baik') $score++;
        if ($this->pernafasan === 'normal') $score++;
        if ($this->kulit_bulu === 'normal') $score++;
        if ($this->mata_hidung === 'normal') $score++;
        if ($this->feses === 'normal') $score++;
        if ($this->aktivitas === 'aktif') $score++;

        return [
            'score' => $score,
            'max_score' => $maxScore,
            'percentage' => round(($score / $maxScore) * 100)
        ];
    }

    /**
     * Static method untuk statistik laporan
     */
    public static function getStatistics($penyuluhId = null, $period = null)
    {
        $query = self::query();

        if ($penyuluhId) {
            $query->where('idPenyuluh', $penyuluhId);
        }

        if ($period) {
            $query->where('tanggal_pemeriksaan', '>=', Carbon::parse($period));
        }

        return [
            'total_laporan' => $query->count(),
            'sehat' => $query->where('status_kesehatan', 'sehat')->count(),
            'perlu_perhatian' => $query->where('status_kesehatan', 'perlu_perhatian')->count(),
            'sakit' => $query->where('status_kesehatan', 'sakit')->count(),
            'laporan_bulan_ini' => $query->whereMonth('tanggal_pemeriksaan', now()->month)
                ->whereYear('tanggal_pemeriksaan', now()->year)
                ->count()
        ];
    }

    /**
     * Method untuk export data
     */
    public function toExportArray()
    {
        return [
            'ID' => $this->id,
            'Tanggal Pemeriksaan' => $this->formatted_tanggal_pemeriksaan,
            'Nama Peternak' => $this->peternak->nama ?? '-',
            'Nama Ternak' => $this->ternak->namaTernak ?? '-',
            'Jenis Ternak' => $this->ternak->jenis ?? '-',
            'Berat Badan (kg)' => $this->berat_badan ?? '-',
            'Suhu Tubuh (°C)' => $this->suhu_tubuh,
            'Nafsu Makan' => $this->nafsu_makan_text,
            'Pernafasan' => $this->pernafasan_text,
            'Kulit & Bulu' => $this->kulit_bulu_text,
            'Mata & Hidung' => $this->mata_hidung_text,
            'Feses' => $this->feses_text,
            'Aktivitas' => $this->aktivitas_text,
            'Tindakan' => $this->tindakan,
            'Rekomendasi' => $this->rekomendasi,
            'Status Kesehatan' => $this->status_kesehatan_text,
            'Penyuluh' => $this->penyuluh->nama ?? '-'
        ];
    }
}
