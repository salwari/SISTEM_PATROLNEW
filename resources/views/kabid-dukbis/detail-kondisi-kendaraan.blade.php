<x-app-layout :title="'Detail Cek Kendaraan'">
    <div class="container mx-auto p-4">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Detail Cek Kendaraan</h2>
            <a href="{{ route('kabid-dukbis.kondisi-kendaraan') }}"
                class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm rounded-lg font-semibold">Kembali</a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Ringkasan --}}
            <div class="lg:col-span-2 bg-white p-6 rounded-lg shadow-md border border-gray-200">
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4 text-sm">
                    <div>
                        <dt class="text-gray-500">Kendaraan</dt>
                        <dd class="font-semibold text-gray-900">{{ $cek->kendaraan->plat_nomor ?? '-' }} &middot; {{ $cek->kendaraan->jenis ?? '' }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Sopir</dt>
                        <dd class="font-semibold text-gray-900">{{ $cek->user->nama ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Waktu cek</dt>
                        <dd class="font-semibold text-gray-900">{{ $cek->tanggal_cek->format('d-m-Y') }}, {{ \Illuminate\Support\Str::substr($cek->waktu_cek, 0, 5) }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Kondisi keseluruhan</dt>
                        <dd>
                            @if ($cek->status_kondisi === 'baik')
                                <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">Baik</span>
                            @else
                                <span class="px-2 py-1 bg-red-100 text-red-700 rounded-full text-xs font-semibold">Perlu Tindakan</span>
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Odometer</dt>
                        <dd class="font-semibold text-gray-900">{{ number_format($cek->odometer, 0, ',', '.') }} km</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Level BBM</dt>
                        <dd class="font-semibold text-gray-900">{{ $cek->level_bbm_label }}</dd>
                    </div>
                    <div class="sm:col-span-2">
                        <dt class="text-gray-500">Catatan sopir</dt>
                        <dd class="text-gray-900">{{ $cek->catatan ?: '-' }}</dd>
                    </div>
                    @if ($cek->latitude && $cek->longitude)
                        <div class="sm:col-span-2">
                            <dt class="text-gray-500">Lokasi saat cek</dt>
                            <dd>
                                <a class="text-blue-600 hover:underline" target="_blank" rel="noopener"
                                    href="https://www.google.com/maps?q={{ $cek->latitude }},{{ $cek->longitude }}">
                                    Lihat di peta ({{ $cek->latitude }}, {{ $cek->longitude }})
                                </a>
                            </dd>
                        </div>
                    @endif
                </dl>
            </div>

            {{-- Foto --}}
            <div class="bg-white p-6 rounded-lg shadow-md border border-gray-200 text-center">
                <p class="text-sm text-gray-500 mb-2 text-left">Foto kendaraan</p>
                @if ($cek->foto && file_exists(public_path('storage/' . $cek->foto)))
                    <a href="{{ asset('storage/' . $cek->foto) }}" target="_blank">
                        <img src="{{ asset('storage/' . $cek->foto) }}" alt="Foto kendaraan"
                            class="w-full h-auto rounded-lg shadow">
                    </a>
                @else
                    <span class="text-gray-400 italic">Tidak ada foto</span>
                @endif
            </div>
        </div>

        {{-- Hasil checklist per kategori --}}
        <h3 class="text-lg font-semibold text-gray-800 mt-8 mb-3">Hasil Checklist</h3>
        @foreach (\App\Models\ChecklistKendaraan::KATEGORI as $kode => $namaKategori)
            @php $butir = $detailPerKategori->get($kode, collect()); @endphp
            @continue($butir->isEmpty())
            <div class="mb-5">
                <h4 class="font-semibold text-gray-700 mb-2">{{ $namaKategori }}</h4>
                <div class="overflow-x-auto border border-gray-200 rounded-lg shadow">
                    <table class="table-auto w-full text-sm text-gray-700">
                        <thead class="bg-blue-200 text-gray-800 uppercase text-xs">
                            <tr>
                                <th class="px-4 py-2 text-left">Butir</th>
                                <th class="px-4 py-2 text-center">Hasil</th>
                                <th class="px-4 py-2 text-left">Catatan</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($butir as $d)
                                <tr class="{{ $d->hasil === 'perlu_tindakan' ? 'bg-red-50' : '' }}">
                                    <td class="px-4 py-2">{{ $d->nama_butir }}</td>
                                    <td class="px-4 py-2 text-center">
                                        @if ($d->hasil === 'baik')
                                            <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs">Baik</span>
                                        @else
                                            <span class="px-2 py-1 bg-red-100 text-red-700 rounded-full text-xs">Perlu Tindakan</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2">{{ $d->catatan ?: '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach

        @if ($cek->detail->isEmpty())
            <p class="text-gray-500 italic">Tidak ada detail checklist pada cek ini.</p>
        @endif
    </div>
</x-app-layout>
