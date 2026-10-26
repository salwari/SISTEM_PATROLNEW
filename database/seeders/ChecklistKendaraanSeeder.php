<?php

namespace Database\Seeders;

use App\Models\Cabang;
use App\Models\ChecklistKendaraan;
use Illuminate\Database\Seeder;

class ChecklistKendaraanSeeder extends Seeder
{
    public function run(): void
    {
        $butir = [
            'eksterior' => [
                'Bodi tidak penyok atau lecet baru',
                'Kaca depan, samping, dan belakang',
                'Kaca spion kiri dan kanan',
                'Lampu depan, belakang, dan sein',
                'Kondisi dan tekanan ban',
                'Wiper kaca depan',
                'Plat nomor terpasang dan terbaca jelas',
            ],
            'mesin_keselamatan' => [
                'Level oli mesin',
                'Level air radiator',
                'Level minyak rem',
                'Level air wiper',
                'Kondisi aki',
                'Rem pedal dan rem tangan',
                'Klakson',
                'Sabuk pengaman',
                'Tidak ada lampu peringatan menyala di dashboard',
            ],
            'kelengkapan' => [
                'STNK',
                'Ban serep',
                'Dongkrak dan kunci roda',
                'Segitiga pengaman',
                'APAR',
                'Kotak P3K',
            ],
        ];

        foreach (Cabang::all() as $cabang) {
            $urutan = 1;

            foreach ($butir as $kategori => $daftar) {
                foreach ($daftar as $nama) {
                    ChecklistKendaraan::updateOrCreate(
                        [
                            'cabang_id' => $cabang->id,
                            'kategori' => $kategori,
                            'nama_butir' => $nama,
                        ],
                        [
                            'urutan' => $urutan++,
                            'aktif' => true,
                        ]
                    );
                }
            }
        }
    }
}