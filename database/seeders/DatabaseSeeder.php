<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\LokasiPatroli;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
     public function run(): void
    {
        $this->call(CabangSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(KategoriKejadianSeeder::class);
        $this->call(LokasiPatroliSeeder::class);
<<<<<<< HEAD
        $this->call(KendaraanSeeder::class);
        $this->call(ChecklistKendaraanSeeder::class);
=======
        $this->call(ChecklistKamarMandiSeeder::class);
        $this->call(KamarMandiSeeder::class);
>>>>>>> 0cc4559d9b40a48a0cd0ba76ddb836c1c47663dc
    }
}
