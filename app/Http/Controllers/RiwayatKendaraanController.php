<?php

namespace App\Http\Controllers;

use App\Models\AbsenKendaraan;
use App\Models\KategoriKondisiKendaraan;
use App\Models\Kendaraan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class RiwayatKendaraanController extends Controller
{
    public function index(Request $request)
    {
        $cabangId = Auth::user()->cabang_id;

        $request->validate([
            'tanggal_dari' => ['nullable', 'date'],
            'tanggal_sampai' => ['nullable', 'date', 'after_or_equal:tanggal_dari'],
            'status' => ['nullable', 'in:aman,darurat'],
            'penanganan' => ['nullable', 'in:terbuka,belum,proses,selesai'],
            'kondisi_id' => ['nullable', 'integer'],
            'kendaraan_id' => ['nullable', 'integer'],
        ]);

        $search = $request->input('search');

        $riwayat = AbsenKendaraan::with(['kendaraan:id,plat_nomor,jenis', 'kategoriKejadian:id,nama_kategori'])
            ->where('cabang_id', $cabangId)
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nama_sopir', 'like', "%{$search}%")
                        ->orWhere('keterangan', 'like', "%{$search}%")
                        ->orWhereHas('kendaraan', fn ($k) => $k->where('plat_nomor', 'like', "%{$search}%")
                            ->orWhere('jenis', 'like', "%{$search}%"));
                });
            })
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->when($request->filled('penanganan'), function ($q) use ($request) {
                if ($request->penanganan === 'terbuka') {
                    $q->daruratTerbuka();
                } else {
                    $q->where('status_penanganan', $request->penanganan);
                }
            })
            ->when($request->filled('kondisi_id'), fn ($q) => $q->where('kejadian_id', $request->kondisi_id))
            ->when($request->filled('kendaraan_id'), fn ($q) => $q->where('kendaraan_id', $request->kendaraan_id))
            ->when($request->filled('tanggal_dari'), fn ($q) => $q->whereDate('tanggal', '>=', $request->tanggal_dari))
            ->when($request->filled('tanggal_sampai'), fn ($q) => $q->whereDate('tanggal', '<=', $request->tanggal_sampai))
            ->orderByDesc('tanggal')
            ->orderByDesc('waktu')
            ->paginate(10)
            ->withQueryString();

        $daftarKendaraan = Kendaraan::diCabang($cabangId)->orderBy('plat_nomor')->get(['id', 'plat_nomor', 'jenis']);
        $daftarKondisi = KategoriKondisiKendaraan::orderBy('nama_kategori')->get(['id', 'nama_kategori']);

        $dasar = AbsenKendaraan::where('cabang_id', $cabangId);
        $hitung = [
            'semua' => (clone $dasar)->count(),
            'perlu' => (clone $dasar)->daruratTerbuka()->count(),
            'selesai' => (clone $dasar)->where('status', 'darurat')->where('status_penanganan', 'selesai')->count(),
            'aman' => (clone $dasar)->where('status', 'aman')->count(),
        ];

        return view('admin.riwayat-kendaraan.index', compact('riwayat', 'daftarKendaraan', 'daftarKondisi', 'search', 'hitung'));
    }

    public function show(AbsenKendaraan $riwayatKendaraan)
    {
        $this->pastikanMilikCabang($riwayatKendaraan);

        $riwayatKendaraan->load(['kendaraan', 'kategoriKejadian', 'user', 'penangan']);

        return view('admin.riwayat-kendaraan.show', ['laporan' => $riwayatKendaraan]);
    }

    public function updatePenanganan(Request $request, AbsenKendaraan $riwayatKendaraan)
    {
        $this->pastikanMilikCabang($riwayatKendaraan);
        abort_if($riwayatKendaraan->status !== 'darurat', 404);

        $validated = $request->validate([
            'status_penanganan' => ['required', 'in:belum,proses,selesai'],
            'catatan_penanganan' => [
                Rule::requiredIf($request->input('status_penanganan') === 'selesai'),
                'nullable', 'string', 'max:1000',
            ],
        ], [
            'status_penanganan.required' => 'Status penanganan wajib dipilih.',
            'catatan_penanganan.required' => 'Catatan wajib diisi saat laporan ditandai selesai (misal: ban sudah ditambal).',
        ]);

        $riwayatKendaraan->update([
            'status_penanganan' => $validated['status_penanganan'],
            'catatan_penanganan' => $validated['catatan_penanganan'] ?? null,
            'ditangani_oleh' => Auth::id(),
            'waktu_ditangani' => now(),
        ]);

        // Sudah selesai -> kembali ke daftar riwayat; status lain tetap di halaman detail.
        if ($validated['status_penanganan'] === 'selesai') {
            return redirect()->route('riwayat-kendaraan.index')
                ->with('success', 'Laporan darurat ' . ($riwayatKendaraan->kendaraan->plat_nomor ?? '') . ' ditandai selesai.');
        }

        return back()->with('success', 'Status penanganan berhasil diperbarui.');
    }

    private function pastikanMilikCabang(AbsenKendaraan $laporan): void
    {
        abort_if((int) $laporan->cabang_id !== (int) Auth::user()->cabang_id, 403);
    }
}