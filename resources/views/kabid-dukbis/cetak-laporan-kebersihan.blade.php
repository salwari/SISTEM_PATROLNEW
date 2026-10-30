<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Kebersihan - {{ $startDate }} s/d {{ $endDate }}</title>
    <style>
        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 12px;
            color: #000;
            margin: 40px;
        }

        h2, .text-center {
            text-align: center;
        }

        hr {
            border: 1px solid #000;
            margin: 10px 0;
        }

        .kop-surat {
            display: table;
            width: 100%;
            margin-bottom: 10px;
        }

        .kop-title {
            display: table-cell;
            vertical-align: middle;
            text-align: left;
        }

        .kop-title h1 {
            font-size: 18px;
            font-weight: bold;
            margin: 0;
            letter-spacing: 0.5px;
        }

        .kop-logo {
            display: table-cell;
            width: 130px;
            vertical-align: middle;
            text-align: right;
        }

        .kop-logo img {
            width: 110px;
            height: auto;
        }

        .periode-info {
            text-align: left;
            margin: 12px 0 16px 0;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        thead {
            background-color: #f2f2f2;
        }

        th, td {
            border: 1px solid #333;
            padding: 6px 8px;
            text-align: center;
        }

        .text-left {
            text-align: left;
        }

        .no-wrap {
            white-space: nowrap;
        }

        .ttd-section {
            margin-top: 40px;
            width: 100%;
        }

        .ttd-kepala {
            width: 200px;
            float: right;
            text-align: left;
        }

        .ttd-kepala p {
            margin: 4px 0;
            line-height: 1.5;
        }

        .ttd-nama {
            display: inline-block;
            font-weight: bold;
            padding-bottom: 1px;
            min-width: 90px;
        }

        .qr-code {
            margin: 6px 0;
        }

        .qr-code img {
            width: 100px;
            height: auto;
        }
    </style>
</head>

<body>
        <div class="kop-surat">
            <div class="kop-title">
                <h1>LAPORAN PEMERIKSAAN KEBERSIHAN</h1>
            </div>
            <div class="kop-logo">
                <img src="{{ public_path('assets/sucofindo.png') }}" alt="Logo">
            </div>
        </div>
        <hr>

        <p class="periode-info">
            <strong>Periode :</strong> {{ \Carbon\Carbon::parse($startDate)->translatedFormat('d F Y') }} s/d {{ \Carbon\Carbon::parse($endDate)->translatedFormat('d F Y') }}
        </p>

    <table>
        <thead>
            <tr>
                <th style="width: 30px;">No</th>
                <th>Petugas OB</th>
                <th>Lokasi Toilet</th>
                <th>Lantai</th>
                <th class="no-wrap">Tanggal</th>
                <th>Waktu</th>
                <th>Status</th>
                <th>Catatan / Temuan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td class="text-left">{{ $item->user->nama ?? '-' }}</td>
                    <td class="text-left">{{ $item->kamarMandi->nama_kamar_mandi ?? '-' }}</td>
                    <td>{{ $item->kamarMandi->lantai ?? '-' }}</td>
                    <td class="no-wrap">{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->waktu)->format('H:i') }}</td>
                    <td>
                        {{ $item->status_kondisi === 'baik' ? 'Baik' : 'Perlu Tindakan' }}
                    </td>
                    <td class="text-left">{{ $item->catatan ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    
    <div class="ttd-section">
        <div class="ttd-kepala">
            <p>{{ $cabang->nama_cabang ?? '-' }}, {{ now()->translatedFormat('d F Y') }}</p>
            <p>Mengetahui,</p>
            <p>Kepala Bidang Dukungan Bisnis</p>

            <div class="qr-code">
                <img src="data:image/png;base64,{{ $qrCode }}" alt="QR Code Verifikasi">
            </div>

            <p class="ttd-nama">{{ $namaKepala }}</p>
            <p>NIP. {{ $nomorInduk }}</p>
        </div>
    </div>
</body>
</html>