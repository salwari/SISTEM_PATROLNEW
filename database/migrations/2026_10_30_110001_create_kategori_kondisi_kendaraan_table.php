<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kategori_kondisi_kendaraan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kategori', 100)->unique();
            $table->timestamps();
        });

        $now = now();
        $daftar = [
            'Ban Bocor / Kempes',
            'Mesin Bermasalah',
            'Rem Bermasalah',
            'Aki / Kelistrikan Bermasalah',
            'Lampu Mati / Rusak',
            'AC Tidak Dingin',
            'Bodi Lecet / Penyok',
            'Kaca Pecah / Retak',
            'Kebocoran Oli / Cairan',
            'BBM Hampir Habis',
            'Kecelakaan / Tabrakan',
            'Kendaraan Hilang / Tidak Ada di Tempat',
            'Lainnya',
        ];

        DB::table('kategori_kondisi_kendaraan')->insert(
            array_map(fn ($nama) => ['nama_kategori' => $nama, 'created_at' => $now, 'updated_at' => $now], $daftar)
        );

        DB::table('absen_kendaraan')->update(['kejadian_id' => null]);

        Schema::table('absen_kendaraan', function (Blueprint $table) {
            $table->dropForeign(['kejadian_id']);
            $table->foreign('kejadian_id')->references('id')->on('kategori_kondisi_kendaraan')->nullOnDelete();
        });
    }

    public function down(): void
    {
        DB::table('absen_kendaraan')->update(['kejadian_id' => null]);

        Schema::table('absen_kendaraan', function (Blueprint $table) {
            $table->dropForeign(['kejadian_id']);
            $table->foreign('kejadian_id')->references('id')->on('kategori_kejadian')->nullOnDelete();
        });

        Schema::dropIfExists('kategori_kondisi_kendaraan');
    }
};