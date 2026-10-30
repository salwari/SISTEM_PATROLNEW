<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AbsenKendaraan extends Model
{
    protected $table = 'absen_kendaraan';

    public const PENANGANAN = [
        'belum' => 'Belum Ditangani',
        'proses' => 'Sedang Ditangani',
        'selesai' => 'Selesai',
    ];

    protected $fillable = [
        'user_id', 'kendaraan_id', 'cabang_id', 'nama_sopir',
        'tanggal', 'waktu', 'foto',
        'latitude', 'longitude', 'akurasi_meter', 'jarak_meter', 'dalam_area',
        'status', 'kejadian_id', 'keterangan',
        'status_penanganan', 'catatan_penanganan', 'ditangani_oleh', 'waktu_ditangani',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
            'dalam_area' => 'boolean',
            'waktu_ditangani' => 'datetime',
        ];
    }

    public function user() { return $this->belongsTo(User::class, 'user_id'); }

    public function kendaraan() { return $this->belongsTo(Kendaraan::class, 'kendaraan_id'); }

    public function cabang() { return $this->belongsTo(Cabang::class, 'cabang_id'); }

    public function kategoriKejadian() { return $this->belongsTo(KategoriKondisiKendaraan::class, 'kejadian_id'); }

    public function penangan() { return $this->belongsTo(User::class, 'ditangani_oleh'); }

    public function scopeDaruratTerbuka($query)
    {
        return $query->where('status', 'darurat')->whereIn('status_penanganan', ['belum', 'proses']);
    }

    public function getJamAttribute(): string
    {
        return substr($this->waktu, 0, 5);
    }

    public function getPenangananLabelAttribute(): string
    {
        return self::PENANGANAN[$this->status_penanganan] ?? '-';
    }
}