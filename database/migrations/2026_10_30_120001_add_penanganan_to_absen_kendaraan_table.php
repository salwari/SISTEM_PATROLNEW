<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('absen_kendaraan', function (Blueprint $table) {
            $table->enum('status_penanganan', ['belum', 'proses', 'selesai'])->nullable()->after('keterangan');
            $table->text('catatan_penanganan')->nullable()->after('status_penanganan');
            $table->foreignId('ditangani_oleh')->nullable()->after('catatan_penanganan')
                ->constrained('users')->nullOnDelete();
            $table->timestamp('waktu_ditangani')->nullable()->after('ditangani_oleh');
        });

        DB::table('absen_kendaraan')->where('status', 'darurat')->update(['status_penanganan' => 'belum']);
    }

    public function down(): void
    {
        Schema::table('absen_kendaraan', function (Blueprint $table) {
            $table->dropConstrainedForeignId('ditangani_oleh');
            $table->dropColumn(['status_penanganan', 'catatan_penanganan', 'waktu_ditangani']);
        });
    }
};