<x-app-layout :title="'Jadwal Sopir'">
    @php
        $gaya = [
            'pagi' => ['label' => 'Pagi', 'badge' => 'bg-yellow-100 text-yellow-800 border-yellow-300'],
            'sore' => ['label' => 'Sore', 'badge' => 'bg-orange-100 text-orange-800 border-orange-300'],
            'malam' => ['label' => 'Malam', 'badge' => 'bg-indigo-100 text-indigo-800 border-indigo-300'],
        ];
        $hariIni = now()->toDateString();
        $tanggalDefault = now()->format('Y-m') === $currentMonth->format('Y-m') ? $hariIni : $currentMonth->format('Y-m-01');
    @endphp

    <div class="container mx-auto p-4">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">
                Jadwal Sopir Bulan {{ $currentMonth->translatedFormat('F Y') }}
            </h2>

            @if ($errors->any())
                <div class="mb-4 p-3 rounded-lg border border-red-200 bg-red-50 text-sm text-red-700">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $pesan)
                            <li>{{ $pesan }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div
                class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2 border border-gray-200 rounded-lg shadow-md p-4">

                <form method="GET" action="{{ route('jadwal-sopir.index') }}" class="w-full sm:w-auto">
                    <input type="month" name="month" value="{{ $currentMonth->format('Y-m') }}"
                        onchange="this.form.submit()"
                        class="border border-gray-300 rounded px-4 py-2 text-sm text-gray-700 shadow-sm w-full min-w-[150px] sm:min-w-[180px]">
                </form>

                <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
                    @if ($bisaGenerate)
                        <button type="button" data-buka-modal="generateModal"
                            class="w-full min-w-[80px] sm:min-w-[100px] whitespace-nowrap px-4 py-2 bg-yellow-400 hover:bg-yellow-500 text-white rounded-lg text-sm font-semibold text-center">
                            {{ $adaJadwal ? 'Generate Ulang' : 'Generate Jadwal' }}
                        </button>
                    @endif

                    <button type="button" data-buka-modal="tambahModal"
                        class="w-full min-w-[80px] sm:min-w-[100px] whitespace-nowrap px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-semibold text-center">
                        Tambah Jadwal
                    </button>

                    <a href="{{ route('jadwal-sopir.cetak-jadwal', ['month' => $currentMonth->format('Y-m')]) }}"
                        class="inline-flex items-center justify-center w-full min-w-[80px] sm:min-w-[100px] whitespace-nowrap px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm font-semibold text-center">
                        <svg class="w-4 h-4 mr-2 text-white" xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 512 512" fill="currentColor" aria-hidden="true">
                            <path
                                d="M128 0C92.7 0 64 28.7 64 64l0 96 64 0 0-96 226.7 0L384 93.3l0 66.7 64 0 0-66.7c0-17-6.7-33.3-18.7-45.3L400 18.7C388 6.7 371.7 0 354.7 0L128 0zM384 352l0 32 0 64-256 0 0-64 0-16 0-16 256 0zm64 32l32 0c17.7 0 32-14.3 32-32l0-96c0-35.3-28.7-64-64-64L64 192c-35.3 0-64 28.7-64 64l0 96c0 17.7 14.3 32 32 32l32 0 0 64c0 35.3 28.7 64 64 64l256 0c35.3 0 64-28.7 64-64l0-64zM432 248a24 24 0 1 1 0 48 24 24 0 1 1 0-48z" />
                        </svg>
                        Cetak
                    </a>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-2 mt-3 text-xs">
                @foreach ($gaya as $g)
                    <span class="px-2 py-0.5 rounded border font-semibold {{ $g['badge'] }}">{{ $g['label'] }}</span>
                @endforeach
                <span class="text-gray-500">Klik tanggal untuk melihat, mengganti, atau menghapus jadwal sopir.</span>
            </div>
        </div>

        <div class="w-full overflow-x-auto border border-gray-200 rounded-lg shadow-md">
            <div class="min-w-[700px] grid grid-cols-7 gap-px text-center text-sm select-none">

                @foreach (['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'] as $hari)
                    <div class="font-semibold py-2 bg-blue-200 text-gray-800 text-xs uppercase border border-gray-200">
                        {{ $hari }}
                    </div>
                @endforeach

                @php
                    $startDayOfWeek = $currentMonth->copy()->startOfMonth()->dayOfWeek;
                    $daysInMonth = $currentMonth->daysInMonth;
                    $sisaSel = (7 - (($startDayOfWeek + $daysInMonth) % 7)) % 7;
                @endphp

                @for ($i = 0; $i < $startDayOfWeek; $i++)
                    <div class="border border-gray-200 min-h-[6rem] bg-gray-100"></div>
                @endfor

                @for ($day = 1; $day <= $daysInMonth; $day++)
                    @php
                        $date = $currentMonth->format('Y-m') . '-' . str_pad($day, 2, '0', STR_PAD_LEFT);
                        $hariJadwal = $jadwalPerTanggal[$date] ?? [];
                        $adaIsi = false;
                    @endphp

                    <div data-tanggal="{{ $date }}"
                        class="border border-gray-200 p-1.5 min-h-[6rem] text-left cursor-pointer hover:bg-blue-50 rounded transition-all overflow-hidden {{ $date === $hariIni ? 'bg-blue-50 ring-2 ring-blue-300' : 'bg-white' }}">
                        <div class="text-xs font-semibold text-gray-800">{{ $day }}</div>

                        @foreach (['pagi', 'sore', 'malam'] as $shift)
                            @if (!empty($hariJadwal[$shift]))
                                @php
                                    $adaIsi = true;
                                    $namaShift = implode(', ', array_column($hariJadwal[$shift], 'nama'));
                                @endphp
                                <div class="mt-1 flex items-center gap-1 text-[11px] leading-tight rounded border px-1 py-0.5 overflow-hidden {{ $gaya[$shift]['badge'] }}"
                                    title="{{ $gaya[$shift]['label'] }}: {{ $namaShift }}">
                                    <span class="font-semibold shrink-0">{{ $gaya[$shift]['label'] }}
                                        ({{ count($hariJadwal[$shift]) }})</span>
                                    <span class="truncate">{{ $namaShift }}</span>
                                </div>
                            @endif
                        @endforeach

                        @unless ($adaIsi)
                            <span class="text-gray-700 text-[10px] italic mt-1 block">Belum dijadwalkan</span>
                        @endunless
                    </div>
                @endfor

                @for ($i = 0; $i < $sisaSel; $i++)
                    <div class="border border-gray-200 min-h-[6rem] bg-gray-100"></div>
                @endfor
            </div>
        </div>

        <div class="mt-4 bg-white p-2">
            <h2 class="text-lg font-bold text-gray-800 mb-2">Rekap Jumlah Jadwal Sopir Bulan
                {{ $currentMonth->translatedFormat('F Y') }}</h2>
            <div class="overflow-x-auto rounded-lg shadow-lg border border-gray-200">
                <table class="table-auto w-full text-sm text-gray-700">
                    <thead class="bg-blue-200 text-gray-800 text-xs uppercase">
                        <tr class="text-center">
                            <th class="px-4 py-2">No</th>
                            <th class="px-4 py-2 text-left">Nama Sopir</th>
                            <th class="px-4 py-2">Pagi</th>
                            <th class="px-4 py-2">Sore</th>
                            <th class="px-4 py-2">Malam</th>
                            <th class="px-4 py-2">Total</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($rekap as $index => $baris)
                            <tr class="hover:bg-gray-50 text-center">
                                <td class="px-4 py-2">{{ $index + 1 }}</td>
                                <td class="px-4 py-2 text-left">{{ $baris['nama'] }}</td>
                                <td class="px-4 py-2">{{ $baris['pagi'] }}</td>
                                <td class="px-4 py-2">{{ $baris['sore'] }}</td>
                                <td class="px-4 py-2">{{ $baris['malam'] }}</td>
                                <td class="px-4 py-2 font-semibold">{{ $baris['total'] }} kali</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-3 text-center text-gray-700">Belum ada data sopir.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if ($histori->isNotEmpty())
            <div class="mt-4 bg-white p-2">
                <h2 class="text-lg font-bold text-gray-800 mb-2">Histori Pergantian Sopir Bulan
                    {{ $currentMonth->translatedFormat('F Y') }}</h2>
                <div class="overflow-x-auto rounded-lg shadow-lg border border-gray-200">
                    <table class="table-auto w-full text-sm text-gray-700">
                        <thead class="bg-blue-200 text-gray-800 uppercase text-xs">
                            <tr class="text-center">
                                <th class="px-4 py-3">No</th>
                                <th class="px-4 py-3">Tanggal Jadwal</th>
                                <th class="px-4 py-3">Waktu Pergantian</th>
                                <th class="px-4 py-3">Shift</th>
                                <th class="px-4 py-3">Sopir Lama</th>
                                <th class="px-4 py-3">Sopir Baru</th>
                                <th class="px-4 py-3">Alasan</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($histori as $index => $item)
                                <tr class="hover:bg-gray-100 text-center">
                                    <td class="px-4 py-2">{{ $index + 1 }}</td>
                                    <td class="px-4 py-2">{{ $item->tanggal->format('d-m-Y') }}</td>
                                    <td class="px-4 py-2">{{ $item->waktu_pergantian->format('d-m-Y H:i') }}</td>
                                    <td class="px-4 py-2">{{ ucfirst($item->shift) }}</td>
                                    <td class="px-4 py-2">{{ $item->petugasLama->nama ?? '-' }}</td>
                                    <td class="px-4 py-2">{{ $item->petugasBaru->nama ?? '-' }}</td>
                                    <td class="px-4 py-2 text-left">{{ $item->alasan }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>

    <div id="hariModal" class="modal-sopir hidden fixed inset-0 z-50 items-center justify-center bg-black bg-opacity-50 p-4">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-lg max-h-[90vh] overflow-y-auto">
            <div class="p-4 border-b flex items-center justify-between">
                <h3 id="hariJudul" class="text-lg font-semibold"></h3>
                <button type="button" data-tutup-modal="hariModal" class="text-gray-500 hover:text-gray-800 text-xl leading-none">&times;</button>
            </div>
            <div id="hariIsi" class="p-4"></div>
            <div class="p-4 border-t flex justify-end gap-2">
                <button type="button" id="btnTambahHari"
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg font-semibold">Tambah Sopir</button>
                <button type="button" data-tutup-modal="hariModal"
                    class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm rounded-lg font-semibold">Tutup</button>
            </div>
        </div>
    </div>

    <div id="tambahModal" class="modal-sopir hidden fixed inset-0 z-50 items-center justify-center bg-black bg-opacity-50 p-4">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md max-h-[90vh] overflow-y-auto">
            <div class="p-4 border-b">
                <h3 class="text-lg font-semibold">Tambah Jadwal Sopir</h3>
            </div>
            <form action="{{ route('jadwal-sopir.store') }}" method="POST" class="p-6 space-y-5">
                @csrf
                <div>
                    <label for="tambah_tanggal" class="block mb-1 text-sm font-medium text-gray-700">Tanggal</label>
                    <input type="date" id="tambah_tanggal" name="tanggal" value="{{ $tanggalDefault }}" required
                        class="mt-1 w-full border border-gray-300 rounded-lg shadow-sm text-sm px-3 py-2 focus:ring focus:ring-blue-300">
                </div>

                <div>
                    <label for="tambah_shift" class="block mb-1 text-sm font-medium text-gray-700">Shift</label>
                    <select id="tambah_shift" name="shift" required
                        class="mt-1 w-full border border-gray-300 rounded-lg shadow-sm text-sm px-3 py-2 focus:ring focus:ring-blue-300">
                        <option value="">-- Pilih Shift --</option>
                        <option value="pagi">Pagi</option>
                        <option value="sore">Sore</option>
                        <option value="malam">Malam</option>
                    </select>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-sm font-medium text-gray-700">Sopir</span>
                        <label class="flex items-center gap-2 text-xs text-gray-600">
                            <input type="checkbox" id="pilihSemua" class="rounded border-gray-300">
                            Pilih semua
                        </label>
                    </div>
                    <div class="max-h-48 overflow-y-auto border border-gray-300 rounded-lg divide-y divide-gray-100">
                        @forelse ($sopir as $s)
                            <label class="flex items-center gap-3 px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 cursor-pointer">
                                <input type="checkbox" name="user_id[]" value="{{ $s->id }}"
                                    class="cb-sopir rounded border-gray-300">
                                {{ $s->nama }}
                            </label>
                        @empty
                            <p class="px-3 py-2 text-sm text-gray-500 italic">Belum ada sopir di cabang ini.</p>
                        @endforelse
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Jumlah sopir dalam satu shift tidak dibatasi.</p>
                </div>

                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" data-tutup-modal="tambahModal"
                        class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm rounded-lg font-semibold">Batal</button>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg font-semibold">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    @if ($bisaGenerate)
        <div id="generateModal" class="modal-sopir hidden fixed inset-0 z-50 items-center justify-center bg-black bg-opacity-50 p-4">
            <div class="bg-white rounded-lg shadow-lg w-full max-w-md max-h-[90vh] overflow-y-auto">
                <div class="p-4 border-b">
                    <h3 class="text-lg font-semibold">{{ $adaJadwal ? 'Generate Ulang Jadwal Sopir' : 'Generate Jadwal Sopir' }}</h3>
                </div>
                <form id="formGenerate" action="{{ route('jadwal-sopir.generate') }}" method="POST"
                    data-ada="{{ $adaJadwal ? '1' : '0' }}" class="p-6 space-y-5">
                    @csrf
                    <input type="hidden" name="month" value="{{ $currentMonth->format('Y-m') }}">

                    <div>
                        <label for="jumlah_per_shift" class="block mb-1 text-sm font-medium text-gray-700">Jumlah sopir per shift</label>
                        <input type="number" id="jumlah_per_shift" name="jumlah_per_shift"
                            value="{{ old('jumlah_per_shift', min(3, max($sopir->count(), 1))) }}" min="1"
                            max="{{ max($sopir->count(), 1) }}" required
                            class="mt-1 w-full border border-gray-300 rounded-lg shadow-sm text-sm px-3 py-2 focus:ring focus:ring-blue-300">
                        <p class="text-xs text-gray-500 mt-1">Tersedia {{ $sopir->count() }} sopir di cabang ini.</p>
                    </div>

                    <div>
                        <span class="block mb-1 text-sm font-medium text-gray-700">Shift yang dibuat</span>
                        <div class="flex flex-wrap gap-4 text-sm text-gray-700">
                            <label class="flex items-center gap-2">
                                <input type="checkbox" name="shifts[]" value="pagi" checked class="rounded border-gray-300"> Pagi
                            </label>
                            <label class="flex items-center gap-2">
                                <input type="checkbox" name="shifts[]" value="sore" checked class="rounded border-gray-300"> Sore
                            </label>
                            <label class="flex items-center gap-2">
                                <input type="checkbox" name="shifts[]" value="malam" class="rounded border-gray-300"> Malam
                            </label>
                        </div>
                    </div>

                    <p class="text-xs text-gray-500">
                        Jadwal dibuat mulai {{ $mulaiGenerate->translatedFormat('d F Y') }} sampai akhir bulan,
                        dan dibagi merata antar sopir. Jadwal yang sudah ada pada rentang tanggal itu akan diganti.
                    </p>

                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" data-tutup-modal="generateModal"
                            class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm rounded-lg font-semibold">Batal</button>
                        <button type="submit"
                            class="px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white text-sm rounded-lg font-semibold">Generate</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <form id="formHapus" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>
</x-app-layout>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const jadwal = @json($jadwalPerTanggal);
        const sopir = @json($sopirJs);
        const CSRF = "{{ csrf_token() }}";
        const URL_UPDATE = "{{ route('jadwal-sopir.update', ['jadwalSopir' => '__ID__']) }}";
        const URL_HAPUS = "{{ route('jadwal-sopir.destroy', ['jadwalSopir' => '__ID__']) }}";

        const SHIFTS = ['pagi', 'sore', 'malam'];
        const LABEL = { pagi: 'Pagi', sore: 'Sore', malam: 'Malam' };
        const BADGE = {
            pagi: 'bg-yellow-100 text-yellow-800 border-yellow-300',
            sore: 'bg-orange-100 text-orange-800 border-orange-300',
            malam: 'bg-indigo-100 text-indigo-800 border-indigo-300'
        };

        let hariAktif = null;

        function esc(teks) {
            return String(teks).replace(/[&<>"']/g, (c) => ({
                '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'
            }[c]));
        }

        function bukaModal(id) {
            const modal = document.getElementById(id);
            if (!modal) return;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function tutupModal(id) {
            const modal = document.getElementById(id);
            if (!modal) return;
            modal.classList.remove('flex');
            modal.classList.add('hidden');
        }

        function formatTanggal(tanggal) {
            const d = new Date(tanggal + 'T00:00:00');
            return d.toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
        }

        function bukaHari(tanggal) {
            hariAktif = tanggal;
            const data = jadwal[tanggal] || {};
            let html = '';

            SHIFTS.forEach((shift) => {
                const items = data[shift] || [];
                if (!items.length) return;

                html += `
                    <div class="mb-4">
                        <div class="flex items-center justify-between mb-2">
                            <span class="px-2 py-0.5 rounded border text-xs font-semibold ${BADGE[shift]}">${LABEL[shift]}</span>
                            <span class="text-xs text-gray-500">${items.length} sopir</span>
                        </div>
                        <ul class="divide-y divide-gray-100 border border-gray-200 rounded-lg">`;

                items.forEach((j) => {
                    const pilihan = sopir
                        .filter((s) => s.id === j.user_id || !items.some((i) => i.user_id === s.id))
                        .map((s) => `<option value="${s.id}" ${s.id === j.user_id ? 'selected' : ''}>${esc(s.nama)}</option>`)
                        .join('');

                    html += `
                            <li class="p-2">
                                <div class="flex items-center justify-between gap-2 text-sm">
                                    <span class="text-gray-800">${esc(j.nama)}</span>
                                    <span class="flex gap-1">
                                        <button type="button" class="btn-ganti px-2 py-1 text-xs bg-yellow-400 hover:bg-yellow-500 text-white rounded font-semibold" data-id="${j.id}">Ganti</button>
                                        <button type="button" class="btn-hapus px-2 py-1 text-xs bg-red-600 hover:bg-red-700 text-white rounded font-semibold" data-id="${j.id}" data-nama="${esc(j.nama)}">Hapus</button>
                                    </span>
                                </div>
                                <form id="ganti-${j.id}" method="POST" action="${URL_UPDATE.replace('__ID__', j.id)}" class="hidden mt-2 space-y-2">
                                    <input type="hidden" name="_token" value="${CSRF}">
                                    <input type="hidden" name="_method" value="PUT">
                                    <select name="user_id" required class="w-full border border-gray-300 rounded-lg shadow-sm text-sm px-3 py-2 focus:ring focus:ring-blue-300">${pilihan}</select>
                                    <textarea name="alasan_pergantian" rows="2" maxlength="255" placeholder="Alasan pergantian (opsional)" class="w-full border border-gray-300 rounded-lg shadow-sm text-sm px-3 py-2 focus:ring focus:ring-blue-300"></textarea>
                                    <div class="flex justify-end gap-2">
                                        <button type="button" class="btn-batal-ganti px-3 py-1.5 bg-gray-600 hover:bg-gray-700 text-white text-xs rounded-lg font-semibold" data-id="${j.id}">Batal</button>
                                        <button type="submit" class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs rounded-lg font-semibold">Perbarui</button>
                                    </div>
                                </form>
                            </li>`;
                });

                html += `
                        </ul>
                    </div>`;
            });

            if (!html) {
                html = '<p class="text-sm text-gray-500 italic">Belum ada sopir yang dijadwalkan pada hari ini.</p>';
            }

            document.getElementById('hariJudul').textContent = formatTanggal(tanggal);
            document.getElementById('hariIsi').innerHTML = html;
            bukaModal('hariModal');
        }

        document.addEventListener('click', (e) => {
            const buka = e.target.closest('[data-buka-modal]');
            if (buka) {
                bukaModal(buka.dataset.bukaModal);
                return;
            }

            const tutup = e.target.closest('[data-tutup-modal]');
            if (tutup) {
                tutupModal(tutup.dataset.tutupModal);
                return;
            }

            const ganti = e.target.closest('.btn-ganti');
            if (ganti) {
                document.getElementById('ganti-' + ganti.dataset.id).classList.toggle('hidden');
                return;
            }

            const batal = e.target.closest('.btn-batal-ganti');
            if (batal) {
                document.getElementById('ganti-' + batal.dataset.id).classList.add('hidden');
                return;
            }

            const hapus = e.target.closest('.btn-hapus');
            if (hapus) {
                Swal.fire({
                    title: 'Konfirmasi Hapus',
                    text: `Apakah Anda yakin ingin menghapus jadwal sopir "${hapus.dataset.nama}"?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#e3342f',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((hasil) => {
                    if (hasil.isConfirmed) {
                        const form = document.getElementById('formHapus');
                        form.action = URL_HAPUS.replace('__ID__', hapus.dataset.id);
                        form.submit();
                    }
                });
                return;
            }

            const hari = e.target.closest('[data-tanggal]');
            if (hari) {
                bukaHari(hari.dataset.tanggal);
            }
        });

        document.querySelectorAll('.modal-sopir').forEach((modal) => {
            modal.addEventListener('click', (e) => {
                if (e.target === modal) tutupModal(modal.id);
            });
        });

        document.getElementById('btnTambahHari').addEventListener('click', () => {
            if (hariAktif) {
                document.getElementById('tambah_tanggal').value = hariAktif;
            }
            tutupModal('hariModal');
            bukaModal('tambahModal');
        });

        const pilihSemua = document.getElementById('pilihSemua');
        if (pilihSemua) {
            pilihSemua.addEventListener('change', () => {
                document.querySelectorAll('.cb-sopir').forEach((cb) => {
                    cb.checked = pilihSemua.checked;
                });
            });
        }

        const formGenerate = document.getElementById('formGenerate');
        if (formGenerate) {
            formGenerate.addEventListener('submit', (e) => {
                if (formGenerate.dataset.ada !== '1') return;
                e.preventDefault();
                Swal.fire({
                    title: 'Generate Ulang Jadwal?',
                    text: 'Jadwal yang sudah ada pada rentang tanggal tersebut akan diganti, termasuk perubahan manual.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#eab308',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, generate ulang',
                    cancelButtonText: 'Batal'
                }).then((hasil) => {
                    if (hasil.isConfirmed) {
                        formGenerate.submit();
                    }
                });
            });
        }
    });
</script>