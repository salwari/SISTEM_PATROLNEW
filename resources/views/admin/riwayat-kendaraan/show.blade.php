<x-app-layout :title="'Detail Patroli Kendaraan'">
    <div class="container mx-auto p-4">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Detail Patroli Kendaraan</h2>
            <a href="{{ route('riwayat-kendaraan.index') }}"
                class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm rounded-lg font-semibold">Kembali</a>
        </div>

        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-800 rounded-lg text-sm">
                @foreach ($errors->all() as $e)
                    <p>{{ $e }}</p>
                @endforeach
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white p-6 rounded-lg shadow-md border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi Patroli</h3>
                <dl class="space-y-2 text-sm text-gray-700">
                    <div class="flex"><dt class="w-40 text-gray-500">Kendaraan</dt>
                        <dd class="font-semibold">{{ $laporan->kendaraan->plat_nomor ?? '-' }} · {{ $laporan->kendaraan->jenis ?? '' }}</dd></div>
                    <div class="flex"><dt class="w-40 text-gray-500">Sopir</dt><dd>{{ $laporan->nama_sopir }}</dd></div>
                    <div class="flex"><dt class="w-40 text-gray-500">Tanggal</dt>
                        <dd>{{ $laporan->tanggal->format('d-m-Y') }} pukul {{ $laporan->jam }}</dd></div>
                    <div class="flex"><dt class="w-40 text-gray-500">Status</dt>
                        <dd>
                            @if ($laporan->status === 'darurat')
                                <span class="px-2 py-1 bg-red-100 text-red-700 rounded-full text-xs">Darurat</span>
                            @else
                                <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs">Aman</span>
                            @endif
                        </dd></div>
                    @if ($laporan->status === 'darurat')
                        <div class="flex"><dt class="w-40 text-gray-500">Kondisi</dt>
                            <dd class="font-semibold">{{ $laporan->kategoriKejadian->nama_kategori ?? '-' }}</dd></div>
                    @endif
                    <div class="flex"><dt class="w-40 text-gray-500">Keterangan</dt><dd>{{ $laporan->keterangan ?: '-' }}</dd></div>
                    <div class="flex"><dt class="w-40 text-gray-500">Lokasi</dt>
                        <dd>
                            <a href="https://www.google.com/maps?q={{ $laporan->latitude }},{{ $laporan->longitude }}" target="_blank"
                                class="text-blue-600 hover:underline">Lihat di peta</a>
                            @if ($laporan->dalam_area === true)
                                <span class="ml-2 px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs">Dalam area</span>
                            @endif
                        </dd></div>
                </dl>

                @if ($laporan->foto)
                    <img src="{{ asset('storage/' . $laporan->foto) }}" alt="Foto kendaraan"
                        class="mt-4 w-full max-w-md rounded-lg border border-gray-200">
                @endif
            </div>

            @if ($laporan->status === 'darurat')
                @php
                    $warna = ['belum' => 'bg-red-100 text-red-700', 'proses' => 'bg-yellow-100 text-yellow-700', 'selesai' => 'bg-green-100 text-green-700'];
                @endphp
                <div class="bg-white p-6 rounded-lg shadow-md border border-gray-200 self-start">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Penanganan</h3>
                    <p class="text-sm text-gray-600 mb-4">
                        Status saat ini:
                        <span class="px-2 py-1 rounded-full text-xs {{ $warna[$laporan->status_penanganan] ?? 'bg-gray-100 text-gray-600' }}">
                            {{ $laporan->penanganan_label }}</span>
                    </p>

                    @if ($laporan->waktu_ditangani)
                        <p class="text-xs text-gray-500 mb-4">
                            Terakhir diperbarui oleh {{ $laporan->penangan->nama ?? '-' }}
                            pada {{ $laporan->waktu_ditangani->format('d-m-Y H:i') }}.
                        </p>
                    @endif

                    <form action="{{ route('riwayat-kendaraan.penanganan', $laporan->id) }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <div>
                            <label for="status_penanganan" class="block text-sm font-medium text-gray-700">Ubah status</label>
                            <select name="status_penanganan" id="status_penanganan"
                                class="mt-1 block w-full rounded-lg border border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500">
                                @foreach (\App\Models\AbsenKendaraan::PENANGANAN as $nilai => $label)
                                    <option value="{{ $nilai }}"
                                        {{ old('status_penanganan', $laporan->status_penanganan) === $nilai ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="catatan_penanganan" class="block text-sm font-medium text-gray-700">
                                Catatan penanganan <span id="wajib-catatan" class="text-red-600 hidden">*</span>
                            </label>
                            <textarea name="catatan_penanganan" id="catatan_penanganan" rows="4" maxlength="1000"
                                placeholder="Contoh: ban sudah ditambal di bengkel, kendaraan siap dipakai."
                                class="mt-1 block w-full rounded-lg border border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500">{{ old('catatan_penanganan', $laporan->catatan_penanganan) }}</textarea>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit"
                                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg font-semibold">Simpan</button>
                        </div>
                    </form>
                </div>
            @endif
        </div>
    </div>

    <script>
        (function () {
            const status = document.getElementById('status_penanganan');
            const bintang = document.getElementById('wajib-catatan');
            if (!status || !bintang) return;
            function sinkron() { bintang.classList.toggle('hidden', status.value !== 'selesai'); }
            status.addEventListener('change', sinkron);
            sinkron();
        })();
    </script>
</x-app-layout>