<?php

namespace App\Http\Controllers;


use App\Models\Patroli;
use App\Models\LokasiPatroli;
use App\Models\JadwalPatroli;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PetugasSecurityController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $userId = $user->id;
        $cabangId = $user->cabang_id; // ambil cabang user login
        $today = Carbon::today()->toDateString();

        // Mengecek apakah petugas dijadwalkan hari ini (via relasi user -> cabang)
        $dijadwalkan = JadwalPatroli::where('user_id', $userId)
            ->whereDate('tanggal', $today)
            ->whereHas('user', function ($query) use ($cabangId) {
                $query->where('cabang_id', $cabangId);
            })
            ->exists();

        if (!$dijadwalkan) {
            return view('petugas-security.dashboard', [
                'totalLokasi' => 0,
                'jumlahSelesai' => 0,
                'jumlahBelum' => 0,
                'persentase' => 0,
                'belumDipatroli' => collect(),
                'aktivitasTerakhir' => collect()
            ]);
        }

        // Lokasi patroli hanya dari cabang user
        $totalLokasi = LokasiPatroli::where('cabang_id', $cabangId)->count();

        // Ambil data patroli hari ini untuk user login (via relasi user -> cabang)
        $patroliHariIni = Patroli::with('lokasiPatroli')
            ->where('user_id', $userId)
            ->whereDate('tanggal_patroli', $today)
            ->whereHas('user', function ($query) use ($cabangId) {
                $query->where('cabang_id', $cabangId);
            })
            ->get();

        $lokasiDipatroliIds = $patroliHariIni->pluck('lokasi_id')->unique();
        $jumlahSelesai = $lokasiDipatroliIds->count();
        $jumlahBelum = $totalLokasi - $jumlahSelesai;
        $persentase = $totalLokasi > 0 ? ($jumlahSelesai / $totalLokasi) * 100 : 0;

        // Lokasi yang belum dipatroli (hanya di cabang user login)
        $belumDipatroli = LokasiPatroli::where('cabang_id', $cabangId)
            ->whereNotIn('id', $lokasiDipatroliIds)
            ->get();

        // 5 aktivitas patroli terakhir
        $aktivitasTerakhir = $patroliHariIni->sortByDesc('waktu_patroli')->take(5);

        return view('petugas-security.dashboard', compact(
            'totalLokasi',
            'jumlahSelesai',
            'jumlahBelum',
            'persentase',
            'belumDipatroli',
            'aktivitasTerakhir'
        ));
    }

    public function scanQr()
    {
        $user = Auth::user();
        $userId = $user->id;
        $cabangId = $user->cabang_id;
        $today = now()->toDateString();

        // cek jadwal dengan join ke users, karena cabang_id ada di tabel users
        $jadwalHariIni = JadwalPatroli::where('user_id', $userId)
            ->whereDate('tanggal', $today)
            ->whereHas('user', function ($query) use ($cabangId) {
                $query->where('cabang_id', $cabangId);
            })
            ->exists();

        if (!$jadwalHariIni) {
            return redirect()->route('petugas-security.dashboard')
                ->with('error', 'Anda tidak dijadwalkan patroli hari ini di cabang Anda.');
        }

        return view('petugas-security.scan-qr');
    }

    public function riwayatPatroli(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');
        $cabangId = Auth::user()->cabang_id; // ambil cabang user yang login

        $riwayatPatroli = Patroli::with(['lokasiPatroli'])
            ->where('user_id', Auth::id())
            ->whereHas('lokasiPatroli', function ($query) use ($cabangId) {
                $query->where('cabang_id', $cabangId); // filter cabang
            })
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->whereHas('lokasiPatroli', function ($subQuery) use ($search) {
                        $subQuery->where('nama_lokasi', 'like', '%' . $search . '%');
                    })->orWhere('tanggal_patroli', 'like', '%' . $search . '%');
                });
            })
            ->when($status, function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->orderByDesc('tanggal_patroli')
            ->paginate(10)
            ->withQueryString();

        return view('petugas-security.riwayat-patroli', compact('riwayatPatroli'));
    }
}
