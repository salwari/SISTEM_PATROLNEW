<x-app-layout :title="'Data Petugas Security'">

    <style>
        .dt-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 1rem;
            box-shadow: 0 2px 8px rgba(15, 23, 42, 0.06);
        }
        .dt-filter {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 0.75rem;
        }
        .dt-select {
            height: 2.5rem;
            min-width: 12rem;
            padding: 0 2.25rem 0 0.9rem;
            font-size: 0.875rem;
            color: #1B4A82;
            background-color: #F8FAFC;
            border: 1px solid #CBD5E1;
            border-radius: 0.75rem;
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='%23035CA8'%3E%3Cpath fill-rule='evenodd' d='M5.23 7.21a.75.75 0 011.06.02L10 11.17l3.71-3.94a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z' clip-rule='evenodd'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.7rem center;
            background-size: 1.1rem;
            transition: border-color 0.2s ease, box-shadow 0.2s ease, background-color 0.2s ease;
        }
        .dt-select:focus {
            outline: none;
            background-color: #ffffff;
            border-color: #0780C9;
            box-shadow: 0 0 0 4px rgba(7, 128, 201, 0.15);
        }
        .dt-search {
            display: flex;
            align-items: stretch;
            flex: 1 1 18rem;
            max-width: 28rem;
            height: 2.5rem;
            background: #F8FAFC;
            border: 1px solid #CBD5E1;
            border-radius: 0.75rem;
            overflow: hidden;
            transition: border-color 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
        }
        .dt-search:focus-within {
            background: #ffffff;
            border-color: #0780C9;
            box-shadow: 0 0 0 4px rgba(7, 128, 201, 0.15);
        }
        .dt-search-icon {
            display: flex;
            align-items: center;
            padding-left: 0.85rem;
            color: #64748B;
        }
        .dt-search-input {
            flex: 1 1 auto;
            min-width: 0;
            padding: 0 0.75rem;
            font-size: 0.875rem;
            color: #1B4A82;
            background: transparent;
            border: 0;
            outline: none;
            box-shadow: none;
        }
        .dt-search-input:focus {
            outline: none;
            box-shadow: none;
            border: 0;
        }
        .dt-search-input::placeholder {
            color: #94A3B8;
        }
        .dt-btn {
            height: 2.5rem;
            padding: 0 1.25rem;
            font-size: 0.875rem;
            font-weight: 600;
            color: #ffffff;
            background: linear-gradient(135deg, #035CA8 0%, #0780C9 100%);
            border: 0;
            border-radius: 0.75rem;
            box-shadow: 0 4px 10px rgba(3, 92, 168, 0.25);
            transition: transform 0.2s ease, box-shadow 0.2s ease, filter 0.2s ease;
        }
        .dt-btn:hover {
            transform: translateY(-2px);
            filter: brightness(1.08);
            box-shadow: 0 10px 20px rgba(3, 92, 168, 0.35);
        }
        .dt-search .dt-btn {
            height: auto;
            border-radius: 0;
            box-shadow: none;
            transform: none;
        }
        .dt-search .dt-btn:hover {
            transform: none;
            box-shadow: none;
        }
        .dt-btn-reset {
            display: inline-flex;
            align-items: center;
            height: 2.5rem;
            padding: 0 1.25rem;
            font-size: 0.875rem;
            font-weight: 600;
            color: #035CA8;
            background: #ffffff;
            border: 1px solid #0780C9;
            border-radius: 0.75rem;
            transition: background 0.2s ease, color 0.2s ease;
        }
        .dt-btn-reset:hover {
            background: #E6F3FB;
            color: #1B4A82;
        }
        .dt-row {
            transition: background 0.2s ease;
        }
        .dt-row:hover {
            background: #F1F7FC;
        }
    </style>

    <div class="container mx-auto p-4">
        <h2 class="text-2xl font-bold mb-5" style="color: #1B4A82;">Daftar Petugas Security</h2>

        <div class="dt-card px-4 py-4 mb-4" style="border-top: 4px solid #0780C9;">
            <form action="{{ route('super-admin.data-petugas') }}" method="GET" class="dt-filter">

                <select name="cabang_id" class="dt-select">
                    <option value="" {{ request('cabang_id') == '' ? 'selected' : '' }}>Semua Cabang</option>
                    @foreach ($cabangs->unique('nama_cabang') as $cabang)
                        <option value="{{ $cabang->id }}"
                            {{ request('cabang_id') == $cabang->id ? 'selected' : '' }}>
                            {{ $cabang->nama_cabang }}
                        </option>
                    @endforeach
                </select>

                <div class="dt-search">
                    <span class="dt-search-icon">
                        <svg style="width: 1.1rem; height: 1.1rem;" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                clip-rule="evenodd" />
                        </svg>
                    </span>
                    <input type="text" id="search" name="search" value="{{ request('search') }}"
                        placeholder="Cari data petugas..." autocomplete="off"
                        class="dt-search-input" />
                    <button type="submit" class="dt-btn">Cari</button>
                </div>

                @if (request('search') || request('cabang_id'))
                    <a href="{{ route('super-admin.data-petugas') }}" class="dt-btn-reset">Kembali</a>
                @endif
            </form>
        </div>

        <div class="dt-card overflow-hidden mb-4">
            <div class="overflow-x-auto">
                <table class="table-auto w-full text-sm" style="color: #334155;">
                    <thead class="uppercase text-xs" style="background: #E6F3FB; color: #1B4A82;">
                        <tr class="text-center">
                            <th class="px-4 py-3">No</th>
                            <th class="px-4 py-3">Nama</th>
                            <th class="px-4 py-3">Cabang</th>
                            <th class="px-4 py-3">Foto</th>
                            <th class="px-4 py-3">Email</th>
                            <th class="px-4 py-3">Nomor Induk</th>
                            <th class="px-4 py-3">No Telepon</th>
                            <th class="px-4 py-3">Alamat</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y" style="background: #ffffff; border-color: #e2e8f0;">
                        @forelse ($users as $user)
                            <tr class="dt-row text-center">
                                <td class="px-4 py-3">
                                    {{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}
                                </td>
                                <td class="px-4 py-3 font-semibold" style="color: #1B4A82;">{{ $user->nama }}</td>
                                <td class="px-4 py-3">{{ $user->cabang->nama_cabang ?? '-' }}</td>
                                <td class="px-4 py-3">
                                    @if ($user->foto && file_exists(public_path('storage/' . $user->foto)))
                                        <img src="{{ asset('storage/' . $user->foto) }}" alt="Foto"
                                            class="object-cover rounded-full mx-auto"
                                            style="width: 4rem; height: 4rem; border: 2px solid #B9D8EE; box-shadow: 0 2px 6px rgba(3, 92, 168, 0.2);">
                                    @else
                                        <div class="flex items-center justify-center rounded-full mx-auto"
                                            style="width: 4rem; height: 4rem; background: #E6F3FB; border: 2px solid #B9D8EE;">
                                            <svg style="width: 2rem; height: 2rem; color: #0780C9;" fill="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    d="M12 12c2.7 0 5-2.3 5-5s-2.3-5-5-5-5 2.3-5 5 2.3 5 5 5zm0 2c-3.3 0-10 1.7-10 5v3h20v-3c0-3.3-6.7-5-10-5z" />
                                            </svg>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-4 py-3">{{ $user->email }}</td>
                                <td class="px-4 py-3">{{ $user->nomor_induk ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $user->no_telepon ?? '-' }}</td>
                                <td class="px-4 py-3 text-left">{{ $user->alamat ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 py-6 text-center" style="color: #64748B;">
                                    Belum ada data petugas security pada cabang ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="flex justify-between items-center">
            <div class="text-sm" style="color: #64748B;">
                Menampilkan {{ $users->firstItem() ?? 0 }} sampai {{ $users->lastItem() ?? 0 }} dari
                {{ $users->total() }} data
            </div>
            <div>
                {{ $users->links('components.pagination') }}
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    document.querySelector('select[name="cabang_id"]').addEventListener('change', function() {
        this.form.submit();
    });
</script>