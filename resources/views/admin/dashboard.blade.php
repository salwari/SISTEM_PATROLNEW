<x-app-layout :title="'Dashboard Admin'">
    {{-- Menghitung jumlah petugas yang belum memenuhi target patroli minimal bulan ini --}}
    @if (count($petugasKurangPatroli) > 0)
        <div class="mt-6 mb-2 p-4 bg-yellow-100 border-l-4 border-yellow-500 text-yellow-800 rounded-lg shadow-md">
            Terdapat {{ count($petugasKurangPatroli) }} petugas yang belum memenuhi target patroli minimal bulan ini.
        </div>
    @endif

    {{-- Notifikasi kendaraan --}}
    @if ($kendaraan['belumDipatroliHariIni']->count() > 0)
        <div class="mt-4 mb-2 p-4 bg-yellow-100 border-l-4 border-yellow-500 text-yellow-800 rounded-lg shadow-md">
            Terdapat {{ $kendaraan['belumDipatroliHariIni']->count() }} kendaraan aktif yang belum dipatroli hari ini:
            <span class="font-semibold">{{ $kendaraan['belumDipatroliHariIni']->pluck('plat_nomor')->join(', ') }}</span>.
        </div>
    @endif
    @if ($kendaraan['laporanTerbuka']->count() > 0)
        <div class="mt-2 mb-2 p-4 bg-red-100 border-l-4 border-red-500 text-red-800 rounded-lg shadow-md">
            Terdapat {{ $kendaraan['laporanTerbuka']->count() }} laporan darurat kendaraan yang <span class="font-semibold">belum selesai ditangani</span>.
        </div>
    @endif

    <div class="grid grid-cols-1 gap-4 mt-4 md:grid-cols-2 lg:grid-cols-3">
        {{-- Card Jumlah Pengguna --}}
        <a href="{{ route('data-pengguna.index') }}">
            <div
                class="flex items-center p-4 bg-white border border-gray-200 rounded-lg shadow hover:shadow-lg transition-shadow duration-300 dark:bg-gray-800 dark:border-gray-700 cursor-pointer">
                <div class="flex items-center justify-center w-12 h-12 rounded-full bg-blue-100">
                    <svg class="w-6 h-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512"
                        fill="currentColor">
                        <path
                            d="M144 0a80 80 0 1 1 0 160A80 80 0 1 1 144 0zM512 0a80 80 0 1 1 0 160A80 80 0 1 1 512 0zM0 298.7C0 239.8 47.8 192 106.7 192l42.7 0c15.9 0 31 3.5 44.6 9.7c-1.3 7.2-1.9 14.7-1.9 22.3c0 38.2 16.8 72.5 43.3 96c-.2 0-.4 0-.7 0L21.3 320C9.6 320 0 310.4 0 298.7zM405.3 320c-.2 0-.4 0-.7 0c26.6-23.5 43.3-57.8 43.3-96c0-7.6-.7-15-1.9-22.3c13.6-6.3 28.7-9.7 44.6-9.7l42.7 0C592.2 192 640 239.8 640 298.7c0 11.8-9.6 21.3-21.3 21.3l-213.3 0zM224 224a96 96 0 1 1 192 0 96 96 0 1 1 -192 0zM128 485.3C128 411.7 187.7 352 261.3 352l117.3 0C452.3 352 512 411.7 512 485.3c0 14.7-11.9 26.7-26.7 26.7l-330.7 0c-14.7 0-26.7-11.9-26.7-26.7z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-base font-semibold text-gray-500">Jumlah Pengguna</h3>
                    <p class="text-2xl font-bold text-blue-600 dark:text-white">{{ $jumlahPengguna }}</p>
                </div>
            </div>
        </a>
        {{-- Card Jumlah Lokasi Patroli --}}
        <a href="{{ route('lokasi-patroli.index') }}">
            <div
                class="flex items-center p-4 bg-white border border-gray-200 rounded-lg shadow hover:shadow-lg transition-shadow duration-300 dark:bg-gray-800 dark:border-gray-700 cursor-pointer">
                <div class="flex items-center justify-center w-12 h-12 rounded-full bg-blue-100">
                    <svg class="w-6 h-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"
                        fill="currentColor">
                        <path
                            d="M215.7 499.2C267 435 384 279.4 384 192C384 86 298 0 192 0S0 86 0 192c0 87.4 117 243 168.3 307.2c12.3 15.3 35.1 15.3 47.4 0zM192 128a64 64 0 1 1 0 128 64 64 0 1 1 0-128z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-base font-semibold text-gray-500">Jumlah Lokasi Patroli</h3>
                    <p class="text-2xl font-bold text-blue-600 dark:text-white">{{ $jumlahLokasiPatroli }}</p>
                </div>
            </div>
        </a>
        {{-- Card Jumlah Patroli --}}
        <a href="{{ route('admin.data-patroli') }}">
            <div
                class="flex items-center p-4 bg-white border border-gray-200 rounded-lg shadow hover:shadow-lg transition-shadow duration-300 dark:bg-gray-800 dark:border-gray-700 cursor-pointer">
                <div class="flex items-center justify-center w-12 h-12 rounded-full bg-blue-100">
                    <svg class="w-6 h-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"
                        fill="currentColor">
                        <path
                            d="M256 0c4.6 0 9.2 1 13.4 2.9L457.7 82.8c22 9.3 38.4 31 38.3 57.2c-.5 99.2-41.3 280.7-213.6 363.2c-16.7 8-36.1 8-52.8 0C57.3 420.7 16.5 239.2 16 140c-.1-26.2 16.3-47.9 38.3-57.2L242.7 2.9C246.8 1 251.4 0 256 0zm0 66.8l0 378.1C394 378 431.1 230.1 432 141.4L256 66.8z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-base font-semibold text-gray-500">Jumlah Patroli</h3>
                    <p class="text-2xl font-bold text-blue-600 dark:text-white">{{ $jumlahPatroli }}</p>
                </div>
            </div>
        </a>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
        {{-- Aktivitas Patroli --}}
        <div class="bg-white p-6 rounded-lg shadow-lg border border-gray-200 dark:bg-gray-800 dark:border-gray-700">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-700 dark:text-white">Aktivitas Patroli</h3>
                <select id="filter" class="border-gray-300 rounded-md shadow-sm text-sm">
                    <option value="harian" selected>Harian</option>
                    <option value="mingguan">Mingguan</option>
                    <option value="bulanan">Bulanan</option>
                </select>
            </div>
            <div class="relative h-72">
                <canvas id="patroliChart" class="w-full h-full"></canvas>
            </div>
        </div>
        {{-- Status Patroli --}}
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-lg border border-gray-200">
            <h2 class="text-lg font-semibold text-gray-700 dark:text-white mb-2">Status Patroli</h2>
            <div class="w-full max-w-xs mx-auto " style="height: 300px;">
                <canvas id="statusDonutChart"></canvas>
            </div>
        </div>
    </div>

    {{-- ================= KENDARAAN ================= --}}
    <h2 class="text-xl font-bold text-gray-800 dark:text-white mt-10 mb-2">Kendaraan</h2>

    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
        <a href="{{ route('kendaraan.index', ['status' => 'aktif']) }}">
            <div class="flex items-center p-4 bg-white border border-gray-200 rounded-lg shadow hover:shadow-lg transition-shadow duration-300 dark:bg-gray-800 dark:border-gray-700">
                <div class="flex items-center justify-center w-12 h-12 rounded-full bg-blue-100">
                    <svg class="w-6 h-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M5 11l1.5-4.5A2 2 0 018.4 5h7.2a2 2 0 011.9 1.5L19 11h.5a1.5 1.5 0 011.5 1.5V17a1 1 0 01-1 1h-1a2 2 0 01-4 0H9a2 2 0 01-4 0H4a1 1 0 01-1-1v-4.5A1.5 1.5 0 014.5 11H5z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-base font-semibold text-gray-500">Kendaraan Aktif</h3>
                    <p class="text-2xl font-bold text-blue-600 dark:text-white">{{ $kendaraan['jumlahAktif'] }}</p>
                </div>
            </div>
        </a>

        <a href="{{ route('riwayat-kendaraan.index', ['tanggal_dari' => now()->toDateString(), 'tanggal_sampai' => now()->toDateString()]) }}">
            <div class="flex items-center p-4 bg-white border border-gray-200 rounded-lg shadow hover:shadow-lg transition-shadow duration-300 dark:bg-gray-800 dark:border-gray-700">
                <div class="flex items-center justify-center w-12 h-12 rounded-full bg-green-100">
                    <svg class="w-6 h-6 text-green-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-4 w-full">
                    <h3 class="text-base font-semibold text-gray-500">Sudah Dipatroli Hari Ini</h3>
                    <p class="text-2xl font-bold text-green-600 dark:text-white">
                        {{ $kendaraan['sudahDipatroli'] }} / {{ $kendaraan['jumlahAktif'] }}
                    </p>
                    @php
                        $persen = $kendaraan['jumlahAktif'] > 0 ? round($kendaraan['sudahDipatroli'] / $kendaraan['jumlahAktif'] * 100) : 0;
                    @endphp
                    <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                        <div class="bg-green-500 h-2 rounded-full" style="width: {{ $persen }}%"></div>
                    </div>
                </div>
            </div>
        </a>

        <a href="{{ route('riwayat-kendaraan.index', ['penanganan' => 'terbuka']) }}">
            <div class="flex items-center p-4 bg-white border border-gray-200 rounded-lg shadow hover:shadow-lg transition-shadow duration-300 dark:bg-gray-800 dark:border-gray-700">
                <div class="flex items-center justify-center w-12 h-12 rounded-full bg-red-100">
                    <svg class="w-6 h-6 text-red-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-base font-semibold text-gray-500">Laporan Darurat Belum Selesai</h3>
                    <p class="text-2xl font-bold text-red-600 dark:text-white">{{ $kendaraan['laporanTerbuka']->count() }}</p>
                </div>
            </div>
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
        <div class="bg-white p-6 rounded-lg shadow-lg border border-gray-200 dark:bg-gray-800 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-700 dark:text-white mb-4">Patroli Kendaraan 7 Hari Terakhir</h3>
            <div class="relative h-72">
                <canvas id="cekKendaraanChart" class="w-full h-full"></canvas>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-lg border border-gray-200">
            <h3 class="text-lg font-semibold text-gray-700 dark:text-white mb-2">Kondisi Kendaraan Terkini</h3>
            <div class="w-full max-w-xs mx-auto" style="height: 300px;">
                <canvas id="kondisiKendaraanChart"></canvas>
            </div>
        </div>
    </div>

    @if ($kendaraan['laporanTerbuka']->count() > 0)
        <div class="bg-white p-6 rounded-lg shadow-lg border border-gray-200 dark:bg-gray-800 dark:border-gray-700 mt-6">
            <h3 class="text-lg font-semibold text-gray-700 dark:text-white mb-4">Laporan Darurat Kendaraan Belum Selesai</h3>
            <div class="overflow-x-auto">
                <table class="table-auto w-full text-sm text-gray-700">
                    <thead class="bg-red-100 text-gray-800 uppercase text-xs">
                        <tr>
                            <th class="px-4 py-2 text-left">Kendaraan</th>
                            <th class="px-4 py-2 text-left">Dilaporkan oleh</th>
                            <th class="px-4 py-2 text-center">Tanggal</th>
                            <th class="px-4 py-2 text-left">Kondisi</th>
                            <th class="px-4 py-2 text-center">Penanganan</th>
                            <th class="px-4 py-2 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($kendaraan['laporanTerbuka'] as $l)
                            <tr>
                                <td class="px-4 py-2 whitespace-nowrap">
                                    <span class="font-semibold">{{ $l->kendaraan->plat_nomor ?? '-' }}</span>
                                    <div class="text-xs text-gray-500">{{ $l->kendaraan->jenis ?? '' }}</div>
                                </td>
                                <td class="px-4 py-2">{{ $l->nama_sopir }}</td>
                                <td class="px-4 py-2 text-center whitespace-nowrap">{{ $l->tanggal->format('d-m-Y') }}</td>
                                <td class="px-4 py-2">
                                    <div class="font-semibold">{{ $l->kategoriKejadian->nama_kategori ?? '-' }}</div>
                                    <div class="text-xs text-gray-500">{{ \Illuminate\Support\Str::limit($l->keterangan ?: '-', 60) }}</div>
                                </td>
                                <td class="px-4 py-2 text-center">
                                    <span class="px-2 py-1 text-xs rounded-full {{ $l->status_penanganan === 'proses' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700' }}">
                                        {{ $l->penanganan_label }}
                                    </span>
                                </td>
                                <td class="px-4 py-2 text-center">
                                    <a href="{{ route('riwayat-kendaraan.show', $l->id) }}"
                                        class="px-2 py-1 text-sm text-white bg-blue-600 hover:bg-blue-700 rounded">Detail</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</x-app-layout>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>

<script>
    // Mengambil elemen canvas untuk grafik aktivitas patroli
    const ctx = document.getElementById('patroliChart').getContext('2d');

    // Menyiapkan data aktivitas patroli berdasarkan filter waktu
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

    // Filter awal yang ditampilkan adalah data harian
    let currentFilter = 'harian';

    // Inisialisasi grafik bar chart untuk menampilkan jumlah aktivitas patroli
    let patroliChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: dataSet[currentFilter].labels,
            datasets: [{
                label: 'Jumlah Aktivitas Patroli',
                data: dataSet[currentFilter].data,
                backgroundColor: 'rgba(59, 130, 246, 0.5)',
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
                    labels: {
                        color: '#111'
                    }
                }
            }
        }
    });

    // Event listener untuk mengubah filter data saat dropdown diganti
    document.getElementById('filter').addEventListener('change', function() {
        const selected = this.value;
        currentFilter = selected;

        // Perbarui data dan label grafik berdasarkan filter yang dipilih
        patroliChart.data.labels = dataSet[selected].labels;
        patroliChart.data.datasets[0].data = dataSet[selected].data;
        patroliChart.update();
    });

    // Inisialisasi grafik doughnut untuk menampilkan persentase status patroli
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
                    'rgba(34,197,94,0.8)',
                    'rgba(239,68,68,0.8)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '50%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        color: '#6B7280'
                    }
                },
                datalabels: {
                    color: '#fff',
                    formatter: (value, context) => {
                        // Hitung total keseluruhan nilai
                        const dataArr = context.chart.data.datasets[0].data;
                        const total = dataArr.reduce((a, b) => a + b, 0);
                        // Hitung persentase untuk masing-masing bagian
                        const percentage = ((value / total) * 100).toFixed(1);
                        return percentage + '%';
                    },
                    font: {
                        weight: 'bold',
                        size: 14,
                    }
                }
            }
        },
        plugins: [ChartDataLabels]
    });
    // ===== Grafik kendaraan =====
    new Chart(document.getElementById('cekKendaraanChart'), {
        type: 'bar',
        data: {
            labels: @json($kendaraan['mingguLabels']),
            datasets: [{
                label: 'Jumlah Patroli Kendaraan',
                data: @json($kendaraan['mingguData']),
                backgroundColor: 'rgba(16, 185, 129, 0.5)',
                borderColor: 'rgba(16, 185, 129, 1)',
                borderWidth: 1,
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
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
                backgroundColor: ['rgba(34,197,94,0.8)', 'rgba(239,68,68,0.8)', 'rgba(156,163,175,0.8)'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '50%',
            plugins: {
                legend: { position: 'bottom', labels: { color: '#6B7280' } },
                datalabels: {
                    color: '#fff',
                    // sembunyikan label untuk irisan 0 supaya tidak tampil "NaN%"
                    display: (ctx) => ctx.dataset.data[ctx.dataIndex] > 0,
                    formatter: (value, ctx) => {
                        const total = ctx.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                        return total ? ((value / total) * 100).toFixed(0) + '%' : '';
                    },
                    font: { weight: 'bold', size: 14 }
                }
            }
        },
        plugins: [ChartDataLabels]
    });
</script>