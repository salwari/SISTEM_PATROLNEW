<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <title>Kalender Jadwal Kebersihan - {{ $currentMonth->translatedFormat('F Y') }}</title>
    <style>
        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 14px;
            margin: 40px;
            color: #000;
            background-color: #fff;
        }

        header {
            text-align: center;
            margin-bottom: 4px;
        }

        header h1 {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 6px;
        }

        .subjudul {
            text-align: center;
            margin-bottom: 20px;
            font-size: 18px;
            font-weight: bold;
        }

        /* Tabel Kalender */
        table.calendar {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed !important;
        }

        table.calendar th,
        table.calendar td {
            border: 1px solid #000;
            width: 14.28%; 
            height: 70px;
            vertical-align: top;
            padding: 2px 4px;
            font-size: 12px;
            line-height: 1.1;
        }

        /* Header Hari */
        table.calendar th {
            background-color: #bfdbfe;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 14px;
            text-align: center;
            padding: 4px 0;
        }

        .day-number {
            font-weight: bold;
            text-align: center;
            font-size: 14px;
            margin-bottom: 3px;
        }

        /* Kotak Nama Petugas */
        .petugas {
            display: block;
            padding: 2px 4px;
            border-radius: 3px;
            margin-bottom: 3px;
            font-size: 12px;
            color: #000;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>
</head>

<body>

    <header>
        <h1>PT SUCOFINDO CABANG {{ strtoupper($cabang) }}</h1>
        <div class="subjudul">Jadwal Kebersihan Bulan {{ $currentMonth->translatedFormat('F Y') }}</div>
    </header>

    @php
        use Carbon\Carbon;

        $startOfMonth = $currentMonth->copy()->startOfMonth();
        $endOfMonth = $currentMonth->copy()->endOfMonth();
        $startDayOfWeek = $startOfMonth->dayOfWeek;
        $totalDays = $currentMonth->daysInMonth;
        $currentDay = 1;

        $daftarWarna = [
            '#ef4444', '#eab308', '#3b82f6', '#f97316', '#a855f7', '#10b981', '#14b8a6'
        ];

        function warnaPetugas($userId, $daftarWarna)
        {
            if (!$userId) return '#e5e7eb';
            return $daftarWarna[($userId - 1) % count($daftarWarna)];
        }
    @endphp

    <table class="calendar">
        <thead>
            <tr>
                <th>Minggu</th>
                <th>Senin</th>
                <th>Selasa</th>
                <th>Rabu</th>
                <th>Kamis</th>
                <th>Jumat</th>
                <th>Sabtu</th>
            </tr>
        </thead>

        <tbody>
            @while ($currentDay <= $totalDays)
                <tr>
                    @for ($i = 0; $i < 7; $i++)
                        @php
                            $cellDate = null;
                            if (($currentDay == 1 && $i >= $startDayOfWeek) || $currentDay > 1) {
                                if ($currentDay <= $totalDays) {
                                    $cellDate = Carbon::create($currentMonth->year, $currentMonth->month, $currentDay);
                                    $currentDay++;
                                }
                            }
                        @endphp

                        <td>
                            @if ($cellDate)
                                <div class="day-number">{{ $cellDate->day }}</div>

                                @php
                                    $jadwalHariIni = collect($jadwalKebersihan[$cellDate->toDateString()] ?? [])
                                        ->sortBy(fn($j) => ['pagi' => 1, 'sore' => 2, 'malam' => 3][$j->shift] ?? 4);
                                @endphp

                                @foreach ($jadwalHariIni as $jadwal)
                                    <div class="petugas" style="background-color: {{ warnaPetugas($jadwal->user_id, $daftarWarna) }}">
                                        {{ $jadwal->user->nama ?? '-' }} ({{ ucfirst($jadwal->shift) }})
                                    </div>
                                @endforeach
                            @endif
                        </td>
                    @endfor
                </tr>
            @endwhile
        </tbody>
    </table>

</body>

</html>