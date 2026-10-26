<?php

namespace App\Http\Controllers;

use App\Models\KamarMandi;
use App\Models\ChecklistKamarMandi;
use App\Models\PemeriksaanKamarMandi;
use App\Models\DetailPemeriksaanKamarMandi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\ObController;

class ObController extends Controller
{
    /**
     * Menampilkan Dashboard OB dengan Progres Pemeriksaan Harian
     */
    public function dashboard()
    {
        $user = Auth::user();
        $cabangId = $user->cabang_id;
        $today = Carbon::today()->toDateString();

        $kamarMandiList = KamarMandi::where('cabang_id', $cabangId)
            ->orderBy('kode_kamar_mandi')
            ->get();

        $pemeriksaanHariIni = PemeriksaanKamarMandi::with(['user', 'detailPemeriksaan.checklist'])
            ->where('cabang_id', $cabangId)
            ->whereDate('tanggal', $today)
            ->get()
            ->keyBy('kamar_mandi_id');

        $totalKamarMandi     = $kamarMandiList->count();
        $jumlahSelesai       = $pemeriksaanHariIni->count();
        $jumlahBelum         = max(0, $totalKamarMandi - $jumlahSelesai);
        $persentaseSelesai   = $totalKamarMandi > 0 ? round(($jumlahSelesai / $totalKamarMandi) * 100) : 0;
        
        $jumlahPerluTindakan = $pemeriksaanHariIni->where('status_kondisi', 'perlu_tindakan')->count();
        $jumlahBaik          = $pemeriksaanHariIni->where('status_kondisi', 'baik')->count();

        return view('ob.dashboard', compact(
            'kamarMandiList',
            'pemeriksaanHariIni',
            'totalKamarMandi',
            'jumlahSelesai',
            'jumlahBelum',
            'persentaseSelesai',
            'jumlahPerluTindakan',
            'jumlahBaik',
            'today'
        ));
    }

    public function scanQr()
    {
        return view('ob.scan-qr');
    }

    public function periksa(KamarMandi $kamarMandi)
    {
        $user = Auth::user();

        if ($kamarMandi->cabang_id !== $user->cabang_id) {
            abort(403, 'Akses tidak diizinkan. Kamar mandi berada di cabang lain.');
        }

        $today = Carbon::today()->toDateString();
        $sudahDiperiksa = PemeriksaanKamarMandi::where('kamar_mandi_id', $kamarMandi->id)
            ->whereDate('tanggal', $today)
            ->first();

        if ($sudahDiperiksa) {
            return redirect()->route('ob.dashboard')
                ->with('error', "{$kamarMandi->nama_kamar_mandi} sudah diperiksa hari ini.");
        }

        $checklistKebersihan = ChecklistKamarMandi::where('is_active', true)
            ->where('kategori', 'kebersihan')
            ->get();

        $checklistKelengkapan = ChecklistKamarMandi::where('is_active', true)
            ->where('kategori', 'kelengkapan')
            ->get();

        return view('ob.form-checklist', compact(
            'kamarMandi',
            'checklistKebersihan',
            'checklistKelengkapan'
        ));
    }

    public function simpanPemeriksaan(Request $request, KamarMandi $kamarMandi)
    {
        $user = Auth::user();

        $rules = [
            'status_kondisi' => 'required|in:baik,perlu_tindakan',
            'checklist'      => 'required|array',
            'checklist.*'    => 'required|in:sesuai,tidak_sesuai',
        ];

        if ($request->status_kondisi === 'perlu_tindakan') {
            $rules['catatan'] = 'required|string|max:500';
            $rules['foto']    = 'required|string'; 
        } else {
            $rules['catatan'] = 'nullable|string|max:500';
            $rules['foto']    = 'nullable|string';
        }

        $messages = [
            'catatan.required' => 'Catatan temuan wajib diisi jika kondisi Perlu Tindakan.',
            'foto.required'    => 'Foto bukti wajib diambil jika kondisi Perlu Tindakan.',
        ];

        $validated = $request->validate($rules, $messages);

        $today = Carbon::today()->toDateString();
        $duplikat = PemeriksaanKamarMandi::where('kamar_mandi_id', $kamarMandi->id)
            ->whereDate('tanggal', $today)
            ->exists();

        if ($duplikat) {
            return redirect()->route('ob.dashboard')
                ->with('error', 'Kamar mandi ini sudah diperiksa hari ini.');
        }

        $fotoPath = null;
        if (!empty($validated['foto'])) {
            $base64Image = preg_replace('/^data:image\/\w+;base64,/', '', $validated['foto']);
            $imageData = base64_decode($base64Image);
            $filename = 'ob_' . time() . '_' . $kamarMandi->id . '.jpg';
            $fotoPath = 'pemeriksaan_ob/' . $filename;
            Storage::disk('public')->put($fotoPath, $imageData);
        }

        DB::transaction(function () use ($user, $kamarMandi, $validated, $fotoPath, $today) {
            $pemeriksaan = PemeriksaanKamarMandi::create([
                'user_id'        => $user->id,
                'kamar_mandi_id' => $kamarMandi->id,
                'cabang_id'      => $user->cabang_id,
                'tanggal'        => $today,
                'waktu'          => Carbon::now()->toTimeString(),
                'status_kondisi' => $validated['status_kondisi'],
                'catatan'        => $validated['catatan'] ?? null,
                'foto'           => $fotoPath,
            ]);

            foreach ($validated['checklist'] as $checklistId => $status) {
                DetailPemeriksaanKamarMandi::create([
                    'pemeriksaan_id' => $pemeriksaan->id,
                    'checklist_id'   => $checklistId,
                    'status'         => $status,
                ]);
            }
        });

        return redirect()->route('ob.dashboard')
            ->with('success', "Pemeriksaan {$kamarMandi->nama_kamar_mandi} berhasil disimpan!");
    }
    
    public function riwayat(Request $request)
    {
        $user = Auth::user();
        $search = $request->input('search');
        $status = $request->input('status');
        $tanggalMulai = $request->input('tanggal_mulai');
        $tanggalSelesai = $request->input('tanggal_selesai');

        $riwayat = PemeriksaanKamarMandi::with(['kamarMandi', 'detailPemeriksaan.checklist'])
            ->where('user_id', $user->id)
            ->when($status, function ($query, $status) {
                $query->where('status_kondisi', $status);
            })
            ->when($tanggalMulai && $tanggalSelesai, function ($query) use ($tanggalMulai, $tanggalSelesai) {
                $query->whereBetween('tanggal', [$tanggalMulai, $tanggalSelesai]);
            })
            ->when($search, function ($query, $search) {
                $query->whereHas('kamarMandi', function ($q) use ($search) {
                    $q->where('nama_kamar_mandi', 'like', "%{$search}%")
                      ->orWhere('kode_kamar_mandi', 'like', "%{$search}%");
                });
            })
            ->orderByDesc('tanggal')
            ->orderByDesc('waktu')
            ->paginate(10)
            ->withQueryString();

        return view('ob.riwayat-cek', compact('riwayat', 'search', 'status', 'tanggalMulai', 'tanggalSelesai'));
    }
}