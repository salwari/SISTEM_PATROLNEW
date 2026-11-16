<x-guest-layout>

    <style>
        .lg-wrap {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 1.25rem;
            box-shadow: 0 20px 45px rgba(3, 92, 168, 0.18);
        }
        .lg-input {
            width: 100%;
            padding: 0.7rem 0.9rem;
            background: #F8FAFC;
            border: 1px solid #CBD5E1;
            border-radius: 0.75rem;
            color: #1B4A82;
            font-size: 0.875rem;
            transition: border-color 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
        }
        .lg-input::placeholder {
            color: #94A3B8;
        }
        .lg-input:focus {
            outline: none;
            background: #ffffff;
            border-color: #0780C9;
            box-shadow: 0 0 0 4px rgba(7, 128, 201, 0.15);
        }
        .lg-btn {
            background: linear-gradient(135deg, #035CA8 0%, #0780C9 100%);
            color: #ffffff;
            box-shadow: 0 6px 14px rgba(3, 92, 168, 0.3);
            transition: transform 0.2s ease, box-shadow 0.2s ease, filter 0.2s ease;
        }
        .lg-btn:hover {
            transform: translateY(-2px);
            filter: brightness(1.08);
            box-shadow: 0 12px 22px rgba(3, 92, 168, 0.4);
        }
        .lg-btn:focus {
            outline: none;
            box-shadow: 0 0 0 4px rgba(7, 128, 201, 0.3);
        }
    </style>

    <div class="w-full max-w-sm mx-auto mt-10 overflow-hidden lg-wrap">

        <div style="height: 0.5rem; background: linear-gradient(90deg, #1B4A82 0%, #035CA8 35%, #0780C9 70%, #05A2E4 100%);"></div>

        <div class="px-6 pt-8 pb-2 text-center">
            <img class="mx-auto h-auto" style="width: 9rem;" src="{{ asset('assets/sucofindo-nobg.png') }}" alt="logo">
            <h1 class="mt-3 text-xl font-bold tracking-wide" style="color: #1B4A82;">SIM PATROLI</h1>
        </div>

        <div class="p-6 sm:p-8 space-y-5">

            <div class="text-center">
                <h3 class="text-lg font-semibold md:text-xl" style="color: #1B4A82;">
                    Lupa Password?
                </h3>
                <p class="mt-1 text-sm" style="color: #64748B;">
                    Masukkan email Anda dan kami akan mengirimkan link untuk mengatur ulang password Anda.
                </p>
            </div>

            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="email" class="block mb-2 text-sm font-medium" style="color: #1B4A82;">
                        Email
                    </label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}"
                        class="lg-input"
                        placeholder="Masukkan email" required autofocus>
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <button type="submit"
                    class="lg-btn w-full font-semibold rounded-xl text-sm px-5 py-3">
                    Kirim Link
                </button>
            </form>
        </div>
    </div>
</x-guest-layout>