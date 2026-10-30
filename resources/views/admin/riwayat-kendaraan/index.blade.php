<x-app-layout :title="'Riwayat Kendaraan'">
    <div class="container mx-auto p-4">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">Riwayat Patroli Kendaraan</h2>

        @php
            $status = request('status');
            $pen = request('penanganan');
            $tabAktif = match (true) {
                $status === 'darurat' && $pen === 'terbuka' => 'perlu',
                $status === 'darurat' && $pen === 'selesai' => 'selesai',
                $status === 'aman' => 'aman',
                default => 'semua',
            };
            $bawa = array_filter(['search' => request('search'), 'kendaraan_id' => request('kendaraan_id'),
                'tanggal_dari' => request('tanggal_dari'), 'tanggal_sampai' => request('tanggal_sampai')]);
            $tabs = [
                'semua' => ['Semua', [], $hitung['semua']],
                'perlu' => ['Perlu Ditangani', ['status' => 'darurat', 'penanganan' => 'terbuka'], $hitung['perlu']],
                'selesai' => ['Darurat Selesai', ['status' => 'darurat', 'penanganan' => 'selesai'], $hitung['selesai']],
                'aman' => ['Aman', ['status' => 'aman'], $hitung['aman']],
            ];
        @endphp

        {{-- Tab cepat --}}
        <div class="flex flex-wrap gap-2 mb-3">
            @foreach ($tabs as $kunci => [$label, $param, $jumlah])
                @php
                    $aktif = $tabAktif === $kunci;
                    $warna = $aktif
                        ? ($kunci === 'perlu' ? 'bg-red-600 text-white' : 'bg-blue-600 text-white')
                        : 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-100';
                @endphp
                <a href="{{ route('riwayat-kendaraan.index', array_merge($bawa, $param)) }}"
                    class="px-4 py-2 text-sm font-semibold rounded-lg {{ $warna }}">
                    {{ $label }}
                    <span class="ml-1 text-xs opacity-80">({{ $jumlah }})</span>
                </a>
            @endforeach
        </div>

        {{-- Cari --}}
        <div class="border border-gray-200 rounded-lg shadow-md mb-4 bg-white px-4 py-3">
            <form action="{{ route('riwayat-kendaraan.index') }}" method="GET" class="space-y-3">
                @if ($status) <input type="hidden" name="status" value="{{ $status }}"> @endif
                @if ($pen) <input type="hidden" name="penanganan" value="{{ $pen }}"> @endif

                <div class="flex gap-2">
                    <input type="text" name="search" value="{{ $search }}" autocomplete="off"
                        placeholder="Cari plat, jenis kendaraan, nama sopir, atau keterangan..."
                        class="flex-1 p-2 text-sm text-gray-900 bg-white border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    <button type="submit" class="px-4 py-2 text-sm text-white bg-blue-600 hover:bg-blue-700 rounded-lg">Cari</button>
                    @if (request()->hasAny(['search', 'kendaraan_id', 'status', 'penanganan', 'tanggal_dari', 'tanggal_sampai']))
                        <a href="{{ route('riwayat-kendaraan.index') }}"
                            class="px-4 py-2 text-sm text-white bg-gray-400 hover:bg-gray-500 rounded-lg">Reset</a>
                    @endif
                </div>

                <details {{ request()->hasAny(['kendaraan_id', 'tanggal_dari', 'tanggal_sampai']) ? 'open' : '' }}>
                    <summary class="text-sm text-blue-700 cursor-pointer">Filter lainnya (kendaraan, tanggal)</summary>
                    <div class="grid gap-3 mt-3" style="grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));">
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
                            <label class="block text-xs text-gray-600 mb-1" for="tanggal_dari">Dari tanggal</label>
                            <input type="date" id="tanggal_dari" name="tanggal_dari" value="{{ request('tanggal_dari') }}"
                                class="block w-full p-2 text-sm border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600 mb-1" for="tanggal_sampai">Sampai tanggal</label>
                            <input type="date" id="tanggal_sampai" name="tanggal_sampai" value="{{ request('tanggal_sampai') }}"
                                class="block w-full p-2 text-sm border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            @error('tanggal_sampai')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </details>
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
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Kondisi</th>
                        <th class="px-4 py-3">Penanganan</th>
                        <th class="px-4 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($riwayat as $data)
                        <tr class="hover:bg-gray-100 text-center">
                            <td class="px-4 py-2">{{ $loop->iteration + ($riwayat->currentPage() - 1) * $riwayat->perPage() }}</td>
                            <td class="px-4 py-2 whitespace-nowrap">
                                {{ $data->tanggal->format('d-m-Y') }}
                                <div class="text-xs text-gray-500">{{ $data->jam }}</div>
                            </td>
                            <td class="px-4 py-2 text-left whitespace-nowrap">
                                <span class="font-semibold">{{ $data->kendaraan->plat_nomor ?? '-' }}</span>
                                <div class="text-xs text-gray-500">{{ $data->kendaraan->jenis ?? '' }}</div>
                            </td>
                            <td class="px-4 py-2 text-left">{{ $data->nama_sopir }}</td>
                            <td class="px-4 py-2">
                                @if ($data->status === 'darurat')
                                    <span class="px-2 py-1 bg-red-100 text-red-700 rounded-full text-xs">Darurat</span>
                                @else
                                    <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs">Aman</span>
                                @endif
                            </td>
                            <td class="px-4 py-2 text-left">{{ $data->status === 'darurat' ? ($data->kategoriKejadian->nama_kategori ?? '-') : '-' }}</td>
                            <td class="px-4 py-2">
                                @if ($data->status === 'darurat')
                                    @php $warna = ['belum' => 'bg-red-100 text-red-700', 'proses' => 'bg-yellow-100 text-yellow-700', 'selesai' => 'bg-green-100 text-green-700']; @endphp
                                    <span class="px-2 py-1 rounded-full text-xs {{ $warna[$data->status_penanganan] ?? 'bg-gray-100 text-gray-600' }}">{{ $data->penanganan_label }}</span>
                                @else
                                    -
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
                            <td colspan="8" class="px-4 py-4 text-center text-gray-700">Belum ada riwayat patroli kendaraan.</td>
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
