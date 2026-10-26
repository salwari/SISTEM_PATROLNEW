<x-app-layout :title="'Absen Kendaraan'">
    <div class="container mx-auto p-4">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">Absen Kendaraan Sopir</h2>

        <div class="border border-gray-200 rounded-lg shadow-md mb-4 bg-white px-4 py-3">
            <form method="GET" action="{{ route('kabid-dukbis.absen-kendaraan') }}" class="flex items-center gap-2">
                <input type="date" name="tanggal" value="{{ $tanggal }}" max="{{ now()->toDateString() }}"
                    class="border border-gray-300 rounded-lg p-2 text-sm text-gray-900 focus:ring-blue-500 focus:border-blue-500">
                <button type="submit" class="px-4 py-2 text-sm text-white bg-blue-600 hover:bg-blue-700 rounded-lg">Terapkan</button>
            </form>
            <p class="mt-3 text-sm text-gray-700"><strong>{{ $sudah }}</strong> dari {{ $total }} kendaraan sudah diabsen.</p>
        </div>

        <div class="overflow-x-auto border border-gray-200 rounded-lg shadow-lg">
            <table class="table-auto w-full text-sm text-gray-700">
                <thead class="bg-blue-200 text-gray-800 uppercase text-xs">
                    <tr class="text-center">
                        <th class="px-4 py-3">No</th>
                        <th class="px-4 py-3 text-left">Kendaraan</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 text-left">Sopir</th>
                        <th class="px-4 py-3">Jam</th>
                        <th class="px-4 py-3">Lokasi</th>
                        <th class="px-4 py-3">Foto</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($kendaraan as $k)
                        @php $a = $k->absenKendaraan->first(); @endphp
                        <tr class="hover:bg-gray-100 text-center">
                            <td class="px-4 py-2">{{ $loop->iteration }}</td>
                            <td class="px-4 py-2 text-left whitespace-nowrap">
                                <span class="font-semibold">{{ $k->plat_nomor }}</span>
                                <div class="text-xs text-gray-500">{{ $k->jenis }}</div></td>
                            <td class="px-4 py-2">
                                @if ($a)
                                    <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs">Sudah absen</span>
                                @else
                                    <span class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs">Belum absen</span>
                                @endif
                            </td>
                            <td class="px-4 py-2 text-left">{{ $a->nama_sopir ?? '-' }}</td>
                            <td class="px-4 py-2">{{ $a->jam ?? '-' }}</td>
                            <td class="px-4 py-2">
                                @if ($a)
                                    <a href="https://www.google.com/maps?q={{ $a->latitude }},{{ $a->longitude }}" target="_blank"
                                        class="text-blue-600 hover:underline">Peta</a>
                                    @if ($a->dalam_area === true)<div class="text-xs text-green-700">Dalam area</div>@endif
                                @else - @endif
                            </td>
                            <td class="px-4 py-2">
                                @if ($a)
                                    <a href="{{ asset('storage/' . $a->foto) }}" target="_blank" class="text-blue-600 hover:underline">Lihat</a>
                                @else - @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-4 py-4 text-center text-gray-700">Belum ada kendaraan aktif.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
