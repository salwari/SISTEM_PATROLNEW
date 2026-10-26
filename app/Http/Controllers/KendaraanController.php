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

        $kendaraan = Kendaraan::query()
            ->where('cabang_id', Auth::user()->cabang_id)
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('plat_nomor', 'like', '%' . $search . '%')
                        ->orWhere('jenis', 'like', '%' . $search . '%')
                        ->orWhere('kode_barcode', 'like', '%' . $search . '%');
                });
            })
            ->orderBy('plat_nomor')
            ->paginate(10)
            ->withQueryString();

        return view('admin.kendaraan.data-kendaraan', compact('kendaraan', 'search'));
    }

    public function create()
    {
        return view('admin.kendaraan.tambah-kendaraan');
    }

    public function store(Request $request)
    {
        $this->rapikanPlat($request);

        $validated = $request->validate([
            'plat_nomor' => ['required', 'string', 'max:15', 'unique:kendaraan,plat_nomor'],
            'jenis' => ['required', 'string', 'max:40'],
            'aktif' => ['nullable', 'boolean'],
        ]);

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

        $validated = $request->validate([
            'plat_nomor' => ['required', 'string', 'max:15', Rule::unique('kendaraan', 'plat_nomor')->ignore($kendaraan->id)],
            'jenis' => ['required', 'string', 'max:40'],
            'aktif' => ['nullable', 'boolean'],
        ]);

        $kendaraan->update([
            'plat_nomor' => $validated['plat_nomor'],
            'jenis' => $validated['jenis'],
            'aktif' => $request->boolean('aktif'),
        ]);

        if (!$kendaraan->qr_code || !Storage::disk('public')->exists($kendaraan->qr_code)) {
            $this->buatQrCode($kendaraan);
        }

        return redirect()->route('kendaraan.index')
            ->with('success', 'Kendaraan berhasil diperbarui.');
    }

    public function destroy(Kendaraan $kendaraan)
    {
        $this->pastikanMilikCabang($kendaraan);

        if ($kendaraan->cekKendaraan()->exists()) {
            return redirect()->route('kendaraan.index')
                ->with('error', 'Kendaraan sudah punya riwayat cek, nonaktifkan saja agar riwayat tidak hilang.');
        }

        if ($kendaraan->qr_code && Storage::disk('public')->exists($kendaraan->qr_code)) {
            Storage::disk('public')->delete($kendaraan->qr_code);
        }

        $kendaraan->delete();

        return redirect()->route('kendaraan.index')
            ->with('success', 'Kendaraan berhasil dihapus.');
    }

    public function downloadQrCode(Kendaraan $kendaraan)
    {
        $this->pastikanMilikCabang($kendaraan);

        if (!$kendaraan->qr_code || !Storage::disk('public')->exists($kendaraan->qr_code)) {
            $this->buatQrCode($kendaraan);
        }

        $qrPath = storage_path('app/public/' . $kendaraan->qr_code);

        $manager = new ImageManager(new \Intervention\Image\Drivers\Gd\Driver());
        $qrImage = $manager->read($qrPath);
        $qrImage->resize(500, 500);

        $width = $qrImage->width();
        $height = $qrImage->height();
        $extraHeight = 80;
        $canvasHeight = $height + $extraHeight;

        $canvas = $manager->create($width, $canvasHeight)->fill('#ffffff');

        $fontPath = public_path('fonts/OpenSans.ttf');

        if (file_exists($fontPath)) {
            $canvas->text($kendaraan->plat_nomor, $width / 2, 30, function ($font) use ($fontPath) {
                $font->filename($fontPath);
                $font->size(24);
                $font->color('#000000');
                $font->align('center');
                $font->valign('top');
            });
        }

        $canvas->place($qrImage, 'top-left', 0, $extraHeight);

        return response($canvas->toPng()->toString())
            ->header('Content-Type', 'image/png')
            ->header('Content-Disposition', 'attachment; filename="qr_kendaraan_' . Str::slug($kendaraan->plat_nomor) . '.png"');
    }

    private function pastikanMilikCabang(Kendaraan $kendaraan): void
    {
        abort_if($kendaraan->cabang_id != Auth::user()->cabang_id, 403);
    }

    private function rapikanPlat(Request $request): void
    {
        $plat = strtoupper(preg_replace('/\s+/', ' ', trim((string) $request->input('plat_nomor'))));

        $request->merge(['plat_nomor' => $plat]);
    }

    private function buatKode(int $cabangId): string
    {
        do {
            $kode = 'KDR-' . $cabangId . '-' . strtoupper(Str::random(8));
        } while (Kendaraan::where('kode_barcode', $kode)->exists());

        return $kode;
    }

    private function buatQrCode(Kendaraan $kendaraan): void
    {
        $url = url('/sopir/cek/create') . '?kode=' . $kendaraan->kode_barcode;

        $qrCodeImage = QrPng::make($url, 300, 2);
        $filePath = "qr_codes/kendaraan_{$kendaraan->id}.png";

        Storage::disk('public')->put($filePath, $qrCodeImage);

        $kendaraan->update([
            'qr_code' => $filePath,
        ]);
    }
}