<?php

namespace App\Http\Controllers;

use App\Models\PemeriksaanKamarMandi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RiwayatKebersihanController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->input('status_kondisi');
        $search = $request->input('search');

        $riwayat = PemeriksaanKamarMandi::with(['user', 'kamarMandi'])
            ->when(Auth::user()->role === 'admin', function ($query) {
                // Filter berdasarkan cabang jika admin cabang
                $query->where('cabang_id', Auth::user()->cabang_id);
            })
            ->when($status, function ($query, $status) {
                $query->where('status_kondisi', $status);
            })
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('catatan', 'like', '%' . $search . '%')
                      ->orWhereHas('user', function ($u) use ($search) {
                          $u->where('nama', 'like', '%' . $search . '%');
                      })
                      ->orWhereHas('kamarMandi', function ($k) use ($search) {
                          $k->where('nama_kamar_mandi', 'like', '%' . $search . '%');
                      });
                });
            })
            ->orderBy('tanggal', 'desc')
            ->orderBy('waktu', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('admin.riwayat-kebersihan.index', compact('riwayat', 'status', 'search'));
    }
}