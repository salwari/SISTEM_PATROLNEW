<x-app-layout :title="'Dashboard Kabid Dukbis'">
    <div class="space-y-6">

        <div>
            <h1 class="text-2xl font-bold text-gray-800">Dashboard Eksekutif Dukungan Bisnis</h1>
            <p class="text-sm text-gray-500">
                Pusat pemantauan terpadu operasional keamanan dan fasilitas kebersihan di Cabang {{ Auth::user()->cabang->nama_cabang ?? '' }}
            </p>
        </div>

        <div class="border-b border-gray-200 bg-white rounded-t-xl px-4 pt-2 shadow-sm">
            <nav class="flex space-x-2 sm:space-x-4" aria-label="Tabs">
                <button type="button" onclick="switchDashboardTab('patroli')" id="tab-btn-patroli"
                    class="tab-btn flex items-center gap-2 py-3 px-4 border-b-2 font-bold text-sm transition duration-150 border-blue-600 text-blue-600">
                    <span>Patroli</span>
                    <span class="ml-1.5 py-0.5 px-2 rounded-full text-[11px] bg-blue-100 text-blue-800 font-semibold">
                        {{ $jumlahLaporanPatroli }} Log
                    </span>
                </button>

                <button type="button" onclick="switchDashboardTab('kebersihan')" id="tab-btn-kebersihan"
                    class="tab-btn flex items-center gap-2 py-3 px-4 border-b-2 font-bold text-sm transition duration-150 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                    <span>Kebersihan</span>
                    @if ($jumlahTemuanAktif > 0)
                        <span class="ml-1.5 py-0.5 px-2 rounded-full text-[11px] bg-red-100 text-red-800 font-bold animate-pulse">
                            {{ $jumlahTemuanAktif }} Temuan Rusak
                        </span>
                    @else
                        <span class="ml-1.5 py-0.5 px-2 rounded-full text-[11px] bg-emerald-100 text-emerald-800 font-semibold">
                            {{ $toiletSelesaiHariIni }}/{{ $jumlahKamarMandi }} Selesai
                        </span>
                    @endif
                </button>
            </nav>
        </div>

        <div id="tab-content-patroli" class="space-y-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <a href="{{ route('kabid-dukbis.data-petugas-security') }}" class="group">
                    <div class="flex items-center p-4 bg-white border border-gray-200 rounded-xl shadow-sm group-hover:shadow-md group-hover:border-blue-400 transition">
                        <div class="flex items-center justify-center w-12 h-12 rounded-xl bg-blue-100 text-blue-600 flex-shrink-0">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-xs font-medium text-gray-500">Petugas Security</h3>
                            <p class="text-2xl font-bold text-gray-900">{{ $jumlahPetugas }} <span class="text-xs font-normal text-gray-400">Personel</span></p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('kabid-dukbis.data-lokasi-patroli') }}" class="group">
                    <div class="flex items-center p-4 bg-white border border-gray-200 rounded-xl shadow-sm group-hover:shadow-md group-hover:border-blue-400 transition">
                        <div class="flex items-center justify-center w-12 h-12 rounded-xl bg-blue-100 text-blue-600 flex-shrink-0">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-xs font-medium text-gray-500">Titik Pos Patroli</h3>
                            <p class="text-2xl font-bold text-gray-900">{{ $jumlahLokasiPatroli }} <span class="text-xs font-normal text-gray-400">Titik</span></p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('kabid-dukbis.laporan-patroli') }}" class="group">
                    <div class="flex items-center p-4 bg-white border border-gray-200 rounded-xl shadow-sm group-hover:shadow-md group-hover:border-blue-400 transition">
                        <div class="flex items-center justify-center w-12 h-12 rounded-xl bg-blue-100 text-blue-600 flex-shrink-0">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V8z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-xs font-medium text-gray-500">Total Log Patroli</h3>
                            <p class="text-2xl font-bold text-gray-900">{{ $jumlahLaporanPatroli }} <span class="text-xs font-normal text-gray-400">Kegiatan</span></p>
                        </div>
                    </div>
                </a>
            </div>

            <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-4">
                    <div>
                        <h3 class="text-base font-bold text-gray-800">Aktivitas Patroli Keamanan</h3>
                        <p class="text-xs text-gray-400">Grafik frekuensi patroli regu security di cabang ini</p>
                    </div>
                    <select id="filter" class="border-gray-300 rounded-lg text-xs text-gray-700 focus:ring-blue-500 focus:border-blue-500 p-2 w-full sm:w-auto">
                        <option value="harian" selected>Harian (7 Hari Terakhir)</option>
                        <option value="mingguan">Mingguan (4 Minggu)</option>
                        <option value="bulanan">Bulanan (1 Tahun)</option>
                    </select>
                </div>
                <div class="relative h-80">
                    <canvas id="patroliChart" class="w-full h-full"></canvas>
                </div>
            </div>
        </div>

        <div id="tab-content-kebersihan" class="hidden space-y-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <a href="{{ route('kabid-dukbis.data-lokasi-kebersihan') }}" class="group">
                    <div class="flex items-center p-4 bg-white border border-gray-200 rounded-xl shadow-sm group-hover:shadow-md group-hover:border-emerald-400 transition">
                        <div class="flex items-center justify-center w-12 h-12 rounded-xl bg-emerald-100 text-emerald-600 flex-shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-xs font-medium text-gray-500">Total Titik Toilet</h3>
                            <p class="text-2xl font-bold text-gray-900">{{ $jumlahKamarMandi }} <span class="text-xs font-normal text-gray-400">Lokasi</span></p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('kabid-dukbis.laporan-kebersihan') }}" class="group">
                    <div class="flex items-center p-4 bg-white border border-gray-200 rounded-xl shadow-sm group-hover:shadow-md group-hover:border-emerald-400 transition">
                        <div class="flex items-center justify-center w-12 h-12 rounded-xl bg-emerald-100 text-emerald-600 flex-shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-xs font-medium text-gray-500">Cek Toilet Hari Ini</h3>
                            <p class="text-2xl font-bold text-emerald-600">
                                {{ $toiletSelesaiHariIni }} <span class="text-sm font-normal text-gray-400">/ {{ $jumlahKamarMandi }} Selesai</span>
                            </p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('kabid-dukbis.temuan-kamar-mandi') }}" class="group">
                    <div class="flex items-center p-4 bg-white border {{ $jumlahTemuanAktif > 0 ? 'border-red-300 bg-red-50/30' : 'border-gray-200' }} rounded-xl shadow-sm group-hover:shadow-md group-hover:border-red-400 transition">
                        <div class="flex items-center justify-center w-12 h-12 rounded-xl {{ $jumlahTemuanAktif > 0 ? 'bg-red-100 text-red-600' : 'bg-gray-100 text-gray-500' }} flex-shrink-0">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-xs font-medium text-gray-500">Temuan Rusak (Perlu Tindakan)</h3>
                            <p class="text-2xl font-bold {{ $jumlahTemuanAktif > 0 ? 'text-red-600' : 'text-gray-900' }}">
                                {{ $jumlahTemuanAktif }} <span class="text-xs font-normal text-gray-400">Laporan</span>
                            </p>
                        </div>
                    </div>
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
                <div class="lg:col-span-2 bg-white p-5 rounded-xl border border-gray-200 shadow-sm space-y-4">
                    <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full {{ $jumlahTemuanAktif > 0 ? 'bg-red-500 animate-pulse' : 'bg-emerald-500' }}"></span>
                            <h3 class="text-base font-bold text-gray-800">Daftar Temuan Fasilitas Rusak Terkini</h3>
                        </div>
                        <a href="{{ route('kabid-dukbis.temuan-kamar-mandi') }}" class="text-xs font-semibold text-blue-600 hover:underline">
                            Buka Semua Temuan &rarr;
                        </a>
                    </div>

                    <div class="space-y-3">
                        @forelse ($temuanTerbaru as $temuan)
                            <div class="p-4 rounded-xl bg-red-50/60 border border-red-100 text-xs space-y-2">
                                <div class="flex justify-between items-center font-bold text-gray-800">
                                    <span class="text-sm font-semibold text-red-700">
                                        {{ $temuan->kamarMandi->kode_kamar_mandi ?? '-' }} - {{ $temuan->kamarMandi->nama_kamar_mandi ?? '-' }}
                                    </span>
                                    <span class="text-[11px] text-gray-500 font-normal">
                                        {{ \Carbon\Carbon::parse($temuan->tanggal)->format('d M Y') }} • {{ \Carbon\Carbon::parse($temuan->waktu)->format('H:i') }} WIB
                                    </span>
                                </div>
                                <p class="text-gray-700 text-xs">
                                    <strong class="text-gray-900">Catatan OB:</strong> "{{ $temuan->catatan ?? 'Tidak ada catatan kerusakan' }}"
                                </p>
                                <div class="flex justify-between items-center pt-1 text-[11px] text-gray-500">
                                    <span>Petugas Pelapor: <strong class="text-gray-800">{{ $temuan->user->nama ?? 'OB' }}</strong></span>
                                    <a href="{{ route('kabid-dukbis.temuan-kamar-mandi') }}" class="text-red-600 font-semibold hover:underline">
                                        Tindak Lanjuti &rarr;
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="py-12 text-center text-gray-400 text-xs">
                                <svg class="w-10 h-10 text-emerald-500 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <p class="font-bold text-gray-700 text-sm">Semua Fasilitas Berfungsi Normal</p>
                                <p class="text-gray-400 mt-1">Tidak ada laporan kerusakan toilet yang membutuhkan perbaikan saat ini.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm space-y-4">
                    <h3 class="text-sm font-bold text-gray-800 border-b border-gray-100 pb-2">Menu Cepat Fasilitas</h3>
                    
                    <div class="space-y-2 text-xs">
                        <a href="{{ route('kabid-dukbis.data-lokasi-kebersihan') }}" 
                            class="flex items-center justify-between p-3 rounded-lg border border-gray-200 hover:bg-gray-50 transition">
                            <span class="font-medium text-gray-700">Master Lokasi Toilet</span>
                            <span class="text-gray-400">&rarr;</span>
                        </a>

                        <a href="{{ route('kabid-dukbis.temuan-kamar-mandi') }}" 
                            class="flex items-center justify-between p-3 rounded-lg border border-gray-200 hover:bg-gray-50 transition">
                            <span class="font-medium text-gray-700">Laporan Temuan Kerusakan</span>
                            <span class="text-gray-400">&rarr;</span>
                        </a>

                        <a href="{{ route('kabid-dukbis.laporan-kebersihan') }}" 
                            class="flex items-center justify-between p-3 rounded-lg border border-gray-200 hover:bg-gray-50 transition">
                            <span class="font-medium text-gray-700">Cetak Laporan Kebersihan</span>
                            <span class="text-gray-400">&rarr;</span>
                        </a>
                    </div>

                    <div class="p-3 bg-emerald-50 rounded-xl border border-emerald-100 text-xs text-emerald-800">
                        <p class="font-bold mb-1">Standar Sanitasi Sucofindo</p>
                        <p class="text-[11px] text-emerald-700 leading-relaxed">
                            Pastikan seluruh titik toilet diperiksa oleh petugas kebersihan secara berkala setiap hari.
                        </p>
                    </div>
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

                btnPatroli.className = 'tab-btn flex items-center gap-2 py-3 px-4 border-b-2 font-bold text-sm transition duration-150 border-blue-600 text-blue-600';
                btnKebersihan.className = 'tab-btn flex items-center gap-2 py-3 px-4 border-b-2 font-bold text-sm transition duration-150 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300';

                if (typeof patroliChart !== 'undefined') {
                    setTimeout(() => { patroliChart.resize(); }, 50);
                }
            } else if (tabName === 'kebersihan') {
                contentPatroli.classList.add('hidden');
                contentKebersihan.classList.remove('hidden');

                btnKebersihan.className = 'tab-btn flex items-center gap-2 py-3 px-4 border-b-2 font-bold text-sm transition duration-150 border-emerald-600 text-emerald-600';
                btnPatroli.className = 'tab-btn flex items-center gap-2 py-3 px-4 border-b-2 font-bold text-sm transition duration-150 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300';
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

        let patroliChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: dataSet[currentFilter].labels,
                datasets: [{
                    label: 'Jumlah Aktivitas Patroli',
                    data: dataSet[currentFilter].data,
                    backgroundColor: 'rgba(59, 130, 246, 0.6)',
                    borderColor: 'rgba(59, 130, 246, 1)',
                    borderWidth: 1,
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
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