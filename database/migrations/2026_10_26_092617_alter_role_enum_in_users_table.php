<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE `users` MODIFY `role` ENUM('super_admin','admin','petugas_security','kabid_dukbis','ob','sopir') NOT NULL");
    }

    public function down(): void
    {
        DB::table('users')->whereIn('role', ['ob', 'sopir'])->delete();

        DB::statement("ALTER TABLE `users` MODIFY `role` ENUM('super_admin','admin','petugas_security','kabid_dukbis') NOT NULL");
    }
};