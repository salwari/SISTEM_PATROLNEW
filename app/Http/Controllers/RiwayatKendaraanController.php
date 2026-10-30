<?php

namespace App\Http\Controllers;

use App\Models\AbsenKendaraan;
use App\Models\Kendaraan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RiwayatKendaraanController extends Controller
{
    public function index(Request $request)
    {
        $cabangId = Auth::user()->cabang_id;

        $request->validate([
            'tanggal_dari' => ['nullable', 'date'],
            'tanggal_sampai' => ['nullable', 'date', 'after_or_equal:tanggal_dari'],
            'lokasi' => ['nullable', 'in:dalam_area,tanpa_area'],
            'kendaraan_id' => ['nullable', 'integer'],
        ]);

        $search = $request->input('search');

        $riwayat = AbsenKendaraan::with(['kendaraan', 'user'])
            ->where('cabang_id', $cabangId)
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nama_sopir', 'like', "%{$search}%")
                        ->orWhereHas('kendaraan', function ($k) use ($search) {
                            $k->where('plat_nomor', 'like', "%{$search}%")
                                ->orWhere('jenis', 'like', "%{$search}%");
                        });
                });
            })
            ->when($request->lokasi === 'dalam_area', fn ($q) => $q->where('dalam_area', true))
            ->when($request->lokasi === 'tanpa_area', fn ($q) => $q->whereNull('dalam_area'))
            ->when($request->filled('kendaraan_id'), fn ($q) => $q->where('kendaraan_id', $request->kendaraan_id))
            ->when($request->filled('tanggal_dari'), fn ($q) => $q->whereDate('tanggal', '>=', $request->tanggal_dari))
            ->when($request->filled('tanggal_sampai'), fn ($q) => $q->whereDate('tanggal', '<=', $request->tanggal_sampai))
            ->orderByDesc('tanggal')
            ->orderByDesc('waktu')
            ->paginate(10)
            ->withQueryString();

        $daftarKendaraan = Kendaraan::diCabang($cabangId)
            ->orderBy('plat_nomor')
            ->get(['id', 'plat_nomor', 'jenis']);

        return view('admin.riwayat-kendaraan.index', compact('riwayat', 'daftarKendaraan', 'search'));
    }
}