<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cek_kendaraan_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cek_kendaraan_id')->constrained('cek_kendaraan')->onDelete('cascade');
            $table->foreignId('checklist_kendaraan_id')->nullable()->constrained('checklist_kendaraan')->nullOnDelete();
            $table->enum('kategori', ['eksterior', 'mesin_keselamatan', 'kelengkapan']);
            $table->string('nama_butir', 100);
            $table->enum('hasil', ['baik', 'perlu_tindakan']);
            $table->string('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cek_kendaraan_detail');
    }
};