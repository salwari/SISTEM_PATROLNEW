<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Laporan darurat bisa terjadi kapan saja (mis. kehabisan bensin siang hari),
 * walau kendaraan sudah dipatroli "aman" pagi tadi. Karena itu satu kendaraan
 * boleh punya lebih dari satu laporan per hari. Batas "1 patroli aman per hari"
 * dijaga di SopirAbsenController.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('absen_kendaraan', function (Blueprint $table) {
            // Foreign key kendaraan_id butuh index sendiri sebelum unique lama dibuang.
            $table->index('kendaraan_id', 'absen_kendaraan_kendaraan_id_index');
            $table->dropUnique('absen_kendaraan_kendaraan_id_tanggal_unique');
            $table->index(['kendaraan_id', 'tanggal', 'status'], 'absen_kendaraan_kendaraan_tanggal_status_index');
        });
    }

    public function down(): void
    {
        Schema::table('absen_kendaraan', function (Blueprint $table) {
            $table->dropIndex('absen_kendaraan_kendaraan_tanggal_status_index');
            $table->unique(['kendaraan_id', 'tanggal'], 'absen_kendaraan_kendaraan_id_tanggal_unique');
            $table->dropIndex('absen_kendaraan_kendaraan_id_index');
        });
    }
};
