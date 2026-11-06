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
                    Reset Password
                </h3>
                <p class="mt-1 text-xs" style="color: #64748B;">Silakan buat password baru untuk akun Anda.</p>
            </div>

            <form method="POST" action="{{ route('password.store') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <div>
                    <label for="email" class="block mb-2 text-sm font-medium" style="color: #1B4A82;">
                        Email
                    </label>
                    <input type="email" name="email" id="email" value="{{ old('email', $request->email) }}"
                        class="lg-input"
                        placeholder="Masukkan email" required>
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div>
                    <label for="password" class="block mb-2 text-sm font-medium" style="color: #1B4A82;">
                        Password Baru
                    </label>
                    <div class="relative">
                        <input type="password" name="password" id="password"
                            class="lg-input" style="padding-right: 3rem;"
                            placeholder="Masukkan password baru" required>
                        <div class="absolute inset-y-0 right-2.5 flex items-center">
                            <button type="button" onclick="togglePasswordVisibility('password', 'eyePassword')" tabindex="-1">
                                <svg id="eyePassword" class="w-5 h-5" style="color: #64748B;"
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

                <div>
                    <label for="password_confirmation" class="block mb-2 text-sm font-medium" style="color: #1B4A82;">
                        Konfirmasi Password
                    </label>
                    <div class="relative">
                        <input type="password" name="password_confirmation" id="password_confirmation"
                            class="lg-input" style="padding-right: 3rem;"
                            placeholder="Konfirmasi password baru" required>
                        <div class="absolute inset-y-0 right-2.5 flex items-center">
                            <button type="button" onclick="togglePasswordVisibility('password_confirmation', 'eyeConfirm')" tabindex="-1">
                                <svg id="eyeConfirm" class="w-5 h-5" style="color: #64748B;"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.522 5 12 5s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7s-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <button type="submit"
                    class="lg-btn w-full font-semibold rounded-xl text-sm px-5 py-3">
                    Reset Password
                </button>
            </form>
        </div>
    </div>

    <script>
        function togglePasswordVisibility(fieldId, eyeId) {
            const input = document.getElementById(fieldId);
            const icon = document.getElementById(eyeId);

            if (input.type === "password") {
                input.type = "text";
                icon.style.color = "#035CA8";
            } else {
                input.type = "password";
                icon.style.color = "#64748B";
            }
        }
    </script>
</x-guest-layout>