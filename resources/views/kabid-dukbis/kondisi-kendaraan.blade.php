<x-app-layout :title="'Kondisi Kendaraan'">
    <div class="container mx-auto p-4">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">Kondisi Kendaraan</h2>

        {{-- Ringkasan --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
            <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-md">
                <p class="text-sm text-gray-500">Kendaraan aktif</p>
                <p class="text-2xl font-bold text-gray-800">{{ $totalKendaraan }}</p>
            </div>
            <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-md">
                <p class="text-sm text-gray-500">Perlu tindakan</p>
                <p class="text-2xl font-bold text-red-700">{{ $jumlahPerluTindakan }}</p>
            </div>
            <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-md">
                <p class="text-sm text-gray-500">Belum pernah dicek</p>
                <p class="text-2xl font-bold text-yellow-700">{{ $jumlahBelumPernahDicek }}</p>
            </div>
        </div>

        {{-- Filter --}}
        <div class="border border-gray-200 rounded-lg shadow-md mb-4 bg-white px-4 py-3">
            <form method="GET" action="{{ route('kabid-dukbis.kondisi-kendaraan') }}" class="flex flex-wrap items-center gap-2">
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari plat atau jenis" autocomplete="off"
                    class="border border-gray-300 rounded-lg p-2 text-sm text-gray-900 focus:ring-blue-500 focus:border-blue-500 w-56">
                <select name="status"
                    class="border border-gray-300 rounded-lg p-2 text-sm text-gray-900 focus:ring-blue-500 focus:border-blue-500 w-48">
                    <option value="">Semua kondisi</option>
                    <option value="perlu_tindakan" @selected($status === 'perlu_tindakan')>Perlu Tindakan</option>
                    <option value="baik" @selected($status === 'baik')>Baik</option>
                    <option value="belum" @selected($status === 'belum')>Belum pernah dicek</option>
                </select>
                <button type="submit" class="px-4 py-2 text-sm text-white bg-blue-600 hover:bg-blue-700 rounded-lg">Terapkan</button>
                @if ($status || $search)
                    <a href="{{ route('kabid-dukbis.kondisi-kendaraan') }}" class="px-4 py-2 text-sm text-white bg-gray-400 hover:bg-gray-500 rounded-lg">Reset</a>
                @endif
            </form>
        </div>

        {{-- Tabel --}}
        <div class="overflow-x-auto border border-gray-200 rounded-lg shadow-lg mb-4">
            <table class="table-auto w-full text-sm text-gray-700">
                <thead class="bg-blue-200 text-gray-800 uppercase text-xs">
                    <tr class="text-center">
                        <th class="px-4 py-3">No</th>
                        <th class="px-4 py-3 text-left">Kendaraan</th>
                        <th class="px-4 py-3">Cek Terakhir</th>
                        <th class="px-4 py-3 text-left">Sopir</th>
                        <th class="px-4 py-3">Odometer</th>
                        <th class="px-4 py-3">BBM</th>
                        <th class="px-4 py-3">Kondisi</th>
                        <th class="px-4 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($kendaraan as $k)
                        @php $cek = $k->cekTerakhir; @endphp
                        <tr class="hover:bg-gray-100 text-center {{ $cek?->status_kondisi === 'perlu_tindakan' ? 'bg-red-50' : '' }}">
                            <td class="px-4 py-2">{{ $loop->iteration }}</td>
                            <td class="px-4 py-2 text-left whitespace-nowrap">
                                <span class="font-semibold">{{ $k->plat_nomor }}</span>
                                <div class="text-xs text-gray-500">{{ $k->jenis }}</div>
                            </td>
                            <td class="px-4 py-2 whitespace-nowrap">
                                @if ($cek)
                                    {{ $cek->tanggal_cek->format('d-m-Y') }}
                                    <div class="text-xs text-gray-500">{{ \Illuminate\Support\Str::substr($cek->waktu_cek, 0, 5) }}</div>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-2 text-left">{{ $cek?->user?->nama ?? '-' }}</td>
                            <td class="px-4 py-2 whitespace-nowrap">{{ $cek ? number_format($cek->odometer, 0, ',', '.') . ' km' : '-' }}</td>
                            <td class="px-4 py-2">{{ $cek?->level_bbm_label ?? '-' }}</td>
                            <td class="px-4 py-2">
                                @if (!$cek)
                                    <span class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs">Belum dicek</span>
                                @elseif ($cek->status_kondisi === 'baik')
                                    <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs">Baik</span>
                                @else
                                    <span class="px-2 py-1 bg-red-100 text-red-700 rounded-full text-xs">Perlu Tindakan</span>
                                @endif
                            </td>
                            <td class="px-4 py-2">
                                @if ($cek)
                                    <a href="{{ route('kabid-dukbis.kondisi-kendaraan.detail', $cek->id) }}"
                                        class="inline-flex items-center px-2 py-1 text-sm text-white bg-blue-600 hover:bg-blue-700 rounded whitespace-nowrap">Detail</a>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="px-4 py-4 text-center text-gray-700">Tidak ada kendaraan yang sesuai.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
