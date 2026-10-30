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

    /**
     * Muat data yang dibutuhkan statusHarian(): laporan pada tanggal tsb (terbaru dulu)
     * dan, untuk hari ini, laporan darurat yang masih terbuka dari hari mana pun.
     */
    public function scopeDenganStatusHarian($query, $tanggal, bool $hariIni = true)
    {
        $query->with(['absenKendaraan' => fn ($q) => $q->with('kategoriKejadian:id,nama_kategori')
            ->whereDate('tanggal', $tanggal)->orderByDesc('id')]);

        if ($hariIni) {
            $query->with(['laporanDaruratTerbuka' => fn ($q) => $q->with('kategoriKejadian:id,nama_kategori')->orderByDesc('id')]);
        }

        return $query;
    }

    /**
     * SATU-SATUNYA sumber kebenaran status kendaraan pada suatu hari (dipakai sopir, admin, dan kabid):
     *  - 'darurat': hari ini -> masih ada laporan darurat yang belum selesai (dari hari kapan pun).
     *               riwayat  -> ada laporan darurat pada tanggal itu.
     *  - 'aman'   : ada patroli aman pada tanggal itu dan tidak sedang darurat.
     *  - 'belum'  : selain itu (termasuk darurat yang sudah selesai, kendaraan perlu dipatroli aman lagi).
     * Butuh scopeDenganStatusHarian() agar relasinya sudah dimuat.
     */
    public function statusHarian(bool $historis = false): string
    {
        $hari = $this->relationLoaded('absenKendaraan') ? $this->absenKendaraan : collect();
        $terbuka = (!$historis && $this->relationLoaded('laporanDaruratTerbuka')) ? $this->laporanDaruratTerbuka : collect();

        if ($terbuka->isNotEmpty() || ($historis && $hari->contains('status', 'darurat'))) {
            return 'darurat';
        }

        return $hari->contains('status', 'aman') ? 'aman' : 'belum';
    }

    /** Laporan yang mewakili status kendaraan pada hari itu (untuk ditampilkan). */
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