<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cabang', function (Blueprint $table) {
            // Titik pusat kantor + radius untuk cek "Dalam area". Kosong = area belum diatur.
            $table->decimal('latitude', 10, 7)->nullable()->after('alamat_cabang');
            $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
            $table->unsignedInteger('radius_meter')->nullable()->after('longitude');
        });

        // Koordinat contoh Cilacap (sama dengan prototipe). Ganti lewat Super Admin > Edit Cabang.
        DB::table('cabang')->where('nama_cabang', 'Cilacap')->update([
            'latitude' => -7.7284100,
            'longitude' => 109.0146400,
            'radius_meter' => 200,
        ]);
    }

    public function down(): void
    {
        Schema::table('cabang', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude', 'radius_meter']);
        });
    }
};
