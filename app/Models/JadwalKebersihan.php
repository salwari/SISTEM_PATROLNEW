<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalKebersihan extends Model
{
    use HasFactory;

    protected $table = 'jadwal_kebersihan';

    protected $fillable = [
        'user_id',
        'tanggal',
        'shift',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}