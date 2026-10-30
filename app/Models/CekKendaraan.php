<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CekKendaraan extends Model
{
    protected $table = 'cek_kendaraan';

    public const LEVEL_BBM = [
        'kosong' => 'Kosong',
        'seperempat' => '1/4',
        'setengah' => '1/2',
        'tigaperempat' => '3/4',
        'penuh' => 'Penuh',
    ];

    public const STATUS = [
        'baik' => 'Baik',
        'perlu_tindakan' => 'Perlu Tindakan',
    ];

    protected $fillable = [
        'user_id',
        'kendaraan_id',
        'cabang_id',
        'tanggal_cek',
        'waktu_cek',
        'odometer',
        'level_bbm',
        'status_kondisi',
        'catatan',
        'foto',
        'latitude',
        'longitude',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_cek' => 'date',
            'odometer' => 'integer',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function kendaraan()
    {
        return $this->belongsTo(Kendaraan::class, 'kendaraan_id');
    }

    public function cabang()
    {
        return $this->belongsTo(Cabang::class, 'cabang_id');
    }

    public function detail()
    {
        return $this->hasMany(CekKendaraanDetail::class, 'cek_kendaraan_id');
    }

    public function getLevelBbmLabelAttribute(): string
    {
        return self::LEVEL_BBM[$this->level_bbm] ?? '-';
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUS[$this->status_kondisi] ?? '-';
    }
}
