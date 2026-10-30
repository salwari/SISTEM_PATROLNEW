<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PergantianPetugas extends Model
{
   use HasFactory;

    protected $table = 'pergantian_petugas';

    protected $fillable = [
        'jadwal_id',
        'petugas_lama_id',
        'petugas_baru_id',
        'waktu_pergantian',
        'alasan',
    ];

    public function jadwal()
    {
        return $this->belongsTo(JadwalPatroli::class, 'jadwal_id');
    }

    public function petugasLama()
    {
        return $this->belongsTo(User::class, 'petugas_lama_id');
    }

    public function petugasBaru()
    {
        return $this->belongsTo(User::class, 'petugas_baru_id');
    }
}

