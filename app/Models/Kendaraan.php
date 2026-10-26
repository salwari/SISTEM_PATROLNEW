<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kendaraan extends Model
{
    protected $table = 'kendaraan';

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
}