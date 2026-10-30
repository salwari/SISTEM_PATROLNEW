<x-app-layout :title="'Tambah Lokasi Kebersihan'">
    <div class="container mx-auto p-4">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">Tambah Lokasi Kebersihan</h2>

        <div class="max-w-full md:max-w-3xl bg-white p-6 rounded-lg shadow-md border border-gray-200">
            <form action="{{ route('lokasi-kebersihan.store') }}" method="POST" class="space-y-6">
                @csrf

                
                <div>
                    <label for="kode_kamar_mandi" class="block text-sm font-medium text-gray-700">
                        Kode Lokasi <span class="text-red-600">*</span>
                    </label>
                    <input type="text" id="kode_kamar_mandi" name="kode_kamar_mandi" 
                        value="{{ old('kode_kamar_mandi') }}" required placeholder="Contoh: KM-01"
                        class="mt-1 block w-full rounded-lg border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm p-2.5">
                    @error('kode_kamar_mandi')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                
                <div>
                    <label for="nama_kamar_mandi" class="block text-sm font-medium text-gray-700">
                        Nama Lokasi Kebersihan <span class="text-red-600">*</span>
                    </label>
                    <input type="text" id="nama_kamar_mandi" name="nama_kamar_mandi" 
                        value="{{ old('nama_kamar_mandi') }}" required placeholder="Contoh: Toilet Lantai 1 Pria"
                        class="mt-1 block w-full rounded-lg border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm p-2.5">
                    @error('nama_kamar_mandi')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                
                <div>
                    <label for="lantai" class="block text-sm font-medium text-gray-700">
                        Lantai <span class="text-red-600">*</span>
                    </label>
                    <input type="text" id="lantai" name="lantai" 
                        value="{{ old('lantai') }}" required placeholder="Contoh: Lantai 1"
                        class="mt-1 block w-full rounded-lg border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm p-2.5">
                    @error('lantai')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                
                <div>
                    <label for="deskripsi" class="block text-sm font-medium text-gray-700">
                        Deskripsi (Opsional)
                    </label>
                    <textarea id="deskripsi" name="deskripsi" rows="3" placeholder="Keterangan tambahan lokasi..."
                        class="mt-1 block w-full rounded-lg border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm p-2.5">{{ old('deskripsi') }}</textarea>
                    @error('deskripsi')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                
                <div class="flex justify-end gap-4 pt-2">
                    <a href="{{ route('lokasi-kebersihan.index') }}"
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