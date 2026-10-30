<x-app-layout :title="'Riwayat Pemeriksaan Kamar Mandi'">
    <div class="max-w-7xl mx-auto p-4 space-y-6">

        <div>
            <h1 class="text-2xl font-bold text-gray-800">Riwayat Pemeriksaan Kamar Mandi</h1>
            <p class="text-sm text-gray-500">Histori seluruh hasil evaluasi kebersihan dan kelengkapan toilet yang telah Anda periksa</p>
        </div>

        <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm">
            <form action="{{ route('ob.riwayat-cek') }}" method="GET" class="space-y-3">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                    
                    <div>
                        <select name="status" onchange="this.form.submit()"
                            class="block w-full border border-gray-300 rounded-lg p-2 text-sm text-gray-900 focus:ring-blue-500 focus:border-blue-500">
                            <option value="" {{ request('status') == '' ? 'selected' : '' }}>Semua status</option>
                            <option value="baik" {{ request('status') == 'baik' ? 'selected' : '' }}>Kondisi Baik</option>
                            <option value="perlu_tindakan" {{ request('status') == 'perlu_tindakan' ? 'selected' : '' }}>Perlu Tindakan</option>
                        </select>
                    </div>

                    <div>
                        <input type="date" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}"
                            class="block w-full border border-gray-300 rounded-lg p-2 text-sm text-gray-700 focus:ring-blue-500 focus:border-blue-500"
                            title="Tanggal Mulai">
                    </div>

                    <div>
                        <input type="date" name="tanggal_selesai" value="{{ request('tanggal_selesai') }}"
                            class="block w-full border border-gray-300 rounded-lg p-2 text-sm text-gray-700 focus:ring-blue-500 focus:border-blue-500"
                            title="Tanggal Selesai">
                    </div>

                    <div class="flex">
                        <div class="relative flex-grow">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Cari kamar mandi..." autocomplete="off"
                                class="block w-full p-2 pl-10 text-sm text-gray-900 bg-white border border-gray-300 rounded-l-lg focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                        <button type="submit"
                            class="px-4 py-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-r-lg border-l-0 border border-blue-600 transition">
                            Filter
                        </button>
                    </div>
                </div>

                @if (request('search') || request('status') || request('tanggal_mulai') || request('tanggal_selesai'))
                    <div class="flex justify-end">
                        <a href="{{ route('ob.riwayat-cek') }}"
                            class="text-xs text-gray-500 hover:text-gray-800 hover:underline">
                            Reset Filter
                        </a>
                    </div>
                @endif
            </form>
        </div>

        <div class="overflow-x-auto border border-gray-200 rounded-xl shadow-sm bg-white">
            <table class="table-auto w-full text-sm text-gray-700">
                <thead class="bg-blue-100 text-gray-800 uppercase text-xs">
                    <tr class="text-center">
                        <th class="px-4 py-3">No</th>
                        <th class="px-4 py-3 text-left">Waktu Cek</th>
                        <th class="px-4 py-3 text-left">Kamar Mandi</th>
                        <th class="px-4 py-3">Lantai</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 text-left">Catatan Temuan</th>
                        <th class="px-4 py-3">Foto</th>
                        <th class="px-4 py-3">Detail</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($riwayat as $index => $item)
                        <tr class="hover:bg-gray-50 text-center transition">
                            <td class="px-4 py-3 font-medium">{{ $riwayat->firstItem() + $index }}</td>
                            <td class="px-4 py-3 text-left whitespace-nowrap">
                                <span class="font-medium text-gray-900 block">{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d M Y') }}</span>
                                <span class="text-xs text-gray-500 font-mono">{{ \Carbon\Carbon::parse($item->waktu)->format('H:i') }} WIB</span>
                            </td>
                            <td class="px-4 py-3 text-left">
                                <span class="font-bold text-blue-700 text-xs font-mono block">{{ $item->kamarMandi->kode_kamar_mandi ?? '-' }}</span>
                                <span class="font-medium text-gray-800">{{ $item->kamarMandi->nama_kamar_mandi ?? '-' }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-700">
                                    {{ $item->kamarMandi->lantai ?? '-' }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                @if ($item->status_kondisi === 'baik')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                        Baik
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                        Perlu Tindakan
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-left text-xs text-gray-600 max-w-xs truncate">
                                {{ $item->catatan ?? '-' }}
                            </td>
                            <td class="px-4 py-3">
                                @if ($item->foto)
                                    <img src="{{ asset('storage/' . $item->foto) }}" alt="Bukti"
                                        class="w-10 h-10 object-cover rounded-lg mx-auto cursor-pointer border border-gray-300 hover:scale-105 transition"
                                        onclick="showFotoModal('{{ asset('storage/' . $item->foto) }}', '{{ $item->kamarMandi->nama_kamar_mandi ?? 'Bukti Foto' }}')">
                                @else
                                    <span class="text-gray-400 text-xs">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <button type="button"
                                    onclick="openHistoryDetail({{ json_encode($item) }})"
                                    class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-lg border border-gray-300 text-gray-700 bg-white hover:bg-gray-100 transition shadow-sm">
                                    Checklist
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-8 text-center text-gray-500">
                                Belum ada data riwayat pemeriksaan yang cocok dengan filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="flex flex-col md:flex-row justify-between items-center gap-3 bg-white p-3 rounded-xl border border-gray-200">
            <div class="text-xs text-gray-600">
                Menampilkan {{ $riwayat->firstItem() ?? 0 }} sampai {{ $riwayat->lastItem() ?? 0 }} dari {{ $riwayat->total() }} data pemeriksaan
            </div>
            <div>
                {{ $riwayat->links('components.pagination') }}
            </div>
        </div>

    </div>

    <div id="fotoModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-60 flex items-center justify-center p-4">
        <div class="relative bg-white rounded-xl shadow-xl max-w-md w-full overflow-hidden">
            <div class="flex justify-between items-center p-3 border-b border-gray-200">
                <h3 id="fotoModalTitle" class="text-sm font-bold text-gray-800"></h3>
                <button onclick="closeFotoModal()" class="text-gray-400 hover:text-gray-900 p-1">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>
            <div class="p-3 text-center">
                <img id="fotoModalImg" src="" alt="Bukti Foto" class="w-full max-h-[70vh] object-contain rounded-lg">
            </div>
        </div>
    </div>

    <div id="detailChecklistModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-60 flex items-center justify-center p-4">
        <div class="relative bg-white rounded-xl shadow-xl max-w-lg w-full max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center p-4 border-b border-gray-200">
                <div>
                    <h3 id="modalKamarMandi" class="text-base font-bold text-gray-900"></h3>
                    <p id="modalWaktuCek" class="text-xs text-gray-500"></p>
                </div>
                <button onclick="closeHistoryDetail()" class="text-gray-400 hover:text-gray-900 p-1">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>

            <div class="p-5 space-y-4 text-xs">
                
                <div class="p-3 rounded-lg bg-gray-50 border border-gray-200 flex justify-between items-center">
                    <span class="font-medium text-gray-600">Status Evaluasi:</span>
                    <span id="modalStatusBadge"></span>
                </div>

                <div id="modalCatatanBox" class="hidden">
                    <span class="font-semibold text-gray-700 block mb-1">Catatan Temuan:</span>
                    <div id="modalCatatan" class="p-3 bg-red-50 border border-red-200 text-red-900 rounded-lg leading-relaxed"></div>
                </div>

                <div>
                    <span class="font-bold text-gray-800 uppercase block mb-2">Hasil Evaluasi Checklist:</span>
                    <div id="modalChecklistItems" class="space-y-1.5 max-h-56 overflow-y-auto pr-1"></div>
                </div>
            </div>

            <div class="p-3 border-t border-gray-200 text-right">
                <button onclick="closeHistoryDetail()" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg text-xs font-semibold transition">
                    Tutup
                </button>
            </div>
        </div>
    </div>

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
            document.getElementById('modalWaktuCek').innerText = `Diperiksa pada ${item.tanggal} pukul ${item.waktu} WIB`;

            const badgeBox = document.getElementById('modalStatusBadge');
            if (item.status_kondisi === 'baik') {
                badgeBox.innerHTML = '<span class="px-2.5 py-1 text-xs font-bold rounded-full bg-green-100 text-green-800">Baik</span>';
            } else {
                badgeBox.innerHTML = '<span class="px-2.5 py-1 text-xs font-bold rounded-full bg-red-100 text-red-800">Perlu Tindakan</span>';
            }

            const catatanBox = document.getElementById('modalCatatanBox');
            if (item.catatan) {
                document.getElementById('modalCatatan').innerText = item.catatan;
                catatanBox.classList.remove('hidden');
            } else {
                catatanBox.classList.add('hidden');
            }

            const checklistContainer = document.getElementById('modalChecklistItems');
            checklistContainer.innerHTML = '';
            if (item.detail_pemeriksaan && item.detail_pemeriksaan.length > 0) {
                item.detail_pemeriksaan.forEach(detail => {
                    const isSesuai = detail.status === 'sesuai';
                    const icon = isSesuai 
                        ? '<span class="text-green-600 font-bold">&#10003; Sesuai</span>' 
                        : '<span class="text-red-600 font-bold">&#10007; Tidak Sesuai</span>';
                    const nama = detail.checklist ? detail.checklist.nama_butir : 'Butir Checklist';

                    const row = document.createElement('div');
                    row.className = 'flex justify-between items-center p-2 rounded bg-gray-50 border border-gray-100';
                    row.innerHTML = `<span>${nama}</span><span>${icon}</span>`;
                    checklistContainer.appendChild(row);
                });
            }

            document.getElementById('detailChecklistModal').classList.remove('hidden');
        }

        function closeHistoryDetail() {
            document.getElementById('detailChecklistModal').classList.add('hidden');
        }
    </script>
</x-app-layout>