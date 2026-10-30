<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Cabang;
use App\Models\AbsenKendaraan;
use App\Models\PemeriksaanKamarMandi;
use App\Models\ChecklistKamarMandi;
use App\Models\Kendaraan;
use App\Models\KamarMandi;
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

        $jumlahPengguna = User::where('cabang_id', $cabangId)
            ->whereIn('role', ['petugas_security', 'kabid_dukbis', 'sopir']) 
            ->count();

        $jumlahLokasiPatroli = LokasiPatroli::where('cabang_id', $cabangId)->count();
        $jumlahPatroli = Patroli::where('cabang_id', $cabangId)->count();

        $harianLabels = [];
        $harianData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $harianLabels[] = Str::title($date->isoFormat('dddd'));
            $harianData[] = Patroli::where('cabang_id', $cabangId)
                ->whereDate('tanggal_patroli', $date)
                ->count();
        }

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

        $statusPatroli = Patroli::where('cabang_id', $cabangId)
            ->select('status', DB::raw('count(*) as total'))
            ->whereIn('status', ['aman', 'darurat'])
            ->groupBy('status')
            ->pluck('total', 'status');

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
        $kebersihan = $this->dataKebersihan($cabangId);

        return view('admin.dashboard', compact(
            'kendaraan',
            'kebersihan',
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

    private function dataKendaraan(int $cabangId): array
    {
        $hariIni = Carbon::today();

        $aktif = Kendaraan::diCabang($cabangId)->aktif();

        $jumlahAktif = (clone $aktif)->count();
        $sudahAbsen = (clone $aktif)->sudahAbsen($hariIni)->count();

        $belumAbsen = (clone $aktif)
            ->belumAbsen($hariIni)
            ->orderBy('plat_nomor')
            ->get(['id', 'plat_nomor', 'jenis']);

        $absenHariIni = AbsenKendaraan::with('kendaraan:id,plat_nomor,jenis')
            ->where('cabang_id', $cabangId)
            ->whereDate('tanggal', $hariIni)
            ->orderByDesc('waktu')
            ->get();

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
            'sudahAbsen' => $sudahAbsen,
            'belumAbsen' => $belumAbsen,
            'absenHariIni' => $absenHariIni,
            'mingguLabels' => $labels,
            'mingguData' => $data,
        ];
    }

    private function dataKebersihan(int $cabangId): array
    {
        $hariIni = Carbon::today();
    
        $aktif = KamarMandi::diCabang($cabangId)->aktif();
    
        $jumlahKamarMandi = (clone $aktif)->count();
        $sudahDicek = (clone $aktif)->sudahDicek($hariIni)->count();
    
        $terkini = (clone $aktif)->with('cekTerakhir.user')->get();
    
        $perluTindakan = $terkini
            ->filter(fn ($k) => $k->cekTerakhir?->status_kondisi === 'perlu_tindakan')
            ->values();
    
        $kondisi = [
            'baik' => $terkini->filter(fn ($k) => $k->cekTerakhir?->status_kondisi === 'baik')->count(),
            'perlu_tindakan' => $perluTindakan->count(),
            'belum_pernah' => $terkini->filter(fn ($k) => $k->cekTerakhir === null)->count(),
        ];
    
        $belumDicekHariIni = (clone $aktif)
            ->belumDicek($hariIni)
            ->orderBy('nama_kamar_mandi')
            ->get(['id', 'nama_kamar_mandi', 'lantai']);
    
        $mulai = Carbon::today()->subDays(6);
        $perTanggal = PemeriksaanKamarMandi::where('cabang_id', $cabangId)
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
            'jumlahKamarMandi' => $jumlahKamarMandi,
            'sudahDicek' => $sudahDicek,
            'belumDicekHariIni' => $belumDicekHariIni,
            'perluTindakan' => $perluTindakan,
            'kondisi' => $kondisi,
            'mingguLabels' => $labels,
            'mingguData' => $data,
        ];
    }

    public function dataPatroli(Request $request)
    {
        $user = Auth::user();
        $cabangId = $user->cabang_id;

        $search = $request->input('search');
        $status = $request->input('status');

        $patroli = Patroli::with(['user', 'lokasiPatroli'])
            ->where('cabang_id', $cabangId)
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