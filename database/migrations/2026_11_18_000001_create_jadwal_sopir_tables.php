<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jadwal_sopir', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->date('tanggal');
            $table->string('shift', 10);
            $table->timestamps();

            $table->unique(['user_id', 'tanggal', 'shift']);
            $table->index(['tanggal', 'shift']);
        });

        Schema::create('pergantian_sopir', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jadwal_sopir_id')->nullable()->constrained('jadwal_sopir')->nullOnDelete();
            $table->date('tanggal');
            $table->string('shift', 10);
            $table->foreignId('petugas_lama_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('petugas_baru_id')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('waktu_pergantian');
            $table->string('alasan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pergantian_sopir');
        Schema::dropIfExists('jadwal_sopir');
    }
};