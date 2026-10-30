<x-app-layout :title="'Edit Data Cabang'">
    <div class="container mx-auto p-4">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">Edit Data Cabang</h2>

        <div class="max-w-full md:max-w-4xl bg-white p-6 rounded-lg shadow-md border border-gray-200">
            <form action="{{ route('data-cabang.update', $cabang->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <div>
                        <label for="nama_cabang" class="block text-sm font-medium text-gray-700">
                            Nama Cabang <span class="text-red-600">*</span>
                        </label>
                        <input type="text" id="nama_cabang" name="nama_cabang"
                            value="{{ old('nama_cabang', $cabang->nama_cabang) }}" required
                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                        @error('nama_cabang')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    
                    <div>
                        <label for="alamat_cabang" class="block text-sm font-medium text-gray-700">
                            Alamat Cabang <span class="text-red-600">*</span>
                        </label>
                        <input type="text" id="alamat_cabang" name="alamat_cabang"
                            value="{{ old('alamat', $cabang->alamat_cabang) }}" required
                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                        @error('alamat')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">
                            Email <span class="text-red-600">*</span>
                        </label>
                        <input type="email" id="email" name="email"
                            value="{{ old('email', $cabang->admin->email ?? '') }}" required
                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                        @error('email')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    
                    <div>
                        <label for="nomor_induk" class="block text-sm font-medium text-gray-700">
                            Nomor Induk Admin <span class="text-red-600">*</span>
                        </label>
                        <input type="text" id="nomor_induk" name="nomor_induk"
                            value="{{ old('nomor_induk', $cabang->admin->nomor_induk ?? '') }}" required
                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                        @error('nomor_induk')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    
                    <div>
                        <label for="no_telepon" class="block text-sm font-medium text-gray-700">
                            No Telepon Admin <span class="text-red-600">*</span>
                        </label>
                        <input type="text" id="no_telepon" name="no_telepon"
                            value="{{ old('no_telepon', $cabang->admin->no_telepon ?? '') }}" required
                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                        @error('no_telepon')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex justify-end gap-4 pt-2">
                    <a href="{{ route('data-cabang.index') }}"
                        class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm rounded-lg font-semibold">Kembali</a>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg font-semibold">Perbarui</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
