<x-app-layout :title="'Tambah Butir Checklist'">
    <div class="container mx-auto p-4">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">Tambah Butir Checklist</h2>

        <div class="max-w-full md:max-w-3xl bg-white p-6 rounded-lg shadow-md border border-gray-200">
            <form action="{{ route('checklist-kendaraan.store') }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label for="kategori" class="block text-sm font-medium text-gray-700">
                        Kategori <span class="text-red-600">*</span>
                    </label>
                    <select id="kategori" name="kategori" required
                        class="mt-1 block w-full rounded-lg border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                        <option value="">Pilih kategori</option>
                        @foreach ($daftarKategori as $kode => $nama)
                            <option value="{{ $kode }}" @selected(old('kategori', $kategoriDipilih) === $kode)>{{ $nama }}</option>
                        @endforeach
                    </select>
                    @error('kategori')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="nama_butir" class="block text-sm font-medium text-gray-700">
                        Nama Butir <span class="text-red-600">*</span>
                    </label>
                    <input type="text" id="nama_butir" name="nama_butir" value="{{ old('nama_butir') }}" required
                        maxlength="100"
                        class="mt-1 block w-full rounded-lg border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                    @error('nama_butir')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="urutan" class="block text-sm font-medium text-gray-700">
                        Urutan (Opsional)
                    </label>
                    <input type="number" id="urutan" name="urutan" value="{{ old('urutan') }}" min="0"
                        placeholder="Kosongkan untuk ditaruh di urutan terakhir"
                        class="mt-1 block w-full rounded-lg border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                    @error('urutan')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center gap-2">
                    <input type="hidden" name="aktif" value="0">
                    <input type="checkbox" id="aktif" name="aktif" value="1" @checked(old('aktif', '1') == '1')
                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <label for="aktif" class="text-sm font-medium text-gray-700">Butir aktif</label>
                </div>

                <div class="flex justify-end gap-4 pt-2">
                    <a href="{{ route('checklist-kendaraan.index') }}"
                        class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm rounded-lg font-semibold">
                        Kembali
                    </a>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg font-semibold">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>