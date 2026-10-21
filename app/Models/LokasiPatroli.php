<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LokasiPatroli extends Model
{
    use HasFactory;

    protected $table = 'lokasi_patroli';

    protected $fillable = [
        'cabang_id',
        'nama_lokasi',
        'deskripsi',
        'qr_code',
    ];

    // Relasi dengan tabel patroli
    public function patroli()
    {
        return $this->hasMany(Patroli::class, 'lokasi_id');
    }

    // Relasi dengan tabel cabang
    public function cabang()
    {
        return $this->belongsTo(Cabang::class, 'cabang_id');
    }
}
