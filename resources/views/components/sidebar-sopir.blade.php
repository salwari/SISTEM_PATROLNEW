<aside id="drawer-navigation"
    class="fixed top-0 left-0 z-40 w-64 h-screen pt-14 transition-transform bg-white border-r border-gray-200 
           -translate-x-full md:translate-x-0 dark:bg-gray-800 dark:border-gray-700">

    <div class="overflow-y-auto py-5 px-3 h-full bg-white dark:bg-gray-800">
        <ul class="space-y-3">

            <li>
                <a href="{{ route('sopir.dashboard') }}"
                   class="flex items-center p-2 text-base font-medium rounded-lg transition duration-75 group
                   {{ request()->routeIs('sopir.dashboard') ? 'bg-gray-200 text-gray-900 dark:bg-gray-700 dark:text-white' : 'text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                    <svg aria-hidden="true"
                         class="w-7 h-7 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"
                         fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path>
                        <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path>
                    </svg>
                    <span class="ml-3">Dashboard</span>
                </a>
            </li>

            <li>
                <a href="{{ route('sopir.scan-qr') }}"
                   class="flex items-center p-2 text-base font-medium rounded-lg transition duration-75 group
                   {{ request()->routeIs('sopir.scan-qr') ? 'bg-gray-200 text-gray-900 dark:bg-gray-700 dark:text-white' : 'text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                    <svg class="w-6 h-6 text-gray-500 transition duration-75 group-hover:text-gray-900" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" fill="currentColor">
                        <path d="M149.1 64.8L138.7 96 64 96C28.7 96 0 124.7 0 160L0 416c0 35.3 28.7 64 64 64l384 0c35.3 0 64-28.7 64-64l0-256c0-35.3-28.7-64-64-64l-74.7 0L362.9 64.8C356.4 45.2 338.1 32 317.4 32L194.6 32c-20.7 0-39 13.2-45.5 32.8zM256 192a96 96 0 1 1 0 192 96 96 0 1 1 0-192z"/>
                    </svg>
                    <span class="ml-3">Scan QR</span>
                </a>
            </li>

            <li>
                <a href="{{ route('sopir.riwayat-absen') }}"
                   class="flex items-center p-2 text-base font-medium rounded-lg transition duration-75 group
                   {{ request()->routeIs('sopir.riwayat-absen') ? 'bg-gray-200 text-gray-900 dark:bg-gray-700 dark:text-white' : 'text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                    <svg class="w-6 h-6 text-gray-500 transition duration-75 group-hover:text-gray-900" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" fill="currentColor">
                        <path d="M75 75L41 41C25.9 25.9 0 36.6 0 57.9L0 168c0 13.3 10.7 24 24 24l110.1 0c21.4 0 32.1-25.9 17-41l-30.8-30.8C155 85.5 203 64 256 64c106 0 192 86 192 192s-86 192-192 192c-40.8 0-78.6-12.7-109.7-34.4c-14.5-10.1-34.4-6.6-44.6 7.9s-6.6 34.4 7.9 44.6C151.2 495 201.7 512 256 512c141.4 0 256-114.6 256-256S397.4 0 256 0C185.3 0 121.3 28.7 75 75zm181 53c-13.3 0-24 10.7-24 24l0 104c0 6.4 2.5 12.5 7 17l72 72c9.4 9.4 24.6 9.4 33.9 0s9.4-24.6 0-33.9l-65-65 0-94.1c0-13.3-10.7-24-24-24z"/>
                    </svg>
                    <span class="ml-3">Riwayat Patroli</span>
                </a>
            </li>

        </ul>
    </div>
</aside>