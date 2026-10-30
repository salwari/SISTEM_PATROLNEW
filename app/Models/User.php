<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'cabang_id',
        'nama',
        'email',
        'password',
        'role',
        'nomor_induk',
        'no_telepon',
        'alamat',
        'foto'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function jadwalPatroli()
    {
        return $this->hasMany(JadwalPatroli::class);
    }

    public function patroli()
    {
        return $this->hasMany(Patroli::class);
    }

    public function digantikanDalamHistori()
    {
        return $this->hasMany(PergantianPetugas::class, 'petugas_lama_id');
    }

    public function menggantikanDalamHistori()
    {
        return $this->hasMany(PergantianPetugas::class, 'petugas_baru_id');
    }

    public function cabang()
    {
        return $this->belongsTo(Cabang::class, 'cabang_id');
    }

    public function pemeriksaanKamarMandi()
    {
        return $this->hasMany(PemeriksaanKamarMandi::class, 'user_id');
    }
}
