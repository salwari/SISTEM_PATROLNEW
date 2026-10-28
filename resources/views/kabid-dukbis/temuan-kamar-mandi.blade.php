<x-app-layout :title="'Temuan Kamar Mandi'">
    <div class="max-w-7xl mx-auto p-4 space-y-6">

        {{-- Header Halaman --}}
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Temuan Fasilitas Kamar Mandi</h1>
            <p class="text-sm text-gray-500">
                Daftar laporan fasilitas toilet yang membutuhkan perbaikan teknisi atau pengadaan barang dari petugas OB
            </p>
        </div>

        {{-- Banner Info Kabid --}}
        <div class="p-4 bg-red-50 border-l-4 border-red-500 text-red-900 rounded-lg shadow-sm flex items-center justify-between">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-3 text-red-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
                <span class="text-xs sm:text-sm">
                    <strong>Pemberitahuan:</strong> Halaman ini khusus menyortir kondisi <strong>Perlu Tindakan</strong> agar dapat segera ditindaklanjuti ke teknisi / bagian pemeliharaan gedung.
                </span>
            </div>
            <span class="hidden sm:inline-block px-3 py-1 bg-red-100 text-red-800 font-bold text-xs rounded-full">
                {{ $temuanList->total() }} Temuan Aktif
            </span>
        </div>

        {{-- Bar Filter & Pencarian --}}
        <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm">
            <form action="{{ route('kabid-dukbis.temuan-kamar-mandi') }}" method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-3 items-center">
                
                {{-- Filter Tanggal Mulai --}}
                <div class="md:col-span-3">
                    <label class="block text-[11px] font-semibold text-gray-500 mb-1">DARI TANGGAL</label>
                    <input type="date" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}"
                        class="w-full border border-gray-300 rounded-lg p-2 text-xs text-gray-700 focus:ring-red-500 focus:border-red-500">
                </div>

                {{-- Filter Tanggal Selesai --}}
                <div class="md:col-span-3">
                    <label class="block text-[11px] font-semibold text-gray-500 mb-1">SAMPAI TANGGAL</label>
                    <input type="date" name="tanggal_selesai" value="{{ request('tanggal_selesai') }}"
                        class="w-full border border-gray-300 rounded-lg p-2 text-xs text-gray-700 focus:ring-red-500 focus:border-red-500">
                </div>

                {{-- Search Box & Tombol --}}
                <div class="md:col-span-5">
                    <label class="block text-[11px] font-semibold text-gray-500 mb-1">CARI LOKASI TOILET</label>
                    <div class="flex">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari kode atau nama toilet..." autocomplete="off"
                            class="block w-full p-2 text-xs text-gray-900 bg-white border border-gray-300 rounded-l-lg focus:ring-red-500 focus:border-red-500" />
                        <button type="submit"
                            class="px-5 py-2 text-xs font-semibold text-white bg-red-600 hover:bg-red-700 rounded-r-lg border border-red-600 transition">
                            Cari
                        </button>
                    </div>
                </div>

                {{-- Tombol Reset --}}
                @if (request('search') || request('tanggal_mulai') || request('tanggal_selesai'))
                    <div class="md:col-span-1 text-center pt-4">
                        <a href="{{ route('kabid-dukbis.temuan-kamar-mandi') }}"
                            class="inline-block px-3 py-2 text-xs text-gray-600 hover:text-gray-900 hover:underline">
                            Reset
                        </a>
                    </div>
                @endif
            </form>
        </div>

        {{-- Tabel Temuan Kerusakan --}}
        <div class="overflow-x-auto border border-gray-200 rounded-xl shadow-sm bg-white">
            <table class="table-auto w-full text-sm text-gray-700">
                <thead class="bg-red-50 text-red-900 uppercase text-xs font-semibold">
                    <tr class="text-center">
                        <th class="px-4 py-3">No</th>
                        <th class="px-4 py-3 text-left">Waktu Laporan</th>
                        <th class="px-4 py-3 text-left">Lokasi Toilet</th>
                        <th class="px-4 py-3">Lantai</th>
                        <th class="px-4 py-3 text-left">Petugas OB</th>
                        <th class="px-4 py-3 text-left">Catatan Kerusakan / Temuan</th>
                        <th class="px-4 py-3">Foto Bukti</th>
                        <th class="px-4 py-3">Detail</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($temuanList as $index => $item)
                        <tr class="hover:bg-gray-50 text-center transition">
                            <td class="px-4 py-3 font-medium">{{ $temuanList->firstItem() + $index }}</td>
                            <td class="px-4 py-3 text-left whitespace-nowrap">
                                <span class="font-medium text-gray-900 block">{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d M Y') }}</span>
                                <span class="text-xs text-gray-500 font-mono">{{ \Carbon\Carbon::parse($item->waktu)->format('H:i') }} WIB</span>
                            </td>
                            <td class="px-4 py-3 text-left">
                                <span class="font-bold text-red-700 text-xs font-mono block">{{ $item->kamarMandi->kode_kamar_mandi ?? '-' }}</span>
                                <span class="font-medium text-gray-800">{{ $item->kamarMandi->nama_kamar_mandi ?? '-' }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-700">
                                    {{ $item->kamarMandi->lantai ?? '-' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-left">
                                <span class="font-medium text-gray-900">{{ $item->user->nama ?? 'Petugas OB' }}</span>
                            </td>
                            <td class="px-4 py-3 text-left text-xs text-red-900 font-medium max-w-xs">
                                <div class="p-2 bg-red-50 rounded border border-red-100">
                                    {{ $item->catatan ?? 'Tidak ada catatan tertulis.' }}
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                @if ($item->foto)
                                    <img src="{{ asset('storage/' . $item->foto) }}" alt="Bukti Rusak"
                                        class="w-12 h-12 object-cover rounded-lg mx-auto cursor-pointer border border-red-200 hover:scale-105 transition"
                                        onclick="showFotoModal('{{ asset('storage/' . $item->foto) }}', '{{ $item->kamarMandi->nama_kamar_mandi ?? 'Bukti Foto' }}')">
                                @else
                                    <span class="text-gray-400 text-xs italic">Tanpa Foto</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <button type="button"
                                    onclick="openHistoryDetail({{ json_encode($item) }})"
                                    class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-lg border border-gray-300 text-gray-700 bg-white hover:bg-gray-100 transition shadow-sm">
                                    Rincian
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-10 text-center text-gray-500">
                                <svg class="w-10 h-10 text-green-500 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <strong>Tidak ada temuan masalah aktif!</strong>
                                <p class="text-xs text-gray-400 mt-1">Seluruh kamar mandi dilaporkan dalam kondisi baik dan berfungsi normal.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="flex flex-col md:flex-row justify-between items-center gap-3 bg-white p-3 rounded-xl border border-gray-200">
            <div class="text-xs text-gray-600">
                Menampilkan {{ $temuanList->firstItem() ?? 0 }} sampai {{ $temuanList->lastItem() ?? 0 }} dari {{ $temuanList->total() }} data temuan
            </div>
            <div>
                {{ $temuanList->links('components.pagination') }}
            </div>
        </div>

    </div>

    {{-- MODAL PREVIEW FOTO --}}
    <div id="fotoModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-60 flex items-center justify-center p-4">
        <div class="relative bg-white rounded-xl shadow-xl max-w-md w-full overflow-hidden">
            <div class="flex justify-between items-center p-3 border-b border-gray-200 bg-gray-50">
                <h3 id="fotoModalTitle" class="text-sm font-bold text-gray-800"></h3>
                <button onclick="closeFotoModal()" class="text-gray-400 hover:text-gray-900 p-1">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>
            <div class="p-3 text-center">
                <img id="fotoModalImg" src="" alt="Bukti Foto" class="w-full max-h-[70vh] object-contain rounded-lg">
            </div>
        </div>
    </div>

    {{-- MODAL RINCIAN BUTIR CHECKLIST --}}
    <div id="detailChecklistModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-60 flex items-center justify-center p-4">
        <div class="relative bg-white rounded-xl shadow-xl max-w-lg w-full max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center p-4 border-b border-gray-200">
                <div>
                    <h3 id="modalKamarMandi" class="text-base font-bold text-gray-900"></h3>
                    <p id="modalWaktuCek" class="text-xs text-gray-500"></p>
                </div>
                <button onclick="closeHistoryDetail()" class="text-gray-400 hover:text-gray-900 p-1">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>

            <div class="p-5 space-y-4 text-xs">
                <div>
                    <span class="font-bold text-gray-800 uppercase block mb-2">Evaluasi Butir Pengecekan:</span>
                    <div id="modalChecklistItems" class="space-y-1.5 max-h-60 overflow-y-auto pr-1"></div>
                </div>
            </div>

            <div class="p-3 border-t border-gray-200 text-right">
                <button onclick="closeHistoryDetail()" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg text-xs font-semibold transition">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    {{-- Script Modal --}}
    <script>
        function showFotoModal(url, title) {
            document.getElementById('fotoModalImg').src = url;
            document.getElementById('fotoModalTitle').innerText = title;
            document.getElementById('fotoModal').classList.remove('hidden');
        }
        function closeFotoModal() {
            document.getElementById('fotoModal').classList.add('hidden');
        }

        function openHistoryDetail(item) {
            document.getElementById('modalKamarMandi').innerText = `${item.kamar_mandi.kode_kamar_mandi} - ${item.kamar_mandi.nama_kamar_mandi}`;
            document.getElementById('modalWaktuCek').innerText = `Dilaporkan oleh ${item.user ? item.user.nama : 'OB'} pada ${item.tanggal} (${item.waktu} WIB)`;

            const checklistContainer = document.getElementById('modalChecklistItems');
            checklistContainer.innerHTML = '';
            if (item.detail_pemeriksaan && item.detail_pemeriksaan.length > 0) {
                item.detail_pemeriksaan.forEach(detail => {
                    const isSesuai = detail.status === 'sesuai';
                    const icon = isSesuai 
                        ? '<span class="text-green-600 font-bold">&#10003; Sesuai</span>' 
                        : '<span class="text-red-600 font-bold">&#10007; Masalah</span>';
                    const bgClass = isSesuai ? 'bg-gray-50 border-gray-100' : 'bg-red-50 border-red-200 font-semibold text-red-900';
                    const nama = detail.checklist ? detail.checklist.nama_butir : 'Butir Checklist';

                    const row = document.createElement('div');
                    row.className = `flex justify-between items-center p-2.5 rounded border ${bgClass}`;
                    row.innerHTML = `<span>${nama}</span><span>${icon}</span>`;
                    checklistContainer.appendChild(row);
                });
            } else {
                checklistContainer.innerHTML = '<p class="text-xs text-gray-400">Tidak ada rincian checklist.</p>';
            }

            document.getElementById('detailChecklistModal').classList.remove('hidden');
        }

        function closeHistoryDetail() {
            document.getElementById('detailChecklistModal').classList.add('hidden');
        }
    </script>
</x-app-layout>