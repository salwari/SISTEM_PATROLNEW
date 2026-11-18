<x-app-layout :title="'Scan QR Lokasi Patroli'">
    <div class="container mx-auto p-6">
        <h2 class="text-2xl font-bold mb-2 text-gray-800 text-center">Scan QR Patroli</h2>

        <div class="bg-white p-4 sm:p-6 rounded-lg shadow-md max-w-xl mx-auto">
            <div id="reader" class="w-full border border-gray-200 rounded-md"></div>

            <div id="fallback" class="hidden text-center">
                <p class="text-sm text-gray-600 mb-3">
                    Kamera langsung tidak tersedia di koneksi ini. Ambil foto QR yang terpasang di pos patroli.
                </p>
                <label class="inline-block px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg font-semibold cursor-pointer">
                    Ambil Foto QR
                    <input type="file" id="qr-file" accept="image/*" capture="environment" class="hidden">
                </label>
            </div>

            <div id="result" class="mt-4 text-center font-semibold text-blue-600"></div>
        </div>
    </div>

    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js" type="text/javascript"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const resultContainer = document.getElementById('result');
            const readerEl = document.getElementById('reader');
            const fallbackEl = document.getElementById('fallback');
            const fileInput = document.getElementById('qr-file');
            const html5QrCode = new Html5Qrcode("reader");
            let hasScanned = false;

            function showError(message) {
                resultContainer.className = "mt-4 text-center font-semibold text-red-600";
                resultContainer.innerText = message;
            }

            function tampilkanFallback() {
                readerEl.classList.add('hidden');
                fallbackEl.classList.remove('hidden');
            }

            function qrValid(decodedText) {
                return decodedText.startsWith('http://') || decodedText.startsWith('https://');
            }

            function arahkan(decodedText) {
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
            }

            function onScanSuccess(decodedText) {
                if (hasScanned) return;

                if (!qrValid(decodedText)) {
                    showError("QR code tidak valid.");
                    return;
                }

                hasScanned = true;

                html5QrCode.stop().then(() => {
                    arahkan(decodedText);
                }).catch(err => {
                    console.error("Gagal menghentikan scanner: ", err);
                });
            }

            function perkecil(file, maks) {
            return new Promise((resolve, reject) => {
                const img = new Image();
                const url = URL.createObjectURL(file);
                img.onload = () => {
                    const skala = Math.min(1, maks / Math.max(img.width, img.height));
                    const c = document.createElement('canvas');
                    c.width = Math.round(img.width * skala);
                    c.height = Math.round(img.height * skala);
                    c.getContext('2d').drawImage(img, 0, 0, c.width, c.height);
                    URL.revokeObjectURL(url);
                    c.toBlob(b => b ? resolve(new File([b], 'qr.png', { type: 'image/png' })) : reject(), 'image/png');
                };
                img.onerror = reject;
                img.src = url;
            });
        }

        function bacaFoto(file) {
            let p = Promise.reject();
            [500, 400, 600, 300, 700, 900, 1200].forEach(u => {
                p = p.catch(() => perkecil(file, u).then(f => html5QrCode.scanFile(f, false)));
            });
            return p;
        }

        fileInput.addEventListener('change', function () {
                if (!this.files.length) return;

                bacaFoto(this.files[0])
                    .then(decodedText => {
                        if (!qrValid(decodedText)) {
                            showError("QR code tidak valid.");
                            return;
                        }
                        arahkan(decodedText);
                    })
                    .catch(() => {
                        showError("QR tidak terbaca. Pastikan terlihat jelas pada foto, lalu coba lagi.");
                    });

                this.value = '';
            });

            if (!window.isSecureContext || !navigator.mediaDevices) {
                tampilkanFallback();
                return;
            }

            html5QrCode.start(
                { facingMode: "environment" },
                { fps: 10, qrbox: { width: 300, height: 300 } },
                onScanSuccess
            ).catch(err => {
                console.error("Gagal mulai scanner: ", err);
                tampilkanFallback();
            });
        });
    </script>
</x-app-layout>