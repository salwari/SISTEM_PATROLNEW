<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('absen_kendaraan', function (Blueprint $table) {
            $table->enum('status', ['aman', 'darurat'])->default('aman')->after('dalam_area');
            $table->foreignId('kejadian_id')->nullable()->after('status')
                ->constrained('kategori_kejadian')->nullOnDelete();
            $table->text('keterangan')->nullable()->after('kejadian_id');
        });
    }

    public function down(): void
    {
        Schema::table('absen_kendaraan', function (Blueprint $table) {
            $table->dropConstrainedForeignId('kejadian_id');
            $table->dropColumn(['status', 'keterangan']);
        });
    }
};