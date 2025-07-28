<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The table associated with the model.
     */
    protected $table = 'users';

    /**
     * The primary key associated with the table.
     */
    protected $primaryKey = 'idUser';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nama',
        'email',
        'password',
        'phone',
        'role',
        'status'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */

    protected $casts = [
        'password' => 'string',
    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relationship dengan Ternak (sebagai pemilik)
     * Foreign key: idPemilik pada tabel ternaks -> references: idUser
     */
    public function ternak()
    {
        return $this->hasMany(Ternak::class, 'idPemilik', 'idUser');
    }

    /**
     * Relationship dengan Konsultasi (generic)
     */
    public function konsultasi()
    {
        return $this->hasMany(Konsultasi::class, 'idPeternak', 'idUser');
    }

    /**
     * Relationship dengan Perawatan
     */
    public function perawatan()
    {
        return $this->hasMany(Perawatan::class, 'idPenyuluh', 'idUser');
    }

    /**
     * Relationship dengan Laporan (sebagai penyuluh)
     * Foreign key: idPenyuluh pada tabel laporan_kesehatan -> references: idUser
     */
    public function laporanSebagaiPenyuluh()
    {
        return $this->hasMany(Laporan::class, 'idPenyuluh', 'idUser');
    }

    /**
     * Relationship dengan Laporan (sebagai peternak)
     * Foreign key: idPeternak pada tabel laporan_kesehatan -> references: idUser
     */
    public function laporanSebagaiPeternak()
    {
        return $this->hasMany(Laporan::class, 'idPeternak', 'idUser');
    }

    /**
     * Alias untuk backward compatibility
     */
    public function laporan()
    {
        return $this->laporanSebagaiPenyuluh();
    }

    /**
     * Relationship dengan Pakan
     */
    public function pakan()
    {
        return $this->hasMany(Pakan::class, 'idPenyuluh', 'idUser');
    }

    /**
     * Relationship dengan Konsultasi sebagai Peternak
     * Foreign key: idPeternak pada tabel konsultasis -> references: idUser
     */
    public function konsultasiSebagaiPeternak()
    {
        return $this->hasMany(Konsultasi::class, 'idPeternak', 'idUser');
    }

    /**
     * Relationship dengan Konsultasi sebagai Penyuluh
     * Foreign key: idPenyuluh pada tabel konsultasis -> references: idUser
     */
    public function konsultasiSebagaiPenyuluh()
    {
        return $this->hasMany(Konsultasi::class, 'idPenyuluh', 'idUser');
    }

    /**
     * Scope untuk filter berdasarkan role
     */
    public function scopeByRole($query, $role)
    {
        return $query->where('role', $role);
    }

    /**
     * Scope untuk peternak
     */
    public function scopePeternak($query)
    {
        return $query->where('role', 'peternak');
    }

    /**
     * Scope untuk penyuluh
     */
    public function scopePenyuluh($query)
    {
        return $query->where('role', 'penyuluh');
    }

    /**
     * Check if user is peternak
     */
    public function isPeternak()
    {
        return $this->role === 'peternak';
    }

    /**
     * Check if user is penyuluh
     */
    public function isPenyuluh()
    {
        return $this->role === 'penyuluh';
    }

    /**
     * Check if user is admin
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Get user's full name (alias untuk nama)
     */
    public function getFullNameAttribute()
    {
        return $this->nama;
    }

    /**
     * Get user's display name
     */
    public function getDisplayNameAttribute()
    {
        return $this->nama . ' (' . ucfirst($this->role) . ')';
    }
}
