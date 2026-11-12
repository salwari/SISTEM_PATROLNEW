
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
        .dark h1 + p:not([style*="rgba(255, 255, 255"]),
        .dark h1 ~ p:not([style*="rgba(255, 255, 255"]),
        .dark h2.text-2xl + p:not([style*="rgba(255, 255, 255"]),
        .dark h2.text-xl + p:not([style*="rgba(255, 255, 255"]) {
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

        .dark .bg-gray-50:not(main):not(.min-h-screen) {
            background-color: #111827 !important; 
            border-color: #374151 !important;     
        }
        .dark .bg-gray-50:not(main):not(.min-h-screen) span.text-gray-800,
        .dark .bg-gray-50:not(main):not(.min-h-screen) span.text-gray-700,
        .dark .bg-gray-50:not(main):not(.min-h-screen) span.text-gray-600 {
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

        .dark .ob-card,
        .dark .sp-card,
        .dark .sc-card,
        .dark .ad-card,
        .dark .ad-stat,
        .dark .dash-kartu {
            background-color: #1f2937 !important;
            border-color: #374151 !important;
            color: #f3f4f6 !important;
        }
        .dark .ad-stat:hover,
        .dark .dash-kartu:hover {
            border-color: #3b82f6 !important;
        }

        .dark [style*="color: #1B4A82"] {
            color: #93c5fd !important;
        }
        .dark h2[style*="color: #1B4A82"],
        .dark h3[style*="color: #1B4A82"],
        .dark .ad-stat p[style*="color: #1B4A82"],
        .dark .dash-kartu p[style*="color: #1B4A82"],
        .dark .rounded-full [style*="color: #1B4A82"] {
            color: #ffffff !important;
        }
        .dark [style*="color: #035CA8"] {
            color: #60a5fa !important;
        }
        .dark [style*="color: #475569"],
        .dark [style*="color: #334155"] {
            color: #d1d5db !important;
        }
        .dark [style*="color: #64748B"] {
            color: #9ca3af !important;
        }
        .dark strong[style*="color: #16A34A"] {
            color: #4ade80 !important;
        }
        .dark strong[style*="color: #D97706"] {
            color: #fbbf24 !important;
        }
        .dark strong[style*="color: #DC2626"],
        .dark p[style*="color: #DC2626"] {
            color: #f87171 !important;
        }

        .dark [style*="border: 1px solid #e2e8f0"] {
            border-color: #374151 !important;
        }
        .dark thead[style*="background: #E6F3FB"] {
            background: #374151 !important;
            color: #93c5fd !important;
        }
        .dark thead[style*="background: #FEE2E2"] {
            background: rgba(127, 29, 29, 0.5) !important;
            color: #fca5a5 !important;
        }
        .dark tbody[style*="background: #ffffff"] {
            background: #1f2937 !important;
            border-color: #374151 !important;
            color: #e5e7eb !important;
        }
        .dark .ob-row:hover,
        .dark .sp-row:hover,
        .dark .sc-row:hover,
        .dark .ad-row:hover {
            background: #2d3748 !important;
        }

        .dark [style*="background: #FFFBEB"] {
            background: rgba(113, 63, 18, 0.35) !important;
            border-color: rgba(245, 158, 11, 0.5) !important;
            color: #fde047 !important;
        }
        .dark [style*="background: #F0FDF4"] {
            background: rgba(6, 78, 59, 0.45) !important;
            border-color: rgba(16, 185, 129, 0.45) !important;
            color: #6ee7b7 !important;
        }
        .dark [style*="background: #F0FDF4"] span[style*="color: #15803D"] {
            color: #34d399 !important;
        }
        .dark [style*="background: #F0FDF4"] span[style*="color: #166534"] {
            color: #a7f3d0 !important;
        }
        .dark [style*="background: #FEF2F2"] {
            background: rgba(127, 29, 29, 0.4) !important;
            border-color: rgba(239, 68, 68, 0.45) !important;
            color: #fca5a5 !important;
        }
        .dark [style*="background: #FEF2F2"] span[style*="color: #B91C1C"],
        .dark [style*="background: #FEF2F2"] span[style*="color: #991B1B"] {
            color: #f87171 !important;
        }
        .dark #modalCatatan[style*="background: #FEF2F2"] {
            color: #fecaca !important;
        }
        .dark [style*="background: #E6F3FB"] {
            background: rgba(30, 58, 138, 0.4) !important;
            border-color: rgba(59, 130, 246, 0.45) !important;
            color: #93c5fd !important;
        }
        .dark [style*="background: #F1F7FC"] {
            background: #111827 !important;
            border-color: #374151 !important;
            color: #e5e7eb !important;
        }

        .dark .ob-badge-baik,
        .dark span[style*="background: #DCFCE7"] {
            background: rgba(6, 78, 59, 0.5) !important;
            color: #34d399 !important;
        }
        .dark .ob-badge-temuan,
        .dark span[style*="background: #FEE2E2"] {
            background: rgba(127, 29, 29, 0.5) !important;
            color: #f87171 !important;
        }
        .dark .ob-badge-belum {
            background: #374151 !important;
            color: #cbd5e1 !important;
        }
        .dark span[style*="background: #FEF3C7"] {
            background: rgba(120, 53, 15, 0.5) !important;
            color: #fcd34d !important;
        }
        .dark .ob-btn-outline {
            background: rgba(30, 58, 138, 0.3) !important;
            color: #60a5fa !important;
            border-color: #3b82f6 !important;
        }
        .dark .ob-btn-outline:hover {
            background: rgba(30, 58, 138, 0.6) !important;
            color: #ffffff !important;
        }
        .dark .dash-kartu [style*="background: #F1F7FC"] {
            background: rgba(59, 130, 246, 0.1) !important;
        }
        .dark .dash-kartu .dash-bg-icon {
            color: rgba(59, 130, 246, 0.08) !important;
        }
        .dark .dash-kartu .dash-link {
            color: #60a5fa !important;
        }
        .dark .dash-kartu:hover .dash-link {
            color: #93c5fd !important;
        }
        .dark .dashboard-tab-btn {
            color: #9ca3af !important;
        }
        .dark .dashboard-tab-btn:hover,
        .dark .dashboard-tab-btn.aktif {
            color: #60a5fa !important;
        }
        .dark .dashboard-tab-btn.aktif {
            border-bottom-color: #3b82f6 !important;
        }
        .dark [style*="border-bottom: 1px solid #D6E8F5"] {
            border-color: #374151 !important;
        }
        .dark .ad-select {
            background: #374151 !important;
            border-color: #4b5563 !important;
            color: #ffffff !important;
        }
        .dark #modalChecklistItems > div {
            background: #111827 !important;
            border-color: #374151 !important;
            color: #e5e7eb !important;
        }
        .dark #modalChecklistItems span[style*="334155"],
        .dark #modalChecklistItems span[style*="51, 65, 85"] {
            color: #e5e7eb !important;
        }
        .dark #modalChecklistItems span[style*="B91C1C"],
        .dark #modalChecklistItems span[style*="185, 28, 28"] {
            color: #f87171 !important;
        }
        .dark #detailModal [style*="border-top: 1px solid #e2e8f0"] {
            border-color: #374151 !important;
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
