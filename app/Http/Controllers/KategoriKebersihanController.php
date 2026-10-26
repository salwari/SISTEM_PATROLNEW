<?php

namespace App\Http\Controllers;

use App\Models\ChecklistKamarMandi;
use Illuminate\Http\Request;

class KategoriKebersihanController extends Controller
{
    public function index(Request $request)
    {
        $query = ChecklistKamarMandi::query();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_butir', 'like', '%' . $request->search . '%')
                  ->orWhere('kategori', 'like', '%' . $request->search . '%');
            });
        }

        $kategoriList = $query->orderBy('kategori')->orderBy('id')->paginate(10)->withQueryString();

        return view('admin.kategori-kebersihan.index', compact('kategoriList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kategori' => 'required|in:kebersihan,kelengkapan',
            'nama_butir' => 'required|string|max:100',
        ]);

        ChecklistKamarMandi::create($validated);

        return back()->with('success', 'Kategori dan pengecekan kebersihan berhasil ditambahkan.');
    }

    public function update(Request $request, ChecklistKamarMandi $checklistKamarMandi)
    {
        $validated = $request->validate([
            'kategori' => 'required|in:kebersihan,kelengkapan',
            'nama_butir' => 'required|string|max:100',
        ]);

        $checklistKamarMandi->update($validated);

        return back()->with('success', 'Kategori dan pengecekan kebersihan berhasil diperbarui.');
    }
    
    public function destroy(ChecklistKamarMandi $checklistKamarMandi)
    {
        $checklistKamarMandi->delete();

        return back()->with('success', 'Kategori dan pengecekan kebersihan berhasil dihapus.');
    }
}