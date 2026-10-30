<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Cabang;
use App\Models\AbsenKendaraan;
use App\Models\Kendaraan;
use App\Models\LokasiPatroli;
use App\Models\Patroli;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $cabangId = $user->cabang_id; 

        // Hitung jumlah sesuai cabang
        $jumlahPengguna = User::where('cabang_id', $cabangId)
            ->whereIn('role', ['petugas_security', 'kabid_dukbis', 'sopir']) // sama dengan yang ditampilkan di Data Pengguna
            ->count();

        $jumlahLokasiPatroli = LokasiPatroli::where('cabang_id', $cabangId)->count();
        $jumlahPatroli = Patroli::where('cabang_id', $cabangId)->count();

        // Aktivitas Harian
        $harianLabels = [];
        $harianData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $harianLabels[] = Str::title($date->isoFormat('dddd'));
            $harianData[] = Patroli::where('cabang_id', $cabangId)
                ->whereDate('tanggal_patroli', $date)
                ->count();
        }

        // Aktivitas Mingguan
        $mingguanLabels = [];
        $mingguanData = [];
        for ($i = 3; $i >= 0; $i--) {
            $startOfWeek = Carbon::now()->startOfWeek()->subWeeks($i);
            $endOfWeek = $startOfWeek->copy()->endOfWeek();
            $mingguanLabels[] = 'Minggu ke-' . (4 - $i);
            $mingguanData[] = Patroli::where('cabang_id', $cabangId)
                ->whereBetween('tanggal_patroli', [$startOfWeek, $endOfWeek])
                ->count();
        }

        // Aktivitas Bulanan
        $bulananLabels = [];
        $bulananData = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $bulananLabels[] = Str::title($month->isoFormat('MMMM'));
            $bulananData[] = Patroli::where('cabang_id', $cabangId)
                ->whereYear('tanggal_patroli', $month->year)
                ->whereMonth('tanggal_patroli', $month->month)
                ->count();
        }

        // Pie Chart Status
        $statusPatroli = Patroli::where('cabang_id', $cabangId)
            ->select('status', DB::raw('count(*) as total'))
            ->whereIn('status', ['aman', 'darurat'])
            ->groupBy('status')
            ->pluck('total', 'status');

        // Notifikasi Petugas Kurang Patroli
        $currentMonth = Carbon::now();
        $minimalPatroli = 8;

        $petugasKurangPatroli = User::where('cabang_id', $cabangId)
            ->where('role', 'petugas_security')
            ->get()
            ->filter(function ($user) use ($currentMonth, $minimalPatroli, $cabangId) {
                $jumlah = Patroli::where('cabang_id', $cabangId)
                    ->where('user_id', $user->id)
                    ->whereMonth('tanggal_patroli', $currentMonth->month)
                    ->whereYear('tanggal_patroli', $currentMonth->year)
                    ->count();
                return $jumlah < $minimalPatroli;
            });

        $kendaraan = $this->dataKendaraan($cabangId);

        return view('admin.dashboard', compact(
            'kendaraan',
            'jumlahPengguna',
            'jumlahLokasiPatroli',
            'jumlahPatroli',
            'harianLabels',
            'harianData',
            'mingguanLabels',
            'mingguanData',
            'bulananLabels',
            'bulananData',
            'statusPatroli',
            'petugasKurangPatroli',
        ));
    }

    /**
     * Ringkasan patroli kendaraan untuk dashboard admin cabang.
     * Laporan terbuka = laporan darurat yang penanganannya belum selesai.
     */
    private function dataKendaraan(int $cabangId): array
    {
        $hariIni = Carbon::today();

        // Status tiap kendaraan dihitung oleh Kendaraan::statusHarian() (sama dengan dashboard sopir & kabid).
        $semua = Kendaraan::diCabang($cabangId)->aktif()
            ->denganStatusHarian($hariIni)
            ->with('patroliTerakhir')
            ->orderBy('plat_nomor')
            ->get();

        $jumlahAktif = $semua->count();

        // "Sudah dipatroli" = aman hari ini + darurat (sudah dilaporkan).
        $sudahDipatroli = $semua->filter(fn ($k) => $k->statusHarian() !== 'belum')->count();

        $belumDipatroliHariIni = $semua->filter(fn ($k) => $k->statusHarian() === 'belum')->values();

        $laporanTerbuka = AbsenKendaraan::with(['kendaraan:id,plat_nomor,jenis', 'kategoriKejadian:id,nama_kategori'])
            ->where('cabang_id', $cabangId)
            ->daruratTerbuka()
            ->orderByDesc('tanggal')
            ->orderByDesc('waktu')
            ->get();

        $darurat = $semua->filter(fn ($k) => $k->statusHarian() === 'darurat')->count();
        $belumPernah = $semua->filter(fn ($k) => $k->statusHarian() !== 'darurat' && $k->patroliTerakhir === null)->count();

        $kondisi = [
            'aman' => $jumlahAktif - $darurat - $belumPernah,
            'darurat' => $darurat,
            'belum_pernah' => $belumPernah,
        ];

        $mulai = Carbon::today()->subDays(6);
        $perTanggal = AbsenKendaraan::where('cabang_id', $cabangId)
            ->whereDate('tanggal', '>=', $mulai)
            ->selectRaw('DATE(tanggal) as tgl, COUNT(*) as total')
            ->groupBy('tgl')
            ->pluck('total', 'tgl');

        $labels = [];
        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $tgl = Carbon::today()->subDays($i);
            $labels[] = Str::title($tgl->isoFormat('dddd'));
            $data[] = (int) ($perTanggal[$tgl->toDateString()] ?? 0);
        }

        return [
            'jumlahAktif' => $jumlahAktif,
            'sudahDipatroli' => $sudahDipatroli,
            'belumDipatroliHariIni' => $belumDipatroliHariIni,
            'laporanTerbuka' => $laporanTerbuka,
            'kondisi' => $kondisi,
            'mingguLabels' => $labels,
            'mingguData' => $data,
        ];
    }

    public function dataPatroli(Request $request)
    {
        $user = Auth::user();
        $cabangId = $user->cabang_id; // ambil cabang admin login

        $search = $request->input('search');
        $status = $request->input('status');

        $patroli = Patroli::with(['user', 'lokasiPatroli'])
            ->where('cabang_id', $cabangId) // filter sesuai cabang
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->whereHas('user', function ($subQuery) use ($search) {
                        $subQuery->where('nama', 'like', '%' . $search . '%');
                    })
                        ->orWhereHas('lokasiPatroli', function ($subQuery) use ($search) {
                            $subQuery->where('nama_lokasi', 'like', '%' . $search . '%');
                        })
                        ->orWhere('tanggal_patroli', 'like', '%' . $search . '%');
                });
            })
            ->when($status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->orderBy('tanggal_patroli', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('admin.data-patroli', compact('patroli', 'search', 'status'));
    }
}