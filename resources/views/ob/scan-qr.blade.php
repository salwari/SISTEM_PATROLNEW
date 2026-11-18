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

            <div id="fallback" class="hidden">
                <p class="text-sm text-gray-600 mb-3">
                    Kamera langsung tidak tersedia di koneksi ini. Ambil foto barcode yang terpasang di kamar mandi.
                </p>
                <label class="inline-block px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg font-semibold cursor-pointer">
                    Ambil Foto Barcode
                    <input type="file" id="qr-file" accept="image/*" capture="environment" class="hidden">
                </label>
            </div>

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
            const readerEl = document.getElementById('reader');
            const fallbackEl = document.getElementById('fallback');
            const fileInput = document.getElementById('qr-file');
            const html5QrCode = new Html5Qrcode("reader");
            let hasScanned = false;

            function showError(message) {
                resultContainer.className = "mt-4 text-sm font-semibold text-red-600 min-h-[24px]";
                resultContainer.innerText = message;
            }

            function tampilkanFallback() {
                readerEl.classList.add('hidden');
                fallbackEl.classList.remove('hidden');
            }

            function adalahBarcodePatroli(decodedText) {
                return decodedText.includes('/petugas-security') || decodedText.includes('/patroli/');
            }

            function arahkan(decodedText) {
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
            }

            function onScanSuccess(decodedText) {
                if (hasScanned) return;

                if (adalahBarcodePatroli(decodedText)) {
                    showError("Ini adalah Barcode Pos Patroli Satpam, bukan Kamar Mandi.");
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
                        if (adalahBarcodePatroli(decodedText)) {
                            showError("Ini adalah Barcode Pos Patroli Satpam, bukan Kamar Mandi.");
                            return;
                        }
                        arahkan(decodedText);
                    })
                    .catch(() => {
                        showError("Barcode tidak terbaca. Pastikan terlihat jelas pada foto, lalu coba lagi.");
                    });

                this.value = '';
            });

            if (!window.isSecureContext || !navigator.mediaDevices) {
                tampilkanFallback();
                return;
            }

            html5QrCode.start(
                { facingMode: "environment" },
                { fps: 10, qrbox: { width: 250, height: 250 } },
                onScanSuccess
            ).catch(err => {
                console.error("Gagal menyalakan kamera: ", err);
                tampilkanFallback();
            });
        });
    </script>
</x-app-layout>