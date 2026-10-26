<x-app-layout :title="'Butir Checklist Kendaraan'">
    <div class="container mx-auto p-4">
        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Butir Checklist Kendaraan</h2>
            <a href="{{ route('checklist-kendaraan.create') }}"
                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm text-center font-semibold whitespace-nowrap">
                Tambah Butir
            </a>
        </div>

        @foreach ($daftarKategori as $kode => $namaKategori)
            <div class="mb-6">
                <div class="flex justify-between items-center mb-2">
                    <h3 class="text-lg font-semibold text-gray-800">{{ $namaKategori }}</h3>
                    <a href="{{ route('checklist-kendaraan.create', ['kategori' => $kode]) }}"
                        class="px-3 py-1 text-sm text-white bg-blue-600 hover:bg-blue-700 rounded-lg whitespace-nowrap">
                        Tambah
                    </a>
                </div>

                <div class="overflow-x-auto border border-gray-200 rounded-lg shadow-lg">
                    <table class="table-auto w-full text-sm text-gray-700">
                        <thead class="bg-blue-200 text-gray-800 uppercase text-xs">
                            <tr class="text-center">
                                <th class="px-4 py-3">Urutan</th>
                                <th class="px-4 py-3 text-left">Nama Butir</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($butir->get($kode, collect()) as $data)
                                <tr class="hover:bg-gray-100">
                                    <td class="px-4 py-2 text-center">{{ $data->urutan }}</td>
                                    <td class="px-4 py-2">{{ $data->nama_butir }}</td>
                                    <td class="px-4 py-2 text-center">
                                        @if ($data->aktif)
                                            <span class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">Aktif</span>
                                        @else
                                            <span class="px-2 py-1 text-xs font-semibold text-gray-700 bg-gray-200 rounded-full">Nonaktif</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2 text-center">
                                        <div class="flex justify-center overflow-x-auto no-scrollbar gap-1">
                                            <a href="{{ route('checklist-kendaraan.edit', $data->id) }}"
                                                class="inline-flex items-center px-2 py-1 text-sm text-white bg-yellow-400 hover:bg-yellow-500 rounded whitespace-nowrap">
                                                <svg class="w-5 h-5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path
                                                        d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                                    <path fill-rule="evenodd"
                                                        d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                Edit
                                            </a>

                                            <form action="{{ route('checklist-kendaraan.destroy', $data->id) }}"
                                                method="POST" class="inline form-hapus">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button"
                                                    class="btn-hapus inline-flex items-center px-2 py-1 text-sm text-white bg-red-600 hover:bg-red-700 rounded whitespace-nowrap"
                                                    data-nama="{{ $data->nama_butir }}">
                                                    <svg class="w-5 h-5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0
                                                        002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0
                                                        012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-4 text-center text-gray-700">
                                        Belum ada butir pada kategori ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach
    </div>
</x-app-layout>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.btn-hapus').forEach(button => {
            button.addEventListener('click', function() {
                const form = this.closest('form');
                const nama = this.getAttribute('data-nama');

                Swal.fire({
                    title: 'Konfirmasi Hapus',
                    text: `Hapus butir "${nama}"? Riwayat cek yang sudah tersimpan tidak ikut hilang.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Hapus',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    });
</script>