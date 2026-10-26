<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChecklistKamarMandi extends Model
{
    use HasFactory;

    protected $table = 'checklist_kamar_mandi';

    protected $fillable = [
        'kategori',
        'nama_butir',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relasi ke detail pemeriksaan
    public function detailPemeriksaan()
    {
        return $this->hasMany(DetailPemeriksaanKamarMandi::class, 'checklist_id');
    }
}