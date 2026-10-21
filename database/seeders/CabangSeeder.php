<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cabang;

class CabangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Cabang::insert([
            [
                'nama_cabang'   => 'Cilacap',
                'alamat_cabang' => 'Jl. Rambutan No. 12, Cilacap',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'nama_cabang'   => 'Brebes',
                'alamat_cabang' => 'Jl. Diponegoro No. 45, Brebes',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'nama_cabang'   => 'Semarang',
                'alamat_cabang' => 'Jl. Pandanaran No. 99, Semarang',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'nama_cabang'   => 'Jakarta',
                'alamat_cabang' => 'Jl. Merdeka No. 1, Jakarta',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'nama_cabang'   => 'Bandung',
                'alamat_cabang' => 'Jl. Kemerdekaan No.82, Bandung',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
        ]);
    }
}
