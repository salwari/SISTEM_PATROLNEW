<x-app-layout :title="'Riwayat Kendaraan'">
    <div class="container mx-auto p-4">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">Riwayat Cek Kendaraan</h2>

        {{-- Filter --}}
        <div class="border border-gray-200 rounded-lg shadow-md mb-4 bg-white px-4 py-3">
            <form action="{{ route('riwayat-kendaraan.index') }}" method="GET"
                class="grid grid-cols-1 md:grid-cols-6 gap-2 items-end">
                <div class="md:col-span-2">
                    <label class="block text-xs text-gray-600 mb-1" for="search">Cari plat, jenis, atau nama sopir</label>
                    <input type="text" id="search" name="search" value="{{ $search }}" autocomplete="off"
                        class="block w-full p-2 text-sm text-gray-900 bg-white border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs text-gray-600 mb-1" for="kendaraan_id">Kendaraan</label>
                    <select id="kendaraan_id" name="kendaraan_id"
                        class="block w-full p-2 text-sm border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Semua</option>
                        @foreach ($daftarKendaraan as $k)
                            <option value="{{ $k->id }}" @selected(request('kendaraan_id') == $k->id)>{{ $k->plat_nomor }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs text-gray-600 mb-1" for="status">Kondisi</label>
                    <select id="status" name="status"
                        class="block w-full p-2 text-sm border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Semua</option>
                        <option value="baik" @selected(request('status') === 'baik')>Baik</option>
                        <option value="perlu_tindakan" @selected(request('status') === 'perlu_tindakan')>Perlu Tindakan</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs text-gray-600 mb-1" for="tanggal_dari">Dari</label>
                    <input type="date" id="tanggal_dari" name="tanggal_dari" value="{{ request('tanggal_dari') }}"
                        class="block w-full p-2 text-sm border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs text-gray-600 mb-1" for="tanggal_sampai">Sampai</label>
                    <input type="date" id="tanggal_sampai" name="tanggal_sampai" value="{{ request('tanggal_sampai') }}"
                        class="block w-full p-2 text-sm border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    @error('tanggal_sampai')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="md:col-span-6 flex gap-2 justify-end">
                    @if (request()->hasAny(['search', 'kendaraan_id', 'status', 'tanggal_dari', 'tanggal_sampai']))
                        <a href="{{ route('riwayat-kendaraan.index') }}"
                            class="px-4 py-2 text-sm text-white bg-gray-400 hover:bg-gray-500 rounded-lg">Reset</a>
                    @endif
                    <button type="submit" class="px-4 py-2 text-sm text-white bg-blue-600 hover:bg-blue-700 rounded-lg">
                        Terapkan
                    </button>
                </div>
            </form>
        </div>

        {{-- Tabel --}}
        <div class="overflow-x-auto border border-gray-200 rounded-lg shadow-lg mb-4">
            <table class="table-auto w-full text-sm text-gray-700">
                <thead class="bg-blue-200 text-gray-800 uppercase text-xs">
                    <tr class="text-center">
                        <th class="px-4 py-3">No</th>
                        <th class="px-4 py-3">Tanggal</th>
                        <th class="px-4 py-3">Kendaraan</th>
                        <th class="px-4 py-3">Sopir</th>
                        <th class="px-4 py-3">Odometer</th>
                        <th class="px-4 py-3">BBM</th>
                        <th class="px-4 py-3">Kondisi</th>
                        <th class="px-4 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($riwayat as $data)
                        <tr class="hover:bg-gray-100 text-center">
                            <td class="px-4 py-2">{{ $loop->iteration + ($riwayat->currentPage() - 1) * $riwayat->perPage() }}</td>
                            <td class="px-4 py-2 whitespace-nowrap">
                                {{ $data->tanggal_cek->format('d-m-Y') }}
                                <div class="text-xs text-gray-500">{{ \Illuminate\Support\Str::substr($data->waktu_cek, 0, 5) }}</div>
                            </td>
                            <td class="px-4 py-2 text-left whitespace-nowrap">
                                <span class="font-semibold">{{ $data->kendaraan->plat_nomor ?? '-' }}</span>
                                <div class="text-xs text-gray-500">{{ $data->kendaraan->jenis ?? '' }}</div>
                            </td>
                            <td class="px-4 py-2 text-left">{{ $data->user->nama ?? '-' }}</td>
                            <td class="px-4 py-2 whitespace-nowrap">{{ number_format($data->odometer, 0, ',', '.') }} km</td>
                            <td class="px-4 py-2">{{ $data->level_bbm_label }}</td>
                            <td class="px-4 py-2">
                                @if ($data->status_kondisi === 'baik')
                                    <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs">Baik</span>
                                @else
                                    <span class="px-2 py-1 bg-red-100 text-red-700 rounded-full text-xs">Perlu Tindakan</span>
                                @endif
                            </td>
                            <td class="px-4 py-2">
                                <a href="{{ route('riwayat-kendaraan.show', $data->id) }}"
                                    class="inline-flex items-center px-2 py-1 text-sm text-white bg-blue-600 hover:bg-blue-700 rounded whitespace-nowrap">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-4 text-center text-gray-700">Belum ada riwayat cek kendaraan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="flex justify-between items-center bg-white px-2 py-3">
            <div class="text-sm text-gray-600">
                Menampilkan {{ $riwayat->firstItem() ?? 0 }} sampai {{ $riwayat->lastItem() ?? 0 }} dari
                {{ $riwayat->total() }} data
            </div>
            <div>{{ $riwayat->links('components.pagination') }}</div>
        </div>
    </div>
</x-app-layout>
