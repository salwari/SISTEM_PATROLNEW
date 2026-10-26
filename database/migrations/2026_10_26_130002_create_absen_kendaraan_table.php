<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('absen_kendaraan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('kendaraan_id')->constrained('kendaraan')->onDelete('cascade');
            $table->foreignId('cabang_id')->constrained('cabang')->onDelete('cascade');
            $table->string('nama_sopir', 100);
            $table->date('tanggal');
            $table->time('waktu');
            $table->string('foto');
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->unsignedSmallInteger('akurasi_meter')->nullable();
            $table->unsignedInteger('jarak_meter')->nullable();
            $table->boolean('dalam_area')->nullable(); // null = area cabang belum diatur
            $table->timestamps();

            $table->unique(['kendaraan_id', 'tanggal']); // satu kendaraan diabsen sekali per hari
            $table->index(['cabang_id', 'tanggal']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('absen_kendaraan');
    }
};
