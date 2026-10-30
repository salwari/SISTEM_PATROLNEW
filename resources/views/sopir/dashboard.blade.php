<x-app-layout :title="'Dashboard Sopir'">
    @php
        $jumlahBelum = $total - $sudah;
        $persentase = $total > 0 ? ($sudah / $total) * 100 : 0;

        $darurat = $daftarKendaraan->filter(fn ($k) => $k->statusHarian() === 'darurat')->values();
        $belumDiabsen = $daftarKendaraan->filter(fn ($k) => $k->statusHarian() === 'belum')->values();

        // Aktivitas hari ini: semua laporan (aman maupun darurat), terbaru di atas.
        $aktivitas = $daftarKendaraan
            ->flatMap(fn ($k) => $k->absenKendaraan->map(fn ($a) => ['k' => $k, 'a' => $a]))
            ->sortByDesc(fn ($x) => $x['a']->waktu)
            ->values();
    @endphp

    <div class="max-w-7xl mx-auto p-2 space-y-2">
        @if ($darurat->isNotEmpty())
            <div class="mt-6 mb-2 p-4 bg-red-50 border-l-4 border-red-500 text-red-800 rounded-lg shadow-md">
                <strong>Kendaraan darurat ({{ $darurat->count() }})</strong> — belum selesai ditangani admin:
                <ul class="mt-2 space-y-1 text-sm">
                    @foreach ($darurat as $k)
                        @php $d = $k->laporanUtama(); @endphp
                        <li>
                            <span class="font-semibold">{{ $k->plat_nomor }}</span>
                            · {{ $d->kategoriKejadian->nama_kategori ?? '-' }}
                            · dilaporkan {{ $d->nama_sopir }}, {{ $d->tanggal->format('d-m-Y') }} {{ $d->jam }}
                            <span class="px-2 py-0.5 rounded-full text-xs {{ $d->status_penanganan === 'proses' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700' }}">{{ $d->penanganan_label }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if ($total > 0 && $jumlahBelum > 0)
            <div class="mt-6 mb-2 p-4 bg-yellow-100 border-l-4 border-yellow-500 text-yellow-800 rounded-lg shadow-md">
                <strong>Perhatian!</strong> Masih ada <strong>{{ $jumlahBelum }}</strong> kendaraan yang belum dipatroli
                hari ini. Segera lakukan patroli untuk menyelesaikan tugas hari ini.
            </div>
        @endif

        @if ($total == 0)
            <div class="mt-6 mb-2 p-4 bg-blue-100 border-l-4 border-blue-500 text-blue-800 rounded-lg shadow-md">
                <strong>Informasi :</strong> Belum ada kendaraan aktif di cabang kamu. Hubungi admin cabang.
            </div>
        @endif

        <div>
            <h1 class="text-2xl font-semibold text-gray-800 mt-6 mb-2">Selamat datang, {{ Auth::user()->nama }}</h1>
            <p class="text-sm text-gray-600">Berikut adalah ringkasan patroli kendaraan hari ini.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="p-5 bg-white border border-gray-200 rounded-lg shadow-md">
                <h2 class="text-lg font-semibold text-gray-800 mb-2">Status Patroli Hari Ini</h2>
                <p class="text-xs text-gray-500 mb-3">{{ now()->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</p>

                <div class="space-y-1 text-sm text-gray-800">
                    <p class="flex space-x-1">
                        <span class="w-44 text-left">Total kendaraan</span><span>:</span>
                        <strong>{{ $total }}</strong>
                    </p>
                    <p class="flex space-x-1">
                        <span class="w-44 text-left">Sudah dipatroli (aman)</span><span>:</span>
                        <strong class="text-green-600">{{ $jumlahAman }}</strong>
                    </p>
                    <p class="flex space-x-1">
                        <span class="w-44 text-left">Darurat</span><span>:</span>
                        <strong class="text-red-600">{{ $jumlahDarurat }}</strong>
                    </p>
                    <p class="flex space-x-1">
                        <span class="w-44 text-left">Belum dipatroli</span><span>:</span>
                        <strong class="text-yellow-700">{{ $jumlahBelum }}</strong>
                    </p>
                </div>

                <div class="mt-4">
                    <div class="relative h-4 bg-gray-200 rounded-full overflow-hidden">
                        <div class="h-full bg-blue-500 transition-all duration-300 ease-in-out"
                            style="width: {{ $persentase }}%"></div>
                        <div class="absolute inset-0 flex items-center justify-center text-xs font-medium text-gray-800">
                            {{ number_format($persentase, 1) }}%
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Kendaraan darurat dihitung sudah dilaporkan.</p>
                </div>
            </div>

            <div class="p-5 bg-white border border-gray-200 rounded-lg shadow-md max-h-64 overflow-y-auto">
                <h2 class="text-lg font-semibold text-gray-800 mb-3">Aktivitas Hari Ini</h2>
                <ul class="space-y-3 text-sm text-gray-700">
                    @forelse ($aktivitas as $x)
                        @php $absen = $x['a']; $k = $x['k']; $isDarurat = $absen->status === 'darurat'; @endphp
                        <li class="flex items-start space-x-2">
                            <span class="mr-2 flex-shrink-0 font-bold {{ $isDarurat ? 'text-red-600' : 'text-green-600' }}">{{ $isDarurat ? '!' : '✓' }}</span>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm leading-tight break-words">
                                    <span class="font-medium text-gray-800">{{ str_replace(':', '.', $absen->jam) }}</span> -
                                    <span>{{ $k->plat_nomor }} · {{ $k->jenis }}</span>
                                    <span class="text-gray-500">oleh {{ $absen->nama_sopir }}</span>
                                    @if ($isDarurat)
                                        <span class="text-red-600 font-semibold">· Darurat: {{ $absen->kategoriKejadian->nama_kategori ?? '-' }}</span>
                                    @endif
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
            <h2 class="text-lg font-bold text-gray-800 mb-4">Daftar Kendaraan Belum Dipatroli</h2>
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
                                <td colspan="4" class="px-4 py-4 text-center text-gray-500">
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
