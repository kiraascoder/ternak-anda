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
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */

    protected $table = 'users';

    protected $primaryKey = 'idUser';

    protected $fillable = [
        'nama',
        'email',
        'password',
        'phone',
        'role',        
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
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

    public function ternak()
    {
        return $this->hasMany(Ternak::class, 'idPemilik');
    }

    public function konsultasi()
    {
        return $this->hasMany(Konsultasi::class);
    }

    public function perawatan()
    {
        return $this->hasMany(Perawatan::class);
    }

    public function laporan()
    {
        return $this->hasMany(Laporan::class);
    }

    public function pakan()
    {
        return $this->hasMany(Pakan::class);
    }

    public function konsultasiSebagaiPeternak()
    {
        return $this->hasMany(Konsultasi::class, 'idPeternak');
    }

    public function konsultasiSebagaiPenyuluh()
    {
        return $this->hasMany(Konsultasi::class, 'idPenyuluh');
    }
}
