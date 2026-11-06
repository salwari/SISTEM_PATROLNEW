<x-app-layout :title="'Dashboard Petugas Security'">

    <style>
        .sc-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 1rem;
            box-shadow: 0 2px 8px rgba(15, 23, 42, 0.06);
        }
        .sc-row {
            transition: background 0.2s ease;
        }
        .sc-row:hover {
            background: #F1F7FC;
        }
        .sc-aktivitas::-webkit-scrollbar {
            width: 6px;
        }
        .sc-aktivitas::-webkit-scrollbar-thumb {
            background: #B9D8EE;
            border-radius: 9999px;
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
                    <p class="text-sm font-medium" style="color: rgba(255, 255, 255, 0.85);">{{ now()->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</p>
                    <h1 class="mt-1 text-2xl font-bold md:text-3xl" style="color: #ffffff;">Selamat datang, {{ Auth::user()->nama }}</h1>
                    <p class="mt-1 text-sm" style="color: rgba(255, 255, 255, 0.85);">Berikut adalah ringkasan kegiatan patrolimu hari ini.</p>
                </div>
                <div class="mt-4 px-6 py-3 text-center rounded-xl md:mt-0"
                    style="background: rgba(255, 255, 255, 0.18); border: 1px solid rgba(255, 255, 255, 0.25);">
                    <p class="text-3xl font-bold" style="color: #ffffff;">{{ number_format($persentase, 0) }}%</p>
                    <p class="text-xs font-medium uppercase tracking-wide" style="color: rgba(255, 255, 255, 0.85);">Selesai Hari Ini</p>
                </div>
            </div>
        </div>

        @if ($jumlahBelum > 0)
            <div class="p-4 rounded-xl"
                style="background: #FFFBEB; border: 1px solid #FDE68A; border-left: 4px solid #F59E0B; color: #92400E;">
                <strong>Perhatian!</strong> Masih ada <strong>{{ $jumlahBelum }}</strong> lokasi yang belum dipatroli
                hari
                ini.
                Segera lakukan patroli untuk menyelesaikan tugas hari ini.
            </div>
        @endif

        @if ($totalLokasi == 0)
            <div class="p-4 rounded-xl"
                style="background: #E6F3FB; border: 1px solid #B9D8EE; border-left: 4px solid #0780C9; color: #1B4A82;">
                <strong>Informasi :</strong> Hari ini kamu tidak memiliki jadwal patroli.
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

            <div class="sc-card p-6" style="border-top: 4px solid #0780C9;">
                <h2 class="text-lg font-semibold mb-4" style="color: #1B4A82;">Status Patroli Hari Ini</h2>

                <div class="space-y-2 text-sm" style="color: #475569;">
                    <p class="flex space-x-1">
                        <span class="w-40 text-left">Total titik lokasi</span>
                        <span>:</span>
                        <strong style="color: #1B4A82;">{{ $totalLokasi }}</strong>
                    </p>
                    <p class="flex space-x-1">
                        <span class="w-40 text-left">Lokasi sudah dipatroli</span>
                        <span>:</span>
                        <strong style="color: #16A34A;">{{ $jumlahSelesai }}</strong>
                    </p>
                    <p class="flex space-x-1">
                        <span class="w-40 text-left">Lokasi belum dipatroli</span>
                        <span>:</span>
                        <strong style="color: #DC2626;">{{ $jumlahBelum }}</strong>
                    </p>
                </div>

                <div class="mt-5">
                    <div class="relative overflow-hidden rounded-full" style="height: 1.125rem; background: #E6F3FB;">
                        <div class="h-full rounded-full transition-all duration-300 ease-in-out"
                            style="width: {{ $persentase }}%; background: linear-gradient(90deg, #035CA8 0%, #05A2E4 100%);"></div>
                        <div
                            class="absolute inset-0 flex items-center justify-center text-xs font-semibold" style="color: #1B4A82;">
                            {{ number_format($persentase, 1) }}%
                        </div>
                    </div>
                </div>
            </div>

            <div class="sc-card sc-aktivitas p-6 max-h-72 overflow-y-auto" style="border-top: 4px solid #05A2E4;">
                <h2 class="text-lg font-semibold mb-4" style="color: #1B4A82;">Aktivitas Terakhir</h2>
                <ul class="space-y-3 text-sm" style="color: #475569;">
                    @forelse ($aktivitasTerakhir as $patroli)
                        <li class="flex items-start space-x-2 p-2 rounded-xl" style="background: #F1F7FC; border: 1px solid #D6E8F5;">
                            <svg class="flex-shrink-0 mr-2" style="width: 1.25rem; height: 1.25rem; color: #16A34A;" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 448 512" fill="currentColor">
                                <path
                                    d="M64 32C28.7 32 0 60.7 0 96L0 416c0 35.3 28.7 64 64 64l320 0c35.3 0 64-28.7 64-64l0-320c0-35.3-28.7-64-64-64L64 32zM337 209L209 337c-9.4 9.4-24.6 9.4-33.9 0l-64-64c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l47 47L303 175c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9z" />
                            </svg>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm leading-tight break-words">
                                    <span class="font-semibold" style="color: #1B4A82;">{{ $patroli->waktu_patroli }}</span> -
                                    <span>{{ $patroli->lokasiPatroli->nama_lokasi }}</span>
                                </p>
                            </div>
                        </li>
                    @empty
                        <li style="color: #64748B;">Belum ada aktivitas hari ini.</li>
                    @endforelse
                </ul>
            </div>
        </div>

        <div class="sc-card p-6">
            <h2 class="text-lg font-bold mb-4" style="color: #1B4A82;">Daftar Lokasi Belum Dipatroli</h2>
            <div class="overflow-x-auto rounded-xl" style="border: 1px solid #e2e8f0;">
                <table class="table-auto w-full text-sm" style="color: #334155;">
                    <thead class="uppercase text-xs" style="background: #E6F3FB; color: #1B4A82;">
                        <tr class="text-center">
                            <th class="px-4 py-3">No</th>
                            <th class="px-4 py-3 text-left">Nama Lokasi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y" style="background: #ffffff; border-color: #e2e8f0;">
                        @forelse ($belumDipatroli as $index => $lokasi)
                            <tr class="sc-row text-center">
                                <td class="px-4 py-3">{{ $index + 1 }}</td>
                                <td class="px-4 py-3 text-left font-semibold" style="color: #035CA8;">{{ $lokasi->nama_lokasi }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="px-4 py-6 text-center" style="color: #64748B;">Semua lokasi sudah
                                    dipatroli.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>