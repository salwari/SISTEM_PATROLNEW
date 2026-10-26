<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KamarMandi;

class KamarMandiSeeder extends Seeder
{
    public function run(): void
    {
        $daftarKamarMandi = [
            [
                'cabang_id'         => 1, 
                'kode_kamar_mandi'  => 'KMD-01',
                'nama_kamar_mandi'  => 'Kamar Mandi Lt.1 Pria',
                'lantai'            => 'Lantai 1',
                'qr_code'           => null,
                'deskripsi'         => 'Toilet pria dekat lobby utama lantai 1',
            ],
            [
                'cabang_id'         => 1,
                'kode_kamar_mandi'  => 'KMD-02',
                'nama_kamar_mandi'  => 'Kamar Mandi Lt.1 Wanita',
                'lantai'            => 'Lantai 1',
                'qr_code'           => null,
                'deskripsi'         => 'Toilet wanita dekat resepsionis lantai 1',
            ],
            [
                'cabang_id'         => 1,
                'kode_kamar_mandi'  => 'KMD-03',
                'nama_kamar_mandi'  => 'Kamar Mandi Lt.2 Pria',
                'lantai'            => 'Lantai 2',
                'qr_code'           => null,
                'deskripsi'         => 'Toilet pria area staf operasional lantai 2',
            ],
            [
                'cabang_id'         => 1,
                'kode_kamar_mandi'  => 'KMD-04',
                'nama_kamar_mandi'  => 'Kamar Mandi Lt.2 Wanita',
                'lantai'            => 'Lantai 2',
                'qr_code'           => null,
                'deskripsi'         => 'Toilet wanita area ruang rapat lantai 2',
            ],
            [
                'cabang_id'         => 1,
                'kode_kamar_mandi'  => 'KMD-05',
                'nama_kamar_mandi'  => 'Kamar Mandi Lt.3 Pimpinan',
                'lantai'            => 'Lantai 3',
                'qr_code'           => null,
                'deskripsi'         => 'Toilet area ruang pimpinan & aula lantai 3',
            ],
        ];

        foreach ($daftarKamarMandi as $kmd) {
            KamarMandi::firstOrCreate(
                [
                    'cabang_id'        => $kmd['cabang_id'],
                    'kode_kamar_mandi' => $kmd['kode_kamar_mandi'],
                ],
                $kmd
            );
        }
    }
}