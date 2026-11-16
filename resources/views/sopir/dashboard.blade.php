<x-app-layout :title="'Dashboard Sopir'">
    @php
        $jumlahBelum = $total - $sudah;
        $persentase = $total > 0 ? ($sudah / $total) * 100 : 0;

        $darurat = $daftarKendaraan->filter(fn ($k) => $k->statusHarian() === 'darurat')->values();
        $belumDiabsen = $daftarKendaraan->filter(fn ($k) => $k->statusHarian() === 'belum')->values();

        $aktivitas = $daftarKendaraan
            ->flatMap(fn ($k) => $k->absenKendaraan->map(fn ($a) => ['k' => $k, 'a' => $a]))
            ->sortByDesc(fn ($x) => $x['a']->waktu)
            ->values();
    @endphp

    <style>
        .sp-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 1rem;
            box-shadow: 0 2px 8px rgba(15, 23, 42, 0.06);
        }
        .sp-btn {
            background: linear-gradient(135deg, #035CA8 0%, #0780C9 100%);
            color: #ffffff;
            box-shadow: 0 4px 10px rgba(3, 92, 168, 0.25);
            transition: transform 0.2s ease, box-shadow 0.2s ease, filter 0.2s ease;
        }
        .sp-btn:hover {
            transform: translateY(-2px);
            filter: brightness(1.08);
            box-shadow: 0 10px 20px rgba(3, 92, 168, 0.35);
        }
        .sp-row {
            transition: background 0.2s ease;
        }
        .sp-row:hover {
            background: #F1F7FC;
        }
        .sp-aktivitas::-webkit-scrollbar {
            width: 6px;
        }
        .sp-aktivitas::-webkit-scrollbar-thumb {
            background: #B9D8EE;
            border-radius: 9999px;
        }
    </style>

    <div class="max-w-7xl mx-auto p-2 space-y-5">

        <div class="relative mt-4 overflow-hidden rounded-2xl px-8 py-7"
            style="background: linear-gradient(110deg, #1B4A82 0%, #035CA8 35%, #0780C9 70%, #05A2E4 100%); box-shadow: 0 10px 25px rgba(3, 92, 168, 0.35);">
            <div class="absolute rounded-full"
                style="top: -4rem; right: -4rem; width: 16rem; height: 16rem; background: rgba(255, 255, 255, 0.1);"></div>
            <div class="absolute rounded-full"
                style="bottom: -6rem; right: 10rem; width: 14rem; height: 14rem; background: rgba(255, 255, 255, 0.1);"></div>
            <div class="relative z-10 flex flex-col gap-1 md:flex-row md:items-center md:justify-between">
                <div>
                    <p class="text-sm font-medium" style="color: rgba(255, 255, 255, 0.85);">{{ now()->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</p>
                    <h1 class="mt-1 text-2xl font-bold md:text-3xl" style="color: #ffffff;">Selamat datang, {{ Auth::user()->nama }}</h1>
                    <p class="mt-1 text-sm" style="color: rgba(255, 255, 255, 0.85);">Berikut adalah ringkasan patroli kendaraan hari ini.</p>
                </div>
                <div class="mt-4 px-6 py-3 text-center rounded-xl md:mt-0"
                    style="background: rgba(255, 255, 255, 0.18); border: 1px solid rgba(255, 255, 255, 0.25);">
                    <p class="text-3xl font-bold" style="color: #ffffff;">{{ number_format($persentase, 0) }}%</p>
                    <p class="text-xs font-medium uppercase tracking-wide" style="color: rgba(255, 255, 255, 0.85);">Selesai Hari Ini</p>
                </div>
            </div>
        </div>

        @if ($darurat->isNotEmpty())
            <div class="p-4 rounded-xl"
                style="background: #FEF2F2; border: 1px solid #FECACA; border-left: 4px solid #EF4444; color: #991B1B;">
                <strong>Kendaraan darurat ({{ $darurat->count() }})</strong> — belum selesai ditangani admin:
                <ul class="mt-2 space-y-1 text-sm">
                    @foreach ($darurat as $k)
                        @php $d = $k->laporanUtama(); @endphp
                        <li>
                            <span class="font-semibold">{{ $k->plat_nomor }}</span>
                            · {{ $d->kategoriKejadian->nama_kategori ?? '-' }}
                            · dilaporkan {{ $d->nama_sopir }}, {{ $d->tanggal->format('d-m-Y') }} {{ $d->jam }}
                            <span class="px-2 py-0.5 rounded-full text-xs font-semibold"
                                style="{{ $d->status_penanganan === 'proses' ? 'background: #FEF3C7; color: #B45309;' : 'background: #FEE2E2; color: #B91C1C;' }}">{{ $d->penanganan_label }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if ($total > 0 && $jumlahBelum > 0)
            <div class="p-4 rounded-xl"
                style="background: #FFFBEB; border: 1px solid #FDE68A; border-left: 4px solid #F59E0B; color: #92400E;">
                <strong>Perhatian!</strong> Masih ada <strong>{{ $jumlahBelum }}</strong> kendaraan yang belum dipatroli
                hari ini. Segera lakukan patroli untuk menyelesaikan tugas hari ini.
            </div>
        @endif

        @if ($total == 0)
            <div class="p-4 rounded-xl"
                style="background: #E6F3FB; border: 1px solid #B9D8EE; border-left: 4px solid #0780C9; color: #1B4A82;">
                <strong>Informasi :</strong> Belum ada kendaraan aktif di cabang kamu. Hubungi admin cabang.
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div class="sp-card p-6" style="border-top: 4px solid #0780C9;">
                <h2 class="text-lg font-semibold mb-1" style="color: #1B4A82;">Status Patroli Hari Ini</h2>
                <p class="text-xs mb-4" style="color: #64748B;">{{ now()->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</p>

                <div class="space-y-2 text-sm" style="color: #475569;">
                    <p class="flex space-x-1">
                        <span class="w-44 text-left">Total kendaraan</span><span>:</span>
                        <strong style="color: #1B4A82;">{{ $total }}</strong>
                    </p>
                    <p class="flex space-x-1">
                        <span class="w-44 text-left">Sudah dipatroli (aman)</span><span>:</span>
                        <strong style="color: #16A34A;">{{ $jumlahAman }}</strong>
                    </p>
                    <p class="flex space-x-1">
                        <span class="w-44 text-left">Darurat</span><span>:</span>
                        <strong style="color: #DC2626;">{{ $jumlahDarurat }}</strong>
                    </p>
                    <p class="flex space-x-1">
                        <span class="w-44 text-left">Belum dipatroli</span><span>:</span>
                        <strong style="color: #D97706;">{{ $jumlahBelum }}</strong>
                    </p>
                </div>

                <div class="mt-5">
                    <div class="relative overflow-hidden rounded-full" style="height: 1.125rem; background: #E6F3FB;">
                        <div class="h-full rounded-full transition-all duration-300 ease-in-out"
                            style="width: {{ $persentase }}%; background: linear-gradient(90deg, #035CA8 0%, #05A2E4 100%);"></div>
                        <div class="absolute inset-0 flex items-center justify-center text-xs font-semibold" style="color: #1B4A82;">
                            {{ number_format($persentase, 1) }}%
                        </div>
                    </div>
                    <p class="text-xs mt-2" style="color: #64748B;">Kendaraan darurat dihitung sudah dilaporkan.</p>
                </div>
            </div>

            <div class="sp-card sp-aktivitas p-6 max-h-72 overflow-y-auto" style="border-top: 4px solid #05A2E4;">
                <h2 class="text-lg font-semibold mb-4" style="color: #1B4A82;">Aktivitas Hari Ini</h2>
                <ul class="space-y-3 text-sm" style="color: #475569;">
                    @forelse ($aktivitas as $x)
                        @php $absen = $x['a']; $k = $x['k']; $isDarurat = $absen->status === 'darurat'; @endphp
                        <li class="flex items-start space-x-2 p-2 rounded-xl"
                            style="{{ $isDarurat ? 'background: #FEF2F2; border: 1px solid #FECACA;' : 'background: #F1F7FC; border: 1px solid #D6E8F5;' }}">
                            <span class="flex-shrink-0 flex items-center justify-center rounded-full font-bold text-xs"
                                style="width: 1.5rem; height: 1.5rem; {{ $isDarurat ? 'background: #FEE2E2; color: #DC2626;' : 'background: #DCFCE7; color: #16A34A;' }}">{{ $isDarurat ? '!' : '✓' }}</span>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm leading-tight break-words">
                                    <span class="font-semibold" style="color: #1B4A82;">{{ str_replace(':', '.', $absen->jam) }}</span> -
                                    <span>{{ $k->plat_nomor }} · {{ $k->jenis }}</span>
                                    <span style="color: #64748B;">oleh {{ $absen->nama_sopir }}</span>
                                    @if ($isDarurat)
                                        <span class="font-semibold" style="color: #DC2626;">· Darurat: {{ $absen->kategoriKejadian->nama_kategori ?? '-' }}</span>
                                    @endif
                                </p>
                            </div>
                        </li>
                    @empty
                        <li style="color: #64748B;">Belum ada aktivitas hari ini.</li>
                    @endforelse
                </ul>
            </div>
        </div>

        <div class="sp-card p-6">
            <h2 class="text-lg font-bold mb-4" style="color: #1B4A82;">Daftar Kendaraan Belum Dipatroli</h2>
            <div class="overflow-x-auto rounded-xl" style="border: 1px solid #e2e8f0;">
                <table class="table-auto w-full text-sm" style="color: #334155;">
                    <thead class="uppercase text-xs" style="background: #E6F3FB; color: #1B4A82;">
                        <tr class="text-center">
                            <th class="px-4 py-3">No</th>
                            <th class="px-4 py-3 text-left">Plat Nomor</th>
                            <th class="px-4 py-3 text-left">Jenis</th>
                            <th class="px-4 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y" style="background: #ffffff; border-color: #e2e8f0;">
                        @forelse ($belumDiabsen as $index => $k)
                            <tr class="sp-row text-center">
                                <td class="px-4 py-3">{{ $index + 1 }}</td>
                                <td class="px-4 py-3 text-left font-semibold" style="color: #035CA8;">{{ $k->plat_nomor }}</td>
                                <td class="px-4 py-3 text-left">{{ $k->jenis }}</td>
                                <td class="px-4 py-3">
                                    <a href="{{ route('sopir.scan-qr') }}"
                                        class="sp-btn inline-flex items-center px-4 py-1.5 rounded-lg text-sm font-semibold">Scan</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-6 text-center" style="color: #64748B;">
                                    {{ $darurat->isNotEmpty() ? 'Semua kendaraan lain sudah dipatroli.' : 'Semua kendaraan sudah dipatroli.' }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>