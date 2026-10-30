<x-app-layout :title="'Tambah Data Cabang'">
    <div class="container mx-auto p-4">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">Tambah Data Cabang</h2>

        <div class="max-w-full md:max-w-4xl bg-white p-6 rounded-lg shadow-md border border-gray-200">
            <form action="{{ route('data-cabang.store') }}" method="POST" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <div>
                        <label for="nama_cabang" class="block text-sm font-medium text-gray-700">
                            Nama Cabang <span class="text-red-600">*</span>
                        </label>
                        <input type="text" id="nama_cabang" name="nama_cabang" value="{{ old('nama_cabang') }}"
                            required
                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                        @error('nama_cabang')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    
                    <div>
                        <label for="alamat" class="block text-sm font-medium text-gray-700">
                            Alamat Cabang <span class="text-red-600">*</span>
                        </label>
                        <input type="text" id="alamat" name="alamat" value="{{ old('alamat') }}" required
                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                        @error('alamat')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">
                            Email <span class="text-red-600">*</span>
                        </label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required
                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                        @error('email')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    
                    <div>
                        <label for="nomor_induk" class="block text-sm font-medium text-gray-700">
                            Nomor Induk Admin <span class="text-red-600">*</span>
                        </label>
                        <input type="text" id="nomor_induk" name="nomor_induk" value="{{ old('nomor_induk') }}"
                            required
                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                        @error('nomor_induk')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    
                    <div>
                        <label for="no_telepon" class="block text-sm font-medium text-gray-700">
                            No Telepon Admin <span class="text-red-600">*</span>
                        </label>
                        <input type="text" id="no_telepon" name="no_telepon" value="{{ old('no_telepon') }}" required
                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                        @error('no_telepon')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    
                    <div class="relative">
                        <label for="password" class="block text-sm font-medium text-gray-700">
                            Password Admin <span class="text-red-600">*</span>
                        </label>
                        <div class="relative">
                            <input type="password" id="password" name="password" required
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm pr-10">
                            <button type="button" onclick="togglePasswordVisibility()"
                                class="absolute inset-y-0 right-0 flex items-center pr-3">
                                <svg id="eyeIcon" class="w-5 h-5 text-gray-500" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Minimal berisi 8 karakter.</p>
                        @error('password')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex justify-end gap-4 pt-2">
                    <a href="{{ route('data-cabang.index') }}"
                        class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm rounded-lg font-semibold">Kembali</a>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg font-semibold">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
<script>
    function togglePasswordVisibility() {
        const passwordField = document.getElementById("password");
        const icon = document.getElementById("eyeIcon");
        if (passwordField.type === "password") {
            passwordField.type = "text";
            icon.classList.remove("text-gray-500");
            icon.classList.add("text-gray-900");
        } else {
            passwordField.type = "password";
            icon.classList.remove("text-gray-900");
            icon.classList.add("text-gray-500");
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const roleSelect = document.getElementById('role');
        const unitField = document.getElementById('unit-field');

        function toggleUnitField() {
            if (roleSelect.value === 'unit') {
                unitField.classList.remove('hidden');
            } else {
                unitField.classList.add('hidden');
            }
        }

        roleSelect.addEventListener('change', toggleUnitField);
        toggleUnitField(); // jalankan saat pertama dimuat
    });
</script>
