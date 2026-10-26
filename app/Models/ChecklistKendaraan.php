<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChecklistKendaraan extends Model
{
    protected $table = 'checklist_kendaraan';

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
}