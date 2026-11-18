<x-app-layout :title="'Jadwal Tugas Kebersihan'">
    <div class="container mx-auto p-4 space-y-5">
        
        {{-- Header & Filter Bulan --}}
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 border border-gray-200 rounded-xl shadow-sm p-4 bg-white dark:bg-gray-800 dark:border-gray-700">
            <div>
                <h2 class="text-xl font-bold text-gray-800 dark:text-white">
                    Jadwal Kebersihan Bulan {{ $currentMonth->translatedFormat('F Y') }}
                </h2>
                <p class="text-xs text-gray-500 dark:text-gray-400">Jadwal shift kerja petugas OB cabang</p>
            </div>

            <form method="GET" action="{{ route('ob.jadwal') }}" class="w-full sm:w-auto">
                <input type="month" name="month" value="{{ $currentMonth->format('Y-m') }}"
                    onchange="this.form.submit()"
                    class="border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white rounded-lg px-3 py-2 text-sm shadow-sm focus:ring-blue-500 w-full sm:w-auto">
            </form>
        </div>

        {{-- Kalender Jadwal --}}
        <div class="w-full overflow-x-auto border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm bg-white dark:bg-gray-800">
            <div class="min-w-[700px] grid grid-cols-7 gap-px text-center text-sm select-none bg-gray-200 dark:bg-gray-700">
                
                @foreach (['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'] as $day)
                    <div class="font-bold py-2 bg-blue-100 dark:bg-gray-800 text-gray-800 dark:text-gray-200 text-xs uppercase">
                        {{ $day }}
                    </div>
                @endforeach

                @php
                    $startOfMonth = $currentMonth->copy()->startOfMonth();
                    $startDayOfWeek = $startOfMonth->dayOfWeek;
                    $daysInMonth = $currentMonth->daysInMonth;

                    $warnaPetugas = [];
                    $daftarWarna = ['bg-blue-600', 'bg-emerald-600', 'bg-purple-600', 'bg-amber-600', 'bg-indigo-600', 'bg-teal-600', 'bg-rose-600'];

                    foreach ($users as $index => $user) {
                        $warnaPetugas[$user->id] = $daftarWarna[$index % count($daftarWarna)];
                    }
                @endphp

                {{-- Kolom Kosong Sebelum Hari Pertama --}}
                @for ($i = 0; $i < $startDayOfWeek; $i++)
                    <div class="border border-gray-100 dark:border-gray-800 h-24 bg-gray-50 dark:bg-gray-900/30"></div>
                @endfor

                {{-- Tanggal-Tanggal Bulan Terpilih --}}
                @for ($day = 1; $day <= $daysInMonth; $day++)
                    @php
                        $date = $currentMonth->copy()->day($day)->format('Y-m-d');
                        $jadwalHariIni = $jadwalKebersihan[$date] ?? [];
                        $isToday = $date === now()->format('Y-m-d');
                    @endphp

                    <div class="border border-gray-100 dark:border-gray-700 p-1.5 min-h-[5.5rem] sm:min-h-[6.5rem] text-left relative bg-white dark:bg-gray-800 {{ $isToday ? 'ring-2 ring-blue-500 rounded' : '' }} hover:bg-blue-50/50 dark:hover:bg-gray-750 transition flex flex-col justify-between">
                        
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-xs font-bold {{ $isToday ? 'bg-blue-600 text-white rounded-full w-5 h-5 flex items-center justify-center' : 'text-gray-700 dark:text-gray-300' }}">
                                {{ $day }}
                            </span>
                            @if($isToday)
                                <span class="text-[9px] text-blue-600 dark:text-blue-400 font-semibold uppercase">Hari ini</span>
                            @endif
                        </div>

                        @if (!empty($jadwalHariIni) && count($jadwalHariIni) > 0)
                            @php
                                $shiftOrder = ['pagi' => 1, 'sore' => 2, 'malam' => 3];
                                $jadwalHariIni = collect($jadwalHariIni)->sortBy(fn($j) => $shiftOrder[$j->shift] ?? 99);
                            @endphp

                            <div class="space-y-1 max-h-20 overflow-y-auto pr-0.5 mt-1">
                                @foreach ($jadwalHariIni as $jadwal)
                                    @php
                                        $isMe = $jadwal->user_id === Auth::id();
                                    @endphp
                                    <div class="flex items-center justify-between text-[11px] px-1.5 py-0.5 rounded text-white {{ $warnaPetugas[$jadwal->user_id] ?? 'bg-gray-600' }} {{ $isMe ? 'ring-1 ring-white font-bold' : '' }}"
                                        title="{{ $jadwal->user->nama ?? '-' }} - Shift {{ ucfirst($jadwal->shift) }}">
                                        <span class="truncate max-w-[90px]">
                                            {{ $isMe ? '⭐ Saya' : ($jadwal->user->nama ?? '-') }}
                                        </span>
                                        <span class="italic opacity-90 text-[10px]">({{ ucfirst($jadwal->shift) }})</span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <span class="text-gray-400 text-[10px] italic mt-1 block">Tidak ada jadwal</span>
                        @endif

                    </div>
                @endfor
                
                {{-- Kolom Kosong Setelah Tanggal Akhir --}}
                @php
                    $totalCells = $startDayOfWeek + $daysInMonth;
                    $remainingCells = 7 - ($totalCells % 7);
                    if ($remainingCells < 7) {
                        for ($i = 0; $i < $remainingCells; $i++) {
                            echo '<div class="border border-gray-100 dark:border-gray-800 h-24 bg-gray-50 dark:bg-gray-900/30"></div>';
                        }
                    }
                @endphp
            </div>
        </div>
    </div>
</x-app-layout>