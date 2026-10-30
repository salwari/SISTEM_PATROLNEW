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

    public function admin()
    {
        return $this->hasOne(User::class, 'cabang_id')->where('role', 'admin');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'cabang_id');
    }

    public function lokasiPatroli()
    {
        return $this->hasMany(LokasiPatroli::class, 'cabang_id');
    }

    public function patroli()
    {
        return $this->hasMany(Patroli::class, 'cabang_id');
    }

    public function kamarMandi()
    {
        return $this->hasMany(KamarMandi::class, 'cabang_id');
    }

}
