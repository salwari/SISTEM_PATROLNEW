<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\JadwalKebersihan;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
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

        // Jika bulan ini belum memiliki jadwal sama sekali, generate otomatis untuk awal
        if ($jadwalRaw->isEmpty()) {
            $this->generateJadwalOtomatis($currentMonth, false, null, $cabangId);

            $jadwalRaw = JadwalKebersihan::with('user')
                ->whereYear('tanggal', $currentMonth->year)
                ->whereMonth('tanggal', $currentMonth->month)
                ->whereHas('user', function ($q) use ($cabangId) {
                    $q->where('cabang_id', $cabangId);
                })
                ->get();
        }

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

    private function generateJadwalOtomatis(Carbon $bulan, bool $overwrite = false, ?Carbon $overrideStartDate = null, ?int $cabangId = null)
    {
        $cabangId = $cabangId ?? Auth::user()->cabang_id;
        $startDate = $overrideStartDate ?? $bulan->copy()->startOfMonth();
        $endDate = $bulan->copy()->endOfMonth();

        $allUsers = User::where('role', 'ob')
            ->where('cabang_id', $cabangId)
            ->orderBy('id')
            ->get();

        if ($allUsers->isEmpty()) return;

        $totalShiftBulan = 0;
        foreach (Carbon::parse($startDate)->daysUntil($endDate) as $tanggal) {
            $totalShiftBulan += 2; // Shift pagi & sore
        }

        $jumlahPetugas = $allUsers->count();
        $maxTugasPerUser = ceil($totalShiftBulan / max($jumlahPetugas, 1));
        $jadwalCounter = $allUsers->pluck('id')->mapWithKeys(fn($id) => [$id => 0])->toArray();

        foreach (Carbon::parse($startDate)->daysUntil($endDate) as $tanggal) {
            $shifts = ['pagi', 'sore'];

            foreach ($shifts as $shift) {
                $activeUsers = $allUsers->filter(fn($u) => $u->created_at->lte($tanggal))->pluck('id');

                $userTerpilih = collect($jadwalCounter)
                    ->only($activeUsers)
                    ->filter(fn($count) => $count < $maxTugasPerUser)
                    ->sort()
                    ->keys()
                    ->first();

                if (!$userTerpilih) continue;

                if (
                    !$overwrite &&
                    JadwalKebersihan::whereDate('tanggal', $tanggal)->where('shift', $shift)
                        ->whereHas('user', fn($q) => $q->where('cabang_id', $cabangId))
                        ->exists()
                ) {
                    continue;
                }

                JadwalKebersihan::updateOrCreate(
                    ['tanggal' => $tanggal->format('Y-m-d'), 'shift' => $shift, 'user_id' => $userTerpilih],
                    ['user_id' => $userTerpilih]
                );

                $jadwalCounter[$userTerpilih]++;
            }
        }
    }

    public function generateUlang(Request $request)
    {
        $monthParam = $request->input('month');
        $currentMonth = $monthParam ? Carbon::parse($monthParam . '-01') : Carbon::now();
        $startDate = $currentMonth->copy()->startOfMonth();
        $cabangId = Auth::user()->cabang_id;

        if ($currentMonth->lt(Carbon::now()->startOfMonth())) {
            return back()->with('error', 'Tidak dapat generate ulang jadwal untuk bulan yang sudah lewat.');
        }

        // Hapus jadwal dari tanggal awal bulan ini lalu buat ulang
        JadwalKebersihan::whereDate('tanggal', '>=', $startDate->format('Y-m-d'))
            ->whereHas('user', fn($q) => $q->where('cabang_id', $cabangId))
            ->delete();

        $this->generateJadwalOtomatis($currentMonth, true, $startDate, $cabangId);

        return back()->with('success', 'Jadwal kebersihan berhasil digenerate ulang.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'tanggal' => 'required|date',
            'shift'   => 'required|in:pagi,sore,malam',
        ]);

        $tanggal = Carbon::parse($request->tanggal)->toDateString();

        $sudahAda = JadwalKebersihan::where('user_id', $request->user_id)
            ->whereDate('tanggal', $tanggal)
            ->where('shift', $request->shift)
            ->exists();

        if ($sudahAda) {
            return back()->withInput()->with('error', 'Petugas ini sudah terdaftar pada shift tersebut.');
        }

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

    public function cetakJadwal(Request $request)
    {
        $month = $request->get('month', now()->format('Y-m'));
        $currentMonth = Carbon::createFromFormat('Y-m', $month);

        $jadwalKebersihan = JadwalKebersihan::with('user')
            ->whereMonth('tanggal', $currentMonth->month)
            ->whereYear('tanggal', $currentMonth->year)
            ->whereHas('user', function ($q) {
                $q->where('cabang_id', Auth::user()->cabang_id);
            })
            ->orderBy('tanggal')
            ->get()
            ->groupBy('tanggal');

        $cabang = Auth::user()->cabang->nama_cabang ?? '-';

        return Pdf::loadView('admin.jadwal-kebersihan.cetak-jadwal', [
            'jadwalKebersihan' => $jadwalKebersihan,
            'currentMonth'     => $currentMonth,
            'cabang'           => $cabang,
        ])->setPaper('a4', 'landscape')
            ->download('jadwal-kebersihan-' . $currentMonth->format('Y-m') . '.pdf');
    }
}