<?php

namespace App\Http\Controllers;

use App\Models\JadwalSopir;
use App\Models\PergantianSopir;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class JadwalSopirController extends Controller
{
    private const SHIFT = ['pagi', 'sore', 'malam'];

    public function index(Request $request)
    {
        $currentMonth = $this->bulanDariRequest($request->input('month'));
        $cabangId = Auth::user()->cabang_id;

        $jadwal = $this->jadwalBulan($currentMonth, $cabangId);
        $jadwalPerTanggal = $this->kelompokkan($jadwal);

        $sopir = $this->daftarSopir($cabangId);
        $sopirJs = $sopir->map(fn ($s) => ['id' => $s->id, 'nama' => $s->nama])->values();

        $rekap = $sopir->map(function ($s) use ($jadwal) {
            $milik = $jadwal->where('user_id', $s->id);

            return [
                'nama' => $s->nama,
                'pagi' => $milik->where('shift', 'pagi')->count(),
                'sore' => $milik->where('shift', 'sore')->count(),
                'malam' => $milik->where('shift', 'malam')->count(),
                'total' => $milik->count(),
            ];
        })->values();

        $histori = PergantianSopir::with(['petugasLama:id,nama', 'petugasBaru:id,nama'])
            ->whereYear('tanggal', $currentMonth->year)
            ->whereMonth('tanggal', $currentMonth->month)
            ->whereHas('petugasBaru', function ($q) use ($cabangId) {
                $q->where('cabang_id', $cabangId);
            })
            ->orderByDesc('waktu_pergantian')
            ->get();

        $bisaGenerate = $currentMonth->format('Y-m') >= Carbon::now()->format('Y-m');
        $mulaiGenerate = $currentMonth->format('Y-m') === Carbon::now()->format('Y-m')
            ? Carbon::today()
            : $currentMonth->copy()->startOfMonth();
        $adaJadwal = $jadwal->isNotEmpty();

        return view('admin.jadwal-sopir.index', compact(
            'currentMonth',
            'jadwalPerTanggal',
            'sopir',
            'sopirJs',
            'rekap',
            'histori',
            'bisaGenerate',
            'mulaiGenerate',
            'adaJadwal'
        ));
    }

    public function generate(Request $request)
    {
        $data = $request->validate([
            'month' => ['required', 'date_format:Y-m'],
            'jumlah_per_shift' => ['required', 'integer', 'min:1', 'max:100'],
            'shifts' => ['required', 'array', 'min:1'],
            'shifts.*' => ['in:' . implode(',', self::SHIFT)],
        ], [
            'jumlah_per_shift.required' => 'Jumlah sopir per shift wajib diisi.',
            'jumlah_per_shift.min' => 'Jumlah sopir per shift minimal 1.',
            'shifts.required' => 'Pilih minimal satu shift.',
            'shifts.min' => 'Pilih minimal satu shift.',
        ]);

        $cabangId = Auth::user()->cabang_id;
        $bulan = Carbon::parse($data['month'] . '-01');

        if ($bulan->format('Y-m') < Carbon::now()->format('Y-m')) {
            return back()->with('error', 'Tidak dapat generate jadwal untuk bulan yang sudah lewat.');
        }

        $sopir = $this->daftarSopir($cabangId);

        if ($sopir->isEmpty()) {
            return back()->with('error', 'Belum ada pengguna dengan hak akses Sopir di cabang ini.');
        }

        $jumlah = (int) $data['jumlah_per_shift'];

        if ($jumlah > $sopir->count()) {
            return back()->withInput()->with('error', 'Jumlah sopir per shift (' . $jumlah . ') melebihi jumlah sopir yang tersedia (' . $sopir->count() . ').');
        }

        $shifts = array_values(array_intersect(self::SHIFT, $data['shifts']));

        $mulai = $bulan->format('Y-m') === Carbon::now()->format('Y-m')
            ? Carbon::today()
            : $bulan->copy()->startOfMonth();
        $akhir = $bulan->copy()->endOfMonth()->startOfDay();

        $hitung = $sopir->pluck('id')->mapWithKeys(fn ($id) => [$id => 0])->all();
        $baris = [];
        $waktu = now();

        for ($tgl = $mulai->copy()->startOfDay(); $tgl->lte($akhir); $tgl->addDay()) {
            $aktif = $sopir
                ->filter(fn ($s) => $s->created_at === null || $s->created_at->copy()->startOfDay()->lte($tgl))
                ->pluck('id')
                ->all();

            if (empty($aktif)) {
                continue;
            }

            $sudahHariIni = [];

            foreach ($shifts as $shift) {
                $kandidat = $aktif;

                usort($kandidat, function ($a, $b) use ($sudahHariIni, $hitung) {
                    return [in_array($a, $sudahHariIni), $hitung[$a], $a]
                        <=> [in_array($b, $sudahHariIni), $hitung[$b], $b];
                });

                foreach (array_slice($kandidat, 0, $jumlah) as $userId) {
                    $baris[] = [
                        'user_id' => $userId,
                        'tanggal' => $tgl->toDateString(),
                        'shift' => $shift,
                        'created_at' => $waktu,
                        'updated_at' => $waktu,
                    ];
                    $hitung[$userId]++;
                    $sudahHariIni[] = $userId;
                }
            }
        }

        DB::transaction(function () use ($cabangId, $mulai, $akhir, $baris) {
            JadwalSopir::diCabang($cabangId)
                ->whereBetween('tanggal', [$mulai->toDateString(), $akhir->toDateString()])
                ->delete();

            foreach (array_chunk($baris, 500) as $potongan) {
                JadwalSopir::insert($potongan);
            }
        });

        return redirect()->route('jadwal-sopir.index', ['month' => $bulan->format('Y-m')])
            ->with('success', 'Jadwal sopir berhasil digenerate, ' . $jumlah . ' sopir per shift.');
    }

    public function store(Request $request)
    {
        $cabangId = Auth::user()->cabang_id;

        $data = $request->validate([
            'tanggal' => ['required', 'date'],
            'shift' => ['required', 'in:' . implode(',', self::SHIFT)],
            'user_id' => ['required', 'array', 'min:1'],
            'user_id.*' => ['integer', $this->aturanSopir()],
        ], [
            'user_id.required' => 'Pilih minimal satu sopir.',
            'user_id.min' => 'Pilih minimal satu sopir.',
            'user_id.*.exists' => 'Sopir yang dipilih tidak valid.',
        ]);

        $tanggal = Carbon::parse($data['tanggal']);

        $sudahAda = JadwalSopir::whereDate('tanggal', $tanggal->toDateString())
            ->where('shift', $data['shift'])
            ->whereIn('user_id', $data['user_id'])
            ->pluck('user_id')
            ->all();

        $baru = array_values(array_diff($data['user_id'], $sudahAda));

        if (empty($baru)) {
            return back()->withInput()->with('error', 'Semua sopir yang dipilih sudah terjadwal pada shift ini.');
        }

        foreach ($baru as $userId) {
            JadwalSopir::create([
                'user_id' => (int) $userId,
                'tanggal' => $tanggal->toDateString(),
                'shift' => $data['shift'],
            ]);
        }

        $pesan = count($baru) . ' sopir berhasil ditambahkan ke jadwal.';

        if (count($sudahAda) > 0) {
            $pesan .= ' ' . count($sudahAda) . ' sopir dilewati karena sudah terjadwal di shift tersebut.';
        }

        return redirect()->route('jadwal-sopir.index', ['month' => $tanggal->format('Y-m')])
            ->with('success', $pesan);
    }

    public function update(Request $request, JadwalSopir $jadwalSopir)
    {
        $this->pastikanCabang($jadwalSopir);

        $data = $request->validate([
            'user_id' => ['required', 'integer', $this->aturanSopir()],
            'alasan_pergantian' => ['nullable', 'string', 'max:255'],
        ]);

        $tanggal = $jadwalSopir->tanggal;
        $bulan = $tanggal->format('Y-m');

        if ((int) $data['user_id'] === (int) $jadwalSopir->user_id) {
            return redirect()->route('jadwal-sopir.index', ['month' => $bulan])
                ->with('error', 'Sopir yang dipilih sama dengan sopir sebelumnya.');
        }

        $sudahAda = JadwalSopir::whereDate('tanggal', $tanggal->toDateString())
            ->where('shift', $jadwalSopir->shift)
            ->where('user_id', $data['user_id'])
            ->where('id', '!=', $jadwalSopir->id)
            ->exists();

        if ($sudahAda) {
            return redirect()->route('jadwal-sopir.index', ['month' => $bulan])
                ->with('error', 'Sopir tersebut sudah terjadwal pada shift ini.');
        }

        DB::transaction(function () use ($jadwalSopir, $data, $tanggal) {
            PergantianSopir::create([
                'jadwal_sopir_id' => $jadwalSopir->id,
                'tanggal' => $tanggal->toDateString(),
                'shift' => $jadwalSopir->shift,
                'petugas_lama_id' => $jadwalSopir->user_id,
                'petugas_baru_id' => $data['user_id'],
                'waktu_pergantian' => now(),
                'alasan' => $data['alasan_pergantian'] ?? 'Diganti oleh admin',
            ]);

            $jadwalSopir->update(['user_id' => $data['user_id']]);
        });

        return redirect()->route('jadwal-sopir.index', ['month' => $bulan])
            ->with('success', 'Sopir pada jadwal berhasil diganti.');
    }

    public function destroy(JadwalSopir $jadwalSopir)
    {
        $this->pastikanCabang($jadwalSopir);

        $bulan = $jadwalSopir->tanggal->format('Y-m');

        $jadwalSopir->delete();

        return redirect()->route('jadwal-sopir.index', ['month' => $bulan])
            ->with('success', 'Jadwal sopir berhasil dihapus.');
    }

    public function cetakJadwal(Request $request)
    {
        $currentMonth = $this->bulanDariRequest($request->input('month'));
        $cabangId = Auth::user()->cabang_id;

        $jadwalPerTanggal = $this->kelompokkan($this->jadwalBulan($currentMonth, $cabangId));
        $cabang = Auth::user()->cabang->nama_cabang ?? '-';

        return Pdf::loadView('admin.jadwal-sopir.cetak-jadwal', [
            'jadwalPerTanggal' => $jadwalPerTanggal,
            'currentMonth' => $currentMonth,
            'cabang' => $cabang,
        ])->setPaper('a4', 'landscape')
            ->download('jadwal-sopir-' . $currentMonth->format('Y-m') . '.pdf');
    }

    private function bulanDariRequest(?string $month): Carbon
    {
        if ($month && preg_match('/^\d{4}-\d{2}$/', $month)) {
            try {
                return Carbon::parse($month . '-01')->startOfDay();
            } catch (\Throwable $e) {
            }
        }

        return Carbon::now()->startOfMonth();
    }

    private function daftarSopir(int $cabangId)
    {
        return User::where('role', 'sopir')
            ->where('cabang_id', $cabangId)
            ->orderBy('nama')
            ->get(['id', 'nama', 'created_at']);
    }

    private function jadwalBulan(Carbon $bulan, int $cabangId)
    {
        return JadwalSopir::with('user:id,nama')
            ->diCabang($cabangId)
            ->whereBetween('tanggal', [
                $bulan->copy()->startOfMonth()->toDateString(),
                $bulan->copy()->endOfMonth()->toDateString(),
            ])
            ->get()
            ->sortBy(fn ($j) => $j->user->nama ?? '')
            ->values();
    }

    private function kelompokkan($jadwal): array
    {
        return $jadwal
            ->groupBy(fn ($j) => $j->tanggal->format('Y-m-d'))
            ->map(function ($hari) {
                return $hari->groupBy('shift')->map(function ($items) {
                    return $items->map(fn ($j) => [
                        'id' => $j->id,
                        'user_id' => $j->user_id,
                        'nama' => $j->user->nama ?? '-',
                    ])->values()->all();
                })->all();
            })
            ->all();
    }

    private function aturanSopir()
    {
        $cabangId = Auth::user()->cabang_id;

        return Rule::exists('users', 'id')->where(function ($q) use ($cabangId) {
            $q->where('role', 'sopir')->where('cabang_id', $cabangId);
        });
    }

    private function pastikanCabang(JadwalSopir $jadwalSopir): void
    {
        if ($jadwalSopir->user->cabang_id != Auth::user()->cabang_id) {
            abort(403, 'Tidak boleh mengubah jadwal dari cabang lain.');
        }
    }
}