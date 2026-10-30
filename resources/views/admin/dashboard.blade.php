<x-app-layout :title="'Dashboard Admin'">
    <div class="mt-4 border-b border-gray-200 dark:border-gray-700">
        <nav class="flex gap-6" aria-label="Tab Dashboard">
            <button type="button" data-tab="tab-patroli"
                class="dashboard-tab-btn pb-2 text-sm font-semibold border-b-2 border-blue-600 text-blue-600 focus:outline-none">
                Patroli
            </button>
            <button type="button" data-tab="tab-kendaraan"
                class="dashboard-tab-btn pb-2 text-sm font-semibold border-b-2 border-transparent text-gray-500 hover:text-gray-700 focus:outline-none">
                Kendaraan
            </button>
            <button type="button" data-tab="tab-kebersihan"
                class="dashboard-tab-btn pb-2 text-sm font-semibold border-b-2 border-transparent text-gray-500 hover:text-gray-700 focus:outline-none">
                Kebersihan
            </button>
        </nav>
    </div>

    <div id="tab-patroli" class="dashboard-tab-panel">
        @if (count($petugasKurangPatroli) > 0)
            <div class="mt-4 px-3 py-2 text-sm bg-yellow-100 border-l-4 border-yellow-500 text-yellow-800 rounded-md shadow-sm">
                Terdapat {{ count($petugasKurangPatroli) }} petugas yang belum memenuhi target patroli minimal bulan ini.
            </div>
        @endif

        <div class="grid grid-cols-1 gap-3 mt-4 sm:grid-cols-2 lg:grid-cols-3">
            <a href="{{ route('data-pengguna.index') }}">
                <div class="flex items-center p-3 bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-300 dark:bg-gray-800 dark:border-gray-700 cursor-pointer">
                    <div class="flex items-center justify-center w-9 h-9 rounded-full bg-blue-100 shrink-0">
                        <svg class="w-4 h-4 text-blue-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512" fill="currentColor">
                            <path d="M144 0a80 80 0 1 1 0 160A80 80 0 1 1 144 0zM512 0a80 80 0 1 1 0 160A80 80 0 1 1 512 0zM0 298.7C0 239.8 47.8 192 106.7 192l42.7 0c15.9 0 31 3.5 44.6 9.7c-1.3 7.2-1.9 14.7-1.9 22.3c0 38.2 16.8 72.5 43.3 96c-.2 0-.4 0-.7 0L21.3 320C9.6 320 0 310.4 0 298.7zM405.3 320c-.2 0-.4 0-.7 0c26.6-23.5 43.3-57.8 43.3-96c0-7.6-.7-15-1.9-22.3c13.6-6.3 28.7-9.7 44.6-9.7l42.7 0C592.2 192 640 239.8 640 298.7c0 11.8-9.6 21.3-21.3 21.3l-213.3 0zM224 224a96 96 0 1 1 192 0 96 96 0 1 1 -192 0zM128 485.3C128 411.7 187.7 352 261.3 352l117.3 0C452.3 352 512 411.7 512 485.3c0 14.7-11.9 26.7-26.7 26.7l-330.7 0c-14.7 0-26.7-11.9-26.7-26.7z" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-xs font-medium text-gray-500">Jumlah Pengguna</h3>
                        <p class="text-xl font-bold text-blue-600 dark:text-white">{{ $jumlahPengguna }}</p>
                    </div>
                </div>
            </a>
            <a href="{{ route('lokasi-patroli.index') }}">
                <div class="flex items-center p-3 bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-300 dark:bg-gray-800 dark:border-gray-700 cursor-pointer">
                    <div class="flex items-center justify-center w-9 h-9 rounded-full bg-blue-100 shrink-0">
                        <svg class="w-4 h-4 text-blue-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" fill="currentColor">
                            <path d="M215.7 499.2C267 435 384 279.4 384 192C384 86 298 0 192 0S0 86 0 192c0 87.4 117 243 168.3 307.2c12.3 15.3 35.1 15.3 47.4 0zM192 128a64 64 0 1 1 0 128 64 64 0 1 1 0-128z" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-xs font-medium text-gray-500">Jumlah Lokasi Patroli</h3>
                        <p class="text-xl font-bold text-blue-600 dark:text-white">{{ $jumlahLokasiPatroli }}</p>
                    </div>
                </div>
            </a>
            <a href="{{ route('admin.data-patroli') }}">
                <div class="flex items-center p-3 bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-300 dark:bg-gray-800 dark:border-gray-700 cursor-pointer">
                    <div class="flex items-center justify-center w-9 h-9 rounded-full bg-blue-100 shrink-0">
                        <svg class="w-4 h-4 text-blue-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" fill="currentColor">
                            <path d="M256 0c4.6 0 9.2 1 13.4 2.9L457.7 82.8c22 9.3 38.4 31 38.3 57.2c-.5 99.2-41.3 280.7-213.6 363.2c-16.7 8-36.1 8-52.8 0C57.3 420.7 16.5 239.2 16 140c-.1-26.2 16.3-47.9 38.3-57.2L242.7 2.9C246.8 1 251.4 0 256 0zm0 66.8l0 378.1C394 378 431.1 230.1 432 141.4L256 66.8z" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-xs font-medium text-gray-500">Jumlah Patroli</h3>
                        <p class="text-xl font-bold text-blue-600 dark:text-white">{{ $jumlahPatroli }}</p>
                    </div>
                </div>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4 mb-4">
            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 dark:bg-gray-800 dark:border-gray-700">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-sm font-semibold text-gray-700 dark:text-white">Aktivitas Patroli</h3>
                    <select id="filter" class="border-gray-300 rounded-md shadow-sm text-xs py-1">
                        <option value="harian" selected>Harian</option>
                        <option value="mingguan">Mingguan</option>
                        <option value="bulanan">Bulanan</option>
                    </select>
                </div>
                <div class="relative h-56">
                    <canvas id="patroliChart" class="w-full h-full"></canvas>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                <h2 class="text-sm font-semibold text-gray-700 dark:text-white mb-3">Status Patroli</h2>
                <div class="relative w-full max-w-[16rem] mx-auto h-56">
                    <canvas id="statusDonutChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div id="tab-kendaraan" class="dashboard-tab-panel hidden">
        @if ($kendaraan['belumAbsen']->count() > 0)
            <div class="mt-4 px-3 py-2 text-sm bg-yellow-100 border-l-4 border-yellow-500 text-yellow-800 rounded-md shadow-sm">
                Terdapat {{ $kendaraan['belumAbsen']->count() }} kendaraan aktif yang belum diabsen hari ini:
                <span class="font-semibold">{{ $kendaraan['belumAbsen']->pluck('plat_nomor')->join(', ') }}</span>.
            </div>
        @endif

        <div class="grid grid-cols-1 gap-3 mt-4 sm:grid-cols-2 lg:grid-cols-3">
            <a href="{{ route('kendaraan.index', ['status' => 'aktif']) }}">
                <div class="flex items-center p-3 bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-300 dark:bg-gray-800 dark:border-gray-700">
                    <div class="flex items-center justify-center w-9 h-9 rounded-full bg-blue-100 shrink-0">
                        <svg class="w-4 h-4 text-blue-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M5 11l1.5-4.5A2 2 0 018.4 5h7.2a2 2 0 011.9 1.5L19 11h.5a1.5 1.5 0 011.5 1.5V17a1 1 0 01-1 1h-1a2 2 0 01-4 0H9a2 2 0 01-4 0H4a1 1 0 01-1-1v-4.5A1.5 1.5 0 014.5 11H5z" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-xs font-medium text-gray-500">Kendaraan Aktif</h3>
                        <p class="text-xl font-bold text-blue-600 dark:text-white">{{ $kendaraan['jumlahAktif'] }}</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('riwayat-kendaraan.index', ['tanggal_dari' => now()->toDateString(), 'tanggal_sampai' => now()->toDateString()]) }}">
                <div class="flex items-center p-3 bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-300 dark:bg-gray-800 dark:border-gray-700">
                    <div class="flex items-center justify-center w-9 h-9 rounded-full bg-green-100 shrink-0">
                        <svg class="w-4 h-4 text-green-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3 w-full">
                        <h3 class="text-xs font-medium text-gray-500">Sudah Diabsen Hari Ini</h3>
                        <p class="text-xl font-bold text-green-600 dark:text-white">
                            {{ $kendaraan['sudahAbsen'] }} / {{ $kendaraan['jumlahAktif'] }}
                        </p>
                        @php
                            $persen = $kendaraan['jumlahAktif'] > 0 ? round($kendaraan['sudahAbsen'] / $kendaraan['jumlahAktif'] * 100) : 0;
                        @endphp
                        <div class="w-full bg-gray-200 rounded-full h-1.5 mt-1">
                            <div class="bg-green-500 h-1.5 rounded-full" style="width: {{ $persen }}%"></div>
                        </div>
                    </div>
                </div>
            </a>

            <a href="{{ route('kendaraan.index', ['status' => 'aktif']) }}">
                <div class="flex items-center p-3 bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-300 dark:bg-gray-800 dark:border-gray-700">
                    <div class="flex items-center justify-center w-9 h-9 rounded-full bg-red-100 shrink-0">
                        <svg class="w-4 h-4 text-red-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-xs font-medium text-gray-500">Belum Diabsen</h3>
                        <p class="text-xl font-bold text-red-600 dark:text-white">{{ $kendaraan['belumAbsen']->count() }}</p>
                    </div>
                </div>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 dark:bg-gray-800 dark:border-gray-700">
                <h3 class="text-sm font-semibold text-gray-700 dark:text-white mb-3">Absen Kendaraan 7 Hari Terakhir</h3>
                <div class="relative h-56">
                    <canvas id="cekKendaraanChart" class="w-full h-full"></canvas>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                <h3 class="text-sm font-semibold text-gray-700 dark:text-white mb-3">Status Absen Hari Ini</h3>
                <div class="relative w-full max-w-[16rem] mx-auto h-56">
                    <canvas id="kondisiKendaraanChart"></canvas>
                </div>
            </div>
        </div>

        <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 dark:bg-gray-800 dark:border-gray-700 mt-4 mb-4">
            <h3 class="text-sm font-semibold text-gray-700 dark:text-white mb-3">Absen Kendaraan Hari Ini</h3>
            <div class="overflow-x-auto">
                <table class="table-auto w-full text-xs text-gray-700">
                    <thead class="bg-blue-100 text-gray-800 uppercase">
                        <tr>
                            <th class="px-3 py-2 text-left">Kendaraan</th>
                            <th class="px-3 py-2 text-left">Sopir</th>
                            <th class="px-3 py-2 text-center">Jam</th>
                            <th class="px-3 py-2 text-center">Lokasi</th>
                            <th class="px-3 py-2 text-center">Foto</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($kendaraan['absenHariIni'] as $a)
                            <tr>
                                <td class="px-3 py-2 whitespace-nowrap">
                                    <span class="font-semibold">{{ $a->kendaraan->plat_nomor ?? '-' }}</span>
                                    <div class="text-xs text-gray-500">{{ $a->kendaraan->jenis ?? '' }}</div>
                                </td>
                                <td class="px-3 py-2">{{ $a->nama_sopir }}</td>
                                <td class="px-3 py-2 text-center whitespace-nowrap">{{ str_replace(':', '.', $a->jam) }}</td>
                                <td class="px-3 py-2 text-center">
                                    @if ($a->dalam_area === true)
                                        <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs">Dalam area</span>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-3 py-2 text-center">
                                    <a href="{{ asset('storage/' . $a->foto) }}" target="_blank"
                                        class="px-2 py-1 text-xs text-white bg-blue-600 hover:bg-blue-700 rounded">Lihat</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-3 py-4 text-center text-gray-500">Belum ada kendaraan yang diabsen hari ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="tab-kebersihan" class="dashboard-tab-panel hidden">
        @if ($kebersihan['belumDicekHariIni']->count() > 0)
            <div class="mt-4 px-3 py-2 text-sm bg-yellow-100 border-l-4 border-yellow-500 text-yellow-800 rounded-md shadow-sm">
                Terdapat {{ $kebersihan['belumDicekHariIni']->count() }} kamar mandi yang belum dicek hari ini:
                <span class="font-semibold">{{ $kebersihan['belumDicekHariIni']->pluck('nama_kamar_mandi')->join(', ') }}</span>.
            </div>
        @endif
        @if ($kebersihan['perluTindakan']->count() > 0)
            <div class="mt-2 px-3 py-2 text-sm bg-red-100 border-l-4 border-red-500 text-red-800 rounded-md shadow-sm">
                Terdapat {{ $kebersihan['perluTindakan']->count() }} kamar mandi dengan hasil cek terakhir <span class="font-semibold">perlu tindakan</span>.
            </div>
        @endif

        <div class="grid grid-cols-1 gap-3 mt-4 sm:grid-cols-2 lg:grid-cols-3">
            <a href="{{ route('lokasi-kebersihan.index') }}">
                <div class="flex items-center p-3 bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-300 dark:bg-gray-800 dark:border-gray-700">
                    <div class="flex items-center justify-center w-9 h-9 rounded-full bg-blue-100 shrink-0">
                        <svg class="w-4 h-4 text-blue-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M10 2.5c-2.5 3.5-5 6-5 9a5 5 0 0010 0c0-3-2.5-5.5-5-9z" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-xs font-medium text-gray-500">Jumlah Kamar Mandi</h3>
                        <p class="text-xl font-bold text-blue-600 dark:text-white">{{ $kebersihan['jumlahKamarMandi'] }}</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('riwayat-kebersihan.index', ['tanggal_dari' => now()->toDateString(), 'tanggal_sampai' => now()->toDateString()]) }}">
                <div class="flex items-center p-3 bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-300 dark:bg-gray-800 dark:border-gray-700">
                    <div class="flex items-center justify-center w-9 h-9 rounded-full bg-green-100 shrink-0">
                        <svg class="w-4 h-4 text-green-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3 w-full">
                        <h3 class="text-xs font-medium text-gray-500">Sudah Dicek Hari Ini</h3>
                        <p class="text-xl font-bold text-green-600 dark:text-white">
                            {{ $kebersihan['sudahDicek'] }} / {{ $kebersihan['jumlahKamarMandi'] }}
                        </p>
                        @php
                            $persenKebersihan = $kebersihan['jumlahKamarMandi'] > 0 ? round($kebersihan['sudahDicek'] / $kebersihan['jumlahKamarMandi'] * 100) : 0;
                        @endphp
                        <div class="w-full bg-gray-200 rounded-full h-1.5 mt-1">
                            <div class="bg-green-500 h-1.5 rounded-full" style="width: {{ $persenKebersihan }}%"></div>
                        </div>
                    </div>
                </div>
            </a>

            <a href="{{ route('riwayat-kebersihan.index', ['status' => 'perlu_tindakan']) }}">
                <div class="flex items-center p-3 bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-300 dark:bg-gray-800 dark:border-gray-700">
                    <div class="flex items-center justify-center w-9 h-9 rounded-full bg-red-100 shrink-0">
                        <svg class="w-4 h-4 text-red-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-xs font-medium text-gray-500">Perlu Tindakan</h3>
                        <p class="text-xl font-bold text-red-600 dark:text-white">{{ $kebersihan['perluTindakan']->count() }}</p>
                    </div>
                </div>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 dark:bg-gray-800 dark:border-gray-700">
                <h3 class="text-sm font-semibold text-gray-700 dark:text-white mb-3">Cek Kebersihan 7 Hari Terakhir</h3>
                <div class="relative h-56">
                    <canvas id="cekKebersihanChart" class="w-full h-full"></canvas>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                <h3 class="text-sm font-semibold text-gray-700 dark:text-white mb-3">Kondisi Kamar Mandi Terkini</h3>
                <div class="relative w-full max-w-[16rem] mx-auto h-56">
                    <canvas id="kondisiKebersihanChart"></canvas>
                </div>
            </div>
        </div>

        @if ($kebersihan['perluTindakan']->count() > 0)
            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 dark:bg-gray-800 dark:border-gray-700 mt-4 mb-4">
                <h3 class="text-sm font-semibold text-gray-700 dark:text-white mb-3">Kamar Mandi yang Perlu Tindakan</h3>
                <div class="overflow-x-auto">
                    <table class="table-auto w-full text-xs text-gray-700">
                        <thead class="bg-red-100 text-gray-800 uppercase">
                            <tr>
                                <th class="px-3 py-2 text-left">Kamar Mandi</th>
                                <th class="px-3 py-2 text-left">Dicek oleh</th>
                                <th class="px-3 py-2 text-center">Tanggal</th>
                                <th class="px-3 py-2 text-left">Catatan</th>
                                <th class="px-3 py-2 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($kebersihan['perluTindakan'] as $km)
                                <tr>
                                    <td class="px-3 py-2 whitespace-nowrap">
                                        <span class="font-semibold">{{ $km->nama_kamar_mandi }}</span>
                                        <div class="text-xs text-gray-500">{{ $km->lantai }}</div>
                                    </td>
                                    <td class="px-3 py-2">{{ $km->cekTerakhir->user->nama ?? '-' }}</td>
                                    <td class="px-3 py-2 text-center whitespace-nowrap">{{ \Carbon\Carbon::parse($km->cekTerakhir->tanggal)->format('d-m-Y') }}</td>
                                    <td class="px-3 py-2">{{ \Illuminate\Support\Str::limit($km->cekTerakhir->catatan ?: '-', 60) }}</td>
                                    <td class="px-3 py-2 text-center">
                                        <a href="{{ route('riwayat-kebersihan.index', ['status' => 'perlu_tindakan', 'tanggal_dari' => \Carbon\Carbon::parse($km->cekTerakhir->tanggal)->toDateString(), 'tanggal_sampai' => \Carbon\Carbon::parse($km->cekTerakhir->tanggal)->toDateString()]) }}"
                                            class="px-2 py-1 text-xs text-white bg-blue-600 hover:bg-blue-700 rounded">Detail</a>
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
    Chart.defaults.font.size = 11;

    const tabButtons = document.querySelectorAll('.dashboard-tab-btn');
    const tabPanels = document.querySelectorAll('.dashboard-tab-panel');

    tabButtons.forEach(function(btn) {
        btn.addEventListener('click', function() {
            const target = this.dataset.tab;

            tabPanels.forEach(function(panel) {
                panel.classList.toggle('hidden', panel.id !== target);
            });

            tabButtons.forEach(function(b) {
                const aktif = b === btn;
                b.classList.toggle('border-blue-600', aktif);
                b.classList.toggle('text-blue-600', aktif);
                b.classList.toggle('border-transparent', !aktif);
                b.classList.toggle('text-gray-500', !aktif);
                b.classList.toggle('hover:text-gray-700', !aktif);
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
                backgroundColor: 'rgba(59, 130, 246, 0.5)',
                borderColor: 'rgba(59, 130, 246, 1)',
                borderWidth: 1,
                borderRadius: 4,
                maxBarThickness: 32
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
                        color: '#111',
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
                    'rgba(34,197,94,0.8)',
                    'rgba(239,68,68,0.8)'
                ],
                borderWidth: 1
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
                        color: '#6B7280',
                        boxWidth: 12
                    }
                },
                datalabels: {
                    color: '#fff',
                    formatter: (value, context) => {
                        const dataArr = context.chart.data.datasets[0].data;
                        const total = dataArr.reduce((a, b) => a + b, 0);
                        const percentage = ((value / total) * 100).toFixed(1);
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
                label: 'Jumlah Absen Kendaraan',
                data: @json($kendaraan['mingguData']),
                backgroundColor: 'rgba(16, 185, 129, 0.5)',
                borderColor: 'rgba(16, 185, 129, 1)',
                borderWidth: 1,
                borderRadius: 4,
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
            labels: ['Sudah Diabsen', 'Belum Diabsen'],
            datasets: [{
                data: [
                    {{ $kendaraan['sudahAbsen'] }},
                    {{ $kendaraan['belumAbsen']->count() }}
                ],
                backgroundColor: ['rgba(34,197,94,0.8)', 'rgba(239,68,68,0.8)'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '55%',
            plugins: {
                legend: { position: 'bottom', labels: { color: '#6B7280', boxWidth: 12 } },
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
                backgroundColor: 'rgba(14, 165, 233, 0.5)',
                borderColor: 'rgba(14, 165, 233, 1)',
                borderWidth: 1,
                borderRadius: 4,
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
                backgroundColor: ['rgba(34,197,94,0.8)', 'rgba(239,68,68,0.8)', 'rgba(156,163,175,0.8)'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '55%',
            plugins: {
                legend: { position: 'bottom', labels: { color: '#6B7280', boxWidth: 12 } },
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