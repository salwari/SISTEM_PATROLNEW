<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cabang extends Model
{
    use HasFactory;

    protected $table = 'cabang';

    protected $fillable = [
        'nama_cabang',
        'alamat_cabang',
        'latitude',
        'longitude',
        'radius_meter',
    ];

    public function punyaArea(): bool
    {
        return $this->latitude !== null && $this->longitude !== null && $this->radius_meter > 0;
    }


    public function jarakKe(float $lat, float $lng): int
    {
        $r = 6371000;
        $dLat = deg2rad($lat - (float) $this->latitude);
        $dLng = deg2rad($lng - (float) $this->longitude);
        $a = sin($dLat / 2) ** 2
            + cos(deg2rad((float) $this->latitude)) * cos(deg2rad($lat)) * sin($dLng / 2) ** 2;

        return (int) round($r * 2 * atan2(sqrt($a), sqrt(1 - $a)));
    }

    public function admin()
    {
        return $this->hasOne(User::class, 'cabang_id')->where('role', 'admin');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'cabang_id');
    }

    public function lokasiPatroli()
    {
        return $this->hasMany(LokasiPatroli::class, 'cabang_id');
    }

    public function patroli()
    {
        return $this->hasMany(Patroli::class, 'cabang_id');
    }

    public function kamarMandi()
    {
        return $this->hasMany(KamarMandi::class, 'cabang_id');
    }

}
