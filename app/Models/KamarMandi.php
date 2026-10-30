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

    public function cabang()
    {
        return $this->belongsTo(Cabang::class, 'cabang_id');
    }

    public function pemeriksaan()
    {
        return $this->hasMany(PemeriksaanKamarMandi::class, 'kamar_mandi_id');
    }

    // Relasi untuk mengambil pemeriksaan terakhir
    public function cekTerakhir()
    {
        return $this->hasOne(PemeriksaanKamarMandi::class, 'kamar_mandi_id')->latestOfMany('id');
    }

    // --- Local Scopes ---
    public function scopeDiCabang($query, $cabangId)
    {
        return $query->where('cabang_id', $cabangId);
    }

    public function scopeAktif($query)
    {
        return $query;
    }

    public function scopeSudahDicek($query, $tanggal)
    {
        return $query->whereHas('pemeriksaan', function ($q) use ($tanggal) {
            $q->whereDate('tanggal', $tanggal);
        });
    }

    public function scopeBelumDicek($query, $tanggal)
    {
        return $query->whereDoesntHave('pemeriksaan', function ($q) use ($tanggal) {
            $q->whereDate('tanggal', $tanggal);
        });
    }
}