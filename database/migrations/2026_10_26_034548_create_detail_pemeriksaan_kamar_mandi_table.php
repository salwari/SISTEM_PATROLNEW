<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_pemeriksaan_kamar_mandi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pemeriksaan_id')->constrained('pemeriksaan_kamar_mandi')->onDelete('cascade');
            $table->foreignId('checklist_id')->constrained('checklist_kamar_mandi')->onDelete('cascade');
            $table->enum('status', ['sesuai', 'tidak_sesuai']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_pemeriksaan_kamar_mandi');
    }
};