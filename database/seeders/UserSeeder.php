<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Cabang;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $defaultPassword = Hash::make('password');
        $defaultFoto = 'assets/profile.png';
        User::firstOrCreate(
            ['email' => 'superadmin@gmail.com'],
            [
                'nama'        => 'Super Admin',
                'nomor_induk' => '012025040001', 
                'no_telepon'  => '085713333394',
                'role'        => 'super_admin',
                'alamat'      => 'Jalan Rambutan, Cilacap',
                'password'    => $defaultPassword,
                'foto'        => $defaultFoto,
                'cabang_id'   => null,
            ]
        );
        $cabangs = Cabang::all();
        $counter = 1;

        foreach ($cabangs as $cabang) {
            $nomorInduk = '02' . now()->format('Ym') . str_pad($counter, 3, '0', STR_PAD_LEFT);
            $email = strtolower(str_replace(' ', '', $cabang->nama_cabang)) . 'sci@gmail.com';

            User::firstOrCreate(
                ['email' => $email],
                [
                    'nama'        => 'Admin ' . $cabang->nama_cabang,
                    'nomor_induk' => $nomorInduk,
                    'no_telepon'  => '0812345678' . str_pad($counter, 2, '0', STR_PAD_LEFT),
                    'role'        => 'admin',
                    'alamat'      => $cabang->alamat_cabang,
                    'password'    => $defaultPassword,
                    'foto'        => $defaultFoto,
                    'cabang_id'   => $cabang->id,
                ]
            );
            $counter++;
        }

        $staffCabang = [
            1 => [ ['Irfan Hadi', 'irfansci@gmail.com', '04202505001', '085678901234', 'kabid_dukbis', 'Jl. Kalimantan No.45, Cilacap Tengah'],
                ['Ahmad', 'ahmadsci@gmail.com', '03202505004', '085700047890', 'petugas_security', 'Jl. Landak No.12, Cilacap Utara'],
                ['Bagas', 'bagassci@gmail.com', '03202505003', '085634673346', 'petugas_security', 'Jl. MT Haryono, Cilacap Selatan'],
                ['Bayu', 'bayusci@gmail.com', '03202505005', '085711267821', 'petugas_security', 'Jl. Cerme No.11, Cilacap Tengah'],
                ['Sutrisno', 'sutrisnosci@gmail.com', '05202510001', '08211000001', 'ob', 'Jl. Kenari No.5, Cilacap Tengah'],
                ['Agus', 'agussci@gmail.com', '06202510001', '08221000001', 'sopir', 'Jl. Sumatera No.8, Cilacap Utara'],
                ['Heri', 'herisci@gmail.com', '06202510002', '08221000002', 'sopir', 'Jl. Kalimantan No.20, Cilacap Selatan'],
            ],
            2 => [ ['Bambang', 'bambangsci@gmail.com', '04202506009', '085722233344', 'kabid_dukbis', 'Jl. Jenderal Sudirman No.50, Brebes'],
                ['Rafly', 'raflydsci@gmail.com', '03202505017', '085755566677', 'petugas_security', 'Jl. Pangeran Diponegoro, Brebes'],
                ['Rizki', 'rizkisci@gmail.com', '03202505015', '085711122233', 'petugas_security', 'Jl. Ahmad Yani No.20, Brebes'],
                ['Wahyu', 'wahyusci@gmail.com', '03202505016', '085733344455', 'petugas_security', 'Jl. Sunan Bonang, Brebes'],
                ['Kasiman', 'kasimansci@gmail.com', '05202510002', '08211000002', 'ob', 'Jl. Veteran No.14, Brebes'],
                ['Sugeng', 'sugengsci@gmail.com', '06202510003', '08221000003', 'sopir', 'Jl. Gajah Mada No.9, Brebes'],
                ['Eko', 'ekosci@gmail.com', '06202510004', '08221000004', 'sopir', 'Jl. Ahmad Yani No.31, Brebes'],
            ],
            3 => [ ['Rendra', 'rendrasci@gmail.com', '04202505007', '085712345678', 'kabid_dukbis', 'Jl. Pandanaran No.10, Semarang'],
                ['Andi Prasetyo', 'andisci@gmail.com', '03202505011', '085743210987', 'petugas_security', 'Jl. Tlogosari Raya, Semarang'],
                ['Badrun', 'badrunsci@gmail.com', '03202505001', '085634668901', 'petugas_security', 'Jl. Ahmad Yani No.16, Semarang Timur'],
                ['Satria', 'satriasci@gmail.com', '03202505010', '085798765432', 'petugas_security', 'Jl. Gajah Mada No.45, Semarang'],
                ['Darto', 'dartosci@gmail.com', '05202510003', '08211000003', 'ob', 'Jl. Sriwijaya No.7, Semarang'],
                ['Slamet', 'slametsci@gmail.com', '06202510005', '08221000005', 'sopir', 'Jl. Pemuda No.60, Semarang'],
                ['Nanang', 'nanangsci@gmail.com', '06202510006', '08221000006', 'sopir', 'Jl. Majapahit No.25, Semarang'],
            ],
            4 => [ ['Fajar', 'fajarsci@gmail.com', '04202506007', '085765432109', 'kabid_dukbis', 'Jl. Sudirman No.55, Jakarta Selatan'],
                ['Dwi Haryanto', 'dwisci@gmail.com', '03202505013', '085734567890', 'petugas_security', 'Jl. Diponegoro No.23, Jakarta Barat'],
                ['Toni', 'tonisci@gmail.com', '03202505014', '085798764689', 'petugas_security', 'Jl. Cempaka Putih, Jakarta Pusat'],
                ['Yusuf', 'yusufsci@gmail.com', '03202505012', '085701234567', 'petugas_security', 'Jl. MH Thamrin No.88, Jakarta Pusat'],
                ['Paijo', 'paijosci@gmail.com', '05202510004', '08211000004', 'ob', 'Jl. Salemba Raya No.3, Jakarta Pusat'],
                ['Budi', 'budisci@gmail.com', '06202510007', '08221000007', 'sopir', 'Jl. Rasuna Said No.12, Jakarta Selatan'],
                ['Hendra', 'hendrasci@gmail.com', '06202510008', '08221000008', 'sopir', 'Jl. Kebon Jeruk No.40, Jakarta Barat'],
            ],
            5 => [ ['Rangga', 'ranggasci@gmail.com', '04202505006', '085634675689', 'kabid_dukbis', 'Jl. Pattimuan No.34, Bandung Barat'],
                ['Agung', 'agungsci@gmail.com', '03202505007', '085717812903', 'petugas_security', 'Jl. Braga No.18, Bandung'],
                ['Dimas', 'dimassci@gmail.com', '03202505008', '085634681204', 'petugas_security', 'Jl. Asia Afrika No.67, Bandung'],
                ['Rahmat', 'rahmatsci@gmail.com', '03202505009', '085634674512', 'petugas_security', 'Jl. Dago No.45, Bandung'],
                ['Ujang', 'ujangsci@gmail.com', '05202510005', '08211000005', 'ob', 'Jl. Cihampelas No.22, Bandung'],
                ['Asep', 'asepsci@gmail.com', '06202510009', '08221000009', 'sopir', 'Jl. Riau No.17, Bandung'],
                ['Dede', 'dedesci@gmail.com', '06202510010', '08221000010', 'sopir', 'Jl. Buah Batu No.38, Bandung'],
            ],
        ];

        foreach ($staffCabang as $cabangId => $users) {
            foreach ($users as [$nama, $email, $nomorInduk, $noTelepon, $role, $alamat]) {
                User::firstOrCreate(
                    ['email' => $email],
                    [
                        'cabang_id'   => $cabangId,
                        'nama'        => $nama,
                        'nomor_induk' => $nomorInduk,
                        'no_telepon'  => $noTelepon,
                        'role'        => $role,
                        'alamat'      => $alamat,
                        'password'    => $defaultPassword,
                        'foto'        => $defaultFoto,
                    ]
                );
            }
        }
    }
}