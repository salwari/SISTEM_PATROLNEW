<x-app-layout :title="'Scan QR Kendaraan'">
    <div class="container mx-auto p-6">
        <h2 class="text-2xl font-bold mb-2 text-gray-800 text-center">Scan QR Kendaraan</h2>

        <div class="bg-white p-4 sm:p-6 rounded-lg shadow-md max-w-xl mx-auto">
            {{-- Area kamera untuk Scan QR --}}
            <div id="reader" class="w-full border border-gray-200 rounded-md"></div>

            {{-- Menampilkan hasil setelah QR discan --}}
            <div id="result" class="mt-4 text-center font-semibold text-blue-600"></div>
        </div>
    </div>
</x-app-layout>

{{-- Memuat library HTML5 QR Code --}}
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const resultContainer = document.getElementById('result');
        const html5QrCode = new Html5Qrcode("reader");

        let hasScanned = false;

        function onScanSuccess(decodedText, decodedResult) {
            if (hasScanned) return;
            hasScanned = true;

            html5QrCode.stop().then(() => {
                resultContainer.innerText = `QR Berhasil: ${decodedText}`;

                // Terima QR baru (/sopir/absen/create) maupun QR lama (/sopir/cek/create).
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
            }).catch(err => {
                console.error("Gagal menghentikan scanner: ", err);
            });
        }
        
        // Mulai scanner dan ambil kamera belakang
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
                    alert("Gagal mulai scanner : " + err);
                });
            } else {
                alert("Kamera tidak ditemukan.");
            }
        }).catch(err => {
            alert("Tidak bisa mendapatkan daftar kamera : " + err);
        });
    });
</script>
