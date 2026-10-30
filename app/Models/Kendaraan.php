<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Route;

class Kendaraan extends Model
{
    protected $table = 'kendaraan';

    /** Diisi manual oleh SopirController (cek terbaru hari ini), bukan kolom database. */
    public $cekHariIni = null;

    protected $fillable = [
        'cabang_id',
        'plat_nomor',
        'jenis',
        'kode_barcode',
        'qr_code',
        'aktif',
    ];

    protected function casts(): array
    {
        return [
            'aktif' => 'boolean',
        ];
    }

    public function cabang()
    {
        return $this->belongsTo(Cabang::class, 'cabang_id');
    }

    public function cekKendaraan()
    {
        return $this->hasMany(CekKendaraan::class, 'kendaraan_id');
    }

    public function absenKendaraan()
    {
        return $this->hasMany(AbsenKendaraan::class, 'kendaraan_id');
    }

    public function patroliTerakhir()
    {
        return $this->hasOne(AbsenKendaraan::class, 'kendaraan_id')->latestOfMany('id');
    }

    public function laporanDaruratTerbuka()
    {
        return $this->hasMany(AbsenKendaraan::class, 'kendaraan_id')->daruratTerbuka();
    }

    /** Cek paling baru, untuk menampilkan kondisi terkini kendaraan. */
    public function cekTerakhir()
    {
        return $this->hasOne(CekKendaraan::class, 'kendaraan_id')->latestOfMany();
    }

    public function scopeAktif($query)
    {
        return $query->where('aktif', true);
    }

    public function scopeDiCabang($query, $cabangId)
    {
        return $query->where('cabang_id', $cabangId);
    }

    /** Kendaraan yang sudah punya minimal satu cek pada tanggal tertentu. */
    public function scopeSudahDicek($query, $tanggal)
    {
        return $query->whereHas('cekKendaraan', fn ($q) => $q->whereDate('tanggal_cek', $tanggal));
    }

    public function scopeBelumDicek($query, $tanggal)
    {
        return $query->whereDoesntHave('cekKendaraan', fn ($q) => $q->whereDate('tanggal_cek', $tanggal));
    }

    /** URL yang disimpan di dalam QR: form absen sopir (?kode=...). */
    public function getUrlScanAttribute(): string
    {
        $query = ['kode' => $this->kode_barcode];

        return Route::has('sopir.absen.create')
            ? route('sopir.absen.create', $query)
            : url('/sopir/absen/create') . '?' . http_build_query($query);
    }
}