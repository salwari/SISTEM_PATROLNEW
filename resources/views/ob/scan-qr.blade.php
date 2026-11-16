<x-app-layout :title="'Scan Barcode Kamar Mandi'">
    <div class="max-w-2xl mx-auto p-4 space-y-4">

        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-bold text-gray-800">Scan Barcode Kamar Mandi</h1>
                <p class="text-xs text-gray-500">Arahkan kamera ke barcode/QR Code yang terpasang di kamar mandi</p>
            </div>
            <a href="{{ route('ob.dashboard') }}"
                class="px-3 py-1.5 bg-gray-200 hover:bg-gray-300 text-gray-700 dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-200 text-xs font-semibold rounded-lg transition">
                &larr; Kembali
            </a>
        </div>

        <div class="bg-white p-4 sm:p-6 rounded-xl shadow-sm border border-gray-200 text-center">
            <div id="reader" class="w-full rounded-lg overflow-hidden border border-gray-200 bg-black min-h-[300px]"></div>

            <div id="result" class="mt-4 text-sm font-semibold text-blue-600 min-h-[24px]"></div>

            <p class="text-xs text-gray-400 mt-2">
                Pastikan stiker barcode mendapatkan cahaya yang cukup dan tidak buram.
            </p>
        </div>

    </div>

    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js" type="text/javascript"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const resultContainer = document.getElementById('result');
            const html5QrCode = new Html5Qrcode("reader");
            let hasScanned = false;

            function showError(message) {
                resultContainer.className = "mt-4 text-sm font-semibold text-red-600 min-h-[24px]";
                resultContainer.innerText = message;
            }

            function onScanSuccess(decodedText) {
                if (hasScanned) return;

                if (decodedText.includes('/petugas-security') || decodedText.includes('/patroli/')) {
                    showError("❌ Ini adalah Barcode Pos Patroli Satpam, bukan Kamar Mandi.");
                    return;
                }

                hasScanned = true;

                html5QrCode.stop().then(() => {
                    resultContainer.className = "mt-4 text-sm font-semibold text-blue-600 min-h-[24px]";
                    resultContainer.innerText = "Barcode Terbaca: Mengalihkan...";

                    let targetUrl = '';

                    try {
                        if (decodedText.startsWith('http://') || decodedText.startsWith('https://')) {
                            const urlObj = new URL(decodedText);

                            if (urlObj.pathname.includes('/ob/kamar-mandi/')) {
                                targetUrl = urlObj.pathname;
                            } else if (urlObj.searchParams.has('kamar_mandi')) {
                                const kmId = urlObj.searchParams.get('kamar_mandi');
                                targetUrl = `/ob/kamar-mandi/${kmId}/periksa`;
                            } else {
                                const match = urlObj.pathname.match(/lokasi-kebersihan\/(\d+)/);
                                if (match) {
                                    targetUrl = `/ob/kamar-mandi/${match[1]}/periksa`;
                                } else {
                                    targetUrl = urlObj.pathname;
                                }
                            }
                        } else {
                            targetUrl = `/ob/kamar-mandi/${decodedText}/periksa`;
                        }
                    } catch (e) {
                        targetUrl = `/ob/kamar-mandi/${decodedText}/periksa`;
                    }

                    setTimeout(() => {
                        window.location.href = targetUrl;
                    }, 800);

                }).catch(err => {
                    console.error("Gagal menghentikan scanner: ", err);
                });
            }

            if (!window.isSecureContext || !navigator.mediaDevices) {
                showError("Kamera hanya bisa dipakai lewat HTTPS. Buka halaman ini dengan alamat https://.");
                return;
            }

            html5QrCode.start(
                { facingMode: "environment" },
                { fps: 10, qrbox: { width: 250, height: 250 } },
                onScanSuccess
            ).catch(err => {
                showError("Gagal menyalakan kamera: " + err);
            });
        });
    </script>
</x-app-layout>