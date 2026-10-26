<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ChecklistKamarMandi;

class ChecklistKamarMandiSeeder extends Seeder
{
    public function run(): void
    {
        $butirChecklist = [
            // Kategori Kebersihan
            [
                'kategori'   => 'kebersihan',
                'nama_butir' => 'Lantai bersih, kering, dan bebas genangan air',
                'is_active'  => true,
            ],
            [
                'kategori'   => 'kebersihan',
                'nama_butir' => 'Kloset & urinoir bersih (tidak berkerak/bernoda)',
                'is_active'  => true,
            ],
            [
                'kategori'   => 'kebersihan',
                'nama_butir' => 'Wastafel dan cermin bersih bebas noda air',
                'is_active'  => true,
            ],
            [
                'kategori'   => 'kebersihan',
                'nama_butir' => 'Tempat sampah bersih dan tidak menumpuk/penuh',
                'is_active'  => true,
            ],
            [
                'kategori'   => 'kebersihan',
                'nama_butir' => 'Ruangan harum dan bebas dari bau tidak sedap',
                'is_active'  => true,
            ],

            // Kategori Kelengkapan & Fasilitas
            [
                'kategori'   => 'kelengkapan',
                'nama_butir' => 'Sabun cuci tangan cair terisi dan siap digunakan',
                'is_active'  => true,
            ],
            [
                'kategori'   => 'kelengkapan',
                'nama_butir' => 'Tisu wastafel / tisu toilet tersedia cukup',
                'is_active'  => true,
            ],
            [
                'kategori'   => 'kelengkapan',
                'nama_butir' => 'Kran air berfungsi normal & mengalir lancar',
                'is_active'  => true,
            ],
            [
                'kategori'   => 'kelengkapan',
                'nama_butir' => 'Kunci pintu dan gagang pintu berfungsi baik',
                'is_active'  => true,
            ],
            [
                'kategori'   => 'kelengkapan',
                'nama_butir' => 'Lampu penerangan dan exhaust fan menyala normal',
                'is_active'  => true,
            ],
        ];

        foreach ($butirChecklist as $item) {
            ChecklistKamarMandi::firstOrCreate(
                ['nama_butir' => $item['nama_butir']],
                $item
            );
        }
    }
}