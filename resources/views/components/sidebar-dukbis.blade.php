<aside id="drawer-navigation"
    class="fixed top-0 left-0 z-40 w-64 h-screen pt-14 transition-transform bg-white border-r border-gray-200 
            -translate-x-full md:translate-x-0 dark:bg-gray-800 dark:border-gray-700">

    <div class="h-full overflow-y-auto py-5 px-3 bg-white dark:bg-gray-800">
        <ul class="space-y-2">

            {{-- Dashboard --}}
            <li class="px-3 text-sm font-semibold text-gray-500 uppercase mb-2">Dashboard</li>
            <li>
                <a href="{{ route('kabid-dukbis.dashboard') }}"
                    class="flex items-center p-2 text-base font-medium rounded-lg transition duration-75 group
                        {{ request()->routeIs('kabid-dukbis.dashboard') ? 'bg-gray-200 text-gray-900 dark:bg-gray-700 dark:text-white' : 'text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                    <svg class="w-6 h-6 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"
                        fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path>
                        <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path>
                    </svg>
                    <span class="ml-3">Dashboard</span>
                </a>
            </li>

            {{-- Menu Navigasi / Fitur --}}
            <li class="px-3 text-sm font-semibold text-gray-500 uppercase mt-6 mb-2">Menu Utama</li>

            {{-- Patroli Dropdown --}}
            @php
                $isKeamananActive = request()->routeIs('kabid-dukbis.data-petugas-security', 'kabid-dukbis.data-lokasi-patroli', 'kabid-dukbis.laporan-patroli*');
                $menuKeamanan = [
                    ['label' => 'Data Petugas Security', 'route' => 'kabid-dukbis.data-petugas-security', 'active' => request()->routeIs('kabid-dukbis.data-petugas-security')],
                    ['label' => 'Data Lokasi Patroli', 'route' => 'kabid-dukbis.data-lokasi-patroli', 'active' => request()->routeIs('kabid-dukbis.data-lokasi-patroli')],
                    ['label' => 'Laporan Patroli', 'route' => 'kabid-dukbis.laporan-patroli', 'active' => request()->routeIs('kabid-dukbis.laporan-patroli*')],
                ];
            @endphp
            <li>
                <button type="button" onclick="toggleSidebarDropdown('keamanan')"
                    class="flex items-center w-full p-2 text-base font-medium transition duration-75 rounded-lg group hover:bg-gray-100 dark:hover:bg-gray-700 
                            {{ $isKeamananActive ? 'text-gray-900 bg-gray-100 dark:bg-gray-700 dark:text-white' : 'text-gray-900 dark:text-white' }}">
                    
                    <svg class="w-6 h-6 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path fill-rule="evenodd" d="M12.516 2.17a.75.75 0 00-1.032 0 11.209 11.209 0 01-7.877 3.08.75.75 0 00-.722.515A12.74 12.74 0 002.25 9.735c0 5.906 3.01 11.4 8.04 14.28 1.05.6 2.37.6 3.42 0 5.03-2.88 8.04-8.374 8.04-14.28a12.738 12.738 0 00-.635-3.97.75.75 0 00-.722-.515 11.209 11.209 0 01-7.877-3.08z" clip-rule="evenodd" />
                    </svg>

                    <span class="flex-1 ml-3 text-left whitespace-nowrap">Patroli</span>
                    <svg id="chevron-keamanan" class="w-6 h-6 text-gray-400" style="transition: transform 0.3s ease; transform: rotate({{ $isKeamananActive ? '180deg' : '0deg' }});" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
                <div id="dropdown-keamanan" style="display: grid; transition: grid-template-rows 0.3s ease; grid-template-rows: {{ $isKeamananActive ? '1fr' : '0fr' }};">
                    <ul class="space-y-1 py-1" style="overflow: hidden; min-height: 0;">
                        @foreach ($menuKeamanan as $item)
                            <li>
                                <a href="{{ route($item['route']) }}"
                                    class="flex items-center gap-3 w-full p-2 pl-11 text-base font-medium rounded-lg transition-all duration-200
                                        {{ $item['active'] ? 'text-blue-600 bg-blue-50 dark:bg-gray-600 dark:text-blue-400' : 'text-gray-900 hover:bg-gray-100 hover:pl-12 dark:text-white dark:hover:bg-gray-700' }}">
                                    <span class="w-1.5 h-1.5 rounded-full shrink-0 {{ $item['active'] ? 'bg-blue-600 dark:bg-blue-400' : 'bg-gray-300 dark:bg-gray-500' }}"></span>
                                    <span>{{ $item['label'] }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </li>

            {{-- Absen Kendaraan (Single Menu) --}}
            <li>
                <a href="{{ route('kabid-dukbis.absen-kendaraan') }}"
                    class="flex items-center p-2 text-base font-medium rounded-lg transition duration-75 group
                    {{ request()->routeIs('kabid-dukbis.absen-kendaraan') ? 'bg-gray-200 text-gray-900 dark:bg-gray-700 dark:text-white' : 'text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                    <svg class="w-6 h-6 text-gray-500 transition duration-75 group-hover:text-gray-900" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" fill="currentColor">
                        <path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM369 209L241 337c-9.4 9.4-24.6 9.4-33.9 0l-64-64c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l47 47L335 175c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9z"/>
                    </svg>
                    <span class="ml-3">Absen Kendaraan</span>
                </a>
            </li>

            {{-- Kebersihan Dropdown --}}
            @php 
                $isKebersihanActive = request()->routeIs('kabid-dukbis.data-lokasi-kebersihan', 'kabid-dukbis.temuan-kamar-mandi', 'kabid-dukbis.laporan-kebersihan');
                $menuKebersihan = [
                    ['label' => 'Data Lokasi Kebersihan', 'route' => 'kabid-dukbis.data-lokasi-kebersihan', 'active' => request()->routeIs('kabid-dukbis.data-lokasi-kebersihan')],
                    ['label' => 'Temuan Kamar Mandi', 'route' => 'kabid-dukbis.temuan-kamar-mandi', 'active' => request()->routeIs('kabid-dukbis.temuan-kamar-mandi')],
                    ['label' => 'Laporan Kebersihan', 'route' => 'kabid-dukbis.laporan-kebersihan', 'active' => request()->routeIs('kabid-dukbis.laporan-kebersihan')],
                ];
            @endphp
            <li>
                <button type="button" onclick="toggleSidebarDropdown('kebersihan')"
                    class="flex items-center w-full p-2 text-base font-medium transition duration-75 rounded-lg group hover:bg-gray-100 dark:hover:bg-gray-700 
                            {{ $isKebersihanActive ? 'text-gray-900 bg-gray-100 dark:bg-gray-700 dark:text-white' : 'text-gray-900 dark:text-white' }}">
                    
                    <svg class="w-6 h-6 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path fill-rule="evenodd" d="M9 4.5a.75.75 0 01.721.544l.813 2.846a3.75 3.75 0 002.576 2.576l2.846.813a.75.75 0 010 1.442l-2.846.813a3.75 3.75 0 00-2.576 2.576l-.813 2.846a.75.75 0 01-1.442 0l-.813-2.846a3.75 3.75 0 00-2.576-2.576l-2.846-.813a.75.75 0 010-1.442l2.846-.813A3.75 3.75 0 007.466 7.89l.813-2.846A.75.75 0 019 4.5zM18 1.5a.75.75 0 01.728.568l.258 1.036c.236.94.97 1.674 1.91 1.91l1.036.258a.75.75 0 010 1.456l-1.036.258c-.94.236-1.674.97-1.91 1.91l-.258 1.036a.75.75 0 01-1.456 0l-.258-1.036a2.625 2.625 0 00-1.91-1.91l-1.036-.258a.75.75 0 010-1.456l1.036-.258a2.625 2.625 0 001.91-1.91l.258-1.036A.75.75 0 0118 1.5zM16.5 15a.75.75 0 01.712.513l.394 1.183c.15.447.5.799.948.948l1.183.395a.75.75 0 010 1.422l-1.183.395c-.447.15-.799.5-.948.948l-.395 1.183a.75.75 0 01-1.422 0l-.395-1.183a1.5 1.5 0 00-.948-.948l-1.183-.395a.75.75 0 010-1.422l1.183-.395c.447-.15.799-.5.948-.948l.395-1.183A.75.75 0 0116.5 15z" clip-rule="evenodd" />
                    </svg>

                    <span class="flex-1 ml-3 text-left whitespace-nowrap">Kebersihan</span>
                    <svg id="chevron-kebersihan" class="w-6 h-6 text-gray-400" style="transition: transform 0.3s ease; transform: rotate({{ $isKebersihanActive ? '180deg' : '0deg' }});" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
                <div id="dropdown-kebersihan" style="display: grid; transition: grid-template-rows 0.3s ease; grid-template-rows: {{ $isKebersihanActive ? '1fr' : '0fr' }};">
                    <ul class="space-y-1 py-1" style="overflow: hidden; min-height: 0;">
                        @foreach ($menuKebersihan as $item)
                            <li>
                                <a href="{{ route($item['route']) }}"
                                    class="flex items-center gap-3 w-full p-2 pl-11 text-base font-medium rounded-lg transition-all duration-200
                                        {{ $item['active'] ? 'text-blue-600 bg-blue-50 dark:bg-gray-600 dark:text-blue-400' : 'text-gray-900 hover:bg-gray-100 hover:pl-12 dark:text-white dark:hover:bg-gray-700' }}">
                                    <span class="w-1.5 h-1.5 rounded-full shrink-0 {{ $item['active'] ? 'bg-blue-600 dark:bg-blue-400' : 'bg-gray-300 dark:bg-gray-500' }}"></span>
                                    <span>{{ $item['label'] }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </li>

        </ul>
    </div>
</aside>

<script>
    function toggleSidebarDropdown(name) {
        var panel = document.getElementById('dropdown-' + name);
        var chevron = document.getElementById('chevron-' + name);
        var isOpen = panel.style.gridTemplateRows.indexOf('1fr') !== -1;
        panel.style.gridTemplateRows = isOpen ? '0fr' : '1fr';
        chevron.style.transform = isOpen ? 'rotate(0deg)' : 'rotate(180deg)';
    }
</script>