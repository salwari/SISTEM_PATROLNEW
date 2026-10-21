<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cabang extends Model
{
    use HasFactory;

    protected $table = 'cabang';

    protected $fillable = [
        'nama_cabang',
        'alamat_cabang',
    ];

    /**
     * Relasi ke User (Admin Cabang)
     */
    public function admin()
    {
        return $this->hasOne(User::class, 'cabang_id')->where('role', 'admin');
    }

    /**
     * Relasi ke semua user dalam cabang ini
     */
    public function users()
    {
        return $this->hasMany(User::class, 'cabang_id');
    }

    /**
     * Relasi ke lokasi patroli di cabang ini
     */
    public function lokasiPatroli()
    {
        return $this->hasMany(LokasiPatroli::class, 'cabang_id');
    }

    /**
     * Relasi ke patroli di cabang ini
     */
    public function patroli()
    {
        return $this->hasMany(Patroli::class, 'cabang_id');
    }

}
