<x-app-layout :title="'Absen Kendaraan'">
    <div class="max-w-xl mx-auto p-2">
        <p class="text-xs font-semibold tracking-widest text-gray-500 mt-4">ABSEN KENDARAAN</p>
        <h2 class="text-2xl font-bold text-gray-800 mb-4">{{ $kendaraan->plat_nomor }} · {{ $kendaraan->jenis }}</h2>

        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-800 rounded-lg shadow-md text-sm">
                @foreach ($errors->all() as $e)
                    <p>{{ $e }}</p>
                @endforeach
            </div>
        @endif

        <form id="form-absen" action="{{ route('sopir.absen.store') }}" method="POST" enctype="multipart/form-data"
            class="bg-white border border-gray-200 rounded-lg shadow-md p-4 space-y-5">
            @csrf
            <input type="hidden" name="kode" value="{{ $kendaraan->kode_barcode }}">
            <input type="hidden" name="latitude" id="latitude">
            <input type="hidden" name="longitude" id="longitude">
            <input type="hidden" name="akurasi" id="akurasi">

            {{-- Nama sopir --}}
            <div>
                <label for="nama_sopir" class="block text-sm font-medium text-gray-700">Nama sopir <span class="text-red-600">*</span></label>
                <input type="text" id="nama_sopir" name="nama_sopir" maxlength="100" required
                    value="{{ old('nama_sopir', Auth::user()->nama) }}"
                    class="mt-1 block w-full rounded-lg border border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
            </div>

            {{-- Nama kendaraan (terkunci, dari QR) --}}
            <div>
                <label class="block text-sm font-medium text-gray-700">Nama kendaraan</label>
                <input type="text" value="{{ $kendaraan->plat_nomor }} · {{ $kendaraan->jenis }}" readonly
                    class="mt-1 block w-full rounded-lg border border-gray-300 bg-gray-100 text-gray-500 text-sm">
            </div>

            {{-- Foto (kamera langsung, seperti patroli Satpam) --}}
            <div>
                <span class="block text-sm font-medium text-gray-700">Foto <span class="text-red-600">*</span></span>
                <div class="mt-1 relative rounded-lg border border-dashed border-gray-300 bg-gray-50 overflow-hidden" style="aspect-ratio: 4 / 3;">
                    <video id="video" class="hidden w-full h-full object-cover" autoplay playsinline muted></video>
                    <img id="foto-preview" class="hidden w-full h-full object-cover" alt="Pratinjau foto">
                    <span id="foto-status" class="absolute inset-0 flex items-center justify-center text-sm text-gray-500 text-center px-4">Membuka kamera...</span>
                </div>
                <canvas id="canvas" class="hidden"></canvas>
                {{-- Input file hanya dipakai untuk mengirim hasil jepretan (dan cadangan bila kamera tidak bisa dibuka) --}}
                <input type="file" id="foto" name="foto" accept="image/*" capture="environment" class="hidden">
                <div class="mt-2 flex flex-wrap gap-2">
                    <button type="button" id="btn-jepret"
                        class="hidden px-4 py-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-lg">Ambil foto</button>
                    <button type="button" id="btn-ulang"
                        class="hidden px-4 py-2 text-sm font-semibold bg-white border border-gray-300 rounded-lg hover:bg-gray-100">Ambil ulang</button>
                    <button type="button" id="btn-file"
                        class="hidden px-4 py-2 text-sm font-semibold bg-white border border-gray-300 rounded-lg hover:bg-gray-100">Pilih dari perangkat</button>
                </div>
            </div>

            {{-- Lokasi --}}
            <div>
                <span class="block text-sm font-medium text-gray-700">Lokasi</span>
                <div class="mt-1 rounded-lg border border-gray-300 p-3 text-sm">
                    <p id="lok-judul" class="font-semibold text-gray-600">Mencari lokasi...</p>
                    <p id="lok-koordinat" class="font-mono text-gray-500 mt-1"></p>
                    <span id="lok-badge" class="hidden"></span>
                </div>
                <div class="mt-2 flex flex-wrap gap-2">
                    <button type="button" id="btn-lokasi" class="hidden px-3 py-1.5 text-sm bg-white border border-gray-300 rounded-lg hover:bg-gray-100">
                        Coba lagi
                    </button>
                    @if (app()->environment('local') && $area)
                        {{-- Khusus testing di komputer lokal tanpa GPS. Tidak tampil di server produksi (APP_ENV=production). --}}
                        <button type="button" id="btn-lokasi-contoh" class="px-3 py-1.5 text-sm bg-yellow-50 border border-yellow-400 text-yellow-800 rounded-lg hover:bg-yellow-100">
                            Pakai lokasi contoh (testing)
                        </button>
                    @endif
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <a href="{{ route('sopir.dashboard') }}"
                    class="px-4 py-2 bg-white border border-gray-300 text-gray-800 text-sm rounded-lg font-semibold hover:bg-gray-100">Batal</a>
                <button type="submit" id="btn-simpan" disabled
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg font-semibold disabled:opacity-50 disabled:cursor-not-allowed">
                    Simpan absen
                </button>
            </div>
        </form>
    </div>
</x-app-layout>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const AREA = @json($area);

        const $ = (id) => document.getElementById(id);
        const foto = $('foto'), btnSimpan = $('btn-simpan');
        let lokasiSiap = false;

        function perbarui() { btnSimpan.disabled = !(lokasiSiap && foto.files && foto.files.length); }

        // ---- Foto: kamera langsung (getUserMedia) lalu jepret ke canvas
        const video = $('video'), canvas = $('canvas'), preview = $('foto-preview'), status = $('foto-status');
        let stream = null;

        function tampilkan(mode) { // 'kamera' | 'hasil' | 'gagal'
            video.classList.toggle('hidden', mode !== 'kamera');
            preview.classList.toggle('hidden', mode !== 'hasil');
            status.classList.toggle('hidden', mode !== 'gagal');
            $('btn-jepret').classList.toggle('hidden', mode !== 'kamera');
            $('btn-ulang').classList.toggle('hidden', mode !== 'hasil');
            $('btn-file').classList.toggle('hidden', mode !== 'gagal');
        }

        function matikanKamera() {
            if (stream) { stream.getTracks().forEach((t) => t.stop()); stream = null; }
        }

        async function nyalakanKamera() {
            foto.value = '';
            perbarui();
            status.textContent = 'Membuka kamera...';
            status.classList.remove('hidden');

            if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                status.textContent = 'Kamera tidak tersedia di browser ini (butuh HTTPS atau localhost).';
                return tampilkan('gagal');
            }
            try {
                stream = await navigator.mediaDevices.getUserMedia({
                    video: { facingMode: { ideal: 'environment' } }, audio: false
                });
                video.srcObject = stream;
                tampilkan('kamera');
            } catch (e) {
                status.textContent = 'Kamera tidak bisa dibuka. Izinkan akses kamera untuk situs ini, atau pilih foto dari perangkat.';
                tampilkan('gagal');
            }
        }

        $('btn-jepret').addEventListener('click', function () {
            if (!video.videoWidth) return;
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);

            canvas.toBlob(function (blob) {
                if (!blob) return;
                const file = new File([blob], 'absen-' + Date.now() + '.jpg', { type: 'image/jpeg' });
                const dt = new DataTransfer();
                dt.items.add(file);
                foto.files = dt.files; // dikirim lewat input file, validasi server tetap sama
                preview.src = URL.createObjectURL(blob);
                matikanKamera();
                tampilkan('hasil');
                perbarui();
            }, 'image/jpeg', 0.85);
        });

        $('btn-ulang').addEventListener('click', nyalakanKamera);

        // Cadangan bila kamera tidak bisa dibuka
        $('btn-file').addEventListener('click', () => foto.click());
        foto.addEventListener('change', function () {
            if (this.files && this.files[0]) {
                preview.src = URL.createObjectURL(this.files[0]);
                tampilkan('hasil');
            }
            perbarui();
        });

        window.addEventListener('beforeunload', matikanKamera);
        nyalakanKamera();

        // ---- Lokasi
        function jarak(lat1, lng1, lat2, lng2) {
            const r = 6371000, rad = (d) => d * Math.PI / 180;
            const a = Math.sin(rad(lat2 - lat1) / 2) ** 2 +
                Math.cos(rad(lat1)) * Math.cos(rad(lat2)) * Math.sin(rad(lng2 - lng1) / 2) ** 2;
            return Math.round(r * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a)));
        }

        function badge(teks, hijau) {
            const b = $('lok-badge');
            b.textContent = teks;
            b.className = 'inline-block mt-2 px-2 py-1 rounded-full text-xs font-semibold ' +
                (hijau ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700');
        }

        function gagal(pesan) {
            lokasiSiap = false;
            $('lok-judul').textContent = pesan;
            $('lok-judul').className = 'font-semibold text-red-700';
            $('lok-koordinat').textContent = '';
            $('lok-badge').className = 'hidden';
            $('btn-lokasi').classList.remove('hidden');
            perbarui();
        }

        function berhasil(pos) {
            const lat = pos.coords.latitude, lng = pos.coords.longitude, acc = Math.round(pos.coords.accuracy);
            $('latitude').value = lat.toFixed(7);
            $('longitude').value = lng.toFixed(7);
            $('akurasi').value = acc;
            $('lok-koordinat').textContent = lat.toFixed(5) + ', ' + lng.toFixed(5) + ' · akurasi ±' + acc + ' m';
            $('lok-judul').className = 'font-semibold text-gray-700';

            if (AREA) {
                const j = jarak(AREA.lat, AREA.lng, lat, lng);
                $('lok-judul').textContent = 'Kawasan Kantor Cabang ' + AREA.nama;
                if (j <= AREA.radius) {
                    badge('Dalam area', true);
                    lokasiSiap = true;
                } else {
                    badge('Di luar area (' + j + ' m dari kantor)', false);
                    lokasiSiap = false;
                    $('btn-lokasi').classList.remove('hidden');
                }
            } else {
                $('lok-judul').textContent = 'Lokasi terdeteksi';
                lokasiSiap = true;
            }
            perbarui();
        }

        function cariLokasi() {
            $('btn-lokasi').classList.add('hidden');
            $('lok-badge').className = 'hidden';
            $('lok-koordinat').textContent = '';
            $('lok-judul').textContent = 'Mencari lokasi...';
            $('lok-judul').className = 'font-semibold text-gray-600';
            lokasiSiap = false;
            perbarui();

            if (!navigator.geolocation) return gagal('Perangkat tidak mendukung lokasi.');

            const pesanDitolak = 'Izin lokasi diblokir browser. Klik ikon pin/gembok di sebelah alamat situs → Lokasi → Izinkan, lalu klik Coba lagi.';
            let selesai = false;
            const tutup = () => { selesai = true; clearTimeout(petunjuk); clearTimeout(batas); };

            // Batas waktu total, supaya tidak menggantung selamanya (mis. menunggu izin atau layanan lokasi Windows).
            const batas = setTimeout(function () {
                if (selesai) return;
                tutup();
                gagal('Lokasi tidak kunjung terdeteksi. Pastikan izin Lokasi untuk situs ini diizinkan dan Lokasi di Windows/HP aktif, lalu klik Coba lagi.');
            }, 30000);

            // Tampilkan status izin lokasi agar jelas penyebabnya.
            const petunjuk = setTimeout(function () {
                if (selesai) return;
                $('lok-koordinat').textContent = 'Menunggu perangkat memberi lokasi...';
            }, 4000);

            if (navigator.permissions && navigator.permissions.query) {
                navigator.permissions.query({ name: 'geolocation' }).then(function (st) {
                    if (selesai) return;
                    if (st.state === 'denied') { tutup(); return gagal(pesanDitolak); }
                    if (st.state === 'prompt') {
                        $('lok-koordinat').textContent = 'Menunggu izin: klik "Izinkan" pada pop-up lokasi di dekat kolom alamat.';
                    }
                }).catch(function () {});
            }

            const ditolak = (err) => err.code === 1;

            // Percobaan 1: akurasi tinggi (GPS, cocok di HP).
            navigator.geolocation.getCurrentPosition(function (pos) {
                if (selesai) return;
                tutup(); berhasil(pos);
            }, function (err) {
                if (selesai) return;
                if (ditolak(err)) { tutup(); return gagal(pesanDitolak); }

                // Percobaan 2: lokasi dari jaringan/Wi-Fi (cocok di laptop tanpa GPS).
                navigator.geolocation.getCurrentPosition(function (pos) {
                    if (selesai) return;
                    tutup(); berhasil(pos);
                }, function (err2) {
                    if (selesai) return;
                    tutup();
                    gagal(ditolak(err2)
                        ? pesanDitolak
                        : 'Lokasi tidak bisa dideteksi (kode ' + err2.code + '). Aktifkan Lokasi di Windows/HP lalu klik Coba lagi.');
                }, { enableHighAccuracy: false, timeout: 15000, maximumAge: 60000 });
            }, { enableHighAccuracy: true, timeout: 8000, maximumAge: 0 });
        }

        $('btn-lokasi').addEventListener('click', cariLokasi);

        const btnContoh = $('btn-lokasi-contoh');
        if (btnContoh && AREA) {
            btnContoh.addEventListener('click', function () {
                berhasil({ coords: { latitude: AREA.lat, longitude: AREA.lng, accuracy: 8 } });
                $('lok-judul').textContent = 'Kawasan Kantor Cabang ' + AREA.nama + ' (lokasi contoh)';
            });
        }
        cariLokasi();

        // Cegah klik ganda
        $('form-absen').addEventListener('submit', function () {
            btnSimpan.disabled = true;
            btnSimpan.textContent = 'Menyimpan...';
        });
    });
</script>
