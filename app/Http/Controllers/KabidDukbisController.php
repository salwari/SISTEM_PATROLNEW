<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\User;
use App\Models\LokasiPatroli;
use App\Models\Patroli;
use App\Models\KamarMandi;
use App\Models\PemeriksaanKamarMandi;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Support\QrPng;
use Illuminate\Support\Facades\Crypt;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class KabidDukbisController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $cabangId = $user->cabang_id;

        $jumlahPetugas = User::where('role', 'petugas_security')
            ->where('cabang_id', $cabangId)
            ->count();

        $jumlahLokasiPatroli = LokasiPatroli::where('cabang_id', $cabangId)
            ->count();

        $jumlahLaporanPatroli = Patroli::whereHas('user', function ($q) use ($cabangId) {
            $q->where('cabang_id', $cabangId);
        })->count();

        $harianLabels = [];
        $harianData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $harianLabels[] = Str::title($date->isoFormat('dddd'));
            $harianData[] = Patroli::whereDate('tanggal_patroli', $date)
                ->whereHas('user', fn($q) => $q->where('cabang_id', $cabangId))
                ->count();
        }

        $mingguanLabels = [];
        $mingguanData = [];
        for ($i = 3; $i >= 0; $i--) {
            $startOfWeek = Carbon::now()->startOfWeek()->subWeeks($i);
            $endOfWeek = $startOfWeek->copy()->endOfWeek();
            $mingguanLabels[] = 'Minggu ke-' . (4 - $i);
            $mingguanData[] = Patroli::whereBetween('tanggal_patroli', [$startOfWeek, $endOfWeek])
                ->whereHas('user', fn($q) => $q->where('cabang_id', $cabangId))
                ->count();
        }

        $bulananLabels = [];
        $bulananData = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $bulananLabels[] = Str::title($month->isoFormat('MMMM'));
            $bulananData[] = Patroli::whereYear('tanggal_patroli', $month->year)
                ->whereMonth('tanggal_patroli', $month->month)
                ->whereHas('user', fn($q) => $q->where('cabang_id', $cabangId))
                ->count();
        }

        $jumlahKamarMandi = KamarMandi::where('cabang_id', $cabangId)->count();

        $toiletSelesaiHariIni = PemeriksaanKamarMandi::where('cabang_id', $cabangId)
            ->whereDate('tanggal', Carbon::today())
            ->distinct('kamar_mandi_id')
            ->count('kamar_mandi_id');

        $jumlahTemuanAktif = PemeriksaanKamarMandi::where('cabang_id', $cabangId)
            ->where('status_kondisi', 'perlu_tindakan')
            ->count();

        $temuanTerbaru = PemeriksaanKamarMandi::with(['kamarMandi', 'user'])
            ->where('cabang_id', $cabangId)
            ->where('status_kondisi', 'perlu_tindakan')
            ->orderByDesc('tanggal')
            ->orderByDesc('waktu')
            ->take(3)
            ->get();

        return view('kabid-dukbis.dashboard', compact(
            'jumlahPetugas',
            'jumlahLokasiPatroli',
            'jumlahLaporanPatroli',
            'harianLabels',
            'harianData',
            'mingguanLabels',
            'mingguanData',
            'bulananLabels',
            'bulananData',
            'jumlahKamarMandi',
            'toiletSelesaiHariIni',
            'jumlahTemuanAktif',
            'temuanTerbaru'
        ));
    }

    public function dataPetugasSecurity(Request $request)
    {
        $search = $request->input('search');
        $cabangId = Auth::user()->cabang_id; 

        $users = User::query()
            ->where('role', 'petugas_security')
            ->where('cabang_id', $cabangId) ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nama', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%')
                        ->orWhere('nomor_induk', 'like', '%' . $search . '%')
                        ->orWhere('no_telepon', 'like', '%' . $search . '%');
                });
            })
            ->orderBy('nama')
            ->paginate(10)
            ->withQueryString();

        return view('kabid-dukbis.data-petugas-security', compact('users', 'search'));
    }

    public function dataLokasiPatroli(Request $request)
    {
        $search = $request->input('search');
        $cabangId = Auth::user()->cabang_id; 

        $lokasiPatroli = LokasiPatroli::with('cabang')
            ->where('cabang_id', $cabangId) ->when($search, function ($query, $search) {
                $query->where('nama_lokasi', 'like', '%' . $search . '%');
            })
            ->orderBy('nama_lokasi')
            ->paginate(10)
            ->withQueryString();

        return view('kabid-dukbis.data-lokasi-patroli', compact('lokasiPatroli', 'search'));
    }

    public function laporanPatroli(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');
        $cabangId = Auth::user()->cabang_id; 

        $dataPatroli = Patroli::with(['user', 'lokasiPatroli'])
            ->where('cabang_id', $cabangId) ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->whereHas('user', function ($qUser) use ($search) {
                        $qUser->where('nama', 'like', '%' . $search . '%');
                    })
                        ->orWhereHas('lokasiPatroli', function ($qLokasi) use ($search) {
                            $qLokasi->where('nama_lokasi', 'like', '%' . $search . '%');
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

        return view('kabid-dukbis.laporan-patroli', compact('dataPatroli', 'search', 'status'));
    }

    public function redirectCetakLaporan(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'tanggal_mulai' => ['required', 'date'],
            'tanggal_selesai' => ['required', 'date'],
            'status' => ['nullable', 'in:aman,darurat'],
        ], [
            'tanggal_mulai.required' => 'Tanggal mulai wajib diisi.',
            'tanggal_mulai.date' => 'Tanggal mulai harus berupa tanggal yang valid.',
            'tanggal_selesai.required' => 'Tanggal selesai wajib diisi.',
            'tanggal_selesai.date' => 'Tanggal selesai harus berupa tanggal yang valid.',
            'status.in' => 'Status yang dipilih tidak valid.',
        ]);

        $validated->after(function ($validator) use ($request) {
            if ($request->filled(['tanggal_mulai', 'tanggal_selesai'])) {
                $start = Carbon::parse($request->tanggal_mulai);
                $end = Carbon::parse($request->tanggal_selesai);

                if ($start->greaterThan($end)) {
                    $validator->errors()->add('tanggal_mulai', 'Tanggal mulai tidak boleh lebih besar dari tanggal selesai.');
                }

                if ($start->format('Y-m') !== $end->format('Y-m')) {
                    $validator->errors()->add('tanggal_mulai', 'Tanggal mulai dan selesai harus dalam bulan dan tahun yang sama.');
                }

                if ($start->diffInMonths($end) > 1) {
                    $validator->errors()->add('tanggal_mulai', 'Rentang tanggal tidak boleh lebih dari satu bulan.');
                }
            }
        });

        if ($validated->fails()) {
            return back()->withInput()->with('error', $validated->errors()->first());
        }

        $token = Crypt::encrypt([
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'status' => $request->status,
        ]);

        return redirect()->route('kabid-dukbis.cetak-laporan-patroli', ['token' => $token]);
    }

    public function cetakLaporanTerenkripsi($token)
    {
        try {
            $params = Crypt::decrypt($token);
        } catch (\Exception $e) {
            abort(403, 'Token tidak valid atau rusak.');
        }

        $startDate = $params['tanggal_mulai'];
        $endDate = $params['tanggal_selesai'];
        $status = $params['status'];

        $cabangId = Auth::user()->cabang_id;

        $dataPatroli = Patroli::with(['user', 'lokasiPatroli'])
            ->where('cabang_id', $cabangId)
            ->whereBetween('tanggal_patroli', [$startDate, $endDate])
            ->when($status, fn($query) => $query->where('status', $status))
            ->get();

        if ($dataPatroli->isEmpty()) {
            return back()->with('error', 'Tidak ditemukan data patroli pada rentang tanggal dan status yang dipilih.');
        }

        $cabang = Cabang::find($cabangId);

        $kepala = User::where('role', 'kabid_dukbis')
            ->where('cabang_id', $cabangId)
            ->first();

        $namaKepala = $kepala?->nama ?? 'Nama Kepala';
        $nomorInduk = $kepala?->nomor_induk ?? '-';

        $adminCabang = User::where('cabang_id', $cabangId)
            ->whereIn('role', ['admin', 'kabid_dukbis'])
            ->first();

        $teleponCabang = $adminCabang?->no_telepon ?? '-';
        $emailCabang = $adminCabang?->email ?? '-';

        $linkVerifikasi = url('/kabid-dukbis/laporan-patroli/cetak/' . $token);

        $qrText = "Laporan Patroli\nDisahkan oleh : $namaKepala" .
            "\nNomor Induk : $nomorInduk" .
            "\nTanggal : " . now()->translatedFormat('d F Y') .
            "\n\nVerifikasi :\n" . $linkVerifikasi;

        $qrCode = base64_encode(QrPng::make($qrText, 150, 2));

        $pdf = Pdf::loadView('kabid-dukbis.cetak-laporan-patroli', [
            'data' => $dataPatroli,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'qrCode' => $qrCode,
            'namaKepala' => $namaKepala,
            'nomorInduk' => $nomorInduk,
            'cabang' => $cabang,
            'teleponCabang' => $teleponCabang,
            'emailCabang' => $emailCabang,
        ])->setPaper('A4', 'portrait');

        return $pdf->stream('laporan_patroli_' . now()->format('Ymd_His') . '.pdf');
    }

    public function verifikasiLaporanToken($token)
    {
        try {
            $params = Crypt::decrypt($token);
        } catch (\Exception $e) {
            abort(403, 'Token tidak valid atau telah rusak.');
        }

        $startDate = $params['tanggal_mulai'];
        $endDate = $params['tanggal_selesai'];
        $status = $params['status'];

        $dataPatroli = Patroli::with(['user', 'lokasiPatroli', 'unit'])
            ->whereBetween('tanggal_patroli', [$startDate, $endDate])
            ->when($status, fn($q) => $q->where('status', $status))
            ->get();

        $cabangId = Auth::user()->cabang_id;

        $kepala = User::where('role', 'kabid_dukbis')
            ->where('cabang_id', $cabangId)
            ->first();

        $namaKepala = $kepala?->nama ?? 'Nama Kepala';
        $nomorInduk = $kepala?->nomor_induk ?? '-';

        $qrText = "Laporan Patroli\nDisahkan oleh : $namaKepala" .
            "\nNomor Induk : $nomorInduk" .
            "\nTanggal : " . now()->translatedFormat('d F Y') .
            "\n\nVerifikasi :\n" . url('/verifikasi-laporan/' . $token);

        $qrCode = base64_encode(QrPng::make($qrText, 150, 2));

        return view('kabid-dukbis.cetak-laporan-patroli', [
            'data' => $dataPatroli,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'qrCode' => $qrCode,
            'preview' => true,
            'namaKepala' => $namaKepala,
        ]);
    }

    public function dataLokasiKebersihan(Request $request)
    {
        $search = $request->input('search');
        $cabangId = Auth::user()->cabang_id;
        $lokasiKebersihan = KamarMandi::where('cabang_id', $cabangId)
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nama_kamar_mandi', 'like', "%{$search}%")
                      ->orWhere('kode_kamar_mandi', 'like', "%{$search}%")
                      ->orWhere('lantai', 'like', "%{$search}%");
                });
            })
            ->orderBy('kode_kamar_mandi')
            ->paginate(10)
            ->withQueryString();
        return view('kabid-dukbis.data-lokasi-kebersihan', compact('lokasiKebersihan', 'search'));
    }
    public function temuanKamarMandi(Request $request)
    {
        $search = $request->input('search');
        $tanggalMulai = $request->input('tanggal_mulai');
        $tanggalSelesai = $request->input('tanggal_selesai');
        $cabangId = Auth::user()->cabang_id;
        $temuanList = PemeriksaanKamarMandi::with(['kamarMandi', 'user', 'detailPemeriksaan.checklist'])
            ->where('cabang_id', $cabangId)
            ->where('status_kondisi', 'perlu_tindakan')
            ->when($search, function ($query, $search) {
                $query->whereHas('kamarMandi', function ($q) use ($search) {
                    $q->where('nama_kamar_mandi', 'like', "%{$search}%")
                      ->orWhere('kode_kamar_mandi', 'like', "%{$search}%");
                });
            })
            ->when($tanggalMulai && $tanggalSelesai, function ($query) use ($tanggalMulai, $tanggalSelesai) {
                $query->whereBetween('tanggal', [$tanggalMulai, $tanggalSelesai]);
            })
            ->orderByDesc('tanggal')
            ->orderByDesc('waktu')
            ->paginate(10)
            ->withQueryString();
        return view('kabid-dukbis.temuan-kamar-mandi', compact('temuanList', 'search', 'tanggalMulai', 'tanggalSelesai'));
    }

    public function laporanKebersihan(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');
        $tanggalMulai = $request->input('tanggal_mulai');
        $tanggalSelesai = $request->input('tanggal_selesai');
        $cabangId = Auth::user()->cabang_id;
        $laporanKebersihan = PemeriksaanKamarMandi::with(['kamarMandi', 'user', 'detailPemeriksaan.checklist'])
            ->where('cabang_id', $cabangId)
            ->when($status, fn($q, $s) => $q->where('status_kondisi', $s))
            ->when($search, function ($query, $search) {
                $query->whereHas('kamarMandi', function ($q) use ($search) {
                    $q->where('nama_kamar_mandi', 'like', "%{$search}%")
                      ->orWhere('kode_kamar_mandi', 'like', "%{$search}%");
                });
            })
            ->when($tanggalMulai && $tanggalSelesai, function ($query) use ($tanggalMulai, $tanggalSelesai) {
                $query->whereBetween('tanggal', [$tanggalMulai, $tanggalSelesai]);
            })
            ->orderByDesc('tanggal')
            ->orderByDesc('waktu')
            ->paginate(10)
            ->withQueryString();
        return view('kabid-dukbis.laporan-kebersihan', compact('laporanKebersihan', 'search', 'status', 'tanggalMulai', 'tanggalSelesai'));
    }
        public function redirectCetakLaporanKebersihan(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'tanggal_mulai' => ['required', 'date'],
            'tanggal_selesai' => ['required', 'date'],
            'status' => ['nullable', 'in:baik,perlu_tindakan'],
        ], [
            'tanggal_mulai.required' => 'Tanggal mulai wajib diisi.',
            'tanggal_mulai.date' => 'Tanggal mulai harus berupa tanggal yang valid.',
            'tanggal_selesai.required' => 'Tanggal selesai wajib diisi.',
            'tanggal_selesai.date' => 'Tanggal selesai harus berupa tanggal yang valid.',
            'status.in' => 'Status yang dipilih tidak valid.',
        ]);

        $validated->after(function ($validator) use ($request) {
            if ($request->filled(['tanggal_mulai', 'tanggal_selesai'])) {
                $start = Carbon::parse($request->tanggal_mulai);
                $end = Carbon::parse($request->tanggal_selesai);

                if ($start->greaterThan($end)) {
                    $validator->errors()->add('tanggal_mulai', 'Tanggal mulai tidak boleh lebih besar dari tanggal selesai.');
                }

                if ($start->format('Y-m') !== $end->format('Y-m')) {
                    $validator->errors()->add('tanggal_mulai', 'Tanggal mulai dan selesai harus dalam bulan dan tahun yang sama.');
                }

                if ($start->diffInMonths($end) > 1) {
                    $validator->errors()->add('tanggal_mulai', 'Rentang tanggal tidak boleh lebih dari satu bulan.');
                }
            }
        });

        if ($validated->fails()) {
            return back()->withInput()->with('error', $validated->errors()->first());
        }

        $cabangId = Auth::user()->cabang_id;
        $adaData = PemeriksaanKamarMandi::where('cabang_id', $cabangId)
            ->whereBetween('tanggal', [$request->tanggal_mulai, $request->tanggal_selesai])
            ->when($request->status, fn($q) => $q->where('status_kondisi', $request->status))
            ->exists();

        if (!$adaData) {
            return back()->withInput()->with('error', 'Tidak ditemukan data pemeriksaan kebersihan pada rentang tanggal dan status yang dipilih.');
        }

        $token = Crypt::encrypt([
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'status' => $request->status,
        ]);

        return redirect()->route('kabid-dukbis.cetak-laporan-kebersihan', ['token' => $token]);

        return redirect()->route('kabid-dukbis.cetak-laporan-kebersihan', ['token' => $token]);
    }

    public function cetakLaporanKebersihanTerenkripsi($token)
    {
        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', 120);

        try {
            $params = Crypt::decrypt($token);
        } catch (\Exception $e) {
            abort(403, 'Token tidak valid atau rusak.');
        }

        $startDate = $params['tanggal_mulai'];
        $endDate = $params['tanggal_selesai'];
        $status = $params['status'];

        $cabangId = Auth::user()->cabang_id;

        $dataKebersihan = PemeriksaanKamarMandi::with(['kamarMandi', 'user'])
            ->where('cabang_id', $cabangId)
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->when($status, fn($query) => $query->where('status_kondisi', $status))
            ->orderBy('tanggal')
            ->orderBy('waktu')
            ->get();

        if ($dataKebersihan->isEmpty()) {
            return redirect()->route('kabid-dukbis.laporan-kebersihan')
                ->with('error', 'Tidak ditemukan data pemeriksaan kebersihan pada rentang tanggal dan status yang dipilih.');
        }

        $cabang = Cabang::find($cabangId);

        $kepala = User::where('role', 'kabid_dukbis')
            ->where('cabang_id', $cabangId)
            ->first();

        $namaKepala = $kepala?->nama ?? 'Nama Kepala';
        $nomorInduk = $kepala?->nomor_induk ?? '-';

        $adminCabang = User::where('cabang_id', $cabangId)
            ->whereIn('role', ['admin', 'kabid_dukbis'])
            ->first();

        $teleponCabang = $adminCabang?->no_telepon ?? '-';
        $emailCabang = $adminCabang?->email ?? '-';

        $linkVerifikasi = url('/kabid-dukbis/laporan-kebersihan/cetak/' . $token);
        $qrText = "Laporan Pemeriksaan Kebersihan\nDisahkan oleh : $namaKepala" .
            "\nNomor Induk : $nomorInduk" .
            "\nTanggal : " . now()->translatedFormat('d F Y') .
            "\n\nVerifikasi :\n" . $linkVerifikasi;

        $qrCode = base64_encode(QrPng::make($qrText, 150, 2));

        $pdf = Pdf::loadView('kabid-dukbis.cetak-laporan-kebersihan', [
            'data' => $dataKebersihan,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'qrCode' => $qrCode,
            'namaKepala' => $namaKepala,
            'nomorInduk' => $nomorInduk,
            'cabang' => $cabang,
            'teleponCabang' => $teleponCabang,
            'emailCabang' => $emailCabang,
        ])->setPaper('A4', 'portrait');

        return $pdf->stream('laporan_kebersihan_' . now()->format('Ymd_His') . '.pdf');
    }
}