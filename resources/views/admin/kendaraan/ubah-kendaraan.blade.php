<x-app-layout :title="'Edit Kendaraan'">
    <div class="container mx-auto p-4">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">Edit Kendaraan</h2>

        <div class="max-w-full md:max-w-3xl bg-white p-6 rounded-lg shadow-md border border-gray-200">
            <form action="{{ route('kendaraan.update', $kendaraan->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label for="plat_nomor" class="block text-sm font-medium text-gray-700">
                        Plat Nomor <span class="text-red-600">*</span>
                    </label>
                    <input type="text" id="plat_nomor" name="plat_nomor"
                        value="{{ old('plat_nomor', $kendaraan->plat_nomor) }}" required maxlength="15"
                        class="mt-1 block w-full rounded-lg border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm uppercase">
                    @error('plat_nomor')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="jenis" class="block text-sm font-medium text-gray-700">
                        Jenis Kendaraan <span class="text-red-600">*</span>
                    </label>
                    <input type="text" id="jenis" name="jenis" value="{{ old('jenis', $kendaraan->jenis) }}" required
                        maxlength="40"
                        class="mt-1 block w-full rounded-lg border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                    @error('jenis')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Kode Barcode</label>
                    <input type="text" value="{{ $kendaraan->kode_barcode }}" readonly
                        class="mt-1 block w-full rounded-lg border border-gray-300 bg-gray-100 text-gray-600 shadow-sm text-sm">
                </div>

                <div class="flex items-center gap-2">
                    <input type="hidden" name="aktif" value="0">
                    <input type="checkbox" id="aktif" name="aktif" value="1"
                        @checked(old('aktif', $kendaraan->aktif ? '1' : '0') == '1')
                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <label for="aktif" class="text-sm font-medium text-gray-700">Kendaraan aktif</label>
                </div>

                <div class="flex justify-end gap-4 pt-2">
                    <a href="{{ route('kendaraan.index') }}"
                        class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm rounded-lg font-semibold">Kembali</a>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg font-semibold">Perbarui</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>