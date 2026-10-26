<?php

namespace App\Http\Controllers;

use App\Models\KamarMandi;
use Illuminate\Http\Request;
use App\Support\QrPng;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Auth;

class LokasiKebersihanController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $lokasiKebersihan = KamarMandi::query()
            ->when(Auth::user()->role === 'admin', function ($query) {
                // Filter sesuai cabang admin yang login
                $query->where('cabang_id', Auth::user()->cabang_id);
            })
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nama_kamar_mandi', 'like', '%' . $search . '%')
                        ->orWhere('kode_kamar_mandi', 'like', '%' . $search . '%')
                        ->orWhere('lantai', 'like', '%' . $search . '%')
                        ->orWhere('deskripsi', 'like', '%' . $search . '%');
                });
            })
            ->orderBy('nama_kamar_mandi')
            ->paginate(10)
            ->withQueryString();

        return view('admin.lokasi-kebersihan.index', compact('lokasiKebersihan', 'search'));
    }

    /**
     * Generate QR Code untuk lokasi kebersihan (Kamar Mandi)
     */
    private function generateQrCode(KamarMandi $kamarMandi): void
    {
        $url = route('lokasi-kebersihan.create', ['kamar_mandi' => $kamarMandi->id]);

        $qrCodeImage = QrPng::make($url, 300, 2);

        $filePath = "qr_codes/kamar_mandi_{$kamarMandi->id}.png";
        Storage::disk('public')->put($filePath, $qrCodeImage);

        $kamarMandi->update([
            'qr_code' => $filePath,
        ]);
    }

    public function create()
    {
        return view('admin.lokasi-kebersihan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_kamar_mandi' => 'required|string|max:20|unique:kamar_mandi,kode_kamar_mandi',
            'nama_kamar_mandi' => 'required|string|max:50',
            'lantai'           => 'required|string|max:20',
            'deskripsi'        => 'nullable|string',
        ]);

        $validated['cabang_id'] = Auth::user()->cabang_id;

        $kamarMandi = KamarMandi::create($validated);

        $this->generateQrCode($kamarMandi);

        return redirect()->route('lokasi-kebersihan.index')
            ->with('success', 'Lokasi Kebersihan berhasil ditambahkan.');
    }

    public function edit(KamarMandi $lokasiKebersihan)
    {
        return view('admin.lokasi-kebersihan.edit', compact('lokasiKebersihan'));
    }

    public function update(Request $request, KamarMandi $lokasiKebersihan)
    {
        $validated = $request->validate([
            'kode_kamar_mandi' => 'required|string|max:20|unique:kamar_mandi,kode_kamar_mandi,' . $lokasiKebersihan->id,
            'nama_kamar_mandi' => 'required|string|max:50',
            'lantai'           => 'required|string|max:20',
            'deskripsi'        => 'nullable|string',
        ]);

        $validated['cabang_id'] = Auth::user()->cabang_id;

        $dataLama = $lokasiKebersihan->only(['kode_kamar_mandi', 'nama_kamar_mandi', 'lantai', 'deskripsi']);

        $lokasiKebersihan->update($validated);

        // Regenerate QR jika ada perubahan data
        if ($dataLama != $validated) {
            if ($lokasiKebersihan->qr_code && Storage::disk('public')->exists($lokasiKebersihan->qr_code)) {
                Storage::disk('public')->delete($lokasiKebersihan->qr_code);
            }
            $this->generateQrCode($lokasiKebersihan);
        }

        return redirect()->route('lokasi-kebersihan.index')
            ->with('success', 'Lokasi Kebersihan berhasil diperbarui.');
    }

    public function destroy(KamarMandi $lokasiKebersihan)
    {
        if ($lokasiKebersihan->qr_code && Storage::disk('public')->exists($lokasiKebersihan->qr_code)) {
            Storage::disk('public')->delete($lokasiKebersihan->qr_code);
        }

        $lokasiKebersihan->delete();

        return redirect()->route('lokasi-kebersihan.index')
            ->with('success', 'Lokasi Kebersihan berhasil dihapus.');
    }

    public function downloadQrCode(KamarMandi $lokasiKebersihan)
    {
        if (!$lokasiKebersihan->qr_code || !Storage::disk('public')->exists($lokasiKebersihan->qr_code)) {
            return redirect()->back()->with('error', 'QR Code tidak ditemukan.');
        }

        $qrPath = storage_path('app/public/' . $lokasiKebersihan->qr_code);

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
            $canvas->text($lokasiKebersihan->nama_kamar_mandi, $width / 2, 30, function ($font) use ($fontPath) {
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
            ->header('Content-Disposition', 'attachment; filename="qr_kamar_mandi_' . $lokasiKebersihan->id . '.png"');
    }
}