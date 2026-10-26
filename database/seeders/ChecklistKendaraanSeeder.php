<?php

namespace Database\Seeders;

use App\Models\Cabang;
use App\Models\ChecklistKendaraan;
use Illuminate\Database\Seeder;

class ChecklistKendaraanSeeder extends Seeder
{
    public function run(): void
    {
        // Daftar butir bawaan ada di ChecklistKendaraan::BUTIR_DEFAULT,
        // dipakai juga saat super admin membuat cabang baru.
        foreach (Cabang::all() as $cabang) {
            ChecklistKendaraan::isiDefaultUntukCabang($cabang->id);
        }
    }
}
