<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPemeriksaanKamarMandi extends Model
{
    use HasFactory;

    protected $table = 'detail_pemeriksaan_kamar_mandi';

    protected $fillable = [
        'pemeriksaan_id',
        'checklist_id',
        'status',
    ];

    public function pemeriksaan()
    {
        return $this->belongsTo(PemeriksaanKamarMandi::class, 'pemeriksaan_id');
    }
    
    public function checklist()
    {
        return $this->belongsTo(ChecklistKamarMandi::class, 'checklist_id');
    }
}