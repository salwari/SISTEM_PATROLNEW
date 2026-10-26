<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PemeriksaanKamarMandi extends Model
{
    use HasFactory;

    protected $table = 'pemeriksaan_kamar_mandi';

    protected $fillable = [
        'user_id',
        'kamar_mandi_id',
        'cabang_id',
        'tanggal',
        'waktu',
        'status_kondisi',
        'catatan',
        'foto',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function kamarMandi()
    {
        return $this->belongsTo(KamarMandi::class, 'kamar_mandi_id');
    }
}