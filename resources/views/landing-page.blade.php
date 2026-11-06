<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>SIM PATROLI - Selamat Datang</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/sucofindo-nobg.png') }}" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
        }
    </script>
    <script>
        
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
    <style>
        html {
            scroll-behavior: smooth;
        }
    </style>
</head>

<body class="font-inter bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 transition-colors duration-200">

    <header class="bg-white/95 dark:bg-gray-900/95 backdrop-blur-md shadow-md border-b border-gray-100 dark:border-gray-800 fixed top-0 left-0 w-full z-50 transition-colors duration-200">
        <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">

            <div class="flex items-center space-x-3">
                <img class="h-auto w-20" src="{{ asset('assets/sucofindo-nobg.png') }}" alt="Logo SUCOFINDO" />
            </div>

            <div class="flex items-center space-x-2 md:hidden">
                <button type="button" onclick="toggleTheme()"
                    class="p-2 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg focus:outline-none transition">
                    <svg class="theme-icon-dark hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                    </svg>
                    <svg class="theme-icon-light hidden w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" fill-rule="evenodd" clip-rule="evenodd"></path>
                    </svg>
                </button>

                <a href="{{ route('login') }}"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2 rounded-full transition text-sm">
                    Login
                </a>

                <button id="menu-toggle" class="text-gray-800 dark:text-gray-200 p-2 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>

            <nav class="hidden md:flex space-x-8 text-base font-medium text-gray-700 dark:text-gray-200">
                <a href="#tentang" class="hover:text-blue-600 dark:hover:text-blue-400 transition">Tentang</a>
                <a href="#visi-misi" class="hover:text-blue-600 dark:hover:text-blue-400 transition">Visi & Misi</a>
                <a href="#fitur" class="hover:text-blue-600 dark:hover:text-blue-400 transition">Fitur</a>
                <a href="#kontak" class="hover:text-blue-600 dark:hover:text-blue-400 transition">Kontak</a>
            </nav>

            
            <div class="hidden md:flex items-center space-x-4">
                
                <button type="button" onclick="toggleTheme()"
                    class="p-2 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg focus:outline-none transition">
                    <svg class="theme-icon-dark hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                    </svg>
                    <svg class="theme-icon-light hidden w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" fill-rule="evenodd" clip-rule="evenodd"></path>
                    </svg>
                </button>

                @if (Route::has('login'))
                    <nav class="inline-block">
                        @auth
                            @if (auth()->user()->role === 'super_admin')
                                <a href="{{ route('super-admin.dashboard') }}"
                                    class="bg-blue-600 hover:bg-blue-700 text-white font-normal px-5 py-2 rounded-full transition">
                                    Dashboard Super Admin
                                </a>
                            @elseif (auth()->user()->role === 'admin')
                                <a href="{{ route('admin.dashboard') }}"
                                    class="bg-blue-600 hover:bg-blue-700 text-white font-normal px-5 py-2 rounded-full transition">
                                    Dashboard Admin
                                </a>
                            @elseif (auth()->user()->role === 'petugas_security')
                                <a href="{{ route('petugas-security.dashboard') }}"
                                    class="bg-blue-600 hover:bg-blue-700 text-white font-normal px-5 py-2 rounded-full transition">
                                    Dashboard Petugas Security
                                </a>
                            @elseif (auth()->user()->role === 'kabid_dukbis')
                                <a href="{{ route('kabid-dukbis.dashboard') }}"
                                    class="bg-blue-600 hover:bg-blue-700 text-white font-normal px-5 py-2 rounded-full transition">
                                    Dashboard Kabid Dukbis
                                </a>
                            @elseif (auth()->user()->role === 'unit')
                                <a href="{{ route('unit.dashboard') }}"
                                    class="bg-blue-600 hover:bg-blue-700 text-white font-normal px-5 py-2 rounded-full transition">
                                    Dashboard Unit
                                </a>
                            @elseif (auth()->user()->role === 'ob')
                                <a href="{{ route('ob.dashboard') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-normal px-5 py-2 rounded-full transition">
                                    Dashboard OB
                                </a>
                            @elseif (auth()->user()->role === 'sopir')
                                <a href="{{ route('sopir.dashboard') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-normal px-5 py-2 rounded-full transition">
                                    Dashboard Sopir
                                </a>
                            @endif 
                        @else
                            <a href="{{ route('login') }}"
                               class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-6 rounded-full shadow transition">
                                Login
                            </a>
                        @endauth
                    </nav>
                @endif
            </div>
        </div>

        <div id="mobile-menu" class="md:hidden hidden px-6 pb-4 space-y-2 bg-white dark:bg-gray-900 border-t border-gray-100 dark:border-gray-800">
            <a href="#tentang" class="block text-gray-800 dark:text-gray-200 hover:text-blue-600 dark:hover:text-blue-400 py-1">Tentang</a>
            <a href="#visi-misi" class="block text-gray-800 dark:text-gray-200 hover:text-blue-600 dark:hover:text-blue-400 py-1">Visi & Misi</a>
            <a href="#fitur" class="block text-gray-800 dark:text-gray-200 hover:text-blue-600 dark:hover:text-blue-400 py-1">Fitur</a>
            <a href="#kontak" class="block text-gray-800 dark:text-gray-200 hover:text-blue-600 dark:hover:text-blue-400 py-1">Kontak</a>
        </div>
    </header>

    <section class="bg-gradient-to-b from-blue-50/50 via-white to-gray-50 dark:from-gray-900 dark:via-gray-900 dark:to-gray-800/80 flex items-center justify-center px-6 md:px-12 py-[88px] md:py-28 lg:py-36 transition-colors duration-200">
        <div class="max-w-6xl mx-auto flex flex-col-reverse md:flex-row items-center gap-10">
            
            <div class="md:w-1/2 text-center md:text-left">
                <h1 class="text-3xl md:text-4xl font-extrabold text-blue-600 dark:text-blue-400 leading-tight mb-6">
                    Selamat Datang di SIM PATROLI
                </h1>
                <p class="text-gray-700 dark:text-gray-300 text-lg mb-6 leading-relaxed">
                    SIM PATROLI adalah sistem berbasis website untuk memantau
                    aktivitas patroli petugas security secara real-time, efisien,
                    dan akurat di lingkungan kerja PT SUCOFINDO Cilacap.
                </p>
                <a href="{{ route('login') }}"
                    class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-full shadow-lg hover:shadow-xl transition transform hover:-translate-y-0.5">
                    Masuk Sekarang
                </a>
            </div>
            
            <div class="md:w-1/2 flex justify-center">
                <img class="w-64 h-auto drop-shadow-md" src="{{ asset('assets/logo-shield.png') }}" alt="Logo SIM PATROLI" />
            </div>
        </div>
    </section>

    
    <section id="tentang" class="py-16 px-6 md:px-10 bg-gray-50 dark:bg-gray-800/40 transition-colors duration-200">
        <div class="max-w-7xl mx-auto grid md:grid-cols-2 gap-12 items-center">
            
            <div class="flex justify-center">
                <img src="{{ asset('assets/gedung-sucofindo.jpg') }}" alt="Gedung SUCOFINDO"
                    class="rounded-xl shadow-lg w-full max-w-2xl h-[360px] object-cover border border-gray-200 dark:border-gray-700">
            </div>

            <div>
                <h2 class="text-2xl font-bold text-blue-700 dark:text-blue-400 mb-6 text-center md:text-left">
                    Sejarah Singkat PT SUCOFINDO
                </h2>
                <p class="text-lg text-gray-700 dark:text-gray-300 text-justify leading-relaxed mb-4">
                    PT SUCOFINDO (Persero) adalah Badan Usaha Milik Negara (BUMN) yang bergerak di bidang inspeksi,
                    pengujian, sertifikasi, konsultansi, dan pelatihan. Dengan pengalaman lebih dari 60 tahun, SUCOFINDO
                    mendukung pertumbuhan industri nasional melalui layanan yang inovatif, andal, dan terpercaya.
                </p>
                <p class="text-lg text-gray-700 dark:text-gray-300 text-justify leading-relaxed">
                    PT Superintending Company of Indonesia (SUCOFINDO) didirikan pada tanggal 22 Oktober 1956 sebagai
                    perusahaan inspeksi pertama di Indonesia. Perusahaan ini merupakan hasil kerja sama antara
                    Pemerintah Republik Indonesia dan SGS Geneva, dan kini menjadi pemimpin jasa TIC (Testing, Inspection, Certification) di
                    Indonesia.
                </p>
            </div>
        </div>
    </section>

    <section id="visi-misi" class="py-16 px-6 md:px-4 bg-blue-50/60 dark:bg-gray-900 transition-colors duration-200">
        <div class="max-w-5xl mx-auto">
            <h2 class="text-2xl font-bold text-blue-700 dark:text-blue-400 mb-10 text-center">Visi & Misi PT SUCOFINDO</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 text-left">
                <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-xl shadow p-6 hover:shadow-lg transition">
                    <h3 class="text-xl font-semibold text-blue-600 dark:text-blue-400 mb-2">Visi</h3>
                    <p class="text-gray-600 dark:text-gray-300 text-justify leading-relaxed">
                        Menjadi Perusahaan Kelas Dunia yang kompetitif, andal dan terpercaya di bidang inspeksi,
                        pengujian, sertifikasi, konsultasi, dan pelatihan.
                    </p>
                </div>
                <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-xl shadow p-6 hover:shadow-lg transition">
                    <h3 class="text-xl font-semibold text-blue-600 dark:text-blue-400 mb-2">Misi</h3>
                    <p class="text-gray-600 dark:text-gray-300 text-justify leading-relaxed">
                        Menciptakan nilai ekonomi kepada para pemangku kepentingan terutama pelanggan, pemegang saham
                        dan pegawai melalui layanan jasa inspeksi, pengujian, sertifikasi, konsultansi serta jasa terkait
                        lainnya untuk menjamin kepastian berusaha.
                    </p>
                </div>
            </div>
        </div>
    </section>

    
    <section id="fitur" class="py-16 px-6 md:px-4 bg-blue-50/60 dark:bg-gray-900 transition-colors duration-200">
        <div class="max-w-6xl mx-auto text-center">
            <h2 class="text-2xl font-bold text-blue-700 dark:text-blue-400 mb-10">Fitur Unggulan SIM PATROLI</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-left">
                
                
                <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-xl shadow p-6 hover:shadow-lg transition text-center flex flex-col justify-between">
                    <div>
                        <div class="text-blue-600 dark:text-blue-400 mb-4">
                            <svg class="mx-auto w-10 h-10 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512">
                                <path d="M215.7 499.2C267 435 384 279.4 384 192C384 86 298 0 192 0S0 86 0 192c0 87.4 117 243 168.3 307.2c12.3 15.3 35.1 15.3 47.4 0zM192 128a64 64 0 1 1 0 128 64 64 0 1 1 0-128z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-blue-600 dark:text-blue-400 mb-2">Scan QR Pos Patroli</h3>
                        <p class="text-gray-600 dark:text-gray-300 text-sm leading-relaxed">
                            Petugas security memindai QR Code di setiap pos titik lokasi untuk validasi kehadiran patroli secara otomatis dan presisi.
                        </p>
                    </div>
                </div>

                
                <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-xl shadow p-6 hover:shadow-lg transition text-center flex flex-col justify-between">
                    <div>
                        <div class="text-blue-600 dark:text-blue-400 mb-4">
                            <svg class="mx-auto w-10 h-10 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                                <path d="M128 0c17.7 0 32 14.3 32 32l0 32 128 0 0-32c0-17.7 14.3-32 32-32s32 14.3 32 32l0 32 48 0c26.5 0 48 21.5 48 48l0 48L0 160l0-48C0 85.5 21.5 64 48 64l48 0 0-32c0-17.7 14.3-32 32-32zM0 192l448 0 0 272c0 26.5-21.5 48-48 48L48 512c-26.5 0-48-21.5-48-48L0 192zm64 80l0 32c0 8.8 7.2 16 16 16l32 0c8.8 0 16-7.2 16-16l0-32c0-8.8-7.2-16-16-16l-32 0c-8.8 0-16 7.2-16 16zm128 0l0 32c0 8.8 7.2 16 16 16l32 0c8.8 0 16-7.2 16-16l0-32c0-8.8-7.2-16-16-16l-32 0c-8.8 0-16 7.2-16 16zm144-16c-8.8 0-16 7.2-16 16l0 32c0 8.8 7.2 16 16 16l32 0c8.8 0 16-7.2 16-16l0-32c0-8.8-7.2-16-16-16l-32 0zM64 400l0 32c0 8.8 7.2 16 16 16l32 0c8.8 0 16-7.2 16-16l0-32c0-8.8-7.2-16-16-16l-32 0c-8.8 0-16 7.2-16 16zm144-16c-8.8 0-16 7.2-16 16l0 32c0 8.8 7.2 16 16 16l32 0c8.8 0 16-7.2 16-16l0-32c0-8.8-7.2-16-16-16l-32 0zm112 16l0 32c0 8.8 7.2 16 16 16l32 0c8.8 0 16-7.2 16-16l0-32c0-8.8-7.2-16-16-16l-32 0c-8.8 0-16 7.2-16 16z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-blue-600 dark:text-blue-400 mb-2">Jadwal & Reminder</h3>
                        <p class="text-gray-600 dark:text-gray-300 text-sm leading-relaxed">
                            Admin mengatur jadwal shift terpadu, dan sistem mengirimkan pengingat kepada petugas sesuai jam operasional tugas.
                        </p>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-xl shadow p-6 hover:shadow-lg transition text-center flex flex-col justify-between">
                    <div>
                        <div class="text-blue-600 dark:text-blue-400 mb-4">
                            <svg class="mx-auto w-10 h-10 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
                                <path d="M0 64C0 28.7 28.7 0 64 0L224 0l0 128c0 17.7 14.3 32 32 32l128 0 0 47-92.8 37.1c-21.3 8.5-35.2 29.1-35.2 52c0 56.6 18.9 148 94.2 208.3c-9 4.8-19.3 7.6-30.2 7.6L64 512c-35.3 0-64-28.7-64-64L0 64zm384 64l-128 0L256 0 384 128zm39.1 97.7c5.7-2.3 12.1-2.3 17.8 0l120 48C570 277.4 576 286.2 576 296c0 63.3-25.9 168.8-134.8 214.2c-5.9 2.5-12.6 2.5-18.5 0C313.9 464.8 288 359.3 288 296c0-9.8 6-18.6 15.1-22.3l120-48zM527.4 312L432 273.8l0 187.8c68.2-33 91.5-99 95.4-149.7z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-blue-600 dark:text-blue-400 mb-2">Laporan & Insiden</h3>
                        <p class="text-gray-600 dark:text-gray-300 text-sm leading-relaxed">
                            Petugas mengisi form patroli dan dapat melaporkan kejadian insiden darurat langsung ke admin via integrasi WhatsApp.
                        </p>
                    </div>
                </div>

              
                <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-xl shadow p-6 hover:shadow-lg transition text-center flex flex-col justify-between">
                    <div>
                        <div class="text-blue-600 dark:text-blue-400 mb-4">
                            <svg class="mx-auto w-10 h-10 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" d="M9 4.5a.75.75 0 01.721.544l.813 2.846a3.75 3.75 0 002.576 2.576l2.846.813a.75.75 0 010 1.442l-2.846.813a3.75 3.75 0 00-2.576 2.576l-.813 2.846a.75.75 0 01-1.442 0l-.813-2.846a3.75 3.75 0 00-2.576-2.576l-2.846-.813a.75.75 0 010-1.442l2.846-.813A3.75 3.75 0 007.466 7.89l.813-2.846A.75.75 0 019 4.5zM18 1.5a.75.75 0 01.728.568l.258 1.036c.236.94.97 1.674 1.91 1.91l1.036.258a.75.75 0 010 1.456l-1.036.258c-.94.236-1.674.97-1.91 1.91l-.258 1.036a.75.75 0 01-1.456 0l-.258-1.036a2.625 2.625 0 00-1.91-1.91l-1.036-.258a.75.75 0 010-1.456l1.036-.258a2.625 2.625 0 001.91-1.91l.258-1.036A.75.75 0 0118 1.5zM16.5 15a.75.75 0 01.712.513l.394 1.183c.15.447.5.799.948.948l1.183.395a.75.75 0 010 1.422l-1.183.395c-.447.15-.799.5-.948.948l-.395 1.183a.75.75 0 01-1.422 0l-.395-1.183a1.5 1.5 0 00-.948-.948l-1.183-.395a.75.75 0 010-1.422l1.183-.395c.447-.15.799-.5.948-.948l.395-1.183A.75.75 0 0116.5 15z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-blue-600 dark:text-blue-400 mb-2">Sanitasi & Kebersihan</h3>
                        <p class="text-gray-600 dark:text-gray-300 text-sm leading-relaxed">
                            Petugas OB memindai QR Code di fasilitas kamar mandi/toilet untuk checklist kebersihan berkala dan evaluasi sanitasi lingkungan.
                        </p>
                    </div>
                </div>

                
                <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-xl shadow p-6 hover:shadow-lg transition text-center flex flex-col justify-between">
                    <div>
                        <div class="text-blue-600 dark:text-blue-400 mb-4">
                            <svg class="mx-auto w-10 h-10 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512">
                                <path d="M192 0c-41.8 0-77.4 26.7-90.5 64H64C28.7 64 0 92.7 0 128V448c0 35.3 28.7 64 64 64H320c35.3 0 64-28.7 64-64V128c0-35.3-28.7-64-64-64H282.5C269.4 26.7 233.8 0 192 0zm0 64a32 32 0 1 1 0 64 32 32 0 1 1 0-64zM305 273L177 401c-9.4 9.4-24.6 9.4-33.9 0L79 337c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l47 47L271 239c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-blue-600 dark:text-blue-400 mb-2">Temuan Fasilitas</h3>
                        <p class="text-gray-600 dark:text-gray-300 text-sm leading-relaxed">
                            Pencatatan fasilitas toilet rusak atau butuh perbaikan agar dapat segera ditindaklanjuti oleh teknisi dan bagian pemeliharaan gedung.
                        </p>
                    </div>
                </div>

                
                <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-xl shadow p-6 hover:shadow-lg transition text-center flex flex-col justify-between">
                    <div>
                        <div class="text-blue-600 dark:text-blue-400 mb-4">
                            <svg class="mx-auto w-10 h-10 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <path d="M3.375 4.5C2.339 4.5 1.5 5.34 1.5 6.375V13.5h12V6.375c0-1.036-.84-1.875-1.875-1.875h-8.25zM13.5 15h-12v2.625c0 1.035.84 1.875 1.875 1.875h.375a3 3 0 106 0h3a.75.75 0 00.75-.75V15z" />
                                <path d="M8.25 19.5a1.5 1.5 0 10-3 0 1.5 1.5 0 003 0zM15 6.75a.75.75 0 00-.75.75V13.5h5.348c.128 0 .253-.05.346-.142l3.129-3.129a.75.75 0 00.22-.53V8.25a.75.75 0 00-.75-.75h-7.53zM15 15v.75a.75.75 0 00.75.75h.375a3 3 0 106 0h.375a.75.75 0 00.75-.75V15h-8.25zM19.5 19.5a1.5 1.5 0 10-3 0 1.5 1.5 0 003 0z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-blue-600 dark:text-blue-400 mb-2">Checklist Kendaraan</h3>
                        <p class="text-gray-600 dark:text-gray-300 text-sm leading-relaxed">
                            Sopir memindai QR armada untuk cek fisik, kondisi mesin, kilometer/BBM, serta penanganan laporan darurat armada operasional.
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </section>


    <section id="kontak" class="py-16 px-6 md:px-4 bg-white dark:bg-gray-800/60 transition-colors duration-200">
        <div class="max-w-7xl mx-auto text-center">
            <h2 class="text-2xl font-bold text-blue-700 dark:text-blue-400 mb-2">Kontak</h2>
            <p class="text-gray-700 dark:text-gray-300 text-lg mb-8">
                Hubungi kami untuk informasi lebih lanjut.
            </p>
            <div class="grid md:grid-cols-3 gap-6 text-center mt-8 max-w-none mx-auto">
                <div>
                    <h4 class="text-blue-600 dark:text-blue-400 font-semibold mb-1">Alamat</h4>
                    <p class="text-gray-600 dark:text-gray-300 leading-relaxed">
                        Jl. Soekarno Hatta No. 280 Ds Menganti,<br />
                        Kec. Kesugihan Cilacap, Jawa Tengah<br />
                    </p>
                </div>
                <div>
                    <h4 class="text-blue-600 dark:text-blue-400 font-semibold mb-1">Email</h4>
                    <p class="text-gray-600 dark:text-gray-300 leading-relaxed">cilacap@sucofindo.co.id</p>
                </div>
                <div>
                    <h4 class="text-blue-600 dark:text-blue-400 font-semibold mb-1">Telepon</h4>
                    <p class="text-gray-600 dark:text-gray-300 leading-relaxed">(+62-282) 540009</p>
                </div>
            </div>
        </div>
    </section>
    <x-footer />

    
    <script>
        const toggleBtn = document.getElementById('menu-toggle');
        const mobileMenu = document.getElementById('mobile-menu');

        toggleBtn?.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });

        function toggleTheme() {
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('theme', 'light');
            } else {
                document.documentElement.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            }
            updateThemeIcons();
        }

        function updateThemeIcons() {
            const isDark = document.documentElement.classList.contains('dark');
            document.querySelectorAll('.theme-icon-dark').forEach(el => el.classList.toggle('hidden', isDark));
            document.querySelectorAll('.theme-icon-light').forEach(el => el.classList.toggle('hidden', !isDark));
        }

        updateThemeIcons();
    </script>
</body>

</html>