<x-app-layout :title="'Dashboard Sopir'">
    @php
        $jumlahBelum = $total - $sudah;
        $persentase = $total > 0 ? ($sudah / $total) * 100 : 0;
        $sudahDiabsen = $daftarKendaraan
            ->filter(fn($k) => $k->absenKendaraan->isNotEmpty())
            ->sortByDesc(fn($k) => $k->absenKendaraan->first()->jam);
        $belumDiabsen = $daftarKendaraan->filter(fn($k) => $k->absenKendaraan->isEmpty())->values();
    @endphp

    <div class="max-w-7xl mx-auto p-2 space-y-2">
        @if ($total > 0 && $jumlahBelum > 0)
            <div class="mt-6 mb-2 p-4 bg-yellow-100 border-l-4 border-yellow-500 text-yellow-800 rounded-lg shadow-md">
                <strong>Perhatian!</strong> Masih ada <strong>{{ $jumlahBelum }}</strong> kendaraan yang belum diabsen
                hari ini. Segera lakukan absen untuk menyelesaikan tugas hari ini.
            </div>
        @endif

        @if ($total == 0)
            <div class="mt-6 mb-2 p-4 bg-blue-100 border-l-4 border-blue-500 text-blue-800 rounded-lg shadow-md">
                <strong>Informasi :</strong> Belum ada kendaraan aktif di cabang kamu. Hubungi admin cabang.
            </div>
        @endif

        <div>
            <h1 class="text-2xl font-semibold text-gray-800 mt-6 mb-2">Selamat datang, {{ Auth::user()->nama }}</h1>
            <p class="text-sm text-gray-600">Berikut adalah ringkasan absen kendaraan hari ini.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="p-5 bg-white border border-gray-200 rounded-lg shadow-md">
                <h2 class="text-lg font-semibold text-gray-800 mb-2">Status Absen Hari Ini</h2>
                <p class="text-xs text-gray-500 mb-3">{{ now()->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</p>

                <div class="space-y-1 text-sm text-gray-800">
                    <p class="flex space-x-1">
                        <span class="w-44 text-left">Total kendaraan</span>
                        <span>:</span>
                        <strong>{{ $total }}</strong>
                    </p>
                    <p class="flex space-x-1">
                        <span class="w-44 text-left">Kendaraan sudah diabsen</span>
                        <span>:</span>
                        <strong class="text-green-600">{{ $sudah }}</strong>
                    </p>
                    <p class="flex space-x-1">
                        <span class="w-44 text-left">Kendaraan belum diabsen</span>
                        <span>:</span>
                        <strong class="text-red-700">{{ $jumlahBelum }}</strong>
                    </p>
                </div>

                <div class="mt-4">
                    <div class="relative h-4 bg-gray-200 rounded-full overflow-hidden">
                        <div class="h-full bg-blue-500 transition-all duration-300 ease-in-out"
                            style="width: {{ $persentase }}%"></div>
                        <div
                            class="absolute inset-0 flex items-center justify-center text-xs font-medium text-gray-800">
                            {{ number_format($persentase, 1) }}%
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-5 bg-white border border-gray-200 rounded-lg shadow-md max-h-64 overflow-y-auto">
                <h2 class="text-lg font-semibold text-gray-800 mb-3">Aktivitas Terakhir</h2>
                <ul class="space-y-3 text-sm text-gray-700">
                    @forelse ($sudahDiabsen as $k)
                        @php $absen = $k->absenKendaraan->first(); @endphp
                        <li class="flex items-start space-x-2">
                            <svg class="w-5 h-5 mr-2 text-green-600 flex-shrink-0" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 448 512" fill="currentColor">
                                <path
                                    d="M64 32C28.7 32 0 60.7 0 96L0 416c0 35.3 28.7 64 64 64l320 0c35.3 0 64-28.7 64-64l0-320c0-35.3-28.7-64-64-64L64 32zM337 209L209 337c-9.4 9.4-24.6 9.4-33.9 0l-64-64c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l47 47L303 175c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9z" />
                            </svg>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm leading-tight break-words">
                                    <span class="font-medium text-gray-800">{{ str_replace(':', '.', $absen->jam) }}</span> -
                                    <span>{{ $k->plat_nomor }} · {{ $k->jenis }}</span>
                                    <span class="text-gray-500">oleh {{ $absen->nama_sopir }}</span>
                                </p>
                            </div>
                        </li>
                    @empty
                        <li class="text-gray-500">Belum ada aktivitas hari ini.</li>
                    @endforelse
                </ul>
            </div>
        </div>

        <div class="mt-12 bg-white p-4">
            <h2 class="text-lg font-bold text-gray-800 mb-4">Daftar Kendaraan Belum Diabsen</h2>
            <div class="overflow-x-auto rounded-lg border border-gray-200">
                <table class="table-auto w-full text-sm text-gray-700">
                    <thead class="bg-blue-200 text-gray-800 uppercase text-xs">
                        <tr class="text-center">
                            <th class="px-4 py-3">No</th>
                            <th class="px-4 py-3 text-left">Plat Nomor</th>
                            <th class="px-4 py-3 text-left">Jenis</th>
                            <th class="px-4 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($belumDiabsen as $index => $k)
                            <tr class="hover:bg-gray-100 text-center">
                                <td class="px-4 py-2">{{ $index + 1 }}</td>
                                <td class="px-4 py-2 text-left font-semibold">{{ $k->plat_nomor }}</td>
                                <td class="px-4 py-2 text-left">{{ $k->jenis }}</td>
                                <td class="px-4 py-2">
                                    <a href="{{ route('sopir.scan-qr') }}"
                                        class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-semibold">Scan</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-4 text-center text-gray-500">Semua kendaraan sudah
                                    diabsen.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>