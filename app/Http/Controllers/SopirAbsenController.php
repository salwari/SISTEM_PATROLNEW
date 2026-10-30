<?php

namespace App\Http\Controllers;

use App\Models\AbsenKendaraan;
use App\Models\Kendaraan;
use App\Models\KategoriKondisiKendaraan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class SopirAbsenController extends Controller
{
    public function create(Request $request)
    {
        $kendaraan = $this->cariKendaraan((string) $request->query('kode'));

        if (!$kendaraan) {
            return redirect()->route('sopir.dashboard')
                ->with('error', 'QR tidak dikenali, kendaraan tidak aktif, atau bukan milik cabang kamu.');
        }

        $sudahPatroli = AbsenKendaraan::where('kendaraan_id', $kendaraan->id)
            ->whereDate('tanggal', now()->toDateString())
            ->first();

        if ($sudahPatroli) {
            return redirect()->route('sopir.dashboard')
                ->with('error', $kendaraan->plat_nomor . ' sudah dipatroli hari ini oleh ' . $sudahPatroli->nama_sopir . ' pukul ' . $sudahPatroli->jam . '.');
        }

        $cabang = Auth::user()->cabang;

        $area = ($cabang && $cabang->punyaArea()) ? [
            'lat' => (float) $cabang->latitude,
            'lng' => (float) $cabang->longitude,
            'radius' => (int) $cabang->radius_meter,
            'nama' => $cabang->nama_cabang,
        ] : null;

        $kategoriKejadian = KategoriKondisiKendaraan::orderBy('id')->get();

        $laporanTerbuka = AbsenKendaraan::with('kategoriKejadian')
            ->where('kendaraan_id', $kendaraan->id)
            ->daruratTerbuka()
            ->orderByDesc('tanggal')
            ->get();

        return view('sopir.form-absen', [
            'kendaraan' => $kendaraan,
            'area' => $area,
            'kategoriKejadian' => $kategoriKejadian,
            'laporanTerbuka' => $laporanTerbuka,
        ]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $kendaraan = $this->cariKendaraan((string) $request->input('kode'));
        abort_if(!$kendaraan, 404);

        $validated = $request->validate([
            'kode' => ['required', 'string'],
            'status' => ['required', 'in:aman,darurat'],
            'kejadian_id' => [Rule::requiredIf($request->input('status') === 'darurat'), 'nullable', 'exists:kategori_kondisi_kendaraan,id'],
            'keterangan' => ['required', 'string', 'max:1000'],
            'foto' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:5120'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'akurasi' => ['nullable', 'numeric', 'min:0', 'max:65535'],
        ], [
            'status.required' => 'Status patroli wajib dipilih.',
            'kejadian_id.required' => 'Kondisi kendaraan wajib dipilih untuk status darurat.',
            'keterangan.required' => 'Keterangan wajib diisi.',
            'foto.required' => 'Foto kendaraan wajib diambil.',
            'foto.image' => 'File harus berupa gambar.',
            'foto.max' => 'Ukuran foto maksimal 5 MB.',
            'latitude.required' => 'Lokasi belum terdeteksi. Izinkan akses lokasi lalu coba lagi.',
            'longitude.required' => 'Lokasi belum terdeteksi. Izinkan akses lokasi lalu coba lagi.',
        ]);

        $hariIni = now()->toDateString();

        if (AbsenKendaraan::where('kendaraan_id', $kendaraan->id)->whereDate('tanggal', $hariIni)->exists()) {
            return redirect()->route('sopir.dashboard')
                ->with('error', $kendaraan->plat_nomor . ' sudah dipatroli hari ini.');
        }

        $cabang = $user->cabang;
        $jarak = null;
        $dalamArea = null;

        if ($cabang && $cabang->punyaArea()) {
            $jarak = $cabang->jarakKe((float) $validated['latitude'], (float) $validated['longitude']);
            $dalamArea = $jarak <= $cabang->radius_meter;

            if (!$dalamArea) {
                throw ValidationException::withMessages([
                    'latitude' => "Kamu berada {$jarak} m dari kantor (batas {$cabang->radius_meter} m). Patroli hanya bisa dilakukan di area kantor.",
                ]);
            }
        }

        $fotoPath = $request->file('foto')->store('absen-kendaraan', 'public');

        try {
            AbsenKendaraan::create([
                'user_id' => $user->id,
                'kendaraan_id' => $kendaraan->id,
                'cabang_id' => $user->cabang_id,
                'nama_sopir' => $user->nama,
                'tanggal' => $hariIni,
                'waktu' => now()->format('H:i:s'),
                'foto' => $fotoPath,
                'latitude' => $validated['latitude'],
                'longitude' => $validated['longitude'],
                'akurasi_meter' => isset($validated['akurasi']) ? (int) round($validated['akurasi']) : null,
                'jarak_meter' => $jarak,
                'dalam_area' => $dalamArea,
                'status' => $validated['status'],
                'kejadian_id' => $validated['status'] === 'darurat' ? $validated['kejadian_id'] : null,
                'keterangan' => $validated['keterangan'],
                'status_penanganan' => $validated['status'] === 'darurat' ? 'belum' : null,
            ]);
        } catch (\Throwable $e) {
            Storage::disk('public')->delete($fotoPath);
            throw $e;
        }

        return redirect()->route('sopir.dashboard')
            ->with('success', 'Patroli ' . $kendaraan->plat_nomor . ' berhasil disimpan.');
    }

    public function riwayat(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');

        $riwayat = AbsenKendaraan::with(['kendaraan:id,plat_nomor,jenis', 'kategoriKejadian:id,nama_kategori'])
            ->where('user_id', Auth::id())
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->whereHas('kendaraan', function ($sub) use ($search) {
                        $sub->where('plat_nomor', 'like', '%' . $search . '%')
                            ->orWhere('jenis', 'like', '%' . $search . '%');
                    })
                        ->orWhere('keterangan', 'like', '%' . $search . '%')
                        ->orWhere('tanggal', 'like', '%' . $search . '%');
                });
            })
            ->when($status, fn ($query) => $query->where('status', $status))
            ->orderByDesc('tanggal')->orderByDesc('waktu')
            ->paginate(10)
            ->withQueryString();

        return view('sopir.riwayat-absen', compact('riwayat'));
    }

    private function cariKendaraan(string $kode): ?Kendaraan
    {
        if ($kode === '') {
            return null;
        }

        return Kendaraan::where('kode_barcode', $kode)
            ->diCabang(Auth::user()->cabang_id)
            ->aktif()
            ->first();
    }
}