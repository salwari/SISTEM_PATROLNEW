<?php

namespace App\Http\Controllers;

use App\Models\ChecklistKendaraan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ChecklistKendaraanController extends Controller
{
    public function index()
    {
        $butir = ChecklistKendaraan::where('cabang_id', Auth::user()->cabang_id)
            ->orderBy('urutan')
            ->orderBy('id')
            ->get()
            ->groupBy('kategori');

        $daftarKategori = ChecklistKendaraan::KATEGORI;

        return view('admin.checklist-kendaraan.daftar-butir', compact('butir', 'daftarKategori'));
    }

    public function create(Request $request)
    {
        $daftarKategori = ChecklistKendaraan::KATEGORI;
        $kategoriDipilih = $request->query('kategori');

        return view('admin.checklist-kendaraan.tambah-butir', compact('daftarKategori', 'kategoriDipilih'));
    }

    public function store(Request $request)
    {
        $cabangId = Auth::user()->cabang_id;

        $validated = $request->validate([
            'kategori' => ['required', Rule::in(array_keys(ChecklistKendaraan::KATEGORI))],
            'nama_butir' => [
                'required',
                'string',
                'max:100',
                Rule::unique('checklist_kendaraan', 'nama_butir')->where(function ($q) use ($cabangId, $request) {
                    return $q->where('cabang_id', $cabangId)->where('kategori', $request->input('kategori'));
                }),
            ],
            'urutan' => ['nullable', 'integer', 'min:0', 'max:65535'],
            'aktif' => ['nullable', 'boolean'],
        ]);

        $urutan = $validated['urutan'] ?? ((int) ChecklistKendaraan::where('cabang_id', $cabangId)->max('urutan') + 1);

        ChecklistKendaraan::create([
            'cabang_id' => $cabangId,
            'kategori' => $validated['kategori'],
            'nama_butir' => $validated['nama_butir'],
            'urutan' => $urutan,
            'aktif' => $request->boolean('aktif'),
        ]);

        return redirect()->route('checklist-kendaraan.index')
            ->with('success', 'Butir checklist berhasil ditambahkan.');
    }

    public function edit(ChecklistKendaraan $checklistKendaraan)
    {
        $this->pastikanMilikCabang($checklistKendaraan);

        $daftarKategori = ChecklistKendaraan::KATEGORI;

        return view('admin.checklist-kendaraan.ubah-butir', compact('checklistKendaraan', 'daftarKategori'));
    }

    public function update(Request $request, ChecklistKendaraan $checklistKendaraan)
    {
        $this->pastikanMilikCabang($checklistKendaraan);

        $cabangId = Auth::user()->cabang_id;

        $validated = $request->validate([
            'kategori' => ['required', Rule::in(array_keys(ChecklistKendaraan::KATEGORI))],
            'nama_butir' => [
                'required',
                'string',
                'max:100',
                Rule::unique('checklist_kendaraan', 'nama_butir')
                    ->where(function ($q) use ($cabangId, $request) {
                        return $q->where('cabang_id', $cabangId)->where('kategori', $request->input('kategori'));
                    })
                    ->ignore($checklistKendaraan->id),
            ],
            'urutan' => ['required', 'integer', 'min:0', 'max:65535'],
            'aktif' => ['nullable', 'boolean'],
        ]);

        $checklistKendaraan->update([
            'kategori' => $validated['kategori'],
            'nama_butir' => $validated['nama_butir'],
            'urutan' => $validated['urutan'],
            'aktif' => $request->boolean('aktif'),
        ]);

        return redirect()->route('checklist-kendaraan.index')
            ->with('success', 'Butir checklist berhasil diperbarui.');
    }

    public function destroy(ChecklistKendaraan $checklistKendaraan)
    {
        $this->pastikanMilikCabang($checklistKendaraan);

        $checklistKendaraan->delete();

        return redirect()->route('checklist-kendaraan.index')
            ->with('success', 'Butir checklist berhasil dihapus.');
    }

    private function pastikanMilikCabang(ChecklistKendaraan $butir): void
    {
        abort_if($butir->cabang_id != Auth::user()->cabang_id, 403);
    }
}