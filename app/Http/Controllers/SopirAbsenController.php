<?php

namespace App\Http\Controllers;

use App\Models\AbsenKendaraan;
use App\Models\Kendaraan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class SopirAbsenController extends Controller
{
    /** Form absen, dibuka dari hasil scan QR (?kode=KDR-...). */
    public function create(Request $request)
    {
        $kendaraan = $this->cariKendaraan((string) $request->query('kode'));

        if (!$kendaraan) {
            return redirect()->route('sopir.dashboard')
                ->with('error', 'QR tidak dikenali, kendaraan tidak aktif, atau bukan milik cabang kamu.');
        }

        $sudahAbsen = AbsenKendaraan::where('kendaraan_id', $kendaraan->id)
            ->whereDate('tanggal', now()->toDateString())
            ->first();

        if ($sudahAbsen) {
            return redirect()->route('sopir.dashboard')
                ->with('error', $kendaraan->plat_nomor . ' sudah diabsen hari ini oleh ' . $sudahAbsen->nama_sopir . ' pukul ' . $sudahAbsen->jam . '.');
        }

        $cabang = Auth::user()->cabang;

        // Data area kantor untuk badge "Dalam area" di browser (null bila belum diatur).
        $area = ($cabang && $cabang->punyaArea()) ? [
            'lat' => (float) $cabang->latitude,
            'lng' => (float) $cabang->longitude,
            'radius' => (int) $cabang->radius_meter,
            'nama' => $cabang->nama_cabang,
        ] : null;

        return view('sopir.form-absen', [
            'kendaraan' => $kendaraan,
            'area' => $area,
        ]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $kendaraan = $this->cariKendaraan((string) $request->input('kode'));
        abort_if(!$kendaraan, 404);

        $validated = $request->validate([
            'kode' => ['required', 'string'],
            'nama_sopir' => ['required', 'string', 'max:100'],
            'foto' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:5120'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'akurasi' => ['nullable', 'numeric', 'min:0', 'max:65535'],
        ], [
            'nama_sopir.required' => 'Nama sopir wajib diisi.',
            'foto.required' => 'Foto kendaraan wajib diambil.',
            'foto.image' => 'File harus berupa gambar.',
            'foto.max' => 'Ukuran foto maksimal 5 MB.',
            'latitude.required' => 'Lokasi belum terdeteksi. Izinkan akses lokasi lalu coba lagi.',
            'longitude.required' => 'Lokasi belum terdeteksi. Izinkan akses lokasi lalu coba lagi.',
        ]);

        $hariIni = now()->toDateString();

        if (AbsenKendaraan::where('kendaraan_id', $kendaraan->id)->whereDate('tanggal', $hariIni)->exists()) {
            return redirect()->route('sopir.dashboard')
                ->with('error', $kendaraan->plat_nomor . ' sudah diabsen hari ini.');
        }

        // Area dicek ulang di server, jangan percaya hasil dari browser.
        $cabang = $user->cabang;
        $jarak = null;
        $dalamArea = null;

        if ($cabang && $cabang->punyaArea()) {
            $jarak = $cabang->jarakKe((float) $validated['latitude'], (float) $validated['longitude']);
            $dalamArea = $jarak <= $cabang->radius_meter;

            if (!$dalamArea) {
                throw ValidationException::withMessages([
                    'latitude' => "Kamu berada {$jarak} m dari kantor (batas {$cabang->radius_meter} m). Absen hanya bisa dilakukan di area kantor.",
                ]);
            }
        }

        $fotoPath = $request->file('foto')->store('absen-kendaraan', 'public');

        try {
            AbsenKendaraan::create([
                'user_id' => $user->id,
                'kendaraan_id' => $kendaraan->id,
                'cabang_id' => $user->cabang_id,
                'nama_sopir' => $validated['nama_sopir'],
                'tanggal' => $hariIni,
                'waktu' => now()->format('H:i:s'),
                'foto' => $fotoPath,
                'latitude' => $validated['latitude'],
                'longitude' => $validated['longitude'],
                'akurasi_meter' => isset($validated['akurasi']) ? (int) round($validated['akurasi']) : null,
                'jarak_meter' => $jarak,
                'dalam_area' => $dalamArea,
            ]);
        } catch (\Throwable $e) {
            Storage::disk('public')->delete($fotoPath);
            throw $e;
        }

        return redirect()->route('sopir.dashboard')
            ->with('success', 'Absen ' . $kendaraan->plat_nomor . ' tersimpan.');
    }

    public function riwayat()
    {
        $riwayat = AbsenKendaraan::with('kendaraan:id,plat_nomor,jenis')
            ->where('user_id', Auth::id())
            ->orderByDesc('tanggal')->orderByDesc('waktu')
            ->paginate(10);

        return view('sopir.riwayat-absen', compact('riwayat'));
    }

    /** Kendaraan harus aktif dan milik cabang sopir yang login. */
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
