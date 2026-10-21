<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patroli extends Model
{
    /** @use HasFactory<\Database\Factories\PatroliFactory> */
    use HasFactory;
    
    protected $table = 'patroli';

    protected $fillable = [
        'user_id',
        'lokasi_id',
        'cabang_id', 
        'kejadian_id',
        'tanggal_patroli',
        'waktu_patroli',
        'status',
        'keterangan',
        'validasi_darurat',
        'foto',
    ];

    // Relasi ke user (petugas)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke lokasi patroli
    public function lokasiPatroli()
    {
        return $this->belongsTo(LokasiPatroli::class, 'lokasi_id');
    }

    // Relasi ke cabang (bukan unit lagi)
    public function cabang()
    {
        return $this->belongsTo(Cabang::class, 'cabang_id');
    }
    
    // Relasi ke kategori kejadian
    public function kategoriKejadian()
    {
        return $this->belongsTo(KategoriKejadian::class, 'kejadian_id');
    }
}
