<?php

namespace Database\Seeders;

use App\Models\Cabang;
use App\Models\Kendaraan;
use Illuminate\Database\Seeder;

class KendaraanSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'Cilacap' => [
                ['R 1234 AB', 'Toyota Avanza'],
                ['R 5678 CD', 'Toyota Innova'],
            ],
            'Brebes' => [
                ['G 2345 EF', 'Toyota Avanza'],
                ['G 6789 GH', 'Mitsubishi L300'],
            ],
            'Semarang' => [
                ['H 3456 IJ', 'Toyota Innova'],
                ['H 7890 KL', 'Daihatsu Gran Max'],
            ],
            'Jakarta' => [
                ['B 4567 MN', 'Toyota Innova Reborn'],
                ['B 8901 OP', 'Toyota Hiace'],
            ],
            'Bandung' => [
                ['D 5678 QR', 'Toyota Avanza'],
                ['D 9012 ST', 'Suzuki APV'],
            ],
        ];

        foreach ($data as $namaCabang => $kendaraan) {
            $cabang = Cabang::where('nama_cabang', $namaCabang)->first();

            if (!$cabang) {
                continue;
            }

            foreach ($kendaraan as [$plat, $jenis]) {
                Kendaraan::updateOrCreate(
                    ['plat_nomor' => $plat],
                    [
                        'cabang_id' => $cabang->id,
                        'jenis' => $jenis,
                        'kode_barcode' => 'KDR-' . $cabang->id . '-' . strtoupper(substr(md5($plat), 0, 8)),
                        'aktif' => true,
                    ]
                );
            }
        }
    }
}