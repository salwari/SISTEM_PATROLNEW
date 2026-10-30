<aside id="drawer-navigation"
    class="fixed top-0 left-0 z-40 w-64 h-screen pt-14 transition-transform bg-white border-r border-gray-200 
           -translate-x-full md:translate-x-0 dark:bg-gray-800 dark:border-gray-700">

    <div class="h-full overflow-y-auto py-5 px-3 bg-white dark:bg-gray-800">
        <ul class="space-y-2">

            
            <li class="px-3 text-sm font-semibold text-gray-500 uppercase mb-2">Dashboard</li>
            <li>
                <a href="{{ route('admin.dashboard') }}"
                    class="flex items-center p-2 text-base font-medium rounded-lg transition duration-75 group
                        {{ request()->routeIs('admin.dashboard') ? 'bg-gray-200 text-gray-900 dark:bg-gray-700 dark:text-white' : 'text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                    <svg class="w-6 h-6 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"
                        fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path>
                        <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path>
                    </svg>
                    <span class="ml-3">Dashboard</span>
                </a>
            </li>

            
            <li class="px-3 text-sm font-semibold text-gray-500 uppercase mt-6 mb-2">Master Data</li>
            <li>
                <a href="{{ route('data-pengguna.index') }}"
                    class="flex items-center p-2 text-base font-medium rounded-lg transition duration-75 group
                        {{ request()->routeIs('data-pengguna.*') ? 'bg-gray-200 text-gray-900 dark:bg-gray-700 dark:text-white' : 'text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                    <svg class="w-6 h-6 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"
                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path fill-rule="evenodd" d="M8 4a4 4 0 1 0 0 8 4 4 0 0 0 0-8Zm-2 9a4 4 0 0 0-4 4v1a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2v-1a4 4 0 0 0-4-4H6Zm7.25-2.095c.478-.86.75-1.85.75-2.905a5.973 5.973 0 0 0-.75-2.906 4 4 0 1 1 0 5.811ZM15.466 20c.34-.588.535-1.271.535-2v-1a5.978 5.978 0 0 0-1.528-4H18a4 4 0 0 1 4 4v1a2 2 0 0 1-2 2h-4.535Z" clip-rule="evenodd" />
                    </svg>
                    <span class="ml-3">Data Pengguna</span>
                </a>
            </li>

            
            @php $isPatroliActive = request()->routeIs('kategori-kejadian.*', 'lokasi-patroli.*', 'jadwal-patroli.*', 'admin.data-patroli'); @endphp
            <li>
                <button type="button" onclick="document.getElementById('dropdown-patroli').classList.toggle('hidden')"
                    class="flex items-center w-full p-2 text-base font-medium transition duration-75 rounded-lg group hover:bg-gray-100 dark:hover:bg-gray-700 
                           {{ $isPatroliActive ? 'text-gray-900 bg-gray-100 dark:bg-gray-700 dark:text-white' : 'text-gray-900 dark:text-white' }}">
                    
                    
                    <svg class="w-6 h-6 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                      <path fill-rule="evenodd" d="M12.516 2.17a.75.75 0 00-1.032 0 11.209 11.209 0 01-7.877 3.08.75.75 0 00-.722.515A12.74 12.74 0 002.25 9.735c0 5.906 3.01 11.4 8.04 14.28 1.05.6 2.37.6 3.42 0 5.03-2.88 8.04-8.374 8.04-14.28a12.738 12.738 0 00-.635-3.97.75.75 0 00-.722-.515 11.209 11.209 0 01-7.877-3.08z" clip-rule="evenodd" />
                    </svg>

                    <span class="flex-1 ml-3 text-left whitespace-nowrap">Patroli</span>
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
                <ul id="dropdown-patroli" class="{{ $isPatroliActive ? '' : 'hidden' }} py-2 space-y-2">
                    <li>
                        <a href="{{ route('kategori-kejadian.index') }}" class="flex items-center w-full p-2 text-base font-medium rounded-lg pl-11 group transition duration-75 
                            {{ request()->routeIs('kategori-kejadian.*') ? 'text-blue-600 bg-gray-50 dark:bg-gray-600 dark:text-blue-400' : 'text-gray-900 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700' }}">Kategori Kejadian</a>
                    </li>
                    <li>
                        <a href="{{ route('lokasi-patroli.index') }}" class="flex items-center w-full p-2 text-base font-medium rounded-lg pl-11 group transition duration-75 
                            {{ request()->routeIs('lokasi-patroli.*') ? 'text-blue-600 bg-gray-50 dark:bg-gray-600 dark:text-blue-400' : 'text-gray-900 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700' }}">Lokasi Patroli</a>
                    </li>
                    <li>
                        <a href="{{ route('jadwal-patroli.index') }}" class="flex items-center w-full p-2 text-base font-medium rounded-lg pl-11 group transition duration-75 
                            {{ request()->routeIs('jadwal-patroli.*') ? 'text-blue-600 bg-gray-50 dark:bg-gray-600 dark:text-blue-400' : 'text-gray-900 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700' }}">Jadwal Patroli</a>
                    </li>
                    <li>
                        <a href="{{ route('admin.data-patroli') }}" class="flex items-center w-full p-2 text-base font-medium rounded-lg pl-11 group transition duration-75 
                            {{ request()->routeIs('admin.data-patroli') ? 'text-blue-600 bg-gray-50 dark:bg-gray-600 dark:text-blue-400' : 'text-gray-900 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700' }}">Riwayat Patroli</a>
                    </li>
                </ul>
            </li>

            
            @php $isKebersihanActive = request()->routeIs('kategori-kebersihan.*', 'lokasi-kebersihan.*', 'jadwal-kebersihan.*', 'riwayat-kebersihan.*'); @endphp
            <li>
                <button type="button" onclick="document.getElementById('dropdown-kebersihan').classList.toggle('hidden')"
                    class="flex items-center w-full p-2 text-base font-medium transition duration-75 rounded-lg group hover:bg-gray-100 dark:hover:bg-gray-700 
                           {{ $isKebersihanActive ? 'text-gray-900 bg-gray-100 dark:bg-gray-700 dark:text-white' : 'text-gray-900 dark:text-white' }}">
                    
                    
                    <svg class="w-6 h-6 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                      <path fill-rule="evenodd" d="M9 4.5a.75.75 0 01.721.544l.813 2.846a3.75 3.75 0 002.576 2.576l2.846.813a.75.75 0 010 1.442l-2.846.813a3.75 3.75 0 00-2.576 2.576l-.813 2.846a.75.75 0 01-1.442 0l-.813-2.846a3.75 3.75 0 00-2.576-2.576l-2.846-.813a.75.75 0 010-1.442l2.846-.813A3.75 3.75 0 007.466 7.89l.813-2.846A.75.75 0 019 4.5zM18 1.5a.75.75 0 01.728.568l.258 1.036c.236.94.97 1.674 1.91 1.91l1.036.258a.75.75 0 010 1.456l-1.036.258c-.94.236-1.674.97-1.91 1.91l-.258 1.036a.75.75 0 01-1.456 0l-.258-1.036a2.625 2.625 0 00-1.91-1.91l-1.036-.258a.75.75 0 010-1.456l1.036-.258a2.625 2.625 0 001.91-1.91l.258-1.036A.75.75 0 0118 1.5zM16.5 15a.75.75 0 01.712.513l.394 1.183c.15.447.5.799.948.948l1.183.395a.75.75 0 010 1.422l-1.183.395c-.447.15-.799.5-.948.948l-.395 1.183a.75.75 0 01-1.422 0l-.395-1.183a1.5 1.5 0 00-.948-.948l-1.183-.395a.75.75 0 010-1.422l1.183-.395c.447-.15.799-.5.948-.948l.395-1.183A.75.75 0 0116.5 15z" clip-rule="evenodd" />
                    </svg>

                    <span class="flex-1 ml-3 text-left whitespace-nowrap">Kebersihan</span>
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
                <ul id="dropdown-kebersihan" class="{{ $isKebersihanActive ? '' : 'hidden' }} py-2 space-y-2">
                    <li>
                        <a href="{{ route('kategori-kebersihan.index') }}" class="flex items-center w-full p-2 text-base font-medium rounded-lg pl-11 group transition duration-75 
                            {{ request()->routeIs('kategori-kebersihan.*') ? 'text-blue-600 bg-gray-50 dark:bg-gray-600 dark:text-blue-400' : 'text-gray-900 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700' }}">Kategori Kebersihan</a>
                    </li>
                    <li>
                        <a href="{{ route('lokasi-kebersihan.index') }}" class="flex items-center w-full p-2 text-base font-medium rounded-lg pl-11 group transition duration-75 
                            {{ request()->routeIs('lokasi-kebersihan.*') ? 'text-blue-600 bg-gray-50 dark:bg-gray-600 dark:text-blue-400' : 'text-gray-900 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700' }}">Lokasi Kebersihan</a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center w-full p-2 text-base font-medium rounded-lg pl-11 group transition duration-75 
                            {{ request()->routeIs('jadwal-kebersihan.*') ? 'text-blue-600 bg-gray-50 dark:bg-gray-600 dark:text-blue-400' : 'text-gray-900 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700' }}">Jadwal Kebersihan</a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center w-full p-2 text-base font-medium rounded-lg pl-11 group transition duration-75 
                            {{ request()->routeIs('riwayat-kebersihan.*') ? 'text-blue-600 bg-gray-50 dark:bg-gray-600 dark:text-blue-400' : 'text-gray-900 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700' }}">Riwayat Kebersihan</a>
                    </li>
                </ul>
            </li>

            
            @php $isKendaraanActive = request()->routeIs('kategori-kendaraan.*', 'data-kendaraan.*', 'riwayat-kendaraan.*'); @endphp
            <li>
                <button type="button" onclick="document.getElementById('dropdown-kendaraan').classList.toggle('hidden')"
                    class="flex items-center w-full p-2 text-base font-medium transition duration-75 rounded-lg group hover:bg-gray-100 dark:hover:bg-gray-700 
                           {{ $isKendaraanActive ? 'text-gray-900 bg-gray-100 dark:bg-gray-700 dark:text-white' : 'text-gray-900 dark:text-white' }}">
                    
                    
                    <svg class="w-6 h-6 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                      <path d="M3.375 4.5C2.339 4.5 1.5 5.34 1.5 6.375V13.5h12V6.375c0-1.036-.84-1.875-1.875-1.875h-8.25zM13.5 15h-12v2.625c0 1.035.84 1.875 1.875 1.875h.375a3 3 0 106 0h3a.75.75 0 00.75-.75V15z" />
                      <path d="M8.25 19.5a1.5 1.5 0 10-3 0 1.5 1.5 0 003 0zM15 6.75a.75.75 0 00-.75.75V13.5h5.348c.128 0 .253-.05.346-.142l3.129-3.129a.75.75 0 00.22-.53V8.25a.75.75 0 00-.75-.75h-7.53zM15 15v.75a.75.75 0 00.75.75h.375a3 3 0 106 0h.375a.75.75 0 00.75-.75V15h-8.25zM19.5 19.5a1.5 1.5 0 10-3 0 1.5 1.5 0 003 0z" />
                    </svg>

                    <span class="flex-1 ml-3 text-left whitespace-nowrap">Kendaraan</span>
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
                <ul id="dropdown-kendaraan" class="{{ $isKendaraanActive ? '' : 'hidden' }} py-2 space-y-2">
                    <li>
                        <a href="#" class="flex items-center w-full p-2 text-base font-medium rounded-lg pl-11 group transition duration-75 
                            {{ request()->routeIs('kategori-kendaraan.*') ? 'text-blue-600 bg-gray-50 dark:bg-gray-600 dark:text-blue-400' : 'text-gray-900 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700' }}">Kategori Kendaraan</a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center w-full p-2 text-base font-medium rounded-lg pl-11 group transition duration-75 
                            {{ request()->routeIs('data-kendaraan.*') ? 'text-blue-600 bg-gray-50 dark:bg-gray-600 dark:text-blue-400' : 'text-gray-900 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700' }}">Data Kendaraan</a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center w-full p-2 text-base font-medium rounded-lg pl-11 group transition duration-75 
                            {{ request()->routeIs('riwayat-kendaraan.*') ? 'text-blue-600 bg-gray-50 dark:bg-gray-600 dark:text-blue-400' : 'text-gray-900 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700' }}">Riwayat Kendaraan</a>
                    </li>
                </ul>
            </li>

        </ul>
    </div>
</aside>