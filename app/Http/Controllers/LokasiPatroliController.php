<?php

namespace App\Http\Controllers;

use App\Models\LokasiPatroli;
use Illuminate\Http\Request;
use App\Support\QrPng;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Auth;

class LokasiPatroliController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $lokasiPatroli = LokasiPatroli::query()
            ->when(Auth::user()->role === 'admin', function ($query) {
                $query->where('cabang_id', Auth::user()->cabang_id);
            })
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nama_lokasi', 'like', '%' . $search . '%')
                        ->orWhere('deskripsi', 'like', '%' . $search . '%');
                });
            })
            ->orderBy('nama_lokasi')
            ->paginate(10)
            ->withQueryString();

        return view('admin.lokasi-patroli.index', compact('lokasiPatroli', 'search'));
    }

    private function generateQrCode(LokasiPatroli $lokasi): void
    {
        $url = route('patroli.create', ['lokasi' => $lokasi->id]);

        $qrCodeImage = QrPng::make($url, 300, 2);

        $filePath = "qr_codes/lokasi_{$lokasi->id}.png";
        Storage::disk('public')->put($filePath, $qrCodeImage);

        $lokasi->update([
            'qr_code' => $filePath,
        ]);
    }

    public function create()
    {
        return view('admin.lokasi-patroli.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_lokasi' => 'required|string|max:40',
            'deskripsi'   => 'nullable|string|max:255',
        ]);

        $validated['cabang_id'] = Auth::user()->cabang_id;

        $lokasi = LokasiPatroli::create($validated);

        $this->generateQrCode($lokasi);

        return redirect()->route('lokasi-patroli.index')
            ->with('success', 'Lokasi Patroli berhasil ditambahkan.');
    }

    public function edit(LokasiPatroli $lokasiPatroli)
    {
        return view('admin.lokasi-patroli.edit', compact('lokasiPatroli'));
    }

    public function update(Request $request, LokasiPatroli $lokasiPatroli)
    {
        $validated = $request->validate([
            'nama_lokasi' => 'required|string|max:40',
            'deskripsi'   => 'nullable|string|max:255',
        ]);

        $validated['cabang_id'] = Auth::user()->cabang_id;

        $dataLama = $lokasiPatroli->only(['nama_lokasi', 'deskripsi']);

        $lokasiPatroli->update($validated);

        if ($dataLama != $validated) {
            if ($lokasiPatroli->qr_code && Storage::disk('public')->exists($lokasiPatroli->qr_code)) {
                Storage::disk('public')->delete($lokasiPatroli->qr_code);
            }
            $this->generateQrCode($lokasiPatroli);
        }

        return redirect()->route('lokasi-patroli.index')
            ->with('success', 'Lokasi Patroli berhasil diperbarui.');
    }

    public function destroy(LokasiPatroli $lokasiPatroli)
    {
        if ($lokasiPatroli->qr_code && Storage::disk('public')->exists($lokasiPatroli->qr_code)) {
            Storage::disk('public')->delete($lokasiPatroli->qr_code);
        }

        $lokasiPatroli->delete();

        return redirect()->route('lokasi-patroli.index')
            ->with('success', 'Lokasi Patroli berhasil dihapus.');
    }

     public function downloadQrCode(LokasiPatroli $lokasiPatroli)
    {
        if (!$lokasiPatroli->qr_code || !Storage::disk('public')->exists($lokasiPatroli->qr_code)) {
            return redirect()->back()->with('error', 'QR Code tidak ditemukan.');
        }

        $qrPath = storage_path('app/public/' . $lokasiPatroli->qr_code);

        $manager = new ImageManager(new \Intervention\Image\Drivers\Gd\Driver());
        $qrImage = $manager->read($qrPath);

        $qrImage->resize(500, 500);

        $width = $qrImage->width();
        $height = $qrImage->height();
        $extraHeight = 80; $canvasHeight = $height + $extraHeight;

        $canvas = $manager->create($width, $canvasHeight)->fill('#ffffff');

        $fontPath = public_path('fonts/OpenSans.ttf');

        if (file_exists($fontPath)) {
            $canvas->text($lokasiPatroli->nama_lokasi, $width / 2, 30, function ($font) use ($fontPath) {
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
            ->header('Content-Disposition', 'attachment; filename="qr_lokasi_' . $lokasiPatroli->id . '.png"');
    }
}