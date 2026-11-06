<x-app-layout :title="'Dashboard Admin'">

    <style>
        .ad-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 1rem;
            box-shadow: 0 2px 8px rgba(15, 23, 42, 0.06);
        }
        .ad-stat {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 1rem;
            box-shadow: 0 2px 8px rgba(15, 23, 42, 0.06);
            transition: transform 0.3s ease, box-shadow 0.3s ease, border-color 0.3s ease;
        }
        .ad-stat:hover {
            transform: translateY(-3px);
            border-color: #0780C9;
            box-shadow: 0 12px 24px rgba(3, 92, 168, 0.18);
        }
        .ad-btn {
            background: linear-gradient(135deg, #035CA8 0%, #0780C9 100%);
            color: #ffffff;
            box-shadow: 0 4px 10px rgba(3, 92, 168, 0.25);
            transition: transform 0.2s ease, box-shadow 0.2s ease, filter 0.2s ease;
        }
        .ad-btn:hover {
            transform: translateY(-2px);
            filter: brightness(1.08);
            box-shadow: 0 10px 20px rgba(3, 92, 168, 0.35);
        }
        .ad-row {
            transition: background 0.2s ease;
        }
        .ad-row:hover {
            background: #F1F7FC;
        }
        .dashboard-tab-btn {
            padding: 0 0.25rem 0.5rem 0.25rem;
            font-size: 0.875rem;
            font-weight: 600;
            color: #64748B;
            border-bottom: 3px solid transparent;
            transition: color 0.2s ease, border-color 0.2s ease;
        }
        .dashboard-tab-btn:hover {
            color: #035CA8;
        }
        .dashboard-tab-btn.aktif {
            color: #035CA8;
            border-bottom-color: #0780C9;
        }
        .ad-select {
            border: 1px solid #B9D8EE;
            border-radius: 0.5rem;
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
            color: #1B4A82;
            background: #F1F7FC;
        }
    </style>

    <div class="relative mt-4 overflow-hidden rounded-2xl px-8 py-7"
        style="background: linear-gradient(110deg, #1B4A82 0%, #035CA8 35%, #0780C9 70%, #05A2E4 100%); box-shadow: 0 10px 25px rgba(3, 92, 168, 0.35);">
        <div class="absolute rounded-full"
            style="top: -4rem; right: -4rem; width: 16rem; height: 16rem; background: rgba(255, 255, 255, 0.1);"></div>
        <div class="absolute rounded-full"
            style="bottom: -6rem; right: 10rem; width: 14rem; height: 14rem; background: rgba(255, 255, 255, 0.1);"></div>
        <div class="relative z-10">
            <p class="text-sm font-medium" style="color: rgba(255, 255, 255, 0.85);">{{ now()->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</p>
            <h1 class="mt-1 text-2xl font-bold md:text-3xl" style="color: #ffffff;">Selamat datang, {{ Auth::user()->nama }}</h1>
            <p class="mt-1 text-sm" style="color: rgba(255, 255, 255, 0.85);">Ringkasan patroli, kendaraan, dan kebersihan cabang.</p>
        </div>
    </div>

    <div class="mt-5 pb-0" style="border-bottom: 1px solid #D6E8F5;">
        <nav class="flex gap-6" aria-label="Tab Dashboard">
            <button type="button" data-tab="tab-patroli" class="dashboard-tab-btn aktif focus:outline-none">Patroli</button>
            <button type="button" data-tab="tab-kendaraan" class="dashboard-tab-btn focus:outline-none">Kendaraan</button>
            <button type="button" data-tab="tab-kebersihan" class="dashboard-tab-btn focus:outline-none">Kebersihan</button>
        </nav>
    </div>

    <div id="tab-patroli" class="dashboard-tab-panel">
        @if (count($petugasKurangPatroli) > 0)
            <div class="mt-4 px-4 py-3 text-sm rounded-xl"
                style="background: #FFFBEB; border: 1px solid #FDE68A; border-left: 4px solid #F59E0B; color: #92400E;">
                Terdapat {{ count($petugasKurangPatroli) }} petugas yang belum memenuhi target patroli minimal bulan ini.
            </div>
        @endif

        <div class="grid grid-cols-1 gap-4 mt-4 sm:grid-cols-2 lg:grid-cols-3">
            <a href="{{ route('data-pengguna.index') }}" class="block">
                <div class="ad-stat flex items-center p-4 cursor-pointer" style="border-top: 4px solid #035CA8;">
                    <div class="flex items-center justify-center shrink-0 rounded-xl"
                        style="width: 2.75rem; height: 2.75rem; background: linear-gradient(135deg, #1B4A82 0%, #035CA8 100%); box-shadow: 0 6px 14px rgba(3, 92, 168, 0.25);">
                        <svg style="width: 1.25rem; height: 1.25rem; color: #ffffff;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512" fill="currentColor">
                            <path d="M144 0a80 80 0 1 1 0 160A80 80 0 1 1 144 0zM512 0a80 80 0 1 1 0 160A80 80 0 1 1 512 0zM0 298.7C0 239.8 47.8 192 106.7 192l42.7 0c15.9 0 31 3.5 44.6 9.7c-1.3 7.2-1.9 14.7-1.9 22.3c0 38.2 16.8 72.5 43.3 96c-.2 0-.4 0-.7 0L21.3 320C9.6 320 0 310.4 0 298.7zM405.3 320c-.2 0-.4 0-.7 0c26.6-23.5 43.3-57.8 43.3-96c0-7.6-.7-15-1.9-22.3c13.6-6.3 28.7-9.7 44.6-9.7l42.7 0C592.2 192 640 239.8 640 298.7c0 11.8-9.6 21.3-21.3 21.3l-213.3 0zM224 224a96 96 0 1 1 192 0 96 96 0 1 1 -192 0zM128 485.3C128 411.7 187.7 352 261.3 352l117.3 0C452.3 352 512 411.7 512 485.3c0 14.7-11.9 26.7-26.7 26.7l-330.7 0c-14.7 0-26.7-11.9-26.7-26.7z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-xs font-medium" style="color: #64748B;">Jumlah Pengguna</h3>
                        <p class="text-2xl font-bold" style="color: #1B4A82;">{{ $jumlahPengguna }}</p>
                    </div>
                </div>
            </a>
            <a href="{{ route('lokasi-patroli.index') }}" class="block">
                <div class="ad-stat flex items-center p-4 cursor-pointer" style="border-top: 4px solid #0780C9;">
                    <div class="flex items-center justify-center shrink-0 rounded-xl"
                        style="width: 2.75rem; height: 2.75rem; background: linear-gradient(135deg, #035CA8 0%, #0780C9 100%); box-shadow: 0 6px 14px rgba(3, 92, 168, 0.25);">
                        <svg style="width: 1.25rem; height: 1.25rem; color: #ffffff;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" fill="currentColor">
                            <path d="M215.7 499.2C267 435 384 279.4 384 192C384 86 298 0 192 0S0 86 0 192c0 87.4 117 243 168.3 307.2c12.3 15.3 35.1 15.3 47.4 0zM192 128a64 64 0 1 1 0 128 64 64 0 1 1 0-128z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-xs font-medium" style="color: #64748B;">Jumlah Lokasi Patroli</h3>
                        <p class="text-2xl font-bold" style="color: #1B4A82;">{{ $jumlahLokasiPatroli }}</p>
                    </div>
                </div>
            </a>
            <a href="{{ route('admin.data-patroli') }}" class="block">
                <div class="ad-stat flex items-center p-4 cursor-pointer" style="border-top: 4px solid #05A2E4;">
                    <div class="flex items-center justify-center shrink-0 rounded-xl"
                        style="width: 2.75rem; height: 2.75rem; background: linear-gradient(135deg, #0780C9 0%, #05A2E4 100%); box-shadow: 0 6px 14px rgba(3, 92, 168, 0.25);">
                        <svg style="width: 1.25rem; height: 1.25rem; color: #ffffff;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" fill="currentColor">
                            <path d="M256 0c4.6 0 9.2 1 13.4 2.9L457.7 82.8c22 9.3 38.4 31 38.3 57.2c-.5 99.2-41.3 280.7-213.6 363.2c-16.7 8-36.1 8-52.8 0C57.3 420.7 16.5 239.2 16 140c-.1-26.2 16.3-47.9 38.3-57.2L242.7 2.9C246.8 1 251.4 0 256 0zm0 66.8l0 378.1C394 378 431.1 230.1 432 141.4L256 66.8z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-xs font-medium" style="color: #64748B;">Jumlah Patroli</h3>
                        <p class="text-2xl font-bold" style="color: #1B4A82;">{{ $jumlahPatroli }}</p>
                    </div>
                </div>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4 mb-4">
            <div class="ad-card p-5" style="border-top: 4px solid #0780C9;">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-sm font-semibold" style="color: #1B4A82;">Aktivitas Patroli</h3>
                    <select id="filter" class="ad-select">
                        <option value="harian" selected>Harian</option>
                        <option value="mingguan">Mingguan</option>
                        <option value="bulanan">Bulanan</option>
                    </select>
                </div>
                <div class="relative h-56">
                    <canvas id="patroliChart" class="w-full h-full"></canvas>
                </div>
            </div>
            <div class="ad-card p-5" style="border-top: 4px solid #05A2E4;">
                <h2 class="text-sm font-semibold mb-3" style="color: #1B4A82;">Status Patroli</h2>
                <div class="relative w-full max-w-[16rem] mx-auto h-56">
                    <canvas id="statusDonutChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div id="tab-kendaraan" class="dashboard-tab-panel hidden">
        @if ($kendaraan['belumDipatroliHariIni']->count() > 0)
            <div class="mt-4 px-4 py-3 text-sm rounded-xl"
                style="background: #FFFBEB; border: 1px solid #FDE68A; border-left: 4px solid #F59E0B; color: #92400E;">
                Terdapat {{ $kendaraan['belumDipatroliHariIni']->count() }} kendaraan aktif yang belum dipatroli hari ini:
                <span class="font-semibold">{{ $kendaraan['belumDipatroliHariIni']->pluck('plat_nomor')->join(', ') }}</span>.
            </div>
        @endif
        @if ($kendaraan['laporanTerbuka']->count() > 0)
            <div class="mt-2 px-4 py-3 text-sm rounded-xl"
                style="background: #FEF2F2; border: 1px solid #FECACA; border-left: 4px solid #EF4444; color: #991B1B;">
                Terdapat {{ $kendaraan['laporanTerbuka']->count() }} laporan darurat kendaraan yang <span class="font-semibold">belum selesai ditangani</span>.
            </div>
        @endif

        <div class="grid grid-cols-1 gap-4 mt-4 sm:grid-cols-2 lg:grid-cols-3">
            <a href="{{ route('kendaraan.index', ['status' => 'aktif']) }}" class="block">
                <div class="ad-stat flex items-center p-4" style="border-top: 4px solid #035CA8;">
                    <div class="flex items-center justify-center shrink-0 rounded-xl"
                        style="width: 2.75rem; height: 2.75rem; background: linear-gradient(135deg, #1B4A82 0%, #035CA8 100%); box-shadow: 0 6px 14px rgba(3, 92, 168, 0.25);">
                        <svg style="width: 1.25rem; height: 1.25rem; color: #ffffff;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M5 11l1.5-4.5A2 2 0 018.4 5h7.2a2 2 0 011.9 1.5L19 11h.5a1.5 1.5 0 011.5 1.5V17a1 1 0 01-1 1h-1a2 2 0 01-4 0H9a2 2 0 01-4 0H4a1 1 0 01-1-1v-4.5A1.5 1.5 0 014.5 11H5z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-xs font-medium" style="color: #64748B;">Kendaraan Aktif</h3>
                        <p class="text-2xl font-bold" style="color: #1B4A82;">{{ $kendaraan['jumlahAktif'] }}</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('riwayat-kendaraan.index', ['tanggal_dari' => now()->toDateString(), 'tanggal_sampai' => now()->toDateString()]) }}" class="block">
                <div class="ad-stat flex items-center p-4" style="border-top: 4px solid #16A34A;">
                    <div class="flex items-center justify-center shrink-0 rounded-xl"
                        style="width: 2.75rem; height: 2.75rem; background: linear-gradient(135deg, #15803D 0%, #22C55E 100%); box-shadow: 0 6px 14px rgba(22, 163, 74, 0.25);">
                        <svg style="width: 1.25rem; height: 1.25rem; color: #ffffff;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-4 w-full">
                        <h3 class="text-xs font-medium" style="color: #64748B;">Sudah Dipatroli Hari Ini</h3>
                        <p class="text-2xl font-bold" style="color: #16A34A;">
                            {{ $kendaraan['sudahDipatroli'] }} / {{ $kendaraan['jumlahAktif'] }}
                        </p>
                        @php
                            $persenKendaraan = $kendaraan['jumlahAktif'] > 0 ? round($kendaraan['sudahDipatroli'] / $kendaraan['jumlahAktif'] * 100) : 0;
                        @endphp
                        <div class="w-full rounded-full mt-1 overflow-hidden" style="height: 0.375rem; background: #E6F3FB;">
                            <div class="rounded-full" style="height: 0.375rem; width: {{ $persenKendaraan }}%; background: linear-gradient(90deg, #035CA8 0%, #05A2E4 100%);"></div>
                        </div>
                    </div>
                </div>
            </a>

            <a href="{{ route('riwayat-kendaraan.index', ['penanganan' => 'terbuka']) }}" class="block">
                <div class="ad-stat flex items-center p-4" style="border-top: 4px solid #DC2626;">
                    <div class="flex items-center justify-center shrink-0 rounded-xl"
                        style="width: 2.75rem; height: 2.75rem; background: linear-gradient(135deg, #B91C1C 0%, #EF4444 100%); box-shadow: 0 6px 14px rgba(220, 38, 38, 0.25);">
                        <svg style="width: 1.25rem; height: 1.25rem; color: #ffffff;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-xs font-medium" style="color: #64748B;">Laporan Darurat Belum Selesai</h3>
                        <p class="text-2xl font-bold" style="color: #DC2626;">{{ $kendaraan['laporanTerbuka']->count() }}</p>
                    </div>
                </div>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
            <div class="ad-card p-5" style="border-top: 4px solid #0780C9;">
                <h3 class="text-sm font-semibold mb-3" style="color: #1B4A82;">Patroli Kendaraan 7 Hari Terakhir</h3>
                <div class="relative h-56">
                    <canvas id="cekKendaraanChart" class="w-full h-full"></canvas>
                </div>
            </div>
            <div class="ad-card p-5" style="border-top: 4px solid #05A2E4;">
                <h3 class="text-sm font-semibold mb-3" style="color: #1B4A82;">Kondisi Kendaraan Terkini</h3>
                <div class="relative w-full max-w-[16rem] mx-auto h-56">
                    <canvas id="kondisiKendaraanChart"></canvas>
                </div>
            </div>
        </div>

        @if ($kendaraan['laporanTerbuka']->count() > 0)
            <div class="ad-card p-5 mt-4 mb-4">
                <h3 class="text-sm font-semibold mb-3" style="color: #1B4A82;">Laporan Darurat Kendaraan Belum Selesai</h3>
                <div class="overflow-x-auto rounded-xl" style="border: 1px solid #e2e8f0;">
                    <table class="table-auto w-full text-xs" style="color: #334155;">
                        <thead class="uppercase" style="background: #E6F3FB; color: #1B4A82;">
                            <tr>
                                <th class="px-3 py-3 text-left">Kendaraan</th>
                                <th class="px-3 py-3 text-left">Dilaporkan oleh</th>
                                <th class="px-3 py-3 text-center">Tanggal</th>
                                <th class="px-3 py-3 text-left">Kondisi</th>
                                <th class="px-3 py-3 text-center">Penanganan</th>
                                <th class="px-3 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y" style="background: #ffffff; border-color: #e2e8f0;">
                            @foreach ($kendaraan['laporanTerbuka'] as $l)
                                <tr class="ad-row">
                                    <td class="px-3 py-3 whitespace-nowrap">
                                        <span class="font-semibold" style="color: #035CA8;">{{ $l->kendaraan->plat_nomor ?? '-' }}</span>
                                        <div class="text-xs" style="color: #64748B;">{{ $l->kendaraan->jenis ?? '' }}</div>
                                    </td>
                                    <td class="px-3 py-3">{{ $l->nama_sopir }}</td>
                                    <td class="px-3 py-3 text-center whitespace-nowrap">{{ $l->tanggal->format('d-m-Y') }}</td>
                                    <td class="px-3 py-3">
                                        <div class="font-semibold" style="color: #1B4A82;">{{ $l->kategoriKejadian->nama_kategori ?? '-' }}</div>
                                        <div class="text-xs" style="color: #64748B;">{{ \Illuminate\Support\Str::limit($l->keterangan ?: '-', 60) }}</div>
                                    </td>
                                    <td class="px-3 py-3 text-center">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full"
                                            style="{{ $l->status_penanganan === 'proses' ? 'background: #FEF3C7; color: #B45309;' : 'background: #FEE2E2; color: #B91C1C;' }}">
                                            {{ $l->penanganan_label }}
                                        </span>
                                    </td>
                                    <td class="px-3 py-3 text-center">
                                        <a href="{{ route('riwayat-kendaraan.show', $l->id) }}"
                                            class="ad-btn inline-flex items-center px-3 py-1 text-xs font-semibold rounded-lg">Detail</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>

    <div id="tab-kebersihan" class="dashboard-tab-panel hidden">
        @if ($kebersihan['belumDicekHariIni']->count() > 0)
            <div class="mt-4 px-4 py-3 text-sm rounded-xl"
                style="background: #FFFBEB; border: 1px solid #FDE68A; border-left: 4px solid #F59E0B; color: #92400E;">
                Terdapat {{ $kebersihan['belumDicekHariIni']->count() }} kamar mandi yang belum dicek hari ini:
                <span class="font-semibold">{{ $kebersihan['belumDicekHariIni']->pluck('nama_kamar_mandi')->join(', ') }}</span>.
            </div>
        @endif
        @if ($kebersihan['perluTindakan']->count() > 0)
            <div class="mt-2 px-4 py-3 text-sm rounded-xl"
                style="background: #FEF2F2; border: 1px solid #FECACA; border-left: 4px solid #EF4444; color: #991B1B;">
                Terdapat {{ $kebersihan['perluTindakan']->count() }} kamar mandi dengan hasil cek terakhir <span class="font-semibold">perlu tindakan</span>.
            </div>
        @endif

        <div class="grid grid-cols-1 gap-4 mt-4 sm:grid-cols-2 lg:grid-cols-3">
            <a href="{{ route('lokasi-kebersihan.index') }}" class="block">
                <div class="ad-stat flex items-center p-4" style="border-top: 4px solid #035CA8;">
                    <div class="flex items-center justify-center shrink-0 rounded-xl"
                        style="width: 2.75rem; height: 2.75rem; background: linear-gradient(135deg, #1B4A82 0%, #035CA8 100%); box-shadow: 0 6px 14px rgba(3, 92, 168, 0.25);">
                        <svg style="width: 1.25rem; height: 1.25rem; color: #ffffff;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M10 2.5c-2.5 3.5-5 6-5 9a5 5 0 0010 0c0-3-2.5-5.5-5-9z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-xs font-medium" style="color: #64748B;">Jumlah Kamar Mandi</h3>
                        <p class="text-2xl font-bold" style="color: #1B4A82;">{{ $kebersihan['jumlahKamarMandi'] }}</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('riwayat-kebersihan.index', ['tanggal_dari' => now()->toDateString(), 'tanggal_sampai' => now()->toDateString()]) }}" class="block">
                <div class="ad-stat flex items-center p-4" style="border-top: 4px solid #16A34A;">
                    <div class="flex items-center justify-center shrink-0 rounded-xl"
                        style="width: 2.75rem; height: 2.75rem; background: linear-gradient(135deg, #15803D 0%, #22C55E 100%); box-shadow: 0 6px 14px rgba(22, 163, 74, 0.25);">
                        <svg style="width: 1.25rem; height: 1.25rem; color: #ffffff;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-4 w-full">
                        <h3 class="text-xs font-medium" style="color: #64748B;">Sudah Dicek Hari Ini</h3>
                        <p class="text-2xl font-bold" style="color: #16A34A;">
                            {{ $kebersihan['sudahDicek'] }} / {{ $kebersihan['jumlahKamarMandi'] }}
                        </p>
                        @php
                            $persenKebersihan = $kebersihan['jumlahKamarMandi'] > 0 ? round($kebersihan['sudahDicek'] / $kebersihan['jumlahKamarMandi'] * 100) : 0;
                        @endphp
                        <div class="w-full rounded-full mt-1 overflow-hidden" style="height: 0.375rem; background: #E6F3FB;">
                            <div class="rounded-full" style="height: 0.375rem; width: {{ $persenKebersihan }}%; background: linear-gradient(90deg, #035CA8 0%, #05A2E4 100%);"></div>
                        </div>
                    </div>
                </div>
            </a>

            <a href="{{ route('riwayat-kebersihan.index', ['status' => 'perlu_tindakan']) }}" class="block">
                <div class="ad-stat flex items-center p-4" style="border-top: 4px solid #DC2626;">
                    <div class="flex items-center justify-center shrink-0 rounded-xl"
                        style="width: 2.75rem; height: 2.75rem; background: linear-gradient(135deg, #B91C1C 0%, #EF4444 100%); box-shadow: 0 6px 14px rgba(220, 38, 38, 0.25);">
                        <svg style="width: 1.25rem; height: 1.25rem; color: #ffffff;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-xs font-medium" style="color: #64748B;">Perlu Tindakan</h3>
                        <p class="text-2xl font-bold" style="color: #DC2626;">{{ $kebersihan['perluTindakan']->count() }}</p>
                    </div>
                </div>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
            <div class="ad-card p-5" style="border-top: 4px solid #0780C9;">
                <h3 class="text-sm font-semibold mb-3" style="color: #1B4A82;">Cek Kebersihan 7 Hari Terakhir</h3>
                <div class="relative h-56">
                    <canvas id="cekKebersihanChart" class="w-full h-full"></canvas>
                </div>
            </div>
            <div class="ad-card p-5" style="border-top: 4px solid #05A2E4;">
                <h3 class="text-sm font-semibold mb-3" style="color: #1B4A82;">Kondisi Kamar Mandi Terkini</h3>
                <div class="relative w-full max-w-[16rem] mx-auto h-56">
                    <canvas id="kondisiKebersihanChart"></canvas>
                </div>
            </div>
        </div>

        @if ($kebersihan['perluTindakan']->count() > 0)
            <div class="ad-card p-5 mt-4 mb-4">
                <h3 class="text-sm font-semibold mb-3" style="color: #1B4A82;">Kamar Mandi yang Perlu Tindakan</h3>
                <div class="overflow-x-auto rounded-xl" style="border: 1px solid #e2e8f0;">
                    <table class="table-auto w-full text-xs" style="color: #334155;">
                        <thead class="uppercase" style="background: #FEE2E2; color: #991B1B;">
                            <tr>
                                <th class="px-3 py-3 text-left">Kamar Mandi</th>
                                <th class="px-3 py-3 text-left">Dicek oleh</th>
                                <th class="px-3 py-3 text-center">Tanggal</th>
                                <th class="px-3 py-3 text-left">Catatan</th>
                                <th class="px-3 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y" style="background: #ffffff; border-color: #e2e8f0;">
                            @foreach ($kebersihan['perluTindakan'] as $km)
                                <tr class="ad-row">
                                    <td class="px-3 py-3 whitespace-nowrap">
                                        <span class="font-semibold" style="color: #1B4A82;">{{ $km->nama_kamar_mandi }}</span>
                                        <div class="text-xs" style="color: #64748B;">{{ $km->lantai }}</div>
                                    </td>
                                    <td class="px-3 py-3">{{ $km->cekTerakhir->user->nama ?? '-' }}</td>
                                    <td class="px-3 py-3 text-center whitespace-nowrap">{{ \Carbon\Carbon::parse($km->cekTerakhir->tanggal)->format('d-m-Y') }}</td>
                                    <td class="px-3 py-3">{{ \Illuminate\Support\Str::limit($km->cekTerakhir->catatan ?: '-', 60) }}</td>
                                    <td class="px-3 py-3 text-center">
                                        <a href="{{ route('riwayat-kebersihan.index', ['status' => 'perlu_tindakan', 'tanggal_dari' => \Carbon\Carbon::parse($km->cekTerakhir->tanggal)->toDateString(), 'tanggal_sampai' => \Carbon\Carbon::parse($km->cekTerakhir->tanggal)->toDateString()]) }}"
                                            class="ad-btn inline-flex items-center px-3 py-1 text-xs font-semibold rounded-lg">Detail</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>

