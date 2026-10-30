<x-app-layout :title="'Dashboard Petugas OB'">

    <div class="max-w-7xl mx-auto p-2 space-y-4">

        @if ($jumlahBelum > 0)
            <div class="mt-4 p-4 bg-yellow-50 border-l-4 border-yellow-500 text-yellow-800 rounded-lg shadow-sm">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2 text-yellow-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                            clip-rule="evenodd"></path>
                    </svg>
                    <span>
                        <strong>Perhatian!</strong> Masih ada <strong>{{ $jumlahBelum }}</strong> dari {{ $totalKamarMandi }} kamar mandi yang belum diperiksa hari ini.
                    </span>
                </div>
            </div>
        @else
            <div class="mt-4 p-4 bg-green-50 border-l-4 border-green-500 text-green-800 rounded-lg shadow-sm">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2 text-green-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd"></path>
                    </svg>
                    <span>
                        <strong>Luar Biasa!</strong> Semua kamar mandi ({{ $totalKamarMandi }}) telah selesai diperiksa hari ini. Terima kasih atas kerja kerasmu!
                    </span>
                </div>
            </div>
        @endif

        <div>
            <h1 class="text-2xl font-semibold text-gray-800 mt-2">Selamat datang, {{ Auth::user()->nama }}</h1>
            <p class="text-sm text-gray-600">
                Pengecekan Kebersihan & Kelengkapan Kamar Mandi &bull;
                <span class="font-medium text-gray-700">{{ \Carbon\Carbon::parse($today)->translatedFormat('l, d F Y') }}</span>
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div class="p-5 bg-white border border-gray-200 rounded-lg shadow-sm">
                <h2 class="text-base font-semibold text-gray-800 mb-3">Progres Pengecekan Hari Ini</h2>

                <div class="space-y-1.5 text-sm text-gray-700">
                    <p class="flex justify-between">
                        <span>Total Kamar Mandi</span>
                        <strong class="text-gray-900">{{ $totalKamarMandi }} lokasi</strong>
                    </p>
                    <p class="flex justify-between">
                        <span>Sudah Diperiksa</span>
                        <strong class="text-green-600">{{ $jumlahSelesai }} selesai</strong>
                    </p>
                    <p class="flex justify-between">
                        <span>Belum Diperiksa</span>
                        <strong class="text-yellow-600">{{ $jumlahBelum }} tersisa</strong>
                    </p>
                </div>

                <div class="mt-4">
                    <div class="flex justify-between text-xs text-gray-600 mb-1">
                        <span>Persentase Selesai</span>
                        <span class="font-bold text-blue-600">{{ $persentaseSelesai }}%</span>
                    </div>
                    <div class="relative h-3.5 bg-gray-200 rounded-full overflow-hidden">
                        <div class="h-full bg-blue-600 rounded-full transition-all duration-500 ease-in-out"
                            style="width: {{ $persentaseSelesai }}%"></div>
                    </div>
                </div>
            </div>

            <div class="p-5 bg-white border border-gray-200 rounded-lg shadow-sm flex flex-col justify-between">
                <div>
                    <h2 class="text-base font-semibold text-gray-800 mb-3">Hasil Evaluasi Hari Ini</h2>
                    <div class="grid grid-cols-2 gap-3 mb-4">
                        <div class="p-3 bg-green-50 rounded-lg border border-green-200 text-center">
                            <span class="block text-2xl font-bold text-green-700">{{ $jumlahBaik }}</span>
                            <span class="text-xs font-medium text-green-800">Kondisi Baik</span>
                        </div>
                        <div class="p-3 bg-red-50 rounded-lg border border-red-200 text-center">
                            <span class="block text-2xl font-bold text-red-700">{{ $jumlahPerluTindakan }}</span>
                            <span class="text-xs font-medium text-red-800">Perlu Tindakan (Temuan)</span>
                        </div>
                    </div>
                </div>

                <a href="{{ route('ob.scan-qr') }}"
                    class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition shadow-sm">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 512 512">
                        <path
                            d="M149.1 64.8L138.7 96 64 96C28.7 96 0 124.7 0 160L0 416c0 35.3 28.7 64 64 64l384 0c35.3 0 64-28.7 64-64l0-256c0-35.3-28.7-64-64-64l-74.7 0L362.9 64.8C356.4 45.2 338.1 32 317.4 32L194.6 32c-20.7 0-39 13.2-45.5 32.8zM256 192a96 96 0 1 1 0 192 96 96 0 1 1 0-192z">
                        </path>
                    </svg>
                    Mulai Scan Barcode Toilet
                </a>
            </div>

        </div>

        <div class="mt-6 bg-white p-5 border border-gray-200 rounded-lg shadow-sm">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-bold text-gray-800">Daftar Kamar Mandi Cabang</h2>
                <span class="text-xs bg-gray-100 text-gray-600 px-3 py-1 rounded-full font-medium">
                    {{ $jumlahSelesai }} / {{ $totalKamarMandi }} Selesai
                </span>
            </div>

            <div class="overflow-x-auto rounded-lg border border-gray-200">
                <table class="table-auto w-full text-sm text-gray-700">
                    <thead class="bg-blue-100 text-gray-800 uppercase text-xs">
                        <tr class="text-center">
                            <th class="px-4 py-3">No</th>
                            <th class="px-4 py-3 text-left">Kode</th>
                            <th class="px-4 py-3 text-left">Nama Kamar Mandi</th>
                            <th class="px-4 py-3">Lantai</th>
                            <th class="px-4 py-3">Waktu Cek</th>
                            <th class="px-4 py-3">Status Hari Ini</th>
                            <th class="px-4 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($kamarMandiList as $index => $km)
                            @php
                                $cek = $pemeriksaanHariIni->get($km->id);
                            @endphp
                            <tr class="hover:bg-gray-50 text-center transition">
                                <td class="px-4 py-3 font-medium">{{ $index + 1 }}</td>
                                <td class="px-4 py-3 text-left font-mono font-semibold text-blue-700">
                                    {{ $km->kode_kamar_mandi }}
                                </td>
                                <td class="px-4 py-3 text-left">
                                    <span class="font-medium text-gray-900 block">{{ $km->nama_kamar_mandi }}</span>
                                    <span class="text-xs text-gray-500">{{ $km->deskripsi ?? '-' }}</span>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="px-2.5 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                        {{ $km->lantai }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 font-mono text-xs">
                                    @if ($cek)
                                        {{ \Carbon\Carbon::parse($cek->waktu)->format('H:i') }} WIB
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    @if ($cek)
                                        @if ($cek->status_kondisi === 'baik')
                                            <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                                Baik
                                            </span>
                                        @else
                                            <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                                Perlu Tindakan
                                            </span>
                                        @endif
                                    @else
                                        <span class="inline-block px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                            Belum Dicek
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    @if ($cek)
                                        
                                        <button type="button"
                                            onclick="openDetailModal({{ json_encode($km) }}, {{ json_encode($cek) }})"
                                            class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-lg border border-gray-300 text-gray-700 bg-white hover:bg-gray-100 transition shadow-sm">
                                            Lihat Detail
                                        </button>
                                    @else
                                        
                                        <a href="{{ route('ob.kamar-mandi.periksa', $km->id) }}"
                                            class="inline-flex items-center px-3 py-1.5 text-xs font-semibold rounded-lg bg-blue-600 hover:bg-blue-700 text-white transition shadow-sm">
                                            Periksa
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                                    Belum ada data kamar mandi yang terdaftar di cabang ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <div id="detailModal" class="fixed inset-0 z-50 hidden bg-gray-900 bg-opacity-50 overflow-y-auto h-full w-full flex items-center justify-center p-4">
        <div class="relative bg-white rounded-xl shadow-xl max-w-lg w-full max-h-[90vh] overflow-y-auto">
            
            <div class="flex items-center justify-between p-4 border-b border-gray-200">
                <div>
                    <h3 id="modalTitle" class="text-lg font-bold text-gray-900">Detail Pemeriksaan</h3>
                    <p id="modalSubtitle" class="text-xs text-gray-500"></p>
                </div>
                <button type="button" onclick="closeDetailModal()" class="text-gray-400 hover:text-gray-900 rounded-lg p-1.5">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>

            <div class="p-5 space-y-4 text-sm text-gray-700">
                <div class="grid grid-cols-2 gap-3 bg-gray-50 p-3 rounded-lg border border-gray-200 text-xs">
                    <div>
                        <span class="text-gray-500 block">Petugas Pemeriksa:</span>
                        <strong id="modalPetugas" class="text-gray-900"></strong>
                    </div>
                    <div>
                        <span class="text-gray-500 block">Waktu Selesai:</span>
                        <strong id="modalWaktu" class="text-gray-900"></strong>
                    </div>
                </div>

                <div>
                    <span class="text-xs text-gray-500 block mb-1">Status Evaluasi:</span>
                    <div id="modalStatusBadge"></div>
                </div>

                <div id="modalCatatanBox" class="hidden">
                    <span class="text-xs text-gray-500 block mb-1">Catatan Temuan Masalah:</span>
                    <div id="modalCatatan" class="p-3 bg-red-50 border border-red-200 text-red-900 rounded-lg text-xs leading-relaxed"></div>
                </div>

                <div id="modalFotoBox" class="hidden">
                    <span class="text-xs text-gray-500 block mb-1">Foto Bukti Lapangan:</span>
                    <img id="modalFoto" src="" alt="Bukti Pemeriksaan" class="w-full h-48 object-cover rounded-lg border border-gray-200">
                </div>

                <div>
                    <span class="text-xs text-gray-500 block mb-2 font-semibold uppercase">Rincian Butir Checklist:</span>
                    <div id="modalChecklistItems" class="space-y-1.5 max-h-48 overflow-y-auto pr-1"></div>
                </div>
            </div>

            <div class="p-4 border-t border-gray-200 text-right">
                <button type="button" onclick="closeDetailModal()" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg text-sm font-medium transition">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <script>
        function openDetailModal(km, cek) {
            document.getElementById('modalTitle').innerText = km.nama_kamar_mandi;
            document.getElementById('modalSubtitle').innerText = `${km.kode_kamar_mandi} &bull; ${km.lantai}`;
            document.getElementById('modalPetugas').innerText = cek.user ? cek.user.nama : 'Petugas OB';
            document.getElementById('modalWaktu').innerText = `${cek.waktu} WIB (${cek.tanggal})`;

            const badgeBox = document.getElementById('modalStatusBadge');
            if (cek.status_kondisi === 'baik') {
                badgeBox.innerHTML = '<span class="px-3 py-1 text-xs font-bold rounded-full bg-green-100 text-green-800">KONDISI BAIK</span>';
            } else {
                badgeBox.innerHTML = '<span class="px-3 py-1 text-xs font-bold rounded-full bg-red-100 text-red-800">PERLU TINDAKAN (TEMUAN)</span>';
            }

            const catatanBox = document.getElementById('modalCatatanBox');
            if (cek.catatan) {
                document.getElementById('modalCatatan').innerText = cek.catatan;
                catatanBox.classList.remove('hidden');
            } else {
                catatanBox.classList.add('hidden');
            }

            const fotoBox = document.getElementById('modalFotoBox');
            if (cek.foto) {
                document.getElementById('modalFoto').src = `/storage/${cek.foto}`;
                fotoBox.classList.remove('hidden');
            } else {
                fotoBox.classList.add('hidden');
            }

            const checklistContainer = document.getElementById('modalChecklistItems');
            checklistContainer.innerHTML = '';
            if (cek.detail_pemeriksaan && cek.detail_pemeriksaan.length > 0) {
                cek.detail_pemeriksaan.forEach(detail => {
                    const isSesuai = detail.status === 'sesuai';
                    const icon = isSesuai 
                        ? '<span class="text-green-600 font-bold">&#10003;</span>' 
                        : '<span class="text-red-600 font-bold">&#10007;</span>';
                    const textClass = isSesuai ? 'text-gray-800' : 'text-red-700 font-semibold';
                    const namaButir = detail.checklist ? detail.checklist.nama_butir : 'Butir Checklist';

                    const itemDiv = document.createElement('div');
                    itemDiv.className = 'flex items-center justify-between p-2 rounded bg-gray-50 text-xs border border-gray-100';
                    itemDiv.innerHTML = `
                        <span class="${textClass}">${namaButir}</span>
                        <span class="flex items-center gap-1">${icon} <span class="capitalize text-[11px]">${detail.status}</span></span>
                    `;
                    checklistContainer.appendChild(itemDiv);
                });
            } else {
                checklistContainer.innerHTML = '<p class="text-xs text-gray-400">Tidak ada rincian checklist.</p>';
            }

            document.getElementById('detailModal').classList.remove('hidden');
        }

        function closeDetailModal() {
            document.getElementById('detailModal').classList.add('hidden');
        }
    </script>

</x-app-layout>