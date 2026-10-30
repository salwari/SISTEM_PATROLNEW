<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patroli extends Model
{
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

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function lokasiPatroli()
    {
        return $this->belongsTo(LokasiPatroli::class, 'lokasi_id');
    }

    public function cabang()
    {
        return $this->belongsTo(Cabang::class, 'cabang_id');
    }
    
    public function kategoriKejadian()
    {
        return $this->belongsTo(KategoriKejadian::class, 'kejadian_id');
    }
}
