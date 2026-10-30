<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\LokasiPatroli;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(CabangSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(KategoriKejadianSeeder::class);
        $this->call(LokasiPatroliSeeder::class);


        $this->call(ChecklistKamarMandiSeeder::class);
        $this->call(KamarMandiSeeder::class);

        $this->call(KendaraanSeeder::class);
        $this->call(ChecklistKendaraanSeeder::class);
    }
}
