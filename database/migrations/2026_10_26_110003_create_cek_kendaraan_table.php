<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cek_kendaraan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('kendaraan_id')->constrained('kendaraan')->onDelete('cascade');
            $table->foreignId('cabang_id')->constrained('cabang')->onDelete('cascade');
            $table->date('tanggal_cek');
            $table->time('waktu_cek');
            $table->unsignedInteger('odometer');
            $table->enum('level_bbm', ['kosong', 'seperempat', 'setengah', 'tigaperempat', 'penuh']);
            $table->enum('status_kondisi', ['baik', 'perlu_tindakan'])->default('baik');
            $table->text('catatan')->nullable();
            $table->string('foto');
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->timestamps();

            $table->index(['kendaraan_id', 'tanggal_cek']);
            $table->index(['cabang_id', 'tanggal_cek']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cek_kendaraan');
    }
};