<x-app-layout :title="'Dashboard Kabid Dukbis'">

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
        .ad-link {
            border: 1px solid #e2e8f0;
            transition: background 0.2s ease, border-color 0.2s ease;
        }
        .ad-link:hover {
            background: #F1F7FC;
            border-color: #B9D8EE;
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
            <h1 class="mt-1 text-2xl font-bold md:text-3xl" style="color: #ffffff;">Dashboard Eksekutif Dukungan Bisnis</h1>
            <p class="mt-1 text-sm" style="color: rgba(255, 255, 255, 0.85);">
                Pusat pemantauan terpadu operasional keamanan dan fasilitas kebersihan di Cabang {{ Auth::user()->cabang->nama_cabang ?? '' }}
            </p>
        </div>
    </div>

    <div class="mt-5 pb-0" style="border-bottom: 1px solid #D6E8F5;">
        <nav class="flex gap-6" aria-label="Tab Dashboard">
            <button type="button" onclick="switchDashboardTab('patroli')" id="tab-btn-patroli"
                class="dashboard-tab-btn aktif focus:outline-none">Patroli</button>
            <button type="button" onclick="switchDashboardTab('kebersihan')" id="tab-btn-kebersihan"
                class="dashboard-tab-btn focus:outline-none">Kebersihan</button>
        </nav>
    </div>

    <div id="tab-content-patroli">
        <div class="grid grid-cols-1 gap-4 mt-4 sm:grid-cols-2 lg:grid-cols-3">
            <a href="{{ route('kabid-dukbis.data-petugas-security') }}" class="block">
                <div class="ad-stat flex items-center p-4 cursor-pointer" style="border-top: 4px solid #035CA8;">
                    <div class="flex items-center justify-center shrink-0 rounded-xl"
                        style="width: 2.75rem; height: 2.75rem; background: linear-gradient(135deg, #1B4A82 0%, #035CA8 100%); box-shadow: 0 6px 14px rgba(3, 92, 168, 0.25);">
                        <svg style="width: 1.25rem; height: 1.25rem; color: #ffffff;" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-xs font-medium" style="color: #64748B;">Petugas Security</h3>
                        <p class="text-2xl font-bold" style="color: #1B4A82;">{{ $jumlahPetugas }} <span class="text-xs font-normal" style="color: #94A3B8;">Personel</span></p>
                    </div>
                </div>
            </a>

            <a href="{{ route('kabid-dukbis.data-lokasi-patroli') }}" class="block">
                <div class="ad-stat flex items-center p-4 cursor-pointer" style="border-top: 4px solid #0780C9;">
                    <div class="flex items-center justify-center shrink-0 rounded-xl"
                        style="width: 2.75rem; height: 2.75rem; background: linear-gradient(135deg, #035CA8 0%, #0780C9 100%); box-shadow: 0 6px 14px rgba(3, 92, 168, 0.25);">
                        <svg style="width: 1.25rem; height: 1.25rem; color: #ffffff;" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-xs font-medium" style="color: #64748B;">Titik Pos Patroli</h3>
                        <p class="text-2xl font-bold" style="color: #1B4A82;">{{ $jumlahLokasiPatroli }} <span class="text-xs font-normal" style="color: #94A3B8;">Titik</span></p>
                    </div>
                </div>
            </a>

            <a href="{{ route('kabid-dukbis.laporan-patroli') }}" class="block">
                <div class="ad-stat flex items-center p-4 cursor-pointer" style="border-top: 4px solid #05A2E4;">
                    <div class="flex items-center justify-center shrink-0 rounded-xl"
                        style="width: 2.75rem; height: 2.75rem; background: linear-gradient(135deg, #0780C9 0%, #05A2E4 100%); box-shadow: 0 6px 14px rgba(3, 92, 168, 0.25);">
                        <svg style="width: 1.25rem; height: 1.25rem; color: #ffffff;" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V8z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-xs font-medium" style="color: #64748B;">Total Log Patroli</h3>
                        <p class="text-2xl font-bold" style="color: #1B4A82;">{{ $jumlahLaporanPatroli }} <span class="text-xs font-normal" style="color: #94A3B8;">Kegiatan</span></p>
                    </div>
                </div>
            </a>
        </div>

        <div class="ad-card p-5 mt-4 mb-4" style="border-top: 4px solid #0780C9;">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-3">
                <div>
                    <h3 class="text-sm font-semibold" style="color: #1B4A82;">Aktivitas Patroli Keamanan</h3>
                    <p class="text-xs" style="color: #64748B;">Grafik frekuensi patroli regu security di cabang ini</p>
                </div>
                <select id="filter" class="ad-select w-full sm:w-auto">
                    <option value="harian" selected>Harian (7 Hari Terakhir)</option>
                    <option value="mingguan">Mingguan (4 Minggu)</option>
                    <option value="bulanan">Bulanan (1 Tahun)</option>
                </select>
            </div>
            <div class="relative h-72">
                <canvas id="patroliChart" class="w-full h-full"></canvas>
            </div>
        </div>
    </div>

    <div id="tab-content-kebersihan" class="hidden">
        <div class="grid grid-cols-1 gap-4 mt-4 sm:grid-cols-2 lg:grid-cols-3">
            <a href="{{ route('kabid-dukbis.data-lokasi-kebersihan') }}" class="block">
                <div class="ad-stat flex items-center p-4 cursor-pointer" style="border-top: 4px solid #035CA8;">
                    <div class="flex items-center justify-center shrink-0 rounded-xl"
                        style="width: 2.75rem; height: 2.75rem; background: linear-gradient(135deg, #1B4A82 0%, #035CA8 100%); box-shadow: 0 6px 14px rgba(3, 92, 168, 0.25);">
                        <svg style="width: 1.25rem; height: 1.25rem; color: #ffffff;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-xs font-medium" style="color: #64748B;">Total Titik Toilet</h3>
                        <p class="text-2xl font-bold" style="color: #1B4A82;">{{ $jumlahKamarMandi }} <span class="text-xs font-normal" style="color: #94A3B8;">Lokasi</span></p>
                    </div>
                </div>
            </a>

            <a href="{{ route('kabid-dukbis.laporan-kebersihan') }}" class="block">
                <div class="ad-stat flex items-center p-4 cursor-pointer" style="border-top: 4px solid #16A34A;">
                    <div class="flex items-center justify-center shrink-0 rounded-xl"
                        style="width: 2.75rem; height: 2.75rem; background: linear-gradient(135deg, #15803D 0%, #22C55E 100%); box-shadow: 0 6px 14px rgba(22, 163, 74, 0.25);">
                        <svg style="width: 1.25rem; height: 1.25rem; color: #ffffff;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-xs font-medium" style="color: #64748B;">Cek Toilet Hari Ini</h3>
                        <p class="text-2xl font-bold" style="color: #16A34A;">
                            {{ $toiletSelesaiHariIni }} <span class="text-sm font-normal" style="color: #94A3B8;">/ {{ $jumlahKamarMandi }} Selesai</span>
                        </p>
                    </div>
                </div>
            </a>

            <a href="{{ route('kabid-dukbis.temuan-kamar-mandi') }}" class="block">
                <div class="ad-stat flex items-center p-4 cursor-pointer"
                    style="border-top: 4px solid {{ $jumlahTemuanAktif > 0 ? '#DC2626' : '#94A3B8' }};">
                    <div class="flex items-center justify-center shrink-0 rounded-xl"
                        style="width: 2.75rem; height: 2.75rem; {{ $jumlahTemuanAktif > 0 ? 'background: linear-gradient(135deg, #B91C1C 0%, #EF4444 100%); box-shadow: 0 6px 14px rgba(220, 38, 38, 0.25);' : 'background: linear-gradient(135deg, #64748B 0%, #94A3B8 100%); box-shadow: 0 6px 14px rgba(100, 116, 139, 0.25);' }}">
                        <svg style="width: 1.25rem; height: 1.25rem; color: #ffffff;" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-xs font-medium" style="color: #64748B;">Temuan Rusak (Perlu Tindakan)</h3>
                        <p class="text-2xl font-bold" style="color: {{ $jumlahTemuanAktif > 0 ? '#DC2626' : '#1B4A82' }};">
                            {{ $jumlahTemuanAktif }} <span class="text-xs font-normal" style="color: #94A3B8;">Laporan</span>
                        </p>
                    </div>
                </div>
            </a>
        </div>

        <div class="grid grid-cols-1 gap-4 mt-4 mb-4 lg:grid-cols-3 items-start">
            <div class="ad-card p-5 space-y-4 lg:col-span-2" style="border-top: 4px solid {{ $jumlahTemuanAktif > 0 ? '#DC2626' : '#16A34A' }};">
                <div class="flex items-center justify-between pb-3" style="border-bottom: 1px solid #E6F3FB;">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full {{ $jumlahTemuanAktif > 0 ? 'animate-pulse' : '' }}"
                            style="background: {{ $jumlahTemuanAktif > 0 ? '#EF4444' : '#22C55E' }};"></span>
                        <h3 class="text-sm font-semibold" style="color: #1B4A82;">Daftar Temuan Fasilitas Rusak Terkini</h3>
                    </div>
                    <a href="{{ route('kabid-dukbis.temuan-kamar-mandi') }}" class="text-xs font-semibold hover:underline" style="color: #035CA8;">
                        Buka Semua Temuan &rarr;
                    </a>
                </div>

                <div class="space-y-3">
                    @forelse ($temuanTerbaru as $temuan)
                        <div class="ad-row p-4 rounded-xl text-xs space-y-2"
                            style="background: #FEF2F2; border: 1px solid #FECACA; border-left: 4px solid #EF4444;">
                            <div class="flex justify-between items-center font-bold">
                                <span class="text-sm font-semibold" style="color: #B91C1C;">
                                    {{ $temuan->kamarMandi->kode_kamar_mandi ?? '-' }} - {{ $temuan->kamarMandi->nama_kamar_mandi ?? '-' }}
                                </span>
                                <span class="font-normal" style="font-size: 0.6875rem; color: #64748B;">
                                    {{ \Carbon\Carbon::parse($temuan->tanggal)->format('d M Y') }} • {{ \Carbon\Carbon::parse($temuan->waktu)->format('H:i') }} WIB
                                </span>
                            </div>
                            <p class="text-xs" style="color: #334155;">
                                <strong style="color: #1B4A82;">Catatan OB:</strong> "{{ $temuan->catatan ?? 'Tidak ada catatan kerusakan' }}"
                            </p>
                            <div class="flex justify-between items-center pt-1" style="font-size: 0.6875rem; color: #64748B;">
                                <span>Petugas Pelapor: <strong style="color: #1B4A82;">{{ $temuan->user->nama ?? 'OB' }}</strong></span>
                                <a href="{{ route('kabid-dukbis.temuan-kamar-mandi') }}" class="font-semibold hover:underline" style="color: #DC2626;">
                                    Tindak Lanjuti &rarr;
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="py-12 text-center text-xs" style="color: #94A3B8;">
                            <svg class="mx-auto mb-2" style="width: 2.5rem; height: 2.5rem; color: #22C55E;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="font-bold text-sm" style="color: #1B4A82;">Semua Fasilitas Berfungsi Normal</p>
                            <p class="mt-1">Tidak ada laporan kerusakan toilet yang membutuhkan perbaikan saat ini.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="ad-card p-5 space-y-4" style="border-top: 4px solid #05A2E4;">
                <h3 class="text-sm font-semibold pb-2" style="color: #1B4A82; border-bottom: 1px solid #E6F3FB;">Menu Cepat Fasilitas</h3>

                <div class="space-y-2 text-xs">
                    <a href="{{ route('kabid-dukbis.data-lokasi-kebersihan') }}"
                        class="ad-link flex items-center justify-between p-3 rounded-lg">
                        <span class="font-medium" style="color: #1B4A82;">Master Lokasi Toilet</span>
                        <span style="color: #0780C9;">&rarr;</span>
                    </a>

                    <a href="{{ route('kabid-dukbis.temuan-kamar-mandi') }}"
                        class="ad-link flex items-center justify-between p-3 rounded-lg">
                        <span class="font-medium" style="color: #1B4A82;">Laporan Temuan Kerusakan</span>
                        <span style="color: #0780C9;">&rarr;</span>
                    </a>

                    <a href="{{ route('kabid-dukbis.laporan-kebersihan') }}"
                        class="ad-link flex items-center justify-between p-3 rounded-lg">
                        <span class="font-medium" style="color: #1B4A82;">Cetak Laporan Kebersihan</span>
                        <span style="color: #0780C9;">&rarr;</span>
                    </a>
                </div>

                <div class="p-3 rounded-xl text-xs" style="background: #F1F7FC; border: 1px solid #B9D8EE; border-left: 4px solid #0780C9; color: #1B4A82;">
                    <p class="font-bold mb-1">Standar Sanitasi Sucofindo</p>
                    <p class="leading-relaxed" style="font-size: 0.6875rem; color: #035CA8;">
                        Pastikan seluruh titik toilet diperiksa oleh petugas kebersihan secara berkala setiap hari.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        function switchDashboardTab(tabName) {
            const contentPatroli = document.getElementById('tab-content-patroli');
            const contentKebersihan = document.getElementById('tab-content-kebersihan');
            const btnPatroli = document.getElementById('tab-btn-patroli');
            const btnKebersihan = document.getElementById('tab-btn-kebersihan');

            if (tabName === 'patroli') {
                contentPatroli.classList.remove('hidden');
                contentKebersihan.classList.add('hidden');

                btnPatroli.classList.add('aktif');
                btnKebersihan.classList.remove('aktif');

                if (typeof patroliChart !== 'undefined') {
                    setTimeout(() => { patroliChart.resize(); }, 50);
                }
            } else if (tabName === 'kebersihan') {
                contentPatroli.classList.add('hidden');
                contentKebersihan.classList.remove('hidden');

                btnKebersihan.classList.add('aktif');
                btnPatroli.classList.remove('aktif');
            }
        }

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

        const isDarkMode = document.documentElement.classList.contains('dark');
        const chartTextColor = isDarkMode ? '#9ca3af' : '#64748B';
        const chartGridColor = isDarkMode ? 'rgba(255, 255, 255, 0.08)' : 'rgba(3, 92, 168, 0.08)';

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
                        ticks: {
                            color: chartTextColor,
                            font: { size: 11 }
                        },
                        grid: {
                            color: chartGridColor
                        }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0,
                            color: chartTextColor,
                            font: { size: 11 }
                        },
                        grid: {
                            color: chartGridColor
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
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
    </script>
</x-app-layout>