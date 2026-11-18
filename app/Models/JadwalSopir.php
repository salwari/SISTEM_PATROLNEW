<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalSopir extends Model
{
    use HasFactory;

    protected $table = 'jadwal_sopir';

    protected $fillable = [
        'user_id',
        'tanggal',
        'shift',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopeDiCabang($query, int $cabangId)
    {
        return $query->whereHas('user', function ($q) use ($cabangId) {
            $q->where('cabang_id', $cabangId);
        });
    }
}