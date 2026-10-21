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
    }
}