<script>
    const isDark = document.documentElement.classList.contains('dark');
    const chartTextColor = isDark ? '#e5e7eb' : '#1B4A82';
    const chartSubTextColor = isDark ? '#9ca3af' : '#64748B';
    const chartGridColor = isDark ? 'rgba(255, 255, 255, 0.08)' : 'rgba(3, 92, 168, 0.08)';
    Chart.defaults.font.size = 11;
    Chart.defaults.color = chartSubTextColor;
    Chart.defaults.borderColor = chartGridColor;

    const tabButtons = document.querySelectorAll('.dashboard-tab-btn');
    const tabPanels = document.querySelectorAll('.dashboard-tab-panel');

    tabButtons.forEach(function(btn) {
        btn.addEventListener('click', function() {
            const target = this.dataset.tab;

            tabPanels.forEach(function(panel) {
                panel.classList.toggle('hidden', panel.id !== target);
            });

            tabButtons.forEach(function(b) {
                b.classList.toggle('aktif', b === btn);
            });

            Object.values(Chart.instances).forEach(function(chart) {
                chart.resize();
            });
        });
    });

    const ctx = document.getElementById('patroliChart').getContext('2d');

    const dataSet = {
        harian: {
            labels: @json($harianLabels),
            data: @json($harianData)
        },
        mingguan: {
            labels: @json($mingguanLabels),
            data: @json($mingguanData)
        },
        bulanan: {
            labels: @json($bulananLabels),
            data: @json($bulananData)
        }
    };

    let currentFilter = 'harian';

    let patroliChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: dataSet[currentFilter].labels,
            datasets: [{
                label: 'Jumlah Aktivitas Patroli',
                data: dataSet[currentFilter].data,
                backgroundColor: 'rgba(3, 92, 168, 0.65)',
                borderColor: 'rgba(3, 92, 168, 1)',
                borderWidth: 1,
                borderRadius: 6,
                maxBarThickness: 32
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    ticks: { color: chartSubTextColor },
                    grid: { color: chartGridColor }
                },
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0,
                        color: chartSubTextColor
                    },
                    grid: { color: chartGridColor }
                }
            },
            plugins: {
                legend: {
                    labels: {
                        color: chartTextColor,
                        boxWidth: 12
                    }
                }
            }
        }
    });

    document.getElementById('filter').addEventListener('change', function() {
        const selected = this.value;
        currentFilter = selected;

        patroliChart.data.labels = dataSet[selected].labels;
        patroliChart.data.datasets[0].data = dataSet[selected].data;
        patroliChart.update();
    });

    const statusDonutChart = new Chart(document.getElementById('statusDonutChart'), {
        type: 'doughnut',
        data: {
            labels: ['Aman', 'Darurat'],
            datasets: [{
                data: [
                    {{ $statusPatroli['aman'] ?? 0 }},
                    {{ $statusPatroli['darurat'] ?? 0 }}
                ],
                backgroundColor: [
                    'rgba(34,197,94,0.85)',
                    'rgba(239,68,68,0.85)'
                ],
                borderWidth: 2,
                borderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '55%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        color: chartTextColor,
                        boxWidth: 12
                    }
                },
                datalabels: {
                    color: '#fff',
                    formatter: (value, context) => {
                        const dataArr = context.chart.data.datasets[0].data;
                        const total = dataArr.reduce((a, b) => a + b, 0);
                        const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                        return percentage + '%';
                    },
                    font: {
                        weight: 'bold',
                        size: 12,
                    }
                }
            }
        },
        plugins: [ChartDataLabels]
    });

    new Chart(document.getElementById('cekKendaraanChart'), {
        type: 'bar',
        data: {
            labels: @json($kendaraan['mingguLabels']),
            datasets: [{
                label: 'Jumlah Patroli Kendaraan',
                data: @json($kendaraan['mingguData']),
                backgroundColor: 'rgba(7, 128, 201, 0.65)',
                borderColor: 'rgba(7, 128, 201, 1)',
                borderWidth: 1,
                borderRadius: 6,
                maxBarThickness: 32
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: { y: { beginAtZero: true, ticks: { precision: 0 } } },
            plugins: { legend: { labels: { boxWidth: 12 } } }
        }
    });

    new Chart(document.getElementById('kondisiKendaraanChart'), {
        type: 'doughnut',
        data: {
            labels: ['Aman', 'Ada Laporan Darurat', 'Belum Pernah Dipatroli'],
            datasets: [{
                data: [
                    {{ $kendaraan['kondisi']['aman'] }},
                    {{ $kendaraan['kondisi']['darurat'] }},
                    {{ $kendaraan['kondisi']['belum_pernah'] }}
                ],
                backgroundColor: ['rgba(34,197,94,0.85)', 'rgba(239,68,68,0.85)', 'rgba(148,163,184,0.85)'],
                borderWidth: 2,
                borderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '55%',
            plugins: {
                legend: { position: 'bottom', labels: { color: chartTextColor, boxWidth: 12 } },
                datalabels: {
                    color: '#fff',
                    display: (ctx) => ctx.dataset.data[ctx.dataIndex] > 0,
                    formatter: (value, ctx) => {
                        const total = ctx.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                        return total ? ((value / total) * 100).toFixed(0) + '%' : '';
                    },
                    font: { weight: 'bold', size: 12 }
                }
            }
        },
        plugins: [ChartDataLabels]
    });

    new Chart(document.getElementById('cekKebersihanChart'), {
        type: 'bar',
        data: {
            labels: @json($kebersihan['mingguLabels']),
            datasets: [{
                label: 'Jumlah Cek Kebersihan',
                data: @json($kebersihan['mingguData']),
                backgroundColor: 'rgba(5, 162, 228, 0.65)',
                borderColor: 'rgba(5, 162, 228, 1)',
                borderWidth: 1,
                borderRadius: 6,
                maxBarThickness: 32
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: { y: { beginAtZero: true, ticks: { precision: 0 } } },
            plugins: { legend: { labels: { boxWidth: 12 } } }
        }
    });

    new Chart(document.getElementById('kondisiKebersihanChart'), {
        type: 'doughnut',
        data: {
            labels: ['Baik', 'Perlu Tindakan', 'Belum Pernah Dicek'],
            datasets: [{
                data: [
                    {{ $kebersihan['kondisi']['baik'] }},
                    {{ $kebersihan['kondisi']['perlu_tindakan'] }},
                    {{ $kebersihan['kondisi']['belum_pernah'] }}
                ],
                backgroundColor: ['rgba(34,197,94,0.85)', 'rgba(239,68,68,0.85)', 'rgba(148,163,184,0.85)'],
                borderWidth: 2,
                borderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '55%',
            plugins: {
                legend: { position: 'bottom', labels: { color: chartTextColor, boxWidth: 12 } },
                datalabels: {
                    color: '#fff',
                    display: (ctx) => ctx.dataset.data[ctx.dataIndex] > 0,
                    formatter: (value, ctx) => {
                        const total = ctx.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                        return total ? ((value / total) * 100).toFixed(0) + '%' : '';
                    },
                    font: { weight: 'bold', size: 12 }
                }
            }
        },
        plugins: [ChartDataLabels]
    });
</script>