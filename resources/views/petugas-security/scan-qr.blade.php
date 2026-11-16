<x-app-layout :title="'Scan QR Lokasi Patroli'">
    <div class="container mx-auto p-6">
        <h2 class="text-2xl font-bold mb-2 text-gray-800 text-center">Scan QR Patroli</h2>

        <div class="bg-white p-4 sm:p-6 rounded-lg shadow-md max-w-xl mx-auto">
            <div id="reader" class="w-full border border-gray-200 rounded-md"></div>

            <div id="result" class="mt-4 text-center font-semibold text-blue-600"></div>
        </div>
    </div>

    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js" type="text/javascript"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const resultContainer = document.getElementById('result');
            const html5QrCode = new Html5Qrcode("reader");
            let hasScanned = false;

            function showError(message) {
                resultContainer.className = "mt-4 text-center font-semibold text-red-600";
                resultContainer.innerText = message;
            }

            function onScanSuccess(decodedText) {
                if (hasScanned) return;

                if (!decodedText.startsWith('http://') && !decodedText.startsWith('https://')) {
                    showError("QR code tidak valid.");
                    return;
                }

                hasScanned = true;

                html5QrCode.stop().then(() => {
                    resultContainer.className = "mt-4 text-center font-semibold text-blue-600";
                    resultContainer.innerText = "QR berhasil dibaca. Mengalihkan...";

                    let targetUrl = decodedText;

                    try {
                        const urlObj = new URL(decodedText);
                        targetUrl = urlObj.pathname + urlObj.search;
                    } catch (e) {
                        targetUrl = decodedText;
                    }

                    setTimeout(() => {
                        window.location.href = targetUrl;
                    }, 1000);
                }).catch(err => {
                    console.error("Gagal menghentikan scanner: ", err);
                });
            }

            if (!window.isSecureContext || !navigator.mediaDevices) {
                showError("Kamera hanya bisa dipakai lewat HTTPS atau localhost.");
                return;
            }

            html5QrCode.start(
                { facingMode: "environment" },
                { fps: 10, qrbox: { width: 300, height: 300 } },
                onScanSuccess
            ).catch(err => {
                showError("Gagal mulai scanner: " + err);
            });
        });
    </script>
</x-app-layout>