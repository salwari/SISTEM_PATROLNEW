<x-app-layout :title="'Scan QR Kendaraan'">
    <div class="container mx-auto p-6">
        <h2 class="text-2xl font-bold mb-2 text-gray-800 text-center">Scan QR Kendaraan</h2>

        <div class="bg-white p-4 sm:p-6 rounded-lg shadow-md max-w-xl mx-auto">
            <div id="reader" class="w-full border border-gray-200 rounded-md"></div>

            <div id="fallback" class="hidden text-center">
                <p class="text-sm text-gray-600 mb-3">
                    Kamera langsung tidak tersedia di koneksi ini. Ambil foto QR yang ditempel pada mobil.
                </p>
                <label class="inline-block px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg font-semibold cursor-pointer">
                    Ambil Foto QR
                    <input type="file" id="qr-file" accept="image/*" capture="environment" class="hidden">
                </label>
            </div>

            <div id="result" class="mt-4 text-center font-semibold text-blue-600"></div>
        </div>
    </div>
</x-app-layout>

<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const resultContainer = document.getElementById('result');
        const readerEl = document.getElementById('reader');
        const fallbackEl = document.getElementById('fallback');
        const fileInput = document.getElementById('qr-file');
        const html5QrCode = new Html5Qrcode("reader");

        let hasScanned = false;

        function prosesHasil(decodedText) {
            resultContainer.innerText = `QR Berhasil: ${decodedText}`;

            let kode = null;
            try {
                const u = new URL(decodedText);
                if (/\/sopir\/(absen|cek)\/create$/.test(u.pathname)) kode = u.searchParams.get('kode');
            } catch (e) {}

            if (kode) {
                setTimeout(() => {
                    window.location.href = @json(route('sopir.absen.create')) + '?kode=' + encodeURIComponent(kode);
                }, 1000);
            } else {
                alert('QR bukan QR kendaraan. Scan QR yang ditempel pada mobil.');
                window.location.reload();
            }
        }

        function onScanSuccess(decodedText, decodedResult) {
            if (hasScanned) return;
            hasScanned = true;

            html5QrCode.stop().then(() => {
                prosesHasil(decodedText);
            }).catch(err => {
                console.error("Gagal menghentikan scanner: ", err);
            });
        }

        function tampilkanFallback() {
            readerEl.classList.add('hidden');
            fallbackEl.classList.remove('hidden');
        }

        fileInput.addEventListener('change', function() {
            if (!this.files.length) return;

            html5QrCode.scanFile(this.files[0], false)
                .then(prosesHasil)
                .catch(() => {
                    alert('QR tidak terbaca. Pastikan QR terlihat jelas pada foto, lalu coba lagi.');
                });

            this.value = '';
        });

        Html5Qrcode.getCameras().then(devices => {
            if (devices && devices.length) {
                const backCam = devices.find(device =>
                    device.label.toLowerCase().includes('back') ||
                    device.label.toLowerCase().includes('environment')
                );

                const cameraId = backCam ? backCam.id : devices[0].id;

                html5QrCode.start(
                    cameraId, {
                        fps: 10,
                        qrbox: {
                            width: 300,
                            height: 300
                        }
                    },
                    onScanSuccess
                ).catch(err => {
                    console.error("Gagal mulai scanner: ", err);
                    tampilkanFallback();
                });
            } else {
                tampilkanFallback();
            }
        }).catch(err => {
            console.error("Tidak bisa mendapatkan daftar kamera: ", err);
            tampilkanFallback();
        });
    });
</script>