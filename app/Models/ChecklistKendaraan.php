<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChecklistKendaraan extends Model
{
    protected $table = 'checklist_kendaraan';

    /** Kategori butir checklist, dipakai admin (kelola butir) dan sopir (form cek). */
    public const KATEGORI = [
        'eksterior' => 'Eksterior',
        'mesin_keselamatan' => 'Mesin dan Keselamatan',
        'kelengkapan' => 'Kelengkapan',
    ];

    /** Butir bawaan untuk cabang baru. Dipakai seeder dan CabangController. */
    public const BUTIR_DEFAULT = [
        'eksterior' => [
            'Bodi tidak penyok atau lecet baru',
            'Kaca dan spion',
            'Lampu depan, belakang, dan sein',
            'Kondisi ban',
        ],
        'mesin_keselamatan' => [
            'Level oli mesin',
            'Level air radiator',
            'Rem berfungsi baik',
            'Tidak ada lampu peringatan menyala di dashboard',
        ],
        'kelengkapan' => [
            'STNK',
            'Ban serep dan dongkrak',
            'APAR',
            'Kotak P3K',
        ],
    ];

    protected $fillable = [
        'cabang_id',
        'kategori',
        'nama_butir',
        'urutan',
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

    public function detailCek()
    {
        return $this->hasMany(CekKendaraanDetail::class, 'checklist_kendaraan_id');
    }

    public function scopeAktif($query)
    {
        return $query->where('aktif', true);
    }

    /**
     * Isi butir bawaan untuk satu cabang. Aman dipanggil berulang
     * (butir yang sudah ada tidak diduplikasi).
     */
    public static function isiDefaultUntukCabang(int $cabangId): void
    {
        $urutan = 1;

        foreach (self::BUTIR_DEFAULT as $kategori => $daftar) {
            foreach ($daftar as $nama) {
                self::updateOrCreate(
                    ['cabang_id' => $cabangId, 'kategori' => $kategori, 'nama_butir' => $nama],
                    ['urutan' => $urutan++, 'aktif' => true]
                );
            }
        }
    }
}
