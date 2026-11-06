<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\LokasiPatroli;
use App\Models\KamarMandi;
use App\Models\Kendaraan;
use App\Models\Cabang;
use App\Models\Patroli;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class SuperAdminController extends Controller
{
    public function dashboard()
    {
        $jumlahCabang = Cabang::count();
        $jumlahPetugasSecurity = User::where('role', 'petugas_security')->count();
        $jumlahLokasiPatroli = LokasiPatroli::count();
        $jumlahPatroli = Patroli::count();
        $jumlahOb = User::where('role', 'ob')->count();
        $jumlahSopir = User::where('role', 'sopir')->count();
        $jumlahLokasiKebersihan = KamarMandi::count();
        $jumlahKendaraan = Kendaraan::count();

        $harianLabels = [];
        $harianData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $harianLabels[] = Str::title($date->isoFormat('dddd'));
            $harianData[] = Patroli::whereDate('tanggal_patroli', $date)->count();
        }

        $mingguanLabels = [];
        $mingguanData = [];
        for ($i = 3; $i >= 0; $i--) {
            $startOfWeek = Carbon::now()->startOfWeek()->subWeeks($i);
            $endOfWeek = $startOfWeek->copy()->endOfWeek();
            $mingguanLabels[] = 'Minggu ke-' . (4 - $i);
            $mingguanData[] = Patroli::whereBetween('tanggal_patroli', [$startOfWeek, $endOfWeek])->count();
        }

        $bulananLabels = [];
        $bulananData = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $bulananLabels[] = Str::title($month->isoFormat('MMMM'));
            $bulananData[] = Patroli::whereYear('tanggal_patroli', $month->year)
                ->whereMonth('tanggal_patroli', $month->month)
                ->count();
        }

        return view('super-admin.dashboard', compact(
            'jumlahCabang',
            'jumlahPetugasSecurity',
            'jumlahLokasiPatroli',
            'jumlahPatroli',
            'jumlahOb',
            'jumlahSopir',
            'jumlahLokasiKebersihan',
            'jumlahKendaraan',
            'harianLabels',
            'harianData',
            'mingguanLabels',
            'mingguanData',
            'bulananLabels',
            'bulananData',
        ));
    }

    public function dataPetugas(Request $request)
    {
        $search = $request->input('search');
        $cabangId = $request->input('cabang_id');

        $users = User::with('cabang')
            ->where('role', 'petugas_security')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nama', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%')
                        ->orWhere('nomor_induk', 'like', '%' . $search . '%')
                        ->orWhere('no_telepon', 'like', '%' . $search . '%');
                });
            })
            ->when($cabangId, function ($query, $cabangId) {
                $query->where('cabang_id', $cabangId);
            })
            ->orderBy('nama')
            ->paginate(10)
            ->withQueryString();

        $cabangs = Cabang::orderBy('nama_cabang')->get();
        return view('super-admin.data-petugas', compact('users', 'search', 'cabangId', 'cabangs'));
    }

    public function dataLokasi(Request $request)
    {
        $search = $request->input('search');
        $cabangId = $request->input('cabang_id');

        $lokasiPatroli = LokasiPatroli::with('cabang')
            ->when($search, function ($query, $search) {
                $query->where('nama_lokasi', 'like', '%' . $search . '%');
            })
            ->when($cabangId, function ($query, $cabangId) {
                $query->where('cabang_id', $cabangId);
            })
            ->orderBy('nama_lokasi')
            ->paginate(10)
            ->withQueryString();

        $cabangs = Cabang::orderBy('nama_cabang')->get();

        return view('super-admin.data-lokasi', compact('lokasiPatroli', 'search', 'cabangId', 'cabangs'));
    }

    public function dataPatroliPetugas(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');
        $cabangId = $request->input('cabang_id');

        $patroli = Patroli::with(['user', 'lokasiPatroli', 'cabang'])
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->whereHas('user', function ($subQuery) use ($search) {
                        $subQuery->where('nama', 'like', '%' . $search . '%');
                    })
                        ->orWhereHas('lokasiPatroli', function ($subQuery) use ($search) {
                            $subQuery->where('nama_lokasi', 'like', '%' . $search . '%');
                        })
                        ->orWhereHas('cabang', function ($qCabang) use ($search) {
                            $qCabang->where('nama_cabang', 'like', '%' . $search . '%');
                        })
                        ->orWhere('tanggal_patroli', 'like', '%' . $search . '%');
                });
            })
            ->when($status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->when($cabangId, function ($query, $cabangId) {
                $query->where('cabang_id', $cabangId);
            })
            ->orderBy('tanggal_patroli', 'desc')
            ->paginate(10)
            ->withQueryString();

        $cabangs = Cabang::orderBy('nama_cabang')->get();

        return view('super-admin.data-patroli-petugas', compact('patroli', 'search', 'status', 'cabangId', 'cabangs'));
    }

    public function dataOb(Request $request)
    {
        return $this->dataStaf($request, 'ob', 'OB', 'super-admin.data-ob');
    }

    public function dataSopir(Request $request)
    {
        return $this->dataStaf($request, 'sopir', 'Sopir', 'super-admin.data-sopir');
    }

    private function dataStaf(Request $request, string $role, string $judul, string $routeName)
    {
        $search = $request->input('search');
        $cabangId = $request->input('cabang_id');

        $users = User::with('cabang')
            ->where('role', $role)
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nama', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%')
                        ->orWhere('nomor_induk', 'like', '%' . $search . '%')
                        ->orWhere('no_telepon', 'like', '%' . $search . '%');
                });
            })
            ->when($cabangId, function ($query, $cabangId) {
                $query->where('cabang_id', $cabangId);
            })
            ->orderBy('nama')
            ->paginate(10)
            ->withQueryString();

        $cabangs = Cabang::orderBy('nama_cabang')->get();

        return view('super-admin.data-staf', compact('users', 'search', 'cabangId', 'cabangs', 'judul', 'routeName'));
    }

    public function dataLokasiKebersihan(Request $request)
    {
        $search = $request->input('search');
        $cabangId = $request->input('cabang_id');

        $lokasiKebersihan = KamarMandi::with('cabang')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nama_kamar_mandi', 'like', '%' . $search . '%')
                        ->orWhere('lantai', 'like', '%' . $search . '%');
                });
            })
            ->when($cabangId, function ($query, $cabangId) {
                $query->where('cabang_id', $cabangId);
            })
            ->orderBy('nama_kamar_mandi')
            ->paginate(10)
            ->withQueryString();

        $cabangs = Cabang::orderBy('nama_cabang')->get();

        return view('super-admin.data-lokasi-kebersihan', compact('lokasiKebersihan', 'search', 'cabangId', 'cabangs'));
    }

    public function dataKendaraan(Request $request)
    {
        $search = $request->input('search');
        $cabangId = $request->input('cabang_id');

        $kendaraan = Kendaraan::with('cabang')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('plat_nomor', 'like', '%' . $search . '%')
                        ->orWhere('jenis', 'like', '%' . $search . '%');
                });
            })
            ->when($cabangId, function ($query, $cabangId) {
                $query->where('cabang_id', $cabangId);
            })
            ->orderBy('plat_nomor')
            ->paginate(10)
            ->withQueryString();

        $cabangs = Cabang::orderBy('nama_cabang')->get();

        return view('super-admin.data-kendaraan', compact('kendaraan', 'search', 'cabangId', 'cabangs'));
    }
}