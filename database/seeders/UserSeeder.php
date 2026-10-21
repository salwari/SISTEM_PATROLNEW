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
        // super admin
        User::create([
            'nama'        => 'Super Admin',
            'email'       => 'superadmin@gmail.com',
            'nomor_induk' => '012025040001', 
            'no_telepon'  => '085713333394',
            'role'        => 'super_admin',
            'alamat'      => 'Jalan Rambutan, Cilacap',
            'password'    => Hash::make('password'),
            'foto'        => 'assets/profile.png',
            'cabang_id'   => null,
        ]);

        // admin cabang
        $cabangs = Cabang::all();
        $counter = 1;

        foreach ($cabangs as $cabang) {
            $nomorInduk = '02' . now()->format('Ym') . str_pad($counter, 3, '0', STR_PAD_LEFT);
            $email = strtolower(str_replace(' ', '', $cabang->nama_cabang)) . 'sci@gmail.com';

            User::create([
                'nama'        => 'Admin ' . $cabang->nama_cabang,
                'email'       => $email,
                'nomor_induk' => $nomorInduk,
                'no_telepon'  => '0812345678' . str_pad($counter, 2, '0', STR_PAD_LEFT),
                'role'        => 'admin',
                'alamat'      => $cabang->alamat_cabang,
                'password'    => Hash::make('password'),
                'foto'        => 'assets/profile.png',
                'cabang_id'   => $cabang->id,
            ]);
            $counter++;
        }

        // petugas dan kabid

        // CABANG 1 - CILACAP
        $usersCabang1 = [
            ['Ahmad', 'ahmadsci@gmail.com', '03202505004', '085700047890', 'petugas_security', 'Jl. Landak No.12, Cilacap Utara'],
            ['Bagas', 'bagassci@gmail.com', '03202505003', '085634673346', 'petugas_security', 'Jl. MT Haryono, Cilacap Selatan'],
            ['Bayu', 'bayusci@gmail.com', '03202505005', '085711267821', 'petugas_security', 'Jl. Cerme No.11, Cilacap Tengah'],
            ['Irfan Hadi', 'irfansci@gmail.com', '04202505001', '085678901234', 'kabid_dukbis', 'Jl. Kalimantan No.45, Cilacap Tengah'],
        ];

        // CABANG 2 - BREBES
        $usersCabang2 = [
            ['Bambang', 'bambangsci@gmail.com', '04202506009', '085722233344', 'kabid_dukbis', 'Jl. Jenderal Sudirman No.50, Brebes'],
            ['Rafly', 'raflydsci@gmail.com', '03202505017', '085755566677', 'petugas_security', 'Jl. Pangeran Diponegoro, Brebes'],
            ['Rizki', 'rizkisci@gmail.com', '03202505015', '085711122233', 'petugas_security', 'Jl. Ahmad Yani No.20, Brebes'],
            ['Wahyu', 'wahyusci@gmail.com', '03202505016', '085733344455', 'petugas_security', 'Jl. Sunan Bonang, Brebes'],
        ];

        // CABANG 3 - SEMARANG
        $usersCabang3 = [
            ['Andi Prasetyo', 'andisci@gmail.com', '03202505011', '085743210987', 'petugas_security', 'Jl. Tlogosari Raya, Semarang'],
            ['Badrun', 'badrunsci@gmail.com', '03202505001', '085634668901', 'petugas_security', 'Jl. Ahmad Yani No.16, Semarang Timur'],
            ['Rendra', 'rendrasci@gmail.com', '04202505007', '085712345678', 'kabid_dukbis', 'Jl. Pandanaran No.10, Semarang'],
            ['Satria', 'satriasci@gmail.com', '03202505010', '085798765432', 'petugas_security', 'Jl. Gajah Mada No.45, Semarang'],
        ];

        // CABANG 4 - JAKARTA
        $usersCabang4 = [
            ['Dwi Haryanto', 'dwisci@gmail.com', '03202505013', '085734567890', 'petugas_security', 'Jl. Diponegoro No.23, Jakarta Barat'],
            ['Fajar', 'fajarsci@gmail.com', '04202506007', '085765432109', 'kabid_dukbis', 'Jl. Sudirman No.55, Jakarta Selatan'],
            ['Toni', 'tonisci@gmail.com', '03202505014', '085798764689', 'petugas_security', 'Jl. Cempaka Putih, Jakarta Pusat'],
            ['Yusuf', 'yusufsci@gmail.com', '03202505012', '085701234567', 'petugas_security', 'Jl. MH Thamrin No.88, Jakarta Pusat'],
        ];

        // CABANG 5 - BANDUNG
        $usersCabang5 = [
            ['Agung', 'agungsci@gmail.com', '03202505007', '085717812903', 'petugas_security', 'Jl. Braga No.18, Bandung'],
            ['Dimas', 'dimassci@gmail.com', '03202505008', '085634681204', 'petugas_security', 'Jl. Asia Afrika No.67, Bandung'],
            ['Rahmat', 'rahmatsci@gmail.com', '03202505009', '085634674512', 'petugas_security', 'Jl. Dago No.45, Bandung'],
            ['Rangga', 'ranggasci@gmail.com', '04202505006', '085634675689', 'kabid_dukbis', 'Jl. Pattimuan No.34, Bandung Barat'],
        ];

        $allUsers = [
            1 => $usersCabang1,
            2 => $usersCabang2,
            3 => $usersCabang3,
            4 => $usersCabang4,
            5 => $usersCabang5,
        ];

        foreach ($allUsers as $cabangId => $users) {
            foreach ($users as $u) {
                User::create([
                    'nama'        => $u[0],
                    'email'       => $u[1],
                    'nomor_induk' => $u[2],
                    'no_telepon'  => $u[3],
                    'role'        => $u[4],
                    'alamat'      => $u[5],
                    'password'    => Hash::make('password'),
                    'foto'        => 'assets/profile.png',
                    'cabang_id'   => $cabangId,
                ]);
            }
        }
    }
}
