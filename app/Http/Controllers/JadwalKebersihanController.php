<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\JadwalKebersihan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JadwalKebersihanController extends Controller
{
    public function index(Request $request)
    {
        $monthParam = $request->input('month');
        $currentMonth = $monthParam ? Carbon::parse($monthParam . '-01') : Carbon::now();
        $cabangId = Auth::user()->cabang_id;

        $jadwalRaw = JadwalKebersihan::with('user')
            ->whereYear('tanggal', $currentMonth->year)
            ->whereMonth('tanggal', $currentMonth->month)
            ->whereHas('user', function ($q) use ($cabangId) {
                $q->where('cabang_id', $cabangId);
            })
            ->get();

        $jadwalKebersihan = $jadwalRaw->groupBy(fn($item) => Carbon::parse($item->tanggal)->format('Y-m-d'));

        $users = User::where('role', 'ob')
            ->where('cabang_id', $cabangId)
            ->orderBy('nama')
            ->get();

        $jumlahKebersihanPerUser = JadwalKebersihan::selectRaw('user_id, count(*) as total')
            ->whereYear('tanggal', $currentMonth->year)
            ->whereMonth('tanggal', $currentMonth->month)
            ->whereHas('user', function ($q) use ($cabangId) {
                $q->where('cabang_id', $cabangId);
            })
            ->groupBy('user_id')
            ->with('user')
            ->get();

        return view('admin.jadwal-kebersihan.index', compact(
            'jadwalKebersihan',
            'currentMonth',
            'users',
            'jumlahKebersihanPerUser'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'tanggal' => 'required|date',
            'shift'   => 'required|in:pagi,sore,malam',
        ]);

        $tanggal = Carbon::parse($request->tanggal)->toDateString();

        // Cek apakah petugas sudah ada di shift tersebut
        $sudahAda = JadwalKebersihan::where('user_id', $request->user_id)
            ->whereDate('tanggal', $tanggal)
            ->where('shift', $request->shift)
            ->exists();

        if ($sudahAda) {
            return back()->withInput()->with('error', 'Petugas ini sudah terdaftar pada shift tersebut.');
        }

        // Simpan (bisa lebih dari 3 orang per shift)
        JadwalKebersihan::create([
            'user_id' => $request->user_id,
            'tanggal' => $tanggal,
            'shift'   => $request->shift,
        ]);

        return redirect()->route('jadwal-kebersihan.index', ['month' => Carbon::parse($tanggal)->format('Y-m')])
            ->with('success', 'Jadwal kebersihan berhasil ditambahkan.');
    }

    public function update(Request $request, JadwalKebersihan $jadwalKebersihan)
    {
        if ($jadwalKebersihan->user->cabang_id !== Auth::user()->cabang_id) {
            abort(403, 'Akses tidak diizinkan.');
        }

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'shift'   => 'required|in:pagi,sore,malam',
        ]);

        // Cek agar tidak duplikat dengan jadwal lain
        $sudahAda = JadwalKebersihan::where('user_id', $request->user_id)
            ->whereDate('tanggal', $jadwalKebersihan->tanggal)
            ->where('shift', $request->shift)
            ->where('id', '!=', $jadwalKebersihan->id)
            ->exists();

        if ($sudahAda) {
            return back()->withInput()->with('error', 'Petugas ini sudah terdaftar pada shift tersebut.');
        }

        $jadwalKebersihan->update([
            'user_id' => $request->user_id,
            'shift'   => $request->shift,
        ]);

        $bulan = Carbon::parse($jadwalKebersihan->tanggal)->format('Y-m');
        return redirect()->route('jadwal-kebersihan.index', ['month' => $bulan])
            ->with('success', 'Jadwal kebersihan berhasil diperbarui.');
    }

    public function destroy(JadwalKebersihan $jadwalKebersihan)
    {
        if ($jadwalKebersihan->user->cabang_id !== Auth::user()->cabang_id) {
            abort(403, 'Akses tidak diizinkan.');
        }

        $bulan = Carbon::parse($jadwalKebersihan->tanggal)->format('Y-m');
        $jadwalKebersihan->delete();

        return redirect()->route('jadwal-kebersihan.index', ['month' => $bulan])
            ->with('success', 'Jadwal kebersihan berhasil dihapus.');
    }
}