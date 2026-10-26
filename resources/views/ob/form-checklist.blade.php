<x-app-layout :title="'Form Pengecekan Kamar Mandi'">
    <div class="max-w-4xl mx-auto p-4 space-y-6">

        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Form Checklist Pemeriksaan</h1>
                <p class="text-sm text-gray-500">Isi evaluasi kebersihan dan kelengkapan fasilitas kamar mandi</p>
            </div>
            <a href="{{ route('ob.dashboard') }}"
                class="inline-flex items-center px-4 py-2 text-xs font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:text-blue-600 shadow-sm transition">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Dashboard
            </a>
        </div>

        <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 flex flex-wrap justify-between items-center gap-3">
            <div>
                <span class="text-xs font-mono font-bold text-blue-700 uppercase bg-blue-100 px-2 py-0.5 rounded">
                    {{ $kamarMandi->kode_kamar_mandi }}
                </span>
                <h2 class="text-lg font-bold text-gray-900 mt-1">{{ $kamarMandi->nama_kamar_mandi }}</h2>
                <p class="text-xs text-gray-600">{{ $kamarMandi->lantai }} &bull; {{ $kamarMandi->deskripsi ?? 'Toilet operasional cabang' }}</p>
            </div>
            <div class="text-right text-xs text-gray-600">
                <span class="block">Petugas: <strong>{{ Auth::user()->nama }}</strong></span>
                <span class="block text-gray-500">{{ now()->translatedFormat('d F Y') }}</span>
            </div>
        </div>

        <form action="{{ route('ob.kamar-mandi.simpan', $kamarMandi->id) }}" method="POST" id="checklistForm" class="space-y-6">
            @csrf

            <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm space-y-4">
                <div class="border-b border-gray-100 pb-2">
                    <h3 class="text-base font-bold text-gray-800 flex items-center">
                        <span class="w-2.5 h-2.5 bg-blue-600 rounded-full mr-2"></span>
                        1. Standar Kebersihan
                    </h3>
                    <p class="text-xs text-gray-500">Pastikan area dalam kondisi bersih dan bebas kotoran</p>
                </div>

                <div class="space-y-3">
                    @foreach ($checklistKebersihan as $item)
                        <div class="p-3 bg-gray-50 rounded-lg border border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <span class="text-sm font-medium text-gray-800">{{ $item->nama_butir }}</span>
                            <div class="flex items-center gap-4 text-xs font-medium flex-shrink-0">
                                <label class="flex items-center cursor-pointer text-green-700">
                                    <input type="radio" name="checklist[{{ $item->id }}]" value="sesuai" checked
                                        class="checklist-radio w-4 h-4 text-green-600 border-gray-300 focus:ring-green-500 mr-1.5">
                                    Bersih / Sesuai
                                </label>
                                <label class="flex items-center cursor-pointer text-red-600">
                                    <input type="radio" name="checklist[{{ $item->id }}]" value="tidak_sesuai"
                                        class="checklist-radio w-4 h-4 text-red-600 border-gray-300 focus:ring-red-500 mr-1.5">
                                    Kotor / Noda
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm space-y-4">
                <div class="border-b border-gray-100 pb-2">
                    <h3 class="text-base font-bold text-gray-800 flex items-center">
                        <span class="w-2.5 h-2.5 bg-indigo-600 rounded-full mr-2"></span>
                        2. Fasilitas & Kelengkapan
                    </h3>
                    <p class="text-xs text-gray-500">Pastikan sarana toilet berfungsi dan perlengkapan tersedia</p>
                </div>

                <div class="space-y-3">
                    @foreach ($checklistKelengkapan as $item)
                        <div class="p-3 bg-gray-50 rounded-lg border border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <span class="text-sm font-medium text-gray-800">{{ $item->nama_butir }}</span>
                            <div class="flex items-center gap-4 text-xs font-medium flex-shrink-0">
                                <label class="flex items-center cursor-pointer text-green-700">
                                    <input type="radio" name="checklist[{{ $item->id }}]" value="sesuai" checked
                                        class="checklist-radio w-4 h-4 text-green-600 border-gray-300 focus:ring-green-500 mr-1.5">
                                    Lengkap / Normal
                                </label>
                                <label class="flex items-center cursor-pointer text-red-600">
                                    <input type="radio" name="checklist[{{ $item->id }}]" value="tidak_sesuai"
                                        class="checklist-radio w-4 h-4 text-red-600 border-gray-300 focus:ring-red-500 mr-1.5">
                                    Kurang / Rusak
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm space-y-4">
                <div>
                    <h3 class="text-base font-bold text-gray-800">3. Status Kondisi Akhir</h3>
                    <p class="text-xs text-gray-500 mt-0.5">
                        💡 <strong>Panduan:</strong> Jika kotor/habis, bersihkan atau isi terlebih dahulu lalu pilih <em>Kondisi Baik</em>. 
                        Pilih <em>Perlu Tindakan</em> hanya jika terdapat kerusakan fasilitas yang membutuhkan teknisi/perbaikan.
                    </p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <label class="flex items-center p-3.5 border-2 rounded-xl cursor-pointer transition has-[:checked]:border-green-600 has-[:checked]:bg-green-50">
                        <input type="radio" name="status_kondisi" id="statusBaik" value="baik" checked
                            class="w-4 h-4 text-green-600 border-gray-300 focus:ring-green-500">
                        <div class="ml-3">
                            <span class="block text-sm font-bold text-gray-900">Kondisi Baik</span>
                            <span class="text-xs text-gray-500">Toilet bersih, rapi, dan semua fasilitas berfungsi normal</span>
                        </div>
                    </label>

                    <label class="flex items-center p-3.5 border-2 rounded-xl cursor-pointer transition has-[:checked]:border-red-600 has-[:checked]:bg-red-50">
                        <input type="radio" name="status_kondisi" id="statusPerluTindakan" value="perlu_tindakan"
                            class="w-4 h-4 text-red-600 border-gray-300 focus:ring-red-500">
                        <div class="ml-3">
                            <span class="block text-sm font-bold text-red-700">Perlu Tindakan (Temuan Masalah)</span>
                            <span class="text-xs text-gray-500">Ada kerusakan fasilitas yang butuh perbaikan teknisi/pengadaan</span>
                        </div>
                    </label>
                </div>

                <div id="catatanSection" class="hidden pt-3 border-t border-gray-100">
                    <label for="catatan" class="block text-xs font-bold text-red-700 uppercase mb-1">
                        Catatan Temuan Masalah <span class="text-red-600">*</span>
                    </label>
                    <textarea name="catatan" id="catatan" rows="3"
                        placeholder="Contoh: Kran wastafel bocor deras, kloset mampet, atau kunci pintu rusak..."
                        class="w-full text-sm rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500"></textarea>
                    @error('catatan')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm space-y-4">
                <div>
                    <h3 class="text-base font-bold text-gray-800">
                        4. Foto Bukti Lapangan 
                        <span id="fotoBadgeWajib" class="hidden text-xs bg-red-100 text-red-700 px-2 py-0.5 rounded font-semibold ml-2">Wajib</span>
                    </h3>
                    <p class="text-xs text-gray-500">Ambil foto kondisi kamar mandi langsung dari kamera perangkat</p>
                </div>

                <div class="border border-gray-200 rounded-xl p-4 bg-gray-50 flex flex-col items-center max-w-md mx-auto">
                    <video id="cameraVideo" class="w-full rounded-lg border border-gray-300 bg-black aspect-video object-cover" autoplay playsinline></video>
                    <img id="photoPreview" class="w-full rounded-lg border border-gray-300 aspect-video object-cover hidden" />

                    <div class="w-full mt-3 flex gap-2">
                        <button type="button" id="snapBtn"
                            class="w-full py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-lg transition shadow-sm">
                            Ambil Foto
                        </button>
                        <button type="button" id="retakeBtn"
                            class="w-full py-2 bg-gray-600 hover:bg-gray-700 text-white text-xs font-bold rounded-lg transition shadow-sm hidden">
                            Ambil Ulang
                        </button>
                    </div>
                </div>

                <canvas id="photoCanvas" class="hidden"></canvas>
                <input type="hidden" name="foto" id="fotoInput">
                @error('foto')
                    <p class="text-xs text-red-600 text-center">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-end gap-3 pt-4">
                <a href="{{ route('ob.dashboard') }}"
                    class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 rounded-lg shadow-sm transition">
                    Batal
                </a>
                <button type="submit"
                    class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-lg transition shadow-md">
                    Simpan Hasil Pemeriksaan
                </button>
            </div>

        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const statusBaik = document.getElementById('statusBaik');
            const statusPerluTindakan = document.getElementById('statusPerluTindakan');
            const catatanSection = document.getElementById('catatanSection');
            const catatanInput = document.getElementById('catatan');
            const fotoBadgeWajib = document.getElementById('fotoBadgeWajib');
            const checklistRadios = document.querySelectorAll('.checklist-radio');

            function updateStatusState() {
                if (statusPerluTindakan.checked) {
                    catatanSection.classList.remove('hidden');
                    catatanInput.required = true;
                    fotoBadgeWajib.classList.remove('hidden');
                } else {
                    catatanSection.classList.add('hidden');
                    catatanInput.required = false;
                    fotoBadgeWajib.classList.add('hidden');
                }
            }

            statusBaik.addEventListener('change', updateStatusState);
            statusPerluTindakan.addEventListener('change', updateStatusState);

            checklistRadios.forEach(radio => {
                radio.addEventListener('change', function () {
                    let hasTidakSesuai = false;
                    checklistRadios.forEach(r => {
                        if (r.checked && r.value === 'tidak_sesuai') {
                            hasTidakSesuai = true;
                        }
                    });

                    if (hasTidakSesuai) {
                        statusPerluTindakan.checked = true;
                    }
                    updateStatusState();
                });
            });

            const video = document.getElementById('cameraVideo');
            const preview = document.getElementById('photoPreview');
            const snapBtn = document.getElementById('snapBtn');
            const retakeBtn = document.getElementById('retakeBtn');
            const canvas = document.getElementById('photoCanvas');
            const fotoInput = document.getElementById('fotoInput');
            let streamObj = null;

            function startCamera() {
                navigator.mediaDevices.getUserMedia({
                    video: { facingMode: { ideal: "environment" } },
                    audio: false
                }).then(stream => {
                    streamObj = stream;
                    video.srcObject = stream;
                    video.classList.remove('hidden');
                    preview.classList.add('hidden');
                    snapBtn.classList.remove('hidden');
                    retakeBtn.classList.add('hidden');
                }).catch(err => {
                    console.log("Kamera tidak aktif atau izin ditolak: ", err);
                });
            }

            startCamera();

            snapBtn.addEventListener('click', function () {
                canvas.width = video.videoWidth || 640;
                canvas.height = video.videoHeight || 480;
                const context = canvas.getContext('2d');
                context.drawImage(video, 0, 0, canvas.width, canvas.height);

                const dataUrl = canvas.toDataURL('image/jpeg', 0.8);
                fotoInput.value = dataUrl;
                preview.src = dataUrl;

                video.classList.add('hidden');
                preview.classList.remove('hidden');
                snapBtn.classList.add('hidden');
                retakeBtn.classList.remove('hidden');
            });

            retakeBtn.addEventListener('click', function () {
                fotoInput.value = '';
                video.classList.remove('hidden');
                preview.classList.add('hidden');
                snapBtn.classList.remove('hidden');
                retakeBtn.classList.add('hidden');
            });
        });
    </script>
</x-app-layout>