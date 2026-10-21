<x-app-layout :title="'Edit Lokasi Patroli'">
    <div class="container mx-auto p-4">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">Edit Lokasi Patroli</h2>

        <div class="max-w-full md:max-w-3xl bg-white p-6 rounded-lg shadow-md border border-gray-200">
            <form action="{{ route('lokasi-patroli.update', $lokasiPatroli->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                {{-- Nama Lokasi --}}
                <div>
                    <label for="nama_lokasi" class="block text-sm font-medium text-gray-700">
                        Nama Lokasi <span class="text-red-600">*</span>
                    </label>
                    <input type="text" id="nama_lokasi" name="nama_lokasi"
                        value="{{ old('nama_lokasi', $lokasiPatroli->nama_lokasi) }}" required
                        class="mt-1 block w-full rounded-lg border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                    @error('nama_lokasi')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Deskripsi Lokasi (opsional) --}}
                <div>
                    <label for="deskripsi" class="block text-sm font-medium text-gray-700">
                        Deskripsi
                    </label>
                    <textarea id="deskripsi" name="deskripsi" rows="3"
                        class="mt-1 block w-full rounded-lg border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">{{ old('deskripsi', $lokasiPatroli->deskripsi) }}</textarea>
                    @error('deskripsi')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tombol --}}
                <div class="flex justify-end gap-4 pt-2">
                    <a href="{{ route('lokasi-patroli.index') }}"
                        class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm rounded-lg font-semibold">Kembali</a>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg font-semibold">Perbarui</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
