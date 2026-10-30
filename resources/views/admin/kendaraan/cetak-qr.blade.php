<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Cetak QR Kendaraan</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: Arial, sans-serif; margin: 0; padding: 16px; color: #111; }
        .bar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; }
        .bar button { padding: 8px 16px; background: #2563eb; color: #fff; border: 0; border-radius: 6px; cursor: pointer; font-size: 14px; }
        .grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; }
        .kartu { border: 1px dashed #555; border-radius: 6px; padding: 12px; text-align: center; page-break-inside: avoid; }
        .kartu img { width: 100%; max-width: 200px; height: auto; }
        .plat { font-size: 20px; font-weight: bold; margin-bottom: 4px; }
        .jenis { font-size: 12px; color: #444; }
        .kode { font-size: 11px; color: #666; margin-top: 4px; word-break: break-all; }
        .kosong { padding: 40px; text-align: center; color: #666; }
        @media print {
            .bar { display: none; }
            body { padding: 0; }
        }
    </style>
</head>

<body>
    <div class="bar">
        <strong>QR Kendaraan ({{ $kendaraan->count() }})</strong>
        <button onclick="window.print()">Cetak</button>
    </div>

    @if ($kendaraan->isEmpty())
        <div class="kosong">Belum ada kendaraan aktif untuk dicetak.</div>
    @else
        <div class="grid">
            @foreach ($kendaraan as $item)
                <div class="kartu">
                    <div class="plat">{{ $item->plat_nomor }}</div>
                    <div class="jenis">{{ $item->jenis }}</div>
                    <img src="{{ asset('storage/' . $item->qr_code) }}" alt="QR {{ $item->plat_nomor }}">
                    <div class="kode">{{ $item->kode_barcode }}</div>
                </div>
            @endforeach
        </div>
    @endif
</body>

</html>
