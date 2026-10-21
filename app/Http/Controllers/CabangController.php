<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CabangController extends Controller
{
    public function index(Request $request)
    {
        $query = Cabang::with('admin');

        if ($request->has('search')) {
            $query->where('nama_cabang', 'like', '%' . $request->search . '%');
        }

        $cabangs = $query->orderBy('nama_cabang')->paginate(5);

        return view('super-admin.data-cabang.index', compact('cabangs'));
    }

    public function create()
    {
        return view('super-admin.data-cabang.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_cabang' => 'required|string|regex:/^[A-Za-z\s]+$/|max:30|unique:cabang,nama_cabang',
            'alamat' => 'required|string',
            'email' => 'required|email|regex:/@gmail\.com$/|unique:users,email',
            'nomor_induk' => 'required|string|regex:/^\d+$/|max:12|unique:users,nomor_induk',
            'no_telepon' => 'required|string|regex:/^\d+$/|digits_between:6,15|unique:users,no_telepon',
            'password' => 'required|string|min:8|max:255',
        ], [
            'nama._cabang.regex' => 'Nama cabang hanya boleh berisi huruf dan spasi.',
            'nama_cabang.unique' => 'Nama cabang sudah terdaftar.',
            'email.regex' => 'Email harus menggunakan domain @gmail.com.',
            'email.unique' => 'Email sudah digunakan.',
            'nomor_induk.regex' => 'Nomor induk harus berupa angka.',
            'nomor_induk.unique' => 'Nomor induk sudah digunakan.',
            'no_telepon.regex' => 'Nomor telepon harus berupa angka.',
            'no_telepon.unique' => 'Nomor telepon sudah digunakan.',
        ]);

        // Simpan cabang
        $cabang = Cabang::create([
            'nama_cabang' => $validated['nama_cabang'],
            'alamat_cabang' => $validated['alamat'],
        ]);

        // Pastikan tidak ada admin ganda di cabang ini
        if (User::where('cabang_id', $cabang->id)->where('role', 'admin')->exists()) {
            return back()->withErrors([
                'email' => 'Cabang ini sudah memiliki admin.'
            ])->withInput();
        }

        // Buat admin cabang otomatis
        User::create([
            'nama' => "Admin " . $validated['nama_cabang'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'admin',
            'nomor_induk' => $validated['nomor_induk'],
            'no_telepon' => $validated['no_telepon'],
            'alamat' => $validated['alamat'],
            'cabang_id' => $cabang->id,
            'foto' => 'profile.png',
        ]);

        return redirect()->route('data-cabang.index')
            ->with('success', 'Cabang dan Admin berhasil ditambahkan.');
    }

    public function edit(Cabang $cabang)
    {
        return view('super-admin.data-cabang.edit', compact('cabang'));
    }

    public function update(Request $request, Cabang $cabang)
    {
        $validated = $request->validate([
            'nama_cabang' => 'required|string|regex:/^[A-Za-z\s]+$/|max:30|unique:cabang,nama_cabang,' . $cabang->id,
            'alamat_cabang' => 'required|string',
            'email' => 'required|email|regex:/@gmail\.com$/|unique:users,email,' . $cabang->admin->id,
            'nomor_induk' => 'required|string|regex:/^\d+$/|max:12|unique:users,nomor_induk,' . $cabang->admin->id,
            'no_telepon' => 'required|string|regex:/^\d+$/|digits_between:6,15|unique:users,no_telepon,' . $cabang->admin->id,
        ], [
            'nama_cabang.regex' => 'Nama cabang hanya boleh berisi huruf dan spasi.',
            'nama_cabang.unique' => 'Nama cabang sudah terdaftar.',
            'email.regex' => 'Email harus menggunakan domain @gmail.com.',
            'email.unique' => 'Email sudah digunakan.',
            'nomor_induk.regex' => 'Nomor induk harus berupa angka.',
            'nomor_induk.unique' => 'Nomor induk sudah digunakan.',
            'no_telepon.regex' => 'Nomor telepon harus berupa angka.',
            'no_telepon.unique' => 'Nomor telepon sudah digunakan.',
        ]);

        // Update data cabang
        $cabang->update([
            'nama_cabang' => $validated['nama_cabang'],
            'alamat_cabang' => $validated['alamat_cabang'],
        ]);

        // Update data admin cabang (relasi 1 cabang punya 1 admin)
        if ($cabang->admin) {
            $cabang->admin->update([
                'nama' => "Admin " . $validated['nama_cabang'],
                'email' => $validated['email'],
                'nomor_induk' => $validated['nomor_induk'],
                'no_telepon' => $validated['no_telepon'],
                'alamat' => $validated['alamat_cabang'],
            ]);
        }

        return redirect()->route('data-cabang.index')->with('success', 'Data cabang berhasil diperbarui.');
    }

    public function destroy(Cabang $cabang)
    {
        $cabang->delete();

        return redirect()->route('data-cabang.index')->with('success', 'Cabang berhasil dihapus.');
    }
}
