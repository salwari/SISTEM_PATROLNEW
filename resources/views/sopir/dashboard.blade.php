<x-app-layout :title="'Dashboard Sopir'">
    <div class="max-w-4xl mx-auto p-2 space-y-4">
        {{-- Alur singkat --}}
        <div class="mt-4 p-4 bg-white border border-gray-200 rounded-lg shadow-md">
            <p class="text-xs font-semibold tracking-widest text-gray-500 mb-3">ALUR SOPIR</p>
            <ol class="grid grid-cols-1 sm:grid-cols-4 gap-3 text-sm text-gray-800">
                <li class="border-l-4 border-blue-500 pl-3"><span class="block text-blue-600 font-semibold">1</span>Scan barcode yang tertempel di mobil</li>
                <li class="border-l-4 border-blue-500 pl-3"><span class="block text-blue-600 font-semibold">2</span>Isi nama sopir dan nama kendaraan</li>
                <li class="border-l-4 border-blue-500 pl-3"><span class="block text-blue-600 font-semibold">3</span>Ambil foto, lokasi terdeteksi otomatis</li>
                <li class="border-l-4 border-blue-500 pl-3"><span class="block text-blue-600 font-semibold">4</span>Absen tersimpan dan terlihat oleh Kabid</li>
            </ol>
            <p class="text-xs text-gray-500 mt-3">Masuk sebagai {{ Auth::user()->nama }}</p>
        </div>

        {{-- Tab --}}
        <div class="flex gap-6 border-b border-gray-200 text-sm">
            <a href="{{ route('sopir.dashboard') }}" class="pb-2 font-semibold text-gray-900 border-b-2 border-blue-600">Absen</a>
            <a href="{{ route('sopir.riwayat-absen') }}" class="pb-2 text-gray-500 hover:text-gray-800">Riwayat absen</a>
        </div>

        {{-- Progres harian --}}
        <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-md">
            <p class="text-xs font-semibold tracking-widest text-gray-500">
                ABSEN HARIAN · {{ strtoupper(now()->locale('id')->isoFormat('dddd, D MMM YYYY')) }}
            </p>
            <p class="mt-2 text-2xl text-gray-800"><strong class="text-4xl">{{ $sudah }}</strong> dari {{ $total }} kendaraan sudah diabsen</p>

            @if ($total > 0)
                <div class="flex gap-1 mt-3">
                    @foreach ($daftarKendaraan as $k)
                        <div class="h-2 flex-1 rounded {{ $k->absenKendaraan->isNotEmpty() ? 'bg-blue-600' : 'bg-gray-200' }}"></div>
                    @endforeach
                </div>
                @if ($sudah === $total)
                    <p class="mt-3 text-sm font-semibold text-green-700">Semua kendaraan sudah diabsen hari ini.</p>
                @endif
            @else
                <p class="mt-3 text-sm text-gray-500">Belum ada kendaraan aktif di cabang kamu. Hubungi admin cabang.</p>
            @endif
        </div>

        {{-- Daftar kendaraan --}}
        <div class="bg-white border border-gray-200 rounded-lg shadow-md divide-y divide-gray-200">
            @foreach ($daftarKendaraan as $k)
                @php $absen = $k->absenKendaraan->first(); @endphp
                <div class="flex items-center gap-3 p-4 text-sm">
                    <span class="w-3 h-3 rounded-full flex-shrink-0 {{ $absen ? 'bg-green-600' : 'border-2 border-gray-300' }}"></span>
                    <div class="flex-1 min-w-0">
                        <span class="font-bold text-gray-900">{{ $k->plat_nomor }} · {{ $k->jenis }}</span>
                    </div>
                    @if ($absen)
                        <span class="font-mono text-gray-500">{{ str_replace(':', '.', $absen->jam) }}</span>
                        <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-xs font-semibold">{{ $absen->nama_sopir }}</span>
                    @else
                        <a href="{{ route('sopir.scan-qr') }}"
                            class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-semibold">Scan</a>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
