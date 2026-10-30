<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriKondisiKendaraan extends Model
{
    protected $table = 'kategori_kondisi_kendaraan';

    protected $fillable = ['nama_kategori'];

    public function absenKendaraan()
    {
        return $this->hasMany(AbsenKendaraan::class, 'kejadian_id');
    }
}