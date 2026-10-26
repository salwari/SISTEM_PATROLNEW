<x-app-layout :title="'Data Lokasi Kebersihan'">
    <div class="max-w-7xl mx-auto p-4 space-y-6">

        {{-- Header Halaman --}}
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Daftar Lokasi Kebersihan</h1>
            <p class="text-sm text-gray-500">
                Master data titik toilet di cabang {{ Auth::user()->cabang->nama_cabang ?? '' }} beserta status QR Code fisik
            </p>
        </div>

        {{-- Search Box --}}
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-4">
            <form action="{{ route('kabid-dukbis.data-lokasi-kebersihan') }}" method="GET"
                class="flex flex-col md:flex-row md:items-center gap-3">
                <div class="relative flex w-full md:w-1/2">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                clip-rule="evenodd" />
                        </svg>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari kode, nama, atau lantai toilet..." autocomplete="off"
                        class="block w-full p-2 pl-10 text-sm text-gray-900 bg-white border border-gray-300 rounded-l-lg focus:ring-blue-500 focus:border-blue-500">
                    <button type="submit"
                        class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-r-lg transition">
                        Cari
                    </button>
                </div>

                @if (request('search'))
                    <a href="{{ route('kabid-dukbis.data-lokasi-kebersihan') }}"
                        class="px-4 py-2 text-xs text-gray-600 hover:text-gray-900 hover:underline">
                        Reset Pencarian
                    </a>
                @endif
            </form>
        </div>

        {{-- Tabel Lokasi Kamar Mandi --}}
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-x-auto">
            <table class="table-auto w-full text-sm text-gray-700">
                <thead class="bg-blue-100 text-gray-800 uppercase text-xs font-semibold">
                    <tr class="text-center">
                        <th class="px-4 py-3">No</th>
                        <th class="px-4 py-3 text-left">Kode</th>
                        <th class="px-4 py-3 text-left">Nama Kamar Mandi</th>
                        <th class="px-4 py-3">Lantai</th>
                        <th class="px-4 py-3 text-left">Deskripsi Lokasi</th>
                        <th class="px-4 py-3">QR Code</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($lokasiKebersihan as $index => $km)
                        <tr class="hover:bg-gray-50 text-center transition">
                            <td class="px-4 py-3 font-medium">
                                {{ $lokasiKebersihan->firstItem() + $index }}
                            </td>
                            <td class="px-4 py-3 text-left font-mono font-bold text-blue-700 text-xs">
                                {{ $km->kode_kamar_mandi }}
                            </td>
                            <td class="px-4 py-3 text-left font-medium text-gray-900">
                                {{ $km->nama_kamar_mandi }}
                            </td>
                            <td class="px-4 py-3">
                                <span class="px-2.5 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                    {{ $km->lantai }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-left text-xs text-gray-600">
                                {{ $km->deskripsi ?? '-' }}
                            </td>
                            <td class="px-4 py-3">
                                @if ($km->qr_code)
                                    <img src="{{ asset('storage/' . $km->qr_code) }}" alt="QR Code"
                                        class="w-12 h-12 mx-auto rounded-lg shadow-sm cursor-pointer border border-gray-200 hover:scale-105 transition"
                                        onclick="showQrModal('{{ asset('storage/' . $km->qr_code) }}', '{{ $km->nama_kamar_mandi }} ({{ $km->kode_kamar_mandi }})')">
                                @else
                                    <span class="text-gray-400 text-xs italic">Belum Ada</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                                Belum ada data kamar mandi yang terdaftar di cabang ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="flex flex-col md:flex-row justify-between items-center gap-3 bg-white p-3 rounded-xl border border-gray-200">
            <div class="text-xs text-gray-600">
                Menampilkan {{ $lokasiKebersihan->firstItem() ?? 0 }} sampai {{ $lokasiKebersihan->lastItem() ?? 0 }} dari {{ $lokasiKebersihan->total() }} lokasi
            </div>
            <div>
                {{ $lokasiKebersihan->links('components.pagination') }}
            </div>
        </div>

    </div>

    {{-- MODAL PREVIEW QR CODE --}}
    <div id="qrModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-60 flex items-center justify-center p-4">
        <div class="relative bg-white rounded-xl shadow-xl max-w-sm w-full overflow-hidden p-5 text-center">
            <button onclick="closeQrModal()" class="absolute top-3 right-3 text-gray-400 hover:text-gray-900 p-1">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
            </button>
            <h3 id="qrModalTitle" class="text-sm font-bold text-gray-800 mb-4 mt-1"></h3>
            <div class="bg-gray-50 p-4 rounded-xl border border-gray-100 inline-block shadow-inner">
                <img id="qrModalImg" src="" alt="QR Code" class="w-48 h-48 mx-auto object-contain">
            </div>
            <p class="text-[11px] text-gray-400 mt-3">Scan menggunakan kamera HP Petugas OB</p>
        </div>
    </div>

    <script>
        function showQrModal(url, title) {
            document.getElementById('qrModalImg').src = url;
            document.getElementById('qrModalTitle').innerText = title;
            document.getElementById('qrModal').classList.remove('hidden');
        }
        function closeQrModal() {
            document.getElementById('qrModal').classList.add('hidden');
        }
    </script>
</x-app-layout>