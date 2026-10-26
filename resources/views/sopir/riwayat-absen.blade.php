<x-app-layout :title="'Riwayat Absen'">
    <div class="max-w-4xl mx-auto p-2 space-y-4">
        <div class="flex gap-6 border-b border-gray-200 text-sm mt-4">
            <a href="{{ route('sopir.dashboard') }}" class="pb-2 text-gray-500 hover:text-gray-800">Absen</a>
            <a href="{{ route('sopir.riwayat-absen') }}" class="pb-2 font-semibold text-gray-900 border-b-2 border-blue-600">Riwayat absen</a>
        </div>

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
                            <td class="px-4 py-2 whitespace-nowrap">{{ $a->tanggal->format('d-m-Y') }}
                                <div class="text-xs text-gray-500">{{ $a->jam }}</div></td>
                            <td class="px-4 py-2 text-left whitespace-nowrap">
                                <span class="font-semibold">{{ $a->kendaraan->plat_nomor ?? '-' }}</span>
                                <div class="text-xs text-gray-500">{{ $a->kendaraan->jenis ?? '' }}</div></td>
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
                        <tr><td colspan="6" class="px-4 py-4 text-center text-gray-700">Belum ada riwayat absen.</td></tr>
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
