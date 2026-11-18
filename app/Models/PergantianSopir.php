<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PergantianSopir extends Model
{
    use HasFactory;

    protected $table = 'pergantian_sopir';

    protected $fillable = [
        'jadwal_sopir_id',
        'tanggal',
        'shift',
        'petugas_lama_id',
        'petugas_baru_id',
        'waktu_pergantian',
        'alasan',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'waktu_pergantian' => 'datetime',
    ];

    public function petugasLama()
    {
        return $this->belongsTo(User::class, 'petugas_lama_id');
    }

    public function petugasBaru()
    {
        return $this->belongsTo(User::class, 'petugas_baru_id');
    }
}