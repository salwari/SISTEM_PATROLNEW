<aside id="drawer-navigation"
    class="fixed top-0 left-0 z-40 w-64 h-screen pt-14 transition-transform bg-white border-r border-gray-200 
           -translate-x-full md:translate-x-0 dark:bg-gray-800 dark:border-gray-700">

    <div class="h-full overflow-y-auto py-5 px-3 bg-white dark:bg-gray-800">
        <ul class="space-y-2">

            <li class="px-3 text-sm font-semibold text-gray-500 uppercase">Dashboard</li>
            <li>
                <a href="{{ route('super-admin.dashboard') }}"
                    class="flex items-center p-2 text-base font-medium rounded-lg transition duration-75 group
                        {{ request()->routeIs('super-admin.dashboard') ? 'bg-gray-200 text-gray-900 dark:bg-gray-700 dark:text-white' : 'text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                    <svg aria-hidden="true"
                        class="w-6 h-6 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"
                        fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path>
                        <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path>
                    </svg>
                    <span class="ml-3">Dashboard</span>
                </a>
            </li>

            <li class="px-3 text-sm font-semibold text-gray-500 uppercase mt-6">Master Data</li>
            <li>
                <a href="{{ route('data-cabang.index') }}"
                    class="flex items-center p-2 text-base font-medium rounded-lg transition duration-75 group
                    {{ request()->routeIs('data-cabang.*') ? 'bg-gray-200 text-gray-900 dark:bg-gray-700 dark:text-white' : 'text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                    <svg class="w-6 h-6 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"
                        fill="currentColor" viewBox="0 0 640 640" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M352 224L352 320L480 320L480 224L352 224zM288 224L160 224L160 320L288 320L288 224zM96 384L96 160C96 124.7 124.7 96 160 96L480 96C515.3 96 544 124.7 544 160L544 480C544 515.3 515.3 544 480 544L160 544C124.7 544 96 515.3 96 480L96 384zM480 384L352 384L352 480L480 480L480 384zM288 480L288 384L160 384L160 480L288 480z" />
                    </svg>
                    <span class="ml-3">Data Cabang</span>
                </a>
            </li>

            <li class="px-3 text-sm font-semibold text-gray-500 uppercase mt-6">Menu Utama</li>

            @php
                $menuGroups = [
                    [
                        'key' => 'patroli',
                        'label' => 'Patroli',
                        'viewBox' => '0 0 24 24',
                        'icon' => 'M12.516 2.17a.75.75 0 00-1.032 0 11.209 11.209 0 01-7.877 3.08.75.75 0 00-.722.515A12.74 12.74 0 002.25 9.735c0 5.906 3.01 11.4 8.04 14.28 1.05.6 2.37.6 3.42 0 5.03-2.88 8.04-8.374 8.04-14.28a12.738 12.738 0 00-.635-3.97.75.75 0 00-.722-.515 11.209 11.209 0 01-7.877-3.08z',
                        'items' => [
                            ['label' => 'Data Petugas Security', 'route' => 'super-admin.data-petugas'],
                            ['label' => 'Data Lokasi Patroli', 'route' => 'super-admin.data-lokasi'],
                            ['label' => 'Data Patroli', 'route' => 'super-admin.data-patroli-petugas'],
                        ],
                    ],
                    [
                        'key' => 'kebersihan',
                        'label' => 'Kebersihan',
                        'viewBox' => '0 0 24 24',
                        'icon' => 'M9 4.5a.75.75 0 01.721.544l.813 2.846a3.75 3.75 0 002.576 2.576l2.846.813a.75.75 0 010 1.442l-2.846.813a3.75 3.75 0 00-2.576 2.576l-.813 2.846a.75.75 0 01-1.442 0l-.813-2.846a3.75 3.75 0 00-2.576-2.576l-2.846-.813a.75.75 0 010-1.442l2.846-.813A3.75 3.75 0 007.466 7.89l.813-2.846A.75.75 0 019 4.5zM18 1.5a.75.75 0 01.728.568l.258 1.036c.236.94.97 1.674 1.91 1.91l1.036.258a.75.75 0 010 1.456l-1.036.258c-.94.236-1.674.97-1.91 1.91l-.258 1.036a.75.75 0 01-1.456 0l-.258-1.036a2.625 2.625 0 00-1.91-1.91l-1.036-.258a.75.75 0 010-1.456l1.036-.258a2.625 2.625 0 001.91-1.91l.258-1.036A.75.75 0 0118 1.5zM16.5 15a.75.75 0 01.712.513l.394 1.183c.15.447.5.799.948.948l1.183.395a.75.75 0 010 1.422l-1.183.395c-.447.15-.799.5-.948.948l-.395 1.183a.75.75 0 01-1.422 0l-.395-1.183a1.5 1.5 0 00-.948-.948l-1.183-.395a.75.75 0 010-1.422l1.183-.395c.447-.15.799-.5.948-.948l.395-1.183A.75.75 0 0116.5 15z',
                        'items' => [
                            ['label' => 'Data OB', 'route' => 'super-admin.data-ob'],
                            ['label' => 'Data Lokasi Kebersihan', 'route' => 'super-admin.data-lokasi-kebersihan'],
                        ],
                    ],
                    [
                        'key' => 'kendaraan',
                        'label' => 'Kendaraan',
                        'viewBox' => '0 0 24 24',
                        'icon' => 'M18.92 6.01C18.72 5.42 18.16 5 17.5 5h-11c-.66 0-1.21.42-1.42 1.01L3 12v8c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1h12v1c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-8l-2.08-5.99zM6.5 16c-.83 0-1.5-.67-1.5-1.5S5.67 13 6.5 13s1.5.67 1.5 1.5S7.33 16 6.5 16zm11 0c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zM5 11l1.5-4.5h11L19 11H5z',
                        'items' => [
                            ['label' => 'Data Sopir', 'route' => 'super-admin.data-sopir'],
                            ['label' => 'Data Kendaraan', 'route' => 'super-admin.data-kendaraan'],
                        ],
                    ],
                ];
            @endphp

            @foreach ($menuGroups as $group)
                @php
                    $groupActive = collect($group['items'])->contains(fn($item) => request()->routeIs($item['route']));
                @endphp
                <li>
                    <button type="button" onclick="toggleSidebarDropdown('{{ $group['key'] }}')"
                        class="flex items-center w-full p-2 text-base font-medium transition duration-75 rounded-lg group hover:bg-gray-100 dark:hover:bg-gray-700 
                                {{ $groupActive ? 'text-gray-900 bg-gray-100 dark:bg-gray-700 dark:text-white' : 'text-gray-900 dark:text-white' }}">

                        <svg class="w-6 h-6 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"
                            xmlns="http://www.w3.org/2000/svg" viewBox="{{ $group['viewBox'] }}" fill="currentColor">
                            <path fill-rule="evenodd" d="{{ $group['icon'] }}" clip-rule="evenodd" />
                        </svg>

                        <span class="flex-1 ml-3 text-left whitespace-nowrap">{{ $group['label'] }}</span>
                        <svg id="chevron-{{ $group['key'] }}" class="w-6 h-6 text-gray-400"
                            style="transition: transform 0.3s ease; transform: rotate({{ $groupActive ? '180deg' : '0deg' }});"
                            fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </button>
                    <div id="dropdown-{{ $group['key'] }}"
                        style="display: grid; transition: grid-template-rows 0.3s ease; grid-template-rows: {{ $groupActive ? '1fr' : '0fr' }};">
                        <ul class="space-y-1 py-1" style="overflow: hidden; min-height: 0;">
                            @foreach ($group['items'] as $item)
                                @php $itemActive = request()->routeIs($item['route']); @endphp
                                <li>
                                    <a href="{{ route($item['route']) }}"
                                        class="flex items-center gap-3 w-full p-2 pl-11 text-base font-medium rounded-lg transition-all duration-200
                                            {{ $itemActive ? 'text-blue-600 bg-blue-50 dark:bg-gray-600 dark:text-blue-400' : 'text-gray-900 hover:bg-gray-100 hover:pl-12 dark:text-white dark:hover:bg-gray-700' }}">
                                        <span class="w-1.5 h-1.5 rounded-full shrink-0 {{ $itemActive ? 'bg-blue-600 dark:bg-blue-400' : 'bg-gray-300 dark:bg-gray-500' }}"></span>
                                        <span>{{ $item['label'] }}</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </li>
            @endforeach

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