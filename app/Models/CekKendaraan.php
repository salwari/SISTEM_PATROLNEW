<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CekKendaraan extends Model
{
    protected $table = 'cek_kendaraan';

    protected $fillable = [
        'user_id',
        'kendaraan_id',
        'cabang_id',
        'tanggal_cek',
        'waktu_cek',
        'odometer',
        'level_bbm',
        'status_kondisi',
        'catatan',
        'foto',
        'latitude',
        'longitude',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function kendaraan()
    {
        return $this->belongsTo(Kendaraan::class, 'kendaraan_id');
    }

    public function cabang()
    {
        return $this->belongsTo(Cabang::class, 'cabang_id');
    }

    public function detail()
    {
        return $this->hasMany(CekKendaraanDetail::class, 'cek_kendaraan_id');
    }
}