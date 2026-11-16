<x-guest-layout :title="'Login'">

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
        .lg-link {
            color: #035CA8;
            transition: color 0.2s ease;
        }
        .lg-link:hover {
            color: #1B4A82;
            text-decoration: underline;
        }
        .lg-check {
            width: 1rem;
            height: 1rem;
            accent-color: #035CA8;
            border-radius: 0.25rem;
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
                    Masuk ke Akun Anda
                </h3>
                <p class="mt-1 text-xs" style="color: #64748B;">Silakan masukkan email dan password Anda.</p>
            </div>

            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="email" class="block mb-2 text-sm font-medium" style="color: #1B4A82;">
                        Email
                    </label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}"
                        class="lg-input"
                        placeholder="Masukkan email" required>
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div>
                    <label for="password" class="block mb-2 text-sm font-medium" style="color: #1B4A82;">
                        Password
                    </label>
                    <div class="relative">
                        <input type="password" name="password" id="password"
                            class="lg-input" style="padding-right: 3rem;"
                            placeholder="Masukkan password" required>
                        <div class="absolute inset-y-0 right-2.5 flex items-center">
                            <button type="button" onclick="togglePasswordVisibility()" tabindex="-1">
                                <svg id="eyeIcon" class="w-5 h-5" style="color: #64748B;"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.522 5 12 5s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7s-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center text-sm" style="color: #475569;">
                        <input id="remember" type="checkbox" name="remember" class="lg-check">
                        <span class="ml-2">Ingat saya</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a class="lg-link text-sm font-medium"
                            href="{{ route('password.request') }}">
                            Lupa Password?
                        </a>
                    @endif
                </div>

                <button type="submit"
                    class="lg-btn w-full font-semibold rounded-xl text-sm px-5 py-3">
                    Masuk
                </button>
            </form>
        </div>
    </div>
</x-guest-layout>
<script>
    function togglePasswordVisibility() {
        const passwordInput = document.getElementById("password");
        const eyeIcon = document.getElementById("eyeIcon");

        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            eyeIcon.style.color = "#035CA8";
        } else {
            passwordInput.type = "password";
            eyeIcon.style.color = "#64748B";
        }
    }
</script>