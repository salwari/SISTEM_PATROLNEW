<x-app-layout :title="'Laporan Kebersihan'">
    <div class="container mx-auto px-4 py-6">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">Laporan Kebersihan</h2>

        
        <div class="bg-white border border-gray-200 rounded-lg shadow-md mb-6 p-4">
            <form action="{{ route('kabid-dukbis.laporan-kebersihan') }}" method="GET"
                class="flex flex-col md:flex-row items-start md:items-center gap-2 w-full md:w-auto">

                
                <select name="status"
                    class="border border-gray-300 rounded-lg p-2 text-sm text-gray-900 focus:ring-blue-500 focus:border-blue-500 w-full md:w-44">
                    <option value="" {{ request('status') == '' ? 'selected' : '' }}>Semua status</option>
                    <option value="baik" {{ request('status') == 'baik' ? 'selected' : '' }}>Kondisi Baik</option>
                    <option value="perlu_tindakan" {{ request('status') == 'perlu_tindakan' ? 'selected' : '' }}>Perlu Tindakan</option>
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
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari toilet atau lokasi..." autocomplete="off"
                            class="block w-full p-2 pl-10 text-sm text-gray-900 bg-white border border-gray-300 rounded-l-lg focus:ring-blue-500 focus:border-blue-500" />
                    </div>
                    <button type="submit"
                        class="px-4 py-2 text-sm text-white bg-blue-600 hover:bg-blue-700 rounded-r-lg border-l-0 border border-blue-600">
                        Cari
                    </button>
                </div>

                @if (request('search') || request('status'))
                    <a href="{{ route('kabid-dukbis.laporan-kebersihan') }}"
                        class="px-4 py-2 text-sm text-white bg-gray-400 hover:bg-gray-500 rounded-lg whitespace-nowrap">
                        Kembali
                    </a>
                @endif
            </form>
        </div>

        <div class="bg-white border border-gray-200 rounded-lg shadow-md mb-6 p-4">
            <form action="{{ route('kabid-dukbis.cetak-laporan-kebersihan-redirect') }}" method="GET" target="_blank"
                class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 items-end">

                @if (request('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif

                <div>
                    <label for="tanggal_mulai" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                    <input type="date" name="tanggal_mulai" id="tanggal_mulai" value="{{ old('tanggal_mulai') }}"
                        class="block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-blue-500 focus:border-blue-500"
                        required>
                </div>

                <div>
                    <label for="tanggal_selesai" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Selesai</label>
                    <input type="date" name="tanggal_selesai" id="tanggal_selesai" value="{{ old('tanggal_selesai') }}"
                        class="block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-blue-500 focus:border-blue-500"
                        required>
                </div>

                <div class="flex justify-end">
                    <button type="submit"
                        class="inline-flex items-center justify-center w-full sm:w-auto px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md text-sm font-semibold transition">
                        <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 512 512">
                            <path d="M128 0C92.7 0 64 28.7 64 64v96h64v-96h226.7L384 93.3v66.7h64v-66.7c0-17-6.7-33.3-18.7-45.3L400 18.7C388 6.7 371.7 0 354.7 0H128zm256 352v96H128v-96h256zm64 32h32c17.7 0 32-14.3 32-32v-96c0-35.3-28.7-64-64-64H64c-35.3 0-64 28.7-64 64v96c0 17.7 14.3 32 32 32h32v64c0 35.3 28.7 64 64 64h256c35.3 0 64-28.7 64-64v-64zM432 248a24 24 0 1 1 0 48 24 24 0 1 1 0-48z" />
                        </svg>
                        Cetak
                    </button>
                </div>
            </form>
        </div>

        <div class="overflow-x-auto border border-gray-200 rounded-lg shadow-md mb-4 bg-white">
            <table class="table-auto w-full text-sm text-gray-700">
                <thead class="bg-blue-100 text-gray-800 uppercase text-xs font-semibold">
                    <tr class="text-center">
                        <th class="px-4 py-3">No</th>
                        <th class="px-4 py-3 text-left">Nama Petugas</th>
                        <th class="px-4 py-3 text-left">Lokasi</th>
                        <th class="px-4 py-3">Tanggal</th>
                        <th class="px-4 py-3">Waktu</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 text-left">Catatan / Temuan</th>
                        <th class="px-4 py-3">Foto</th>
                        <th class="px-4 py-3">Detail</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($laporanKebersihan as $index => $item)
                        <tr class="hover:bg-gray-50 text-center transition">
                            <td class="px-4 py-3 font-medium text-gray-600">{{ $laporanKebersihan->firstItem() + $index }}</td>
                            <td class="px-4 py-3 text-left font-medium text-gray-900">{{ $item->user->nama ?? 'Petugas OB' }}</td>
                            <td class="px-4 py-3 text-left">
                                <span class="font-bold text-blue-700 text-xs font-mono block">{{ $item->kamarMandi->kode_kamar_mandi ?? '-' }}</span>
                                <span class="text-gray-900">{{ $item->kamarMandi->nama_kamar_mandi ?? '-' }}</span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                            <td class="px-4 py-3 font-mono text-xs">{{ \Carbon\Carbon::parse($item->waktu)->format('H:i') }} WIB</td>
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
                            <td class="px-4 py-3 text-left text-xs text-gray-600 max-w-xs truncate">{{ $item->catatan ?? '-' }}</td>
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
                                    Rincian
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-4 py-10 text-center text-gray-500">
                                <strong>Belum ada data laporan kebersihan.</strong>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="flex flex-col md:flex-row justify-between items-center gap-3 bg-white p-3 rounded-xl border border-gray-200">
            <div class="text-xs text-gray-600">
                Menampilkan {{ $laporanKebersihan->firstItem() ?? 0 }} sampai {{ $laporanKebersihan->lastItem() ?? 0 }} dari {{ $laporanKebersihan->total() }} data
            </div>
            <div>
                {{ $laporanKebersihan->links('components.pagination') }}
            </div>
        </div>
    </div>

    <div id="fotoModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-60 flex items-center justify-center p-4">
        <div class="relative bg-white rounded-xl shadow-xl max-w-md w-full overflow-hidden">
            <div class="flex justify-between items-center p-3 border-b border-gray-200 bg-gray-50">
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
            document.getElementById('modalWaktuCek').innerText = `Diperiksa oleh ${item.user ? item.user.nama : 'OB'} pada ${item.tanggal} (${item.waktu} WIB)`;

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

        document.querySelector('select[name="status"]').addEventListener('change', function() {
            this.form.submit();
        });
    </script>
</x-app-layout>