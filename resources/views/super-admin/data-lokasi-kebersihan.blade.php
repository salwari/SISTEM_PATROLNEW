<x-app-layout :title="'Data Lokasi Kebersihan'">
    <div class="container mx-auto p-4">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">Daftar Lokasi Kebersihan</h2>

        <div class="overflow-x-auto border border-gray-200 rounded-lg shadow-md mb-4">
            <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 px-4 py-3 bg-white">
                <form action="{{ route('super-admin.data-lokasi-kebersihan') }}" method="GET"
                    class="flex flex-col md:flex-row items-start md:items-center gap-2 w-full md:w-auto">

                    <select name="cabang_id"
                        class="border border-gray-300 rounded-lg p-2 text-sm text-gray-900 focus:ring-blue-500 focus:border-blue-500 w-full md:w-48">
                        <option value="" {{ request('cabang_id') == '' ? 'selected' : '' }}>Semua Cabang</option>
                        @foreach ($cabangs as $cabang)
                            <option value="{{ $cabang->id }}"
                                {{ request('cabang_id') == $cabang->id ? 'selected' : '' }}>
                                {{ $cabang->nama_cabang }}
                            </option>
                        @endforeach
                    </select>

                    <div class="flex w-full md:w-96">
                        <div class="relative flex-grow">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg class="w-5 h-5 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <input type="text" id="search" name="search" value="{{ request('search') }}"
                                placeholder="Cari lokasi kebersihan..." autocomplete="off"
                                class="block w-full p-2 pl-10 text-sm text-gray-900 bg-white border border-gray-300 rounded-l-lg focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                        <button type="submit"
                            class="px-4 py-2 text-sm text-white bg-blue-600 hover:bg-blue-700 rounded-r-lg border-l-0 border border-blue-600">
                            Cari
                        </button>
                    </div>

                    @if (request('search') || request('cabang_id'))
                        <a href="{{ route('super-admin.data-lokasi-kebersihan') }}"
                            class="px-4 py-2 text-sm text-white bg-gray-400 hover:bg-gray-500 rounded-lg">
                            Kembali
                        </a>
                    @endif
                </form>
            </div>
        </div>

        <div class="overflow-x-auto border border-gray-200 rounded-lg shadow-lg mb-4">
            <table class="table-auto w-full text-sm text-gray-700">
                <thead class="bg-blue-200 text-gray-800 uppercase text-xs">
                    <tr class="text-center">
                        <th class="px-4 py-3">No</th>
                        <th class="px-4 py-3">Nama Kamar Mandi</th>
                        <th class="px-4 py-3">Lantai</th>
                        <th class="px-4 py-3">Cabang</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($lokasiKebersihan as $lokasi)
                        <tr class="hover:bg-gray-100 text-center">
                            <td class="px-4 py-2">
                                {{ $loop->iteration + ($lokasiKebersihan->currentPage() - 1) * $lokasiKebersihan->perPage() }}
                            </td>
                            <td class="px-4 py-2">{{ $lokasi->nama_kamar_mandi }}</td>
                            <td class="px-4 py-2">{{ $lokasi->lantai ?? '-' }}</td>
                            <td class="px-4 py-2">{{ $lokasi->cabang->nama_cabang ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-4 text-center text-gray-700">
                                Belum ada data lokasi kebersihan pada cabang ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="flex justify-between items-center bg-white">
            <div class="text-sm text-gray-600">
                Menampilkan {{ $lokasiKebersihan->firstItem() ?? 0 }} sampai {{ $lokasiKebersihan->lastItem() ?? 0 }} dari
                {{ $lokasiKebersihan->total() }} data
            </div>
            <div>
                {{ $lokasiKebersihan->links('components.pagination') }}
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    document.querySelector('select[name="cabang_id"]').addEventListener('change', function() {
        this.form.submit();
    });
</script>