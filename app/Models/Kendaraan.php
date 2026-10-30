<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Route;

class Kendaraan extends Model
{
    protected $table = 'kendaraan';

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

    public function scopeDenganStatusHarian($query, $tanggal, bool $hariIni = true)
    {
        $query->with(['absenKendaraan' => fn ($q) => $q->with('kategoriKejadian:id,nama_kategori')
            ->whereDate('tanggal', $tanggal)->orderByDesc('id')]);

        if ($hariIni) {
            $query->with(['laporanDaruratTerbuka' => fn ($q) => $q->with('kategoriKejadian:id,nama_kategori')->orderByDesc('id')]);
        }

        return $query;
    }

    public function statusHarian(bool $historis = false): string
    {
        $hari = $this->relationLoaded('absenKendaraan') ? $this->absenKendaraan : collect();
        $terbuka = (!$historis && $this->relationLoaded('laporanDaruratTerbuka')) ? $this->laporanDaruratTerbuka : collect();

        if ($terbuka->isNotEmpty() || ($historis && $hari->contains('status', 'darurat'))) {
            return 'darurat';
        }

        return $hari->contains('status', 'aman') ? 'aman' : 'belum';
    }

    public function laporanUtama(bool $historis = false): ?AbsenKendaraan
    {
        $hari = $this->relationLoaded('absenKendaraan') ? $this->absenKendaraan : collect();
        $terbuka = (!$historis && $this->relationLoaded('laporanDaruratTerbuka')) ? $this->laporanDaruratTerbuka : collect();

        if ($terbuka->isNotEmpty()) {
            return $terbuka->sortByDesc('id')->first();
        }
        if ($historis && ($d = $hari->firstWhere('status', 'darurat'))) {
            return $d;
        }

        return $hari->sortByDesc('id')->first();
    }

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

    public function scopeSudahAbsen($query, $tanggal)
    {
        return $query->whereHas('absenKendaraan', fn ($q) => $q->whereDate('tanggal', $tanggal));
    }

    public function scopeBelumAbsen($query, $tanggal)
    {
        return $query->whereDoesntHave('absenKendaraan', fn ($q) => $q->whereDate('tanggal', $tanggal));
    }

    public function getUrlScanAttribute(): string
    {
        $query = ['kode' => $this->kode_barcode];

        return Route::has('sopir.absen.create')
            ? route('sopir.absen.create', $query)
            : url('/sopir/absen/create') . '?' . http_build_query($query);
    }
}