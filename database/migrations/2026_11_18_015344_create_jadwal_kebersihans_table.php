<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jadwal_kebersihan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->date('tanggal');
            $table->enum('shift', ['pagi', 'sore', 'malam']);
            $table->timestamps();

            $table->index(['tanggal', 'shift']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwal_kebersihan');
    }
};