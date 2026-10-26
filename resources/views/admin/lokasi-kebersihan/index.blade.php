<x-app-layout :title="'Data Lokasi Kebersihan'">
    <div class="container mx-auto p-4">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">Daftar Lokasi Kebersihan</h2>

        <div class="overflow-x-auto border border-gray-200 rounded-lg shadow-md mb-4">
            <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 px-4 py-3 bg-white">
                {{-- Search --}}
                <form id="search-form" action="{{ route('lokasi-kebersihan.index') }}" method="GET"
                    class="flex flex-row items-center gap-2 w-full md:w-1/2">
                    <div class="relative flex flex-grow">
                        <input type="text" id="search" name="search" value="{{ request('search') }}"
                            placeholder="Cari kode, nama, lantai..." autocomplete="off"
                            class="block w-full p-2 pl-4 text-sm text-gray-900 bg-white border border-gray-300 rounded-l-lg focus:ring-blue-500 focus:border-blue-500" />
                        <button type="submit"
                            class="px-4 py-2 text-sm text-white bg-blue-600 hover:bg-blue-700 rounded-r-lg">
                            Cari
                        </button>
                    </div>

                    @if (request('search'))
                        <a href="{{ route('lokasi-kebersihan.index') }}"
                            class="px-4 py-2 text-sm text-white bg-gray-400 hover:bg-gray-500 rounded-lg whitespace-nowrap">
                            Kembali
                        </a>
                    @endif
                </form>

                {{-- Tambah Lokasi --}}
                <a href="{{ route('lokasi-kebersihan.create') }}"
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm text-center font-semibold whitespace-nowrap">
                    Tambah Lokasi
                </a>
            </div>
        </div>

        {{-- Tabel Data Lokasi Kebersihan --}}
        <div class="overflow-x-auto border border-gray-200 rounded-lg shadow-lg mb-4">
            <table class="table-auto w-full text-sm text-gray-700">
                <thead class="bg-blue-200 text-gray-800 uppercase text-xs">
                    <tr class="text-center">
                        <th class="px-4 py-3">No</th>
                        <th class="px-4 py-3">Kode</th>
                        <th class="px-4 py-3">Nama Lokasi</th>
                        <th class="px-4 py-3">Lantai</th>
                        <th class="px-4 py-3">Deskripsi</th>
                        <th class="px-4 py-3">QR Code</th>
                        <th class="px-4 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($lokasiKebersihan as $data)
                        <tr class="hover:bg-gray-100">
                            <td class="px-4 py-2 text-center">
                                {{ $loop->iteration + ($lokasiKebersihan->currentPage() - 1) * $lokasiKebersihan->perPage() }}
                            </td>
                            <td class="px-4 py-2 text-center font-semibold">{{ $data->kode_kamar_mandi }}</td>
                            <td class="px-4 py-2">{{ $data->nama_kamar_mandi }}</td>
                            <td class="px-4 py-2 text-center">{{ $data->lantai }}</td>
                            <td class="px-4 py-2">{{ $data->deskripsi ?? '-' }}</td>
                            <td class="px-4 py-2 text-center">
                                @if ($data->qr_code)
                                    <img src="{{ asset('storage/' . $data->qr_code) }}" alt="QR Code"
                                        class="w-16 h-auto object-contain mx-auto rounded cursor-pointer"
                                        onclick="showQrModal('{{ asset('storage/' . $data->qr_code) }}', '{{ $data->nama_kamar_mandi }}')">
                                @else
                                    <span class="text-gray-400 italic">Tidak tersedia</span>
                                @endif
                            </td>
                            <td class="px-4 py-2 text-center">
                                <div class="flex gap-1 justify-center">
                                    @if ($data->qr_code)
                                        <a href="{{ route('lokasi-kebersihan.downloadQrCode', $data->id) }}"
                                            class="px-2 py-1 text-sm text-white bg-green-600 hover:bg-green-700 rounded">
                                            Unduh
                                        </a>
                                    @endif
                                    <a href="{{ route('lokasi-kebersihan.edit', $data->id) }}"
                                        class="px-2 py-1 text-sm text-white bg-yellow-400 hover:bg-yellow-500 rounded">
                                        Edit
                                    </a>
                                    <form action="{{ route('lokasi-kebersihan.destroy', $data->id) }}" method="POST" class="inline form-hapus">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button"
                                            class="btn-hapus px-2 py-1 text-sm text-white bg-red-600 hover:bg-red-700 rounded"
                                            data-nama="{{ $data->nama_kamar_mandi }}">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-4 text-center text-gray-700">
                                Belum ada data lokasi kebersihan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="flex justify-between items-center bg-white">
            <div class="text-sm text-gray-600">
                Menampilkan {{ $lokasiKebersihan->firstItem() ?? 0 }} sampai {{ $lokasiKebersihan->lastItem() ?? 0 }} dari
                {{ $lokasiKebersihan->total() }} data
            </div>
            <div>
                {{ $lokasiKebersihan->links('components.pagination') }}
            </div>
        </div>

        {{-- Modal Preview QR --}}
        <div id="qrModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-black bg-opacity-50">
            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="relative w-full max-w-sm bg-white rounded-lg shadow-xl p-6 text-center">
                    <button onclick="closeModal()" class="absolute top-2 right-2 text-gray-700 hover:text-gray-900 font-bold">✕</button>
                    <h3 id="qrModalNamaLokasi" class="text-lg font-semibold mb-4 text-gray-800"></h3>
                    <img id="qrModalImg" src="" alt="QR Preview" class="mx-auto max-w-[200px] rounded-lg shadow-md border">
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    function showQrModal(imageUrl, namaLokasi) {
        document.getElementById('qrModalImg').src = imageUrl;
        document.getElementById('qrModalNamaLokasi').textContent = namaLokasi;
        document.getElementById('qrModal').classList.remove('hidden');
    }
    function closeModal() {
        document.getElementById('qrModal').classList.add('hidden');
    }
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.btn-hapus').forEach(button => {
            button.addEventListener('click', function() {
                const form = this.closest('form');
                const nama = this.getAttribute('data-nama');
                Swal.fire({
                    title: 'Konfirmasi Hapus',
                    text: `Apakah Anda yakin ingin menghapus lokasi "${nama}"?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Hapus'
                }).then((res) => { if (res.isConfirmed) form.submit(); });
            });
        });
    });
</script>