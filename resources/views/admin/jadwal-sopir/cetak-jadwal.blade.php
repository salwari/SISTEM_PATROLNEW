<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <title>Jadwal Sopir - {{ $currentMonth->translatedFormat('F Y') }}</title>
    <style>
        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 12px;
            margin: 30px;
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
            margin-bottom: 16px;
            font-size: 18px;
            font-weight: bold;
        }

        table.calendar {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        table.calendar th,
        table.calendar td {
            border: 1px solid #000;
            width: 14.28%;
            height: 70px;
            vertical-align: top;
            padding: 2px 3px;
            font-size: 10px;
            line-height: 1.15;
        }

        table.calendar tr {
            page-break-inside: avoid;
        }

        table.calendar th {
            background-color: #bfdbfe;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 13px;
            text-align: center;
            padding: 2px 0;
        }

        .day-number {
            font-weight: bold;
            text-align: center;
            font-size: 12px;
            margin-bottom: 2px;
        }

        .shift {
            padding: 2px 3px;
            border-radius: 3px;
            margin-bottom: 3px;
            color: #000;
        }

        .shift-pagi {
            background-color: #fde68a;
        }

        .shift-sore {
            background-color: #fdba74;
        }

        .shift-malam {
            background-color: #c7d2fe;
        }

        .shift-judul {
            font-weight: bold;
        }
    </style>
</head>

<body>

    <header>
        <h1>PT SUCOFINDO CABANG {{ strtoupper($cabang) }}</h1>
        <div class="subjudul">Jadwal Sopir Bulan {{ $currentMonth->translatedFormat('F Y') }}</div>
    </header>

    @php
        $offset = $currentMonth->copy()->startOfMonth()->dayOfWeek;
        $totalHari = $currentMonth->daysInMonth;
        $jumlahBaris = (int) ceil(($offset + $totalHari) / 7);
        $labelShift = ['pagi' => 'Pagi', 'sore' => 'Sore', 'malam' => 'Malam'];
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
            @for ($baris = 0; $baris < $jumlahBaris; $baris++)
                <tr>
                    @for ($kolom = 0; $kolom < 7; $kolom++)
                        @php
                            $hari = $baris * 7 + $kolom - $offset + 1;
                            $tanggal = $currentMonth->format('Y-m') . '-' . str_pad($hari, 2, '0', STR_PAD_LEFT);
                            $jadwalHari = ($hari >= 1 && $hari <= $totalHari) ? ($jadwalPerTanggal[$tanggal] ?? []) : [];
                        @endphp

                        <td>
                            @if ($hari >= 1 && $hari <= $totalHari)
                                <div class="day-number">{{ $hari }}</div>

                                @foreach (['pagi', 'sore', 'malam'] as $shift)
                                    @if (!empty($jadwalHari[$shift]))
                                        <div class="shift shift-{{ $shift }}">
                                            <span class="shift-judul">{{ $labelShift[$shift] }}:</span>
                                            {{ implode(', ', array_column($jadwalHari[$shift], 'nama')) }}
                                        </div>
                                    @endif
                                @endforeach
                            @endif
                        </td>
                    @endfor
                </tr>
            @endfor
        </tbody>
    </table>

</body>

</html>