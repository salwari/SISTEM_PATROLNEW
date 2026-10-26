<?php

namespace App\Http\Controllers;

use App\Models\CekKendaraan;
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
            'status' => ['nullable', 'in:baik,perlu_tindakan'],
            'kendaraan_id' => ['nullable', 'integer'],
        ]);

        $search = $request->input('search');

        $riwayat = CekKendaraan::with(['kendaraan', 'user'])
            ->where('cabang_id', $cabangId)
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->whereHas('kendaraan', fn ($k) => $k->where('plat_nomor', 'like', "%{$search}%")
                            ->orWhere('jenis', 'like', "%{$search}%"))
                        ->orWhereHas('user', fn ($u) => $u->where('nama', 'like', "%{$search}%"));
                });
            })
            ->when($request->filled('status'), fn ($q) => $q->where('status_kondisi', $request->status))
            ->when($request->filled('kendaraan_id'), fn ($q) => $q->where('kendaraan_id', $request->kendaraan_id))
            ->when($request->filled('tanggal_dari'), fn ($q) => $q->whereDate('tanggal_cek', '>=', $request->tanggal_dari))
            ->when($request->filled('tanggal_sampai'), fn ($q) => $q->whereDate('tanggal_cek', '<=', $request->tanggal_sampai))
            ->orderByDesc('tanggal_cek')
            ->orderByDesc('waktu_cek')
            ->paginate(10)
            ->withQueryString();

        $daftarKendaraan = Kendaraan::diCabang($cabangId)->orderBy('plat_nomor')->get(['id', 'plat_nomor', 'jenis']);

        return view('admin.riwayat-kendaraan.index', compact('riwayat', 'daftarKendaraan', 'search'));
    }

    public function show(CekKendaraan $riwayatKendaraan)
    {
        abort_if((int) $riwayatKendaraan->cabang_id !== (int) Auth::user()->cabang_id, 403);

        $riwayatKendaraan->load(['kendaraan', 'user', 'detail']);

        // Detail dikelompokkan per kategori sesuai urutan di ChecklistKendaraan::KATEGORI.
        $detailPerKategori = $riwayatKendaraan->detail->groupBy('kategori');

        return view('admin.riwayat-kendaraan.show', [
            'cek' => $riwayatKendaraan,
            'detailPerKategori' => $detailPerKategori,
        ]);
    }
}
