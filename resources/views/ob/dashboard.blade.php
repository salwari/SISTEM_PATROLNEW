<x-app-layout :title="'Dashboard Petugas OB'">

    <style>
        .ob-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 1rem;
            box-shadow: 0 2px 8px rgba(15, 23, 42, 0.06);
        }
        .ob-btn {
            background: linear-gradient(135deg, #035CA8 0%, #0780C9 100%);
            color: #ffffff;
            box-shadow: 0 4px 10px rgba(3, 92, 168, 0.25);
            transition: transform 0.2s ease, box-shadow 0.2s ease, filter 0.2s ease;
        }
        .ob-btn:hover {
            transform: translateY(-2px);
            filter: brightness(1.08);
            box-shadow: 0 10px 20px rgba(3, 92, 168, 0.35);
        }
        .ob-btn-outline {
            background: #ffffff;
            color: #035CA8;
            border: 1px solid #0780C9;
            transition: background 0.2s ease, color 0.2s ease;
        }
        .ob-btn-outline:hover {
            background: #E6F3FB;
            color: #1B4A82;
        }
        .ob-row {
            transition: background 0.2s ease;
        }
        .ob-row:hover {
            background: #F1F7FC;
        }
        .ob-badge-baik {
            background: #DCFCE7;
            color: #166534;
        }
        .ob-badge-temuan {
            background: #FEE2E2;
            color: #991B1B;
        }
        .ob-badge-belum {
            background: #F1F5F9;
            color: #475569;
        }
    </style>

    <div class="max-w-7xl mx-auto p-2 space-y-5">

        <div class="relative mt-4 overflow-hidden rounded-2xl px-8 py-7"
            style="background: linear-gradient(110deg, #1B4A82 0%, #035CA8 35%, #0780C9 70%, #05A2E4 100%); box-shadow: 0 10px 25px rgba(3, 92, 168, 0.35);">
            <div class="absolute rounded-full"
                style="top: -4rem; right: -4rem; width: 16rem; height: 16rem; background: rgba(255, 255, 255, 0.1);"></div>
            <div class="absolute rounded-full"
                style="bottom: -6rem; right: 10rem; width: 14rem; height: 14rem; background: rgba(255, 255, 255, 0.1);"></div>
            <div class="relative z-10 flex flex-col gap-1 md:flex-row md:items-center md:justify-between">
                <div>
                    <p class="text-sm font-medium" style="color: rgba(255, 255, 255, 0.85);">{{ \Carbon\Carbon::parse($today)->translatedFormat('l, d F Y') }}</p>
                    <h1 class="mt-1 text-2xl font-bold md:text-3xl" style="color: #ffffff;">Selamat datang, {{ Auth::user()->nama }}</h1>
                    <p class="mt-1 text-sm" style="color: rgba(255, 255, 255, 0.85);">Pengecekan Kebersihan &amp; Kelengkapan Kamar Mandi</p>
                </div>
                <div class="mt-4 px-6 py-3 text-center rounded-xl md:mt-0"
                    style="background: rgba(255, 255, 255, 0.18); border: 1px solid rgba(255, 255, 255, 0.25);">
                    <p class="text-3xl font-bold" style="color: #ffffff;">{{ $persentaseSelesai }}%</p>
                    <p class="text-xs font-medium uppercase tracking-wide" style="color: rgba(255, 255, 255, 0.85);">Selesai Hari Ini</p>
                </div>
            </div>
        </div>

        @if ($jumlahBelum > 0)
            <div class="p-4 rounded-xl flex items-center"
                style="background: #FFFBEB; border: 1px solid #FDE68A; border-left: 4px solid #F59E0B; color: #92400E;">
                <svg class="w-5 h-5 mr-2 flex-shrink-0" style="color: #D97706;" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                        clip-rule="evenodd"></path>
                </svg>
                <span>
                    <strong>Perhatian!</strong> Masih ada <strong>{{ $jumlahBelum }}</strong> dari {{ $totalKamarMandi }} kamar mandi yang belum diperiksa hari ini.
                </span>
            </div>
        @else
            <div class="p-4 rounded-xl flex items-center"
                style="background: #F0FDF4; border: 1px solid #BBF7D0; border-left: 4px solid #22C55E; color: #166534;">
                <svg class="w-5 h-5 mr-2 flex-shrink-0" style="color: #16A34A;" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                        clip-rule="evenodd"></path>
                </svg>
                <span>
                    <strong>Luar Biasa!</strong> Semua kamar mandi ({{ $totalKamarMandi }}) telah selesai diperiksa hari ini. Terima kasih atas kerja kerasmu!
                </span>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

            <div class="ob-card p-6" style="border-top: 4px solid #0780C9;">
                <h2 class="text-base font-semibold mb-4" style="color: #1B4A82;">Progres Pengecekan Hari Ini</h2>

                <div class="space-y-2 text-sm" style="color: #475569;">
                    <p class="flex justify-between">
                        <span>Total Kamar Mandi</span>
                        <strong style="color: #1B4A82;">{{ $totalKamarMandi }} lokasi</strong>
                    </p>
                    <p class="flex justify-between">
                        <span>Sudah Diperiksa</span>
                        <strong style="color: #16A34A;">{{ $jumlahSelesai }} selesai</strong>
                    </p>
                    <p class="flex justify-between">
                        <span>Belum Diperiksa</span>
                        <strong style="color: #D97706;">{{ $jumlahBelum }} tersisa</strong>
                    </p>
                </div>

                <div class="mt-5">
                    <div class="flex justify-between text-xs mb-1" style="color: #475569;">
                        <span>Persentase Selesai</span>
                        <span class="font-bold" style="color: #035CA8;">{{ $persentaseSelesai }}%</span>
                    </div>
                    <div class="relative overflow-hidden rounded-full" style="height: 0.875rem; background: #E6F3FB;">
                        <div class="h-full rounded-full transition-all duration-500 ease-in-out"
                            style="width: {{ $persentaseSelesai }}%; background: linear-gradient(90deg, #035CA8 0%, #05A2E4 100%);"></div>
                    </div>
                </div>
            </div>

            <div class="ob-card p-6 flex flex-col justify-between" style="border-top: 4px solid #05A2E4;">
                <div>
                    <h2 class="text-base font-semibold mb-4" style="color: #1B4A82;">Hasil Evaluasi Hari Ini</h2>
                    <div class="grid grid-cols-2 gap-3 mb-5">
                        <div class="p-3 rounded-xl text-center" style="background: #F0FDF4; border: 1px solid #BBF7D0;">
                            <span class="block text-2xl font-bold" style="color: #15803D;">{{ $jumlahBaik }}</span>
                            <span class="text-xs font-medium" style="color: #166534;">Kondisi Baik</span>
                        </div>
                        <div class="p-3 rounded-xl text-center" style="background: #FEF2F2; border: 1px solid #FECACA;">
                            <span class="block text-2xl font-bold" style="color: #B91C1C;">{{ $jumlahPerluTindakan }}</span>
                            <span class="text-xs font-medium" style="color: #991B1B;">Perlu Tindakan (Temuan)</span>
                        </div>
                    </div>
                </div>

                <a href="{{ route('ob.scan-qr') }}"
                    class="ob-btn w-full inline-flex items-center justify-center px-4 py-3 text-sm font-semibold rounded-xl">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 512 512">
                        <path
                            d="M149.1 64.8L138.7 96 64 96C28.7 96 0 124.7 0 160L0 416c0 35.3 28.7 64 64 64l384 0c35.3 0 64-28.7 64-64l0-256c0-35.3-28.7-64-64-64l-74.7 0L362.9 64.8C356.4 45.2 338.1 32 317.4 32L194.6 32c-20.7 0-39 13.2-45.5 32.8zM256 192a96 96 0 1 1 0 192 96 96 0 1 1 0-192z">
                        </path>
                    </svg>
                    Mulai Scan Barcode Toilet
                </a>
            </div>

        </div>

        <div class="ob-card p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-bold" style="color: #1B4A82;">Daftar Kamar Mandi Cabang</h2>
                <span class="text-xs px-3 py-1 rounded-full font-semibold" style="background: #E6F3FB; color: #035CA8;">
                    {{ $jumlahSelesai }} / {{ $totalKamarMandi }} Selesai
                </span>
            </div>

            <div class="overflow-x-auto rounded-xl" style="border: 1px solid #e2e8f0;">
                <table class="table-auto w-full text-sm" style="color: #334155;">
                    <thead class="uppercase text-xs" style="background: #E6F3FB; color: #1B4A82;">
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
                    <tbody class="divide-y" style="background: #ffffff; border-color: #e2e8f0;">
                        @forelse ($kamarMandiList as $index => $km)
                            @php
                                $cek = $pemeriksaanHariIni->get($km->id);
                            @endphp
                            <tr class="ob-row text-center">
                                <td class="px-4 py-3 font-medium">{{ $index + 1 }}</td>
                                <td class="px-4 py-3 text-left font-mono font-semibold" style="color: #035CA8;">
                                    {{ $km->kode_kamar_mandi }}
                                </td>
                                <td class="px-4 py-3 text-left">
                                    <span class="font-semibold block" style="color: #1B4A82;">{{ $km->nama_kamar_mandi }}</span>
                                    <span class="text-xs" style="color: #64748B;">{{ $km->deskripsi ?? '-' }}</span>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="px-2.5 py-0.5 rounded text-xs font-medium" style="background: #F1F7FC; color: #035CA8;">
                                        {{ $km->lantai }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 font-mono text-xs">
                                    @if ($cek)
                                        {{ \Carbon\Carbon::parse($cek->waktu)->format('H:i') }} WIB
                                    @else
                                        <span style="color: #94A3B8;">-</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    @if ($cek)
                                        @if ($cek->status_kondisi === 'baik')
                                            <span class="ob-badge-baik inline-block px-3 py-1 rounded-full text-xs font-semibold">
                                                Baik
                                            </span>
                                        @else
                                            <span class="ob-badge-temuan inline-block px-3 py-1 rounded-full text-xs font-semibold">
                                                Perlu Tindakan
                                            </span>
                                        @endif
                                    @else
                                        <span class="ob-badge-belum inline-block px-3 py-1 rounded-full text-xs font-medium">
                                            Belum Dicek
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    @if ($cek)
                                        <button type="button"
                                            onclick="openDetailModal({{ json_encode($km) }}, {{ json_encode($cek) }})"
                                            class="ob-btn-outline inline-flex items-center px-3 py-1.5 text-xs font-semibold rounded-lg">
                                            Lihat Detail
                                        </button>
                                    @else
                                        <a href="{{ route('ob.kamar-mandi.periksa', $km->id) }}"
                                            class="ob-btn inline-flex items-center px-3 py-1.5 text-xs font-semibold rounded-lg">
                                            Periksa
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-8 text-center" style="color: #64748B;">
                                    Belum ada data kamar mandi yang terdaftar di cabang ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <div id="detailModal" class="fixed inset-0 z-50 hidden overflow-y-auto h-full w-full flex items-center justify-center p-4"
        style="background: rgba(23, 37, 84, 0.55);">
        <div class="relative bg-white rounded-2xl max-w-lg w-full max-h-[90vh] overflow-y-auto"
            style="box-shadow: 0 25px 50px rgba(3, 92, 168, 0.35);">

            <div class="flex items-center justify-between p-5 rounded-t-2xl"
                style="background: linear-gradient(110deg, #1B4A82 0%, #035CA8 50%, #0780C9 100%);">
                <div>
                    <h3 id="modalTitle" class="text-lg font-bold" style="color: #ffffff;">Detail Pemeriksaan</h3>
                    <p id="modalSubtitle" class="text-xs" style="color: rgba(255, 255, 255, 0.85);"></p>
                </div>
                <button type="button" onclick="closeDetailModal()" class="rounded-lg p-1.5" style="color: rgba(255, 255, 255, 0.85);">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>

            <div class="p-5 space-y-4 text-sm" style="color: #334155;">
                <div class="grid grid-cols-2 gap-3 p-3 rounded-xl text-xs" style="background: #F1F7FC; border: 1px solid #D6E8F5;">
                    <div>
                        <span class="block" style="color: #64748B;">Petugas Pemeriksa:</span>
                        <strong id="modalPetugas" style="color: #1B4A82;"></strong>
                    </div>
                    <div>
                        <span class="block" style="color: #64748B;">Waktu Selesai:</span>
                        <strong id="modalWaktu" style="color: #1B4A82;"></strong>
                    </div>
                </div>

                <div>
                    <span class="text-xs block mb-1" style="color: #64748B;">Status Evaluasi:</span>
                    <div id="modalStatusBadge"></div>
                </div>

                <div id="modalCatatanBox" class="hidden">
                    <span class="text-xs block mb-1" style="color: #64748B;">Catatan Temuan Masalah:</span>
                    <div id="modalCatatan" class="p-3 rounded-xl text-xs leading-relaxed"
                        style="background: #FEF2F2; border: 1px solid #FECACA; color: #7F1D1D;"></div>
                </div>

                <div id="modalFotoBox" class="hidden">
                    <span class="text-xs block mb-1" style="color: #64748B;">Foto Bukti Lapangan:</span>
                    <img id="modalFoto" src="" alt="Bukti Pemeriksaan" class="w-full h-48 object-cover rounded-xl" style="border: 1px solid #e2e8f0;">
                </div>

                <div>
                    <span class="text-xs block mb-2 font-semibold uppercase" style="color: #035CA8;">Rincian Butir Checklist:</span>
                    <div id="modalChecklistItems" class="space-y-1.5 max-h-48 overflow-y-auto pr-1"></div>
                </div>
            </div>

            <div class="p-4 text-right" style="border-top: 1px solid #e2e8f0;">
                <button type="button" onclick="closeDetailModal()" class="ob-btn px-5 py-2 rounded-xl text-sm font-semibold">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <script>
        function openDetailModal(km, cek) {
            document.getElementById('modalTitle').innerText = km.nama_kamar_mandi;
            document.getElementById('modalSubtitle').innerText = `${km.kode_kamar_mandi} \u2022 ${km.lantai}`;
            document.getElementById('modalPetugas').innerText = cek.user ? cek.user.nama : 'Petugas OB';
            document.getElementById('modalWaktu').innerText = `${cek.waktu} WIB (${cek.tanggal})`;

            const badgeBox = document.getElementById('modalStatusBadge');
            if (cek.status_kondisi === 'baik') {
                badgeBox.innerHTML = '<span class="ob-badge-baik px-3 py-1 text-xs font-bold rounded-full">KONDISI BAIK</span>';
            } else {
                badgeBox.innerHTML = '<span class="ob-badge-temuan px-3 py-1 text-xs font-bold rounded-full">PERLU TINDAKAN (TEMUAN)</span>';
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
                        ? '<span style="color: #16A34A; font-weight: 700;">&#10003;</span>'
                        : '<span style="color: #DC2626; font-weight: 700;">&#10007;</span>';
                    const textStyle = isSesuai ? 'color: #334155;' : 'color: #B91C1C; font-weight: 600;';
                    const namaButir = detail.checklist ? detail.checklist.nama_butir : 'Butir Checklist';

                    const itemDiv = document.createElement('div');
                    itemDiv.className = 'flex items-center justify-between p-2 rounded-lg text-xs';
                    itemDiv.style.cssText = 'background: #F1F7FC; border: 1px solid #D6E8F5;';
                    itemDiv.innerHTML = `
                        <span style="${textStyle}">${namaButir}</span>
                        <span class="flex items-center gap-1">${icon} <span class="capitalize text-[11px]">${detail.status}</span></span>
                    `;
                    checklistContainer.appendChild(itemDiv);
                });
            } else {
                checklistContainer.innerHTML = '<p class="text-xs" style="color: #94A3B8;">Tidak ada rincian checklist.</p>';
            }

            document.getElementById('detailModal').classList.remove('hidden');
        }

        function closeDetailModal() {
            document.getElementById('detailModal').classList.add('hidden');
        }
    </script>

</x-app-layout>