<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KamarMandi extends Model
{
    use HasFactory;

    protected $table = 'kamar_mandi';

    protected $fillable = [
        'cabang_id',
        'kode_kamar_mandi',
        'nama_kamar_mandi',
        'lantai',
        'qr_code',
        'deskripsi',
    ];

    // Relasi ke Cabang
    public function cabang()
    {
        return $this->belongsTo(Cabang::class, 'cabang_id');
    }

    // Relasi ke histori pemeriksaan
    public function pemeriksaan()
    {
        return $this->hasMany(PemeriksaanKamarMandi::class, 'kamar_mandi_id');
    }
}