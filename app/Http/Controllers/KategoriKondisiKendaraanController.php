<?php

namespace App\Http\Controllers;

use App\Models\KategoriKondisiKendaraan;
use Illuminate\Http\Request;

class KategoriKondisiKendaraanController extends Controller
{
    public function index(Request $request)
    {
        $query = KategoriKondisiKendaraan::query()->withCount('absenKendaraan');

        if ($request->filled('search')) {
            $query->where('nama_kategori', 'like', '%' . $request->search . '%');
        }

        $kategori = $query->orderBy('nama_kategori')->paginate(10)->withQueryString();

        return view('admin.kendaraan.kategori-kondisi', compact('kategori'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:100|unique:kategori_kondisi_kendaraan,nama_kategori',
        ]);

        KategoriKondisiKendaraan::create($validated);

        return back()->with('success', 'Kondisi kendaraan berhasil ditambahkan.');
    }

    public function update(Request $request, KategoriKondisiKendaraan $kategoriKondisiKendaraan)
    {
        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:100|unique:kategori_kondisi_kendaraan,nama_kategori,' . $kategoriKondisiKendaraan->id,
        ]);

        $kategoriKondisiKendaraan->update($validated);

        return back()->with('success', 'Kondisi kendaraan berhasil diperbarui.');
    }

    public function destroy(KategoriKondisiKendaraan $kategoriKondisiKendaraan)
    {
        $dipakai = $kategoriKondisiKendaraan->absenKendaraan()->count();

        if ($dipakai > 0) {
            return back()->with('error', "Kondisi \"{$kategoriKondisiKendaraan->nama_kategori}\" sudah dipakai di {$dipakai} laporan patroli, jadi tidak bisa dihapus.");
        }

        $kategoriKondisiKendaraan->delete();

        return back()->with('success', 'Kondisi kendaraan berhasil dihapus.');
    }
}