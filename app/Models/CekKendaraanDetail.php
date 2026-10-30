<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CekKendaraanDetail extends Model
{
    protected $table = 'cek_kendaraan_detail';

    protected $fillable = [
        'cek_kendaraan_id',
        'checklist_kendaraan_id',
        'kategori',
        'nama_butir',
        'hasil',
        'catatan',
    ];

    public function cekKendaraan()
    {
        return $this->belongsTo(CekKendaraan::class, 'cek_kendaraan_id');
    }

    public function butir()
    {
        return $this->belongsTo(ChecklistKendaraan::class, 'checklist_kendaraan_id');
    }
}