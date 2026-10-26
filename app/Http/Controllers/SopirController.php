<?php

namespace App\Http\Controllers;

use App\Models\Kendaraan;
use Illuminate\Support\Facades\Auth;

class SopirController extends Controller
{
    /**
     * Dashboard sopir = absen harian. Sebuah kendaraan dianggap "sudah diabsen" bila ada absen
     * hari ini, siapa pun sopirnya. Hanya kendaraan aktif di cabang sopir yang tampil.
     */
    public function dashboard()
    {
        $user = Auth::user();
        $hariIni = now()->toDateString();

        $daftarKendaraan = Kendaraan::diCabang($user->cabang_id)
            ->aktif()
            ->with(['absenKendaraan' => fn ($q) => $q->whereDate('tanggal', $hariIni)])
            ->orderBy('plat_nomor')
            ->get();

        $total = $daftarKendaraan->count();
        $sudah = $daftarKendaraan->filter(fn ($k) => $k->absenKendaraan->isNotEmpty())->count();

        return view('sopir.dashboard', compact('daftarKendaraan', 'total', 'sudah'));
    }

    public function scanQr()
    {
        return view('sopir.scan-qr');
    }
}
