
@props(['title' => ''])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'SIM PATROLI' }}</title>
    
    <link rel="icon" type="image/png" href="{{ asset('assets/sucofindo-nobg.png') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    
    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />

    
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">

    <script>
        if (localStorage.getItem('theme') === 'dark') {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    <style>
        .dark h1,
        .dark h2.text-2xl,
        .dark h2.text-xl {
            color: #ffffff !important;
        }
        .dark h1 + p,
        .dark h1 ~ p,
        .dark h2.text-2xl + p,
        .dark h2.text-xl + p {
            color: #9ca3af !important;
        }

        .dark div.bg-white,
        .dark .bg-white.rounded-xl,
        .dark .bg-white.rounded-lg,
        .dark .bg-white.rounded-t-xl,
        .dark div.bg-white.border {
            background-color: #1f2937 !important; 
            border-color: #374151 !important;     
            color: #f3f4f6;
        }
        .dark .bg-white h2,
        .dark .bg-white h3,
        .dark strong.text-gray-900 {
            color: #ffffff !important;
        }
        .dark .bg-white p,
        .dark div.text-gray-600,
        .dark div.text-sm.text-gray-600,
        .dark .bg-white span.text-gray-600,
        .dark .bg-white span.text-gray-500 {
            color: #9ca3af;
        }

        .dark .bg-blue-50 {
            background-color: rgba(30, 58, 138, 0.3) !important; 
            border-color: rgba(59, 130, 246, 0.4) !important;
        }
        .dark .bg-blue-50 h2 {
            color: #ffffff !important;
        }
        .dark .bg-blue-50 p,
        .dark .bg-blue-50 span,
        .dark .bg-blue-50 div {
            color: #93c5fd !important;
        }
        .dark .bg-blue-50 strong {
            color: #ffffff !important;
        }
        .dark .bg-blue-100 {
            background-color: #1e3a8a !important;
            color: #bfdbfe !important;
        }

        .dark .bg-gray-50 {
            background-color: #111827 !important; 
            border-color: #374151 !important;     
        }
        .dark .bg-gray-50 span.text-gray-800,
        .dark .bg-gray-50 span {
            color: #e5e7eb !important;            
        }

        .dark .bg-green-50,
        .dark .bg-green-100 {
            background-color: rgba(6, 78, 59, 0.4) !important; 
            border-color: rgba(16, 185, 129, 0.4) !important;
        }
        .dark .bg-green-50 span,
        .dark .bg-green-100 span,
        .dark .bg-green-50 .text-green-700,
        .dark .bg-green-50 .text-green-800,
        .dark .bg-green-100.text-green-800 {
            color: #34d399 !important; 
        }

        .dark .bg-red-50,
        .dark .bg-red-100 {
            background-color: rgba(127, 29, 29, 0.4) !important; 
            border-color: rgba(239, 68, 68, 0.4) !important;
        }
        .dark .bg-red-50 span,
        .dark .bg-red-100 span,
        .dark .bg-red-50 .text-red-700,
        .dark .bg-red-50 .text-red-800,
        .dark .bg-red-100.text-red-800 {
            color: #f87171 !important; 
        }

        .dark .bg-yellow-50,
        .dark .bg-yellow-100 {
            background-color: rgba(113, 63, 18, 0.35) !important; 
            border-color: #eab308 !important;                     
            color: #fde047 !important;                            
        }
        .dark .bg-yellow-50 span,
        .dark .bg-yellow-100 span,
        .dark .bg-yellow-50 strong,
        .dark .bg-yellow-100 strong {
            color: #fef08a !important;                             
        }
        
        .dark .bg-gray-200,
        .dark a.bg-gray-200,
        .dark button.bg-gray-200 {
            background-color: #374151 !important;
            color: #e5e7eb !important;
        }
        .dark .bg-gray-200:hover,
        .dark a.bg-gray-200:hover,
        .dark button.bg-gray-200:hover {
            background-color: #4b5563 !important;
            color: #ffffff !important;
        }

        .dark .bg-gray-100 {
            background-color: #374151 !important;
            color: #e5e7eb !important;             
        }

        .dark thead {
            background-color: #374151 !important; 
        }
        .dark thead th {
            color: #e5e7eb !important;
        }
        .dark tbody,
        .dark tbody.bg-white,
        .dark tbody tr {
            background-color: #1f2937 !important;
            border-color: #374151 !important;
            color: #d1d5db !important;
        }
        .dark tbody tr:hover {
            background-color: #2d3748 !important;
        }

        .dark td.text-gray-900,
        .dark td.text-gray-800,
        .dark td span.text-gray-900,
        .dark td span.text-gray-800,
        .dark td strong {
            color: #ffffff !important;
        }
      
        .dark td.text-gray-700,
        .dark td.text-gray-600,
        .dark td.text-gray-500,
        .dark td span.text-gray-600,
        .dark td span.text-gray-500 {
            color: #9ca3af !important; 
        }

        .dark input[type="text"],
        .dark input[type="email"],
        .dark input[type="password"],
        .dark input[type="number"],
        .dark input[type="date"],
        .dark input[type="time"],
        .dark input[type="search"],
        .dark select,
        .dark textarea {
            background-color: #374151 !important;
            border-color: #4b5563 !important;
            color: #ffffff !important;
        }
        .dark a.bg-white,
        .dark button.bg-white {
            background-color: #374151 !important;
            border-color: #4b5563 !important;
            color: #e5e7eb !important;
        }
        .dark a.bg-white:hover,
        .dark button.bg-white:hover {
            background-color: #4b5563 !important;
            color: #ffffff !important;
        }
        .dark label:has(input[value="baik"]:checked) {
            background-color: rgba(6, 78, 59, 0.5) !important; 
            border-color: #10b981 !important;                 
        }
        .dark label:has(input[value="baik"]:checked) span:first-child {
            color: #34d399 !important;                        
        }
        .dark label:has(input[value="baik"]:checked) span:last-child {
            color: #d1fae5 !important;                        
        }
        .dark label:has(input[value="perlu_tindakan"]:checked) {
            background-color: rgba(127, 29, 29, 0.5) !important;
            border-color: #ef4444 !important;                   
        }
        .dark label:has(input[value="perlu_tindakan"]:checked) span:first-child {
            color: #f87171 !important;                           
        }
        .dark label:has(input[value="perlu_tindakan"]:checked) span:last-child {
            color: #fee2e2 !important;                           
        }

        .dark label:has(input[name="status_kondisi"]:not(:checked)) {
            background-color: #111827 !important;
            border-color: #374151 !important;    
        }
        .dark label:has(input[name="status_kondisi"]:not(:checked)) span:first-child {
            color: #ffffff !important;
        }
        .dark label:has(input[name="status_kondisi"]:not(:checked)) span:last-child {
            color: #9ca3af !important;
        }

        .dark label.text-green-700 {
            color: #34d399 !important;
        }
        .dark label.text-red-600 {
            color: #f87171 !important;
        }

        .dark .bg-white.rounded-xl,
        .dark .bg-white.rounded-lg,
        .dark .bg-white.rounded-t-xl,
        .dark div.bg-white.border {
            background-color: #1f2937 !important; 
            border-color: #374151 !important;     
            color: #f3f4f6;
        }

        .dark .tab-btn.border-transparent {
            color: #9ca3af !important; 
        }
        .dark .tab-btn.border-transparent:hover {
            color: #e5e7eb !important;
        }

        .dark .bg-emerald-50 {
            background-color: rgba(6, 78, 59, 0.45) !important;
            border-color: rgba(16, 185, 129, 0.4) !important;
        }
        .dark .bg-emerald-50 p.font-bold {
            color: #6ee7b7 !important; 
        }
        .dark .bg-emerald-50 p,
        .dark .bg-emerald-50 span {
            color: #a7f3d0 !important; 
        }

        .dark a.border-gray-200 {
            border-color: #374151 !important;
            background-color: #111827 !important; 
        }
        .dark a.border-gray-200 span.text-gray-700 {
            color: #e5e7eb !important;            
        }
        .dark a.hover\:bg-gray-50:hover,
        .dark .hover\:bg-gray-50:hover {
            background-color: #374151 !important; 
            border-color: #3b82f6 !important;     
        }
        .dark a.hover\:bg-gray-50:hover span,
        .dark .hover\:bg-gray-50:hover span {
            color: #ffffff !important;            
        }

        .dark p.text-gray-700 {
            color: #e5e7eb !important;
        }

        .dark label,
        .dark label.text-gray-700,
        .dark label.text-gray-800,
        .dark label.text-gray-600 {
            color: #e5e7eb !important; 
        }

        .dark input[type="date"] {
            color-scheme: dark;        
            color: #ffffff !important;
        }

        .dark input::placeholder,
        .dark textarea::placeholder {
            color: #9ca3af !important; 
        }
    </style>
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 transition-colors duration-200">
        
        @include('layouts.navigation')

        @if (Auth::check())
            @if(Auth::user()->role == 'super_admin')
                <x-sidebar-super-admin />
            @elseif(Auth::user()->role == 'admin')
                <x-sidebar-admin />
            @elseif(Auth::user()->role == 'petugas_security')
                <x-sidebar-petugas />
            @elseif(Auth::user()->role == 'kabid_dukbis')
                <x-sidebar-dukbis />
            @elseif(Auth::user()->role == 'sopir')
                <x-sidebar-sopir />
            @elseif(Auth::user()->role == 'ob')
                <x-sidebar-ob />
            @endif
        @endif

        <main class="relative pt-16 md:ml-64 p-4 bg-gray-50 dark:bg-gray-900 min-h-screen text-gray-900 dark:text-gray-100 transition-colors duration-200">
            {{ $slot }}
            <x-alert />
        </main>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
    <script>
        const themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
        const themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');
        const themeToggleBtn = document.getElementById('theme-toggle');

        if (document.documentElement.classList.contains('dark')) {
            themeToggleLightIcon?.classList.remove('hidden');
        } else {
            themeToggleDarkIcon?.classList.remove('hidden');
        }

        themeToggleBtn?.addEventListener('click', function() {
            themeToggleDarkIcon?.classList.toggle('hidden');
            themeToggleLightIcon?.classList.toggle('hidden');

            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('theme', 'light');
            } else {
                document.documentElement.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            }
        });
    </script>
</body>

</html>
