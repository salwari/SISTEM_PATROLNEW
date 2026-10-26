<?php

namespace App\Http\Controllers;

use App\Models\Kendaraan;
use App\Support\QrPng;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Intervention\Image\ImageManager;

class KendaraanController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');

        $kendaraan = Kendaraan::query()
            ->with('cekTerakhir')
            ->diCabang(Auth::user()->cabang_id)
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('plat_nomor', 'like', '%' . $search . '%')
                        ->orWhere('jenis', 'like', '%' . $search . '%')
                        ->orWhere('kode_barcode', 'like', '%' . $search . '%');
                });
            })
            ->when($status === 'aktif', fn ($q) => $q->where('aktif', true))
            ->when($status === 'nonaktif', fn ($q) => $q->where('aktif', false))
            ->orderBy('plat_nomor')
            ->paginate(10)
            ->withQueryString();

        return view('admin.kendaraan.data-kendaraan', compact('kendaraan', 'search', 'status'));
    }

    public function create()
    {
        return view('admin.kendaraan.tambah-kendaraan');
    }

    public function store(Request $request)
    {
        $this->rapikanPlat($request);

        $validated = $request->validate($this->aturan(), $this->pesan());

        $cabangId = Auth::user()->cabang_id;

        $kendaraan = Kendaraan::create([
            'cabang_id' => $cabangId,
            'plat_nomor' => $validated['plat_nomor'],
            'jenis' => $validated['jenis'],
            'kode_barcode' => $this->buatKode($cabangId),
            'aktif' => $request->boolean('aktif'),
        ]);

        $this->buatQrCode($kendaraan);

        return redirect()->route('kendaraan.index')
            ->with('success', 'Kendaraan berhasil ditambahkan.');
    }

    public function edit(Kendaraan $kendaraan)
    {
        $this->pastikanMilikCabang($kendaraan);

        return view('admin.kendaraan.ubah-kendaraan', compact('kendaraan'));
    }

    public function update(Request $request, Kendaraan $kendaraan)
    {
        $this->pastikanMilikCabang($kendaraan);
        $this->rapikanPlat($request);

        $validated = $request->validate($this->aturan($kendaraan), $this->pesan());

        $kendaraan->update([
            'plat_nomor' => $validated['plat_nomor'],
            'jenis' => $validated['jenis'],
            'aktif' => $request->boolean('aktif'),
        ]);

        // Isi QR hanya berisi kode_barcode, jadi plat berubah tidak membuat QR lama tidak valid.
        $this->pastikanQrAda($kendaraan);

        return redirect()->route('kendaraan.index')
            ->with('success', 'Kendaraan berhasil diperbarui.');
    }

    public function destroy(Kendaraan $kendaraan)
    {
        $this->pastikanMilikCabang($kendaraan);

        if ($kendaraan->cekKendaraan()->exists() || $kendaraan->absenKendaraan()->exists()) {
            return redirect()->route('kendaraan.index')
                ->with('error', 'Kendaraan sudah punya riwayat cek/absen, nonaktifkan saja agar riwayat tidak hilang.');
        }

        if ($kendaraan->qr_code && Storage::disk('public')->exists($kendaraan->qr_code)) {
            Storage::disk('public')->delete($kendaraan->qr_code);
        }

        $kendaraan->delete();

        return redirect()->route('kendaraan.index')
            ->with('success', 'Kendaraan berhasil dihapus.');
    }

    /** Unduh satu QR (PNG siap cetak: plat di atas, QR, lalu kode). */
    public function downloadQrCode(Kendaraan $kendaraan)
    {
        $this->pastikanMilikCabang($kendaraan);

        $manager = new ImageManager(new \Intervention\Image\Drivers\Gd\Driver());

        // QR dibuat langsung berukuran 500px (bukan memperbesar gambar 300px) supaya tetap tajam saat discan.
        $qrImage = $manager->read(QrPng::make($kendaraan->url_scan, 500, 2));

        $width = $qrImage->width();
        $headerHeight = 80;
        $footerHeight = 50;

        $canvas = $manager->create($width, $qrImage->height() + $headerHeight + $footerHeight)->fill('#ffffff');
        $canvas->place($qrImage, 'top-left', 0, $headerHeight);

        $fontPath = public_path('fonts/OpenSans.ttf');

        if (file_exists($fontPath)) {
            $tulis = function (string $teks, int $y, int $ukuran) use ($canvas, $fontPath, $width) {
                $canvas->text($teks, $width / 2, $y, function ($font) use ($fontPath, $ukuran) {
                    $font->filename($fontPath);
                    $font->size($ukuran);
                    $font->color('#000000');
                    $font->align('center');
                    $font->valign('top');
                });
            };

            $tulis($kendaraan->plat_nomor, 25, 28);
            $tulis($kendaraan->kode_barcode, $headerHeight + $qrImage->height() + 8, 18);
        }

        return response($canvas->toPng()->toString())
            ->header('Content-Type', 'image/png')
            ->header('Content-Disposition', 'attachment; filename="qr_kendaraan_' . Str::slug($kendaraan->plat_nomor) . '.png"');
    }

    /** Halaman lembar cetak: semua kendaraan aktif cabang dalam satu halaman, dicetak lewat browser. */
    public function cetakQr(Request $request)
    {
        $kendaraan = Kendaraan::query()
            ->diCabang(Auth::user()->cabang_id)
            ->aktif()
            ->when($request->filled('id'), fn ($q) => $q->whereIn('id', (array) $request->input('id')))
            ->orderBy('plat_nomor')
            ->get();

        foreach ($kendaraan as $item) {
            $this->pastikanQrAda($item);
        }

        return view('admin.kendaraan.cetak-qr', compact('kendaraan'));
    }

    private function aturan(?Kendaraan $kendaraan = null): array
    {
        return [
            'plat_nomor' => [
                'required',
                'string',
                'max:15',
                'regex:/^[A-Z]{1,2} \d{1,4}( [A-Z]{1,3})?$/',
                Rule::unique('kendaraan', 'plat_nomor')->ignore($kendaraan?->id),
            ],
            'jenis' => ['required', 'string', 'max:40'],
            'aktif' => ['nullable', 'boolean'],
        ];
    }

    private function pesan(): array
    {
        return [
            'plat_nomor.regex' => 'Format plat tidak valid. Contoh: R 1234 AB.',
            'plat_nomor.unique' => 'Plat nomor sudah terdaftar.',
        ];
    }

    private function pastikanMilikCabang(Kendaraan $kendaraan): void
    {
        abort_if((int) $kendaraan->cabang_id !== (int) Auth::user()->cabang_id, 403);
    }

    /**
     * Seragamkan plat jadi huruf besar dengan spasi tunggal antar bagian,
     * sehingga "r1234ab", "R  1234 ab" dan "R 1234 AB" dianggap plat yang sama.
     */
    private function rapikanPlat(Request $request): void
    {
        $plat = strtoupper(trim((string) $request->input('plat_nomor')));

        if (preg_match('/^([A-Z]{1,2})\s*(\d{1,4})\s*([A-Z]{0,3})$/', $plat, $m)) {
            $plat = trim($m[1] . ' ' . $m[2] . ' ' . $m[3]);
        } else {
            $plat = preg_replace('/\s+/', ' ', $plat);
        }

        $request->merge(['plat_nomor' => $plat]);
    }

    private function buatKode(int $cabangId): string
    {
        do {
            $kode = 'KDR-' . $cabangId . '-' . strtoupper(Str::random(8));
        } while (Kendaraan::where('kode_barcode', $kode)->exists());

        return $kode;
    }

    private function pastikanQrAda(Kendaraan $kendaraan): void
    {
        if (!$kendaraan->qr_code || !Storage::disk('public')->exists($kendaraan->qr_code)) {
            $this->buatQrCode($kendaraan);
        }
    }

    private function buatQrCode(Kendaraan $kendaraan): void
    {
        $filePath = "qr_codes/kendaraan_{$kendaraan->id}.png";

        Storage::disk('public')->put($filePath, QrPng::make($kendaraan->url_scan, 300, 2));

        $kendaraan->update(['qr_code' => $filePath]);
    }
}
