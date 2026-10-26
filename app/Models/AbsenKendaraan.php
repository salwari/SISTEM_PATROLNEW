<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AbsenKendaraan extends Model
{
    protected $table = 'absen_kendaraan';

    protected $fillable = [
        'user_id', 'kendaraan_id', 'cabang_id', 'nama_sopir',
        'tanggal', 'waktu', 'foto',
        'latitude', 'longitude', 'akurasi_meter', 'jarak_meter', 'dalam_area',
    ];

    protected function casts(): array
    {
        return ['tanggal' => 'date', 'dalam_area' => 'boolean'];
    }

    public function user() { return $this->belongsTo(User::class, 'user_id'); }

    public function kendaraan() { return $this->belongsTo(Kendaraan::class, 'kendaraan_id'); }

    public function cabang() { return $this->belongsTo(Cabang::class, 'cabang_id'); }

    public function getJamAttribute(): string
    {
        return substr($this->waktu, 0, 5);
    }
}
