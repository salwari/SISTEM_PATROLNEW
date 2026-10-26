<x-app-layout :title="'Scan Barcode Kamar Mandi'">
    <div class="max-w-2xl mx-auto p-4 space-y-4">

        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-bold text-gray-800">Scan Barcode Kamar Mandi</h1>
                <p class="text-xs text-gray-500">Arahkan kamera ke barcode/QR Code yang terpasang di kamar mandi</p>
            </div>
            <a href="{{ route('ob.dashboard') }}"
                class="px-3 py-1.5 bg-gray-200 hover:bg-gray-300 text-gray-700 text-xs font-semibold rounded-lg transition">
                &larr; Kembali
            </a>
        </div>

        <div class="bg-white p-4 sm:p-6 rounded-xl shadow-sm border border-gray-200 text-center">
            <div id="reader" class="w-full rounded-lg overflow-hidden border border-gray-200 bg-black min-h-[300px]"></div>

            {{-- Pesan Status Hasil Scan --}}
            <div id="result" class="mt-4 text-sm font-semibold text-blue-600 min-h-[24px]"></div>

            <p class="text-xs text-gray-400 mt-2">
                Pastikan stiker barcode mendapatkan cahaya yang cukup dan tidak buram.
            </p>
        </div>

    </div>

    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const resultContainer = document.getElementById('result');
            const html5QrCode = new Html5Qrcode("reader");
            let hasScanned = false;

            function onScanSuccess(decodedText) {
                if (hasScanned) return;
                hasScanned = true;

                html5QrCode.stop().then(() => {
                    resultContainer.innerText = `Barcode Terbaca: Mengalihkan...`;

                    if (decodedText.startsWith('http://') || decodedText.startsWith('https://')) {
                        setTimeout(() => {
                            window.location.href = decodedText;
                        }, 1000);
                    } else {
                        window.location.href = `/ob/kamar-mandi/${decodedText}/periksa`;
                    }
                }).catch(err => {
                    console.error("Gagal menghentikan scanner: ", err);
                });
            }

            Html5Qrcode.getCameras().then(devices => {
                if (devices && devices.length) {
                    const backCam = devices.find(device =>
                        device.label.toLowerCase().includes('back') ||
                        device.label.toLowerCase().includes('environment')
                    );
                    const cameraId = backCam ? backCam.id : devices[0].id;

                    html5QrCode.start(
                        cameraId,
                        {
                            fps: 10,
                            qrbox: { width: 250, height: 250 }
                        },
                        onScanSuccess
                    ).catch(err => {
                        resultContainer.innerText = "Gagal menyalakan kamera: " + err;
                    });
                } else {
                    resultContainer.innerText = "Kamera tidak ditemukan pada perangkat ini.";
                }
            }).catch(err => {
                resultContainer.innerText = "Izin akses kamera ditolak atau tidak didukung.";
            });
        });
    </script>
</x-app-layout>