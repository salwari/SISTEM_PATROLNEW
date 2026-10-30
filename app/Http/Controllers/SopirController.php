<?php

namespace App\Http\Controllers;

use App\Models\Kendaraan;
use Illuminate\Support\Facades\Auth;

class SopirController extends Controller
{
    /**
     * Dashboard sopir. Status tiap kendaraan memakai Kendaraan::statusHarian():
     * aman (sudah dipatroli aman hari ini), darurat (laporan darurat belum selesai), atau belum.
     */
    public function dashboard()
    {
        $user = Auth::user();

        $daftarKendaraan = Kendaraan::diCabang($user->cabang_id)
            ->aktif()
            ->denganStatusHarian(now()->toDateString())
            ->orderBy('plat_nomor')
            ->get();

        $total = $daftarKendaraan->count();
        $jumlahAman = $daftarKendaraan->filter(fn ($k) => $k->statusHarian() === 'aman')->count();
        $jumlahDarurat = $daftarKendaraan->filter(fn ($k) => $k->statusHarian() === 'darurat')->count();
        $sudah = $jumlahAman + $jumlahDarurat; // sudah dilaporkan (aman atau darurat)

        return view('sopir.dashboard', compact('daftarKendaraan', 'total', 'sudah', 'jumlahAman', 'jumlahDarurat'));
    }

    public function scanQr()
    {
        return view('sopir.scan-qr');
    }
}
