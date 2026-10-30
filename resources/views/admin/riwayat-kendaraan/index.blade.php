<x-app-layout :title="'Riwayat Absen Kendaraan'">
    <div class="max-w-7xl mx-auto p-2 space-y-4">
        <h1 class="text-2xl font-semibold text-gray-800 mt-4">Riwayat Absen Kendaraan</h1>

        <form method="GET" action="{{ route('riwayat-kendaraan.index') }}"
            class="bg-white border border-gray-200 rounded-lg shadow-md p-4 space-y-3">
            <div>
                <label for="search" class="block text-xs text-gray-600">Cari plat, jenis, atau nama sopir</label>
                <input type="text" id="search" name="search" value="{{ $search }}"
                    class="mt-1 block w-full rounded-lg border border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                <div class="md:col-span-3">
                    <label for="kendaraan_id" class="block text-xs text-gray-600">Kendaraan</label>
                    <select id="kendaraan_id" name="kendaraan_id"
                        class="mt-1 block w-full rounded-lg border border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Semua</option>
                        @foreach ($daftarKendaraan as $k)
                            <option value="{{ $k->id }}" @selected(request('kendaraan_id') == $k->id)>
                                {{ $k->plat_nomor }} · {{ $k->jenis }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="lokasi" class="block text-xs text-gray-600">Lokasi</label>
                    <select id="lokasi" name="lokasi"
                        class="mt-1 block w-full rounded-lg border border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Semua</option>
                        <option value="dalam_area" @selected(request('lokasi') === 'dalam_area')>Dalam area</option>
                        <option value="tanpa_area" @selected(request('lokasi') === 'tanpa_area')>Area belum diatur</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                <div class="md:col-span-3">
                    <label for="tanggal_dari" class="block text-xs text-gray-600">Dari</label>
                    <input type="date" id="tanggal_dari" name="tanggal_dari" value="{{ request('tanggal_dari') }}"
                        class="mt-1 block w-full rounded-lg border border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div>
                    <label for="tanggal_sampai" class="block text-xs text-gray-600">Sampai</label>
                    <input type="date" id="tanggal_sampai" name="tanggal_sampai" value="{{ request('tanggal_sampai') }}"
                        class="mt-1 block w-full rounded-lg border border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
            </div>

            @error('tanggal_sampai')
                <p class="text-xs text-red-600">{{ $message }}</p>
            @enderror

            <div class="flex justify-end gap-2">
                <a href="{{ route('riwayat-kendaraan.index') }}"
                    class="px-4 py-2 bg-white border border-gray-300 text-gray-800 text-sm rounded-lg font-semibold hover:bg-gray-100">Reset</a>
                <button type="submit"
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg font-semibold">Terapkan</button>
            </div>
        </form>

        <div class="overflow-x-auto border border-gray-200 rounded-lg shadow-lg">
            <table class="table-auto w-full text-sm text-gray-700">
                <thead class="bg-blue-200 text-gray-800 uppercase text-xs">
                    <tr class="text-center">
                        <th class="px-4 py-3">No</th>
                        <th class="px-4 py-3">Tanggal</th>
                        <th class="px-4 py-3 text-left">Kendaraan</th>
                        <th class="px-4 py-3 text-left">Sopir</th>
                        <th class="px-4 py-3">Lokasi</th>
                        <th class="px-4 py-3">Foto</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($riwayat as $a)
                        <tr class="hover:bg-gray-100 text-center">
                            <td class="px-4 py-2">{{ $loop->iteration + ($riwayat->currentPage() - 1) * $riwayat->perPage() }}</td>
                            <td class="px-4 py-2 whitespace-nowrap">
                                {{ $a->tanggal->format('d-m-Y') }}
                                <div class="text-xs text-gray-500">{{ $a->jam }}</div>
                            </td>
                            <td class="px-4 py-2 text-left whitespace-nowrap">
                                <span class="font-semibold">{{ $a->kendaraan->plat_nomor ?? '-' }}</span>
                                <div class="text-xs text-gray-500">{{ $a->kendaraan->jenis ?? '' }}</div>
                            </td>
                            <td class="px-4 py-2 text-left">{{ $a->nama_sopir }}</td>
                            <td class="px-4 py-2">
                                @if ($a->dalam_area === true)
                                    <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs">Dalam area</span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-2">
                                <a href="{{ asset('storage/' . $a->foto) }}" target="_blank" class="text-blue-600 hover:underline">Lihat</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-4 text-center text-gray-700">Belum ada riwayat absen kendaraan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="flex justify-between items-center bg-white px-2 py-3">
            <div class="text-sm text-gray-600">
                Menampilkan {{ $riwayat->firstItem() ?? 0 }} sampai {{ $riwayat->lastItem() ?? 0 }} dari {{ $riwayat->total() }} data
            </div>
            <div>{{ $riwayat->links('components.pagination') }}</div>
        </div>
    </div>
</x-app-layout>