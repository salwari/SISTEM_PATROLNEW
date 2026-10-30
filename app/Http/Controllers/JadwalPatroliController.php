<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\JadwalPatroli;
use App\Models\PergantianPetugas;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class JadwalPatroliController extends Controller
{
    public function index(Request $request)
    {
        $monthParam = $request->input('month');
        $currentMonth = $monthParam ? Carbon::parse($monthParam . '-01') : Carbon::now();
        $cabangId = Auth::user()->cabang_id;

        $jadwalPatroliRaw = JadwalPatroli::with('user')
            ->whereYear('tanggal', $currentMonth->year)
            ->whereMonth('tanggal', $currentMonth->month)
            ->whereHas('user', function ($q) use ($cabangId) {
                $q->where('cabang_id', $cabangId);
            })
            ->get();

        if ($jadwalPatroliRaw->isEmpty()) {
            $this->generateJadwalOtomatis($currentMonth, false, null, $cabangId);

            $jadwalPatroliRaw = JadwalPatroli::with('user')
                ->whereYear('tanggal', $currentMonth->year)
                ->whereMonth('tanggal', $currentMonth->month)
                ->whereHas('user', function ($q) use ($cabangId) {
                    $q->where('cabang_id', $cabangId);
                })
                ->get();
        }

        $jadwalPatroli = $jadwalPatroliRaw->groupBy(fn($item) => Carbon::parse($item->tanggal)->format('Y-m-d'));

        $users = User::where('role', 'petugas_security')
            ->where('cabang_id', $cabangId)
            ->get();

        $jumlahPatroliPerUser = JadwalPatroli::selectRaw('user_id, count(*) as total')
            ->whereYear('tanggal', $currentMonth->year)
            ->whereMonth('tanggal', $currentMonth->month)
            ->whereHas('user', function ($q) use ($cabangId) {
                $q->where('cabang_id', $cabangId);
            })
            ->groupBy('user_id')
            ->with('user')
            ->get();

        $historiPergantian = PergantianPetugas::with(['jadwal', 'petugasLama', 'petugasBaru'])
            ->whereHas('jadwal.user', function ($q) use ($cabangId) {
                $q->where('cabang_id', $cabangId);
            })
            ->whereHas('jadwal', function ($q) use ($currentMonth) {
                $q->whereYear('tanggal', $currentMonth->year)
                    ->whereMonth('tanggal', $currentMonth->month);
            })
            ->orderByDesc('waktu_pergantian')
            ->get();

        return view('admin.jadwal-patroli.index', compact(
            'jadwalPatroli',
            'currentMonth',
            'users',
            'jumlahPatroliPerUser',
            'historiPergantian'
        ));
    }

    private function generateJadwalOtomatis(Carbon $bulan, bool $overwrite = false, ?Carbon $overrideStartDate = null, ?int $cabangId = null)
    {
        $cabangId = $cabangId ?? Auth::user()->cabang_id;

        $startDate = $overrideStartDate ?? $bulan->copy()->startOfMonth();
        $endDate = $bulan->copy()->endOfMonth();

        $allUsers = User::where('role', 'petugas_security')
            ->where('cabang_id', $cabangId)
            ->orderBy('id')
            ->get();

        if ($allUsers->isEmpty())
            return;

        $totalShiftBulan = 0;
        foreach (Carbon::parse($startDate)->daysUntil($endDate) as $tanggal) {
            $hari = $tanggal->dayOfWeek;
            $totalShiftBulan += 2;
        }

        $jumlahPetugas = $allUsers->count();
        $maxPatroliPerUser = ceil($totalShiftBulan / max($jumlahPetugas, 1));

        $jadwalCounter = $allUsers->pluck('id')->mapWithKeys(fn($id) => [$id => 0])->toArray();

        foreach (Carbon::parse($startDate)->daysUntil($endDate) as $tanggal) {
            $hari = $tanggal->dayOfWeek;
            $shifts = ['pagi', 'sore'];

            foreach ($shifts as $shift) {
                $activeUsers = $allUsers->filter(fn($u) => $u->created_at->lte($tanggal))->pluck('id');

                $userTerpilih = collect($jadwalCounter)
                    ->only($activeUsers)
                    ->filter(fn($count) => $count < $maxPatroliPerUser)
                    ->sort()
                    ->keys()
                    ->first();

                if (!$userTerpilih)
                    continue;

                if (
                    !$overwrite &&
                    JadwalPatroli::whereDate('tanggal', $tanggal)->where('shift', $shift)
                        ->whereHas('user', fn($q) => $q->where('cabang_id', $cabangId))
                        ->exists()
                ) {
                    continue;
                }

                JadwalPatroli::updateOrCreate(
                    ['tanggal' => $tanggal->format('Y-m-d'), 'shift' => $shift],
                    ['user_id' => $userTerpilih]
                );

                $jadwalCounter[$userTerpilih]++;
            }
        }
    }

    public function generateUlang(Request $request)
    {
        $monthParam = $request->input('month');
        $startDateInput = $request->input('start_date');
        $currentMonth = $monthParam ? Carbon::parse($monthParam . '-01') : Carbon::now();
        $startDate = $startDateInput ? Carbon::parse($startDateInput) : $currentMonth->copy()->startOfMonth();
        $cabangId = Auth::user()->cabang_id;

        if ($currentMonth->lt(Carbon::now()->startOfMonth())) {
            return back()->with('error', 'Tidak dapat generate ulang jadwal untuk bulan yang sudah lewat.');
        }

        $jadwalAda = JadwalPatroli::whereYear('tanggal', $currentMonth->year)
            ->whereMonth('tanggal', $currentMonth->month)
            ->whereHas('user', fn($q) => $q->where('cabang_id', $cabangId))
            ->exists();

        if (!$jadwalAda) {
            $this->generateJadwalOtomatis($currentMonth, false, $startDate, $cabangId);
            return back()->with('success', 'Jadwal patroli berhasil digenerate pertama kali untuk bulan ini.');
        }

        $sudahGenerateHariIni = JadwalPatroli::whereDate('created_at', Carbon::today())
            ->whereYear('tanggal', $currentMonth->year)
            ->whereMonth('tanggal', $currentMonth->month)
            ->whereHas('user', fn($q) => $q->where('cabang_id', $cabangId))
            ->exists();

        if ($sudahGenerateHariIni) {
            return back()->with('error', 'Jadwal bulan ini sudah digenerate ulang hari ini, tidak perlu generate lagi.');
        }

        JadwalPatroli::whereDate('tanggal', '>=', $startDate->format('Y-m-d'))
            ->whereHas('user', fn($q) => $q->where('cabang_id', $cabangId))
            ->delete();

        $this->generateJadwalOtomatis($currentMonth, true, $startDate, $cabangId);

        return back()->with('success', 'Jadwal patroli berhasil digenerate ulang.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'tanggal' => 'required|date',
            'shift' => 'required|in:pagi,sore,malam',
        ]);

        $tanggal = Carbon::parse($request->tanggal);

        $jumlahPetugas = JadwalPatroli::whereDate('tanggal', $tanggal)
            ->where('shift', $request->shift)
            ->whereHas('user', function ($q) {
                $q->where('cabang_id', Auth::user()->cabang_id);
            })
            ->count();

        if ($jumlahPetugas >= 3) {
            return back()->withInput()->with('error', 'Shift ini sudah terisi maksimal 3 petugas.');
        }

        JadwalPatroli::create([
            'user_id' => $request->user_id,
            'tanggal' => $tanggal,
            'shift' => $request->shift,
        ]);

        return redirect()->route('jadwal-patroli.index')
            ->with('success', 'Jadwal berhasil ditambahkan.');
    }

    public function update(Request $request, JadwalPatroli $jadwalPatroli)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'shift' => 'required|in:pagi,sore,malam',
            'alasan_pergantian' => 'nullable|string|max:255',
        ]);

        $tanggal = $jadwalPatroli->tanggal;

        $jumlahPetugas = JadwalPatroli::where('tanggal', $tanggal)
            ->where('shift', $request->shift)
            ->where('id', '!=', $jadwalPatroli->id)
            ->whereHas('user', function ($q) {
                $q->where('cabang_id', Auth::user()->cabang_id);
            })
            ->count();

        if ($jumlahPetugas >= 3) {
            return back()->withInput()->with('error', 'Shift ini sudah terisi maksimal 3 petugas.');
        }

        if ($jadwalPatroli->user_id != $request->user_id) {
            $bulanIni = Carbon::now();

            $jumlahPergantian = PergantianPetugas::where('petugas_lama_id', $jadwalPatroli->user_id)
                ->whereMonth('waktu_pergantian', $bulanIni->month)
                ->whereYear('waktu_pergantian', $bulanIni->year)
                ->count();

            if ($jumlahPergantian >= 3) {
                return back()->withInput()->with('error', 'Petugas ini sudah diganti 3 kali dalam bulan ini.');
            }

            PergantianPetugas::create([
                'jadwal_id' => $jadwalPatroli->id,
                'petugas_lama_id' => $jadwalPatroli->user_id,
                'petugas_baru_id' => $request->user_id,
                'waktu_pergantian' => now(),
                'alasan' => $request->alasan_pergantian ?? 'Diganti oleh admin',
            ]);
        }

        $jadwalPatroli->update([
            'user_id' => $request->user_id,
            'shift' => $request->shift,
        ]);

        return redirect()->route('jadwal-patroli.index')
            ->with('success', 'Jadwal berhasil diperbarui.');
    }

    public function destroy(JadwalPatroli $jadwalPatroli)
    {
        if ($jadwalPatroli->user->cabang_id !== Auth::user()->cabang_id) {
            abort(403, 'Tidak boleh menghapus jadwal dari cabang lain.');
        }

        $jadwalPatroli->delete();

        return redirect()->route('jadwal-patroli.index')
            ->with('success', 'Jadwal patroli berhasil dihapus.');
    }

    public function cetakJadwal(Request $request)
    {
        $month = $request->get('month', now()->format('Y-m'));
        $currentMonth = Carbon::createFromFormat('Y-m', $month);

        $jadwalPatroli = JadwalPatroli::with('user')
            ->whereMonth('tanggal', $currentMonth->month)
            ->whereYear('tanggal', $currentMonth->year)
            ->whereHas('user', function ($q) {
                $q->where('cabang_id', Auth::user()->cabang_id);
            })
            ->orderBy('tanggal')
            ->get()
            ->groupBy('tanggal');

        $cabang = Auth::user()->cabang->nama_cabang ?? '-';

        return Pdf::loadView('admin.jadwal-patroli.cetak-jadwal', [
            'jadwalPatroli' => $jadwalPatroli,
            'currentMonth' => $currentMonth,
            'cabang' => $cabang,
        ])->setPaper('a4', 'landscape')
            ->download('jadwal-patroli-' . $currentMonth->format('Y-m') . '.pdf');
    }
}
