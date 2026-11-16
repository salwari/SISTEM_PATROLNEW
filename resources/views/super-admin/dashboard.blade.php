<x-app-layout :title="'Dashboard Super Admin'">

    @php
        $kartu = [
            [
                'modul' => 'Cabang',
                'label' => 'Jumlah Cabang',
                'route' => 'data-cabang.index',
                'value' => $jumlahCabang,
                'viewBox' => '0 0 640 640',
                'icon' => 'M352 224L352 320L480 320L480 224L352 224zM288 224L160 224L160 320L288 320L288 224zM96 384L96 160C96 124.7 124.7 96 160 96L480 96C515.3 96 544 124.7 544 160L544 480C544 515.3 515.3 544 480 544L160 544C124.7 544 96 515.3 96 480L96 384zM480 384L352 384L352 480L480 480L480 384zM288 480L288 384L160 384L160 480L288 480z',
            ],
            [
                'modul' => 'Patroli',
                'label' => 'Petugas Security',
                'route' => 'super-admin.data-petugas',
                'value' => $jumlahPetugasSecurity,
                'viewBox' => '0 0 640 512',
                'icon' => 'M144 0a80 80 0 1 1 0 160A80 80 0 1 1 144 0zM512 0a80 80 0 1 1 0 160A80 80 0 1 1 512 0zM0 298.7C0 239.8 47.8 192 106.7 192l42.7 0c15.9 0 31 3.5 44.6 9.7c-1.3 7.2-1.9 14.7-1.9 22.3c0 38.2 16.8 72.5 43.3 96c-.2 0-.4 0-.7 0L21.3 320C9.6 320 0 310.4 0 298.7zM405.3 320c-.2 0-.4 0-.7 0c26.6-23.5 43.3-57.8 43.3-96c0-7.6-.7-15-1.9-22.3c13.6-6.3 28.7-9.7 44.6-9.7l42.7 0C592.2 192 640 239.8 640 298.7c0 11.8-9.6 21.3-21.3 21.3l-213.3 0zM224 224a96 96 0 1 1 192 0 96 96 0 1 1 -192 0zM128 485.3C128 411.7 187.7 352 261.3 352l117.3 0C452.3 352 512 411.7 512 485.3c0 14.7-11.9 26.7-26.7 26.7l-330.7 0c-14.7 0-26.7-11.9-26.7-26.7z',
            ],
            [
                'modul' => 'Patroli',
                'label' => 'Lokasi Patroli',
                'route' => 'super-admin.data-lokasi',
                'value' => $jumlahLokasiPatroli,
                'viewBox' => '0 0 384 512',
                'icon' => 'M215.7 499.2C267 435 384 279.4 384 192C384 86 298 0 192 0S0 86 0 192c0 87.4 117 243 168.3 307.2c12.3 15.3 35.1 15.3 47.4 0zM192 128a64 64 0 1 1 0 128 64 64 0 1 1 0-128z',
            ],
            [
                'modul' => 'Patroli',
                'label' => 'Total Patroli',
                'route' => 'super-admin.data-patroli-petugas',
                'value' => $jumlahPatroli,
                'viewBox' => '0 0 512 512',
                'icon' => 'M256 0c4.6 0 9.2 1 13.4 2.9L457.7 82.8c22 9.3 38.4 31 38.3 57.2c-.5 99.2-41.3 280.7-213.6 363.2c-16.7 8-36.1 8-52.8 0C57.3 420.7 16.5 239.2 16 140c-.1-26.2 16.3-47.9 38.3-57.2L242.7 2.9C246.8 1 251.4 0 256 0zm0 66.8l0 378.1C394 378 431.1 230.1 432 141.4L256 66.8z',
            ],
            [
                'modul' => 'Kebersihan',
                'label' => 'Office Boy (OB)',
                'route' => 'super-admin.data-ob',
                'value' => $jumlahOb,
                'viewBox' => '0 0 24 24',
                'icon' => 'M12 12c2.7 0 5-2.3 5-5s-2.3-5-5-5-5 2.3-5 5 2.3 5 5 5zm0 2c-3.3 0-10 1.7-10 5v3h20v-3c0-3.3-6.7-5-10-5z',
            ],
            [
                'modul' => 'Kebersihan',
                'label' => 'Lokasi Kebersihan',
                'route' => 'super-admin.data-lokasi-kebersihan',
                'value' => $jumlahLokasiKebersihan,
                'viewBox' => '0 0 20 20',
                'icon' => 'M10 2.5c-2.5 3.5-5 6-5 9a5 5 0 0010 0c0-3-2.5-5.5-5-9z',
            ],
            [
                'modul' => 'Kendaraan',
                'label' => 'Sopir',
                'route' => 'super-admin.data-sopir',
                'value' => $jumlahSopir,
                'viewBox' => '0 0 24 24',
                'icon' => 'M12 12c2.7 0 5-2.3 5-5s-2.3-5-5-5-5 2.3-5 5 2.3 5 5 5zm0 2c-3.3 0-10 1.7-10 5v3h20v-3c0-3.3-6.7-5-10-5z',
            ],
            [
                'modul' => 'Kendaraan',
                'label' => 'Kendaraan',
                'route' => 'super-admin.data-kendaraan',
                'value' => $jumlahKendaraan,
                'viewBox' => '0 0 24 24',
                'icon' => 'M5 11l1.5-4.5A2 2 0 018.4 5h7.2a2 2 0 011.9 1.5L19 11h.5a1.5 1.5 0 011.5 1.5V17a1 1 0 01-1 1h-1a2 2 0 01-4 0H9a2 2 0 01-4 0H4a1 1 0 01-1-1v-4.5A1.5 1.5 0 014.5 11H5z',
            ],
        ];

        $warna = [
            ['#1B4A82', '#035CA8'],
            ['#035CA8', '#0780C9'],
            ['#0560A8', '#0692D6'],
            ['#0780C9', '#05A2E4'],
            ['#1B4A82', '#0560A8'],
            ['#035CA8', '#0692D6'],
            ['#0560A8', '#0780C9'],
            ['#0780C9', '#05A2E4'],
        ];
    @endphp

    <style>
        .dash-kartu {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            transition: transform 0.3s ease, box-shadow 0.3s ease, border-color 0.3s ease;
            box-shadow: 0 2px 8px rgba(15, 23, 42, 0.06);
        }
        .dash-kartu:hover {
            transform: translateY(-4px);
            border-color: #0780C9;
            box-shadow: 0 14px 28px rgba(3, 92, 168, 0.18);
        }
        .dash-kartu .dash-bg-icon {
            transition: transform 0.3s ease;
        }
        .dash-kartu:hover .dash-bg-icon {
            transform: scale(1.1);
        }
        .dash-kartu .dash-panah {
            transition: transform 0.2s ease;
        }
        .dash-kartu:hover .dash-panah {
            transform: translateX(4px);
        }
        .dash-kartu .dash-link {
            color: #035CA8;
        }
        .dash-kartu:hover .dash-link {
            color: #1B4A82;
        }
    </style>

    <div class="relative mt-4 overflow-hidden rounded-2xl px-8 py-7"
        style="background: linear-gradient(110deg, #1B4A82 0%, #035CA8 35%, #0780C9 70%, #05A2E4 100%); box-shadow: 0 10px 25px rgba(3, 92, 168, 0.35);">
        <div class="absolute rounded-full"
            style="top: -4rem; right: -4rem; width: 16rem; height: 16rem; background: rgba(255, 255, 255, 0.1);"></div>
        <div class="absolute rounded-full"
            style="bottom: -6rem; right: 10rem; width: 14rem; height: 14rem; background: rgba(255, 255, 255, 0.1);"></div>
        <div class="relative z-10 flex flex-col gap-1 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-sm font-medium" style="color: rgba(255, 255, 255, 0.85);">{{ now()->isoFormat('dddd, D MMMM Y') }}</p>
                <h2 class="mt-1 text-2xl font-bold md:text-3xl" style="color: #ffffff;">Selamat datang, {{ auth()->user()->nama ?? 'Super Admin' }}</h2>
                <p class="mt-1 text-sm" style="color: rgba(255, 255, 255, 0.85);">Ringkasan data dari seluruh cabang. Klik kartu untuk melihat detail.</p>
            </div>
            <div class="mt-4 px-6 py-3 text-center rounded-xl md:mt-0"
                style="background: rgba(255, 255, 255, 0.18); border: 1px solid rgba(255, 255, 255, 0.25);">
                <p class="text-3xl font-bold" style="color: #ffffff;">{{ $jumlahCabang }}</p>
                <p class="text-xs font-medium uppercase tracking-wide" style="color: rgba(255, 255, 255, 0.85);">Cabang Aktif</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-5 mt-5 sm:grid-cols-2 lg:grid-cols-4 lg:grid-rows-2 lg:min-h-[calc(100vh-22rem)]">
        @foreach ($kartu as $i => $item)
            @php
                $w = $warna[$i % count($warna)];
            @endphp
            <a href="{{ route($item['route']) }}" class="block h-full">
                <div class="dash-kartu relative flex flex-col justify-between h-full p-6 overflow-hidden rounded-2xl"
                    style="min-height: 11rem; border-top: 4px solid {{ $w[1] }};">

                    <div class="absolute rounded-full"
                        style="top: -2.5rem; right: -2.5rem; width: 9rem; height: 9rem; background: #F1F7FC;"></div>
                    <svg class="dash-bg-icon absolute"
                        style="bottom: -1.5rem; right: -1.5rem; width: 9rem; height: 9rem; color: #EAF3FB;"
                        xmlns="http://www.w3.org/2000/svg" viewBox="{{ $item['viewBox'] }}" fill="currentColor">
                        <path d="{{ $item['icon'] }}" />
                    </svg>

                    <div class="relative z-10 flex items-start justify-between">
                        <div class="flex items-center justify-center rounded-xl"
                            style="width: 3.5rem; height: 3.5rem; background: linear-gradient(135deg, {{ $w[0] }} 0%, {{ $w[1] }} 100%); box-shadow: 0 6px 14px rgba(3, 92, 168, 0.25);">
                            <svg style="width: 1.75rem; height: 1.75rem; color: #ffffff;" xmlns="http://www.w3.org/2000/svg"
                                viewBox="{{ $item['viewBox'] }}" fill="currentColor">
                                <path d="{{ $item['icon'] }}" />
                            </svg>
                        </div>
                        <span class="px-3 py-1 text-xs font-semibold uppercase tracking-wide rounded-full"
                            style="background: #E6F3FB; color: #035CA8;">{{ $item['modul'] }}</span>
                    </div>

                    <div class="relative z-10">
                        <p class="text-5xl font-extrabold" style="color: #1B4A82;">{{ $item['value'] }}</p>
                        <h3 class="mt-1 text-base font-medium" style="color: #475569;">{{ $item['label'] }}</h3>
                    </div>

                    <div class="dash-link relative z-10 flex items-center text-sm font-semibold">
                        <span>Lihat data</span>
                        <svg class="dash-panah ml-1" style="width: 1rem; height: 1rem;"
                            fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                    </div>
                </div>
            </a>
        @endforeach
    </div>
</x-app-layout>