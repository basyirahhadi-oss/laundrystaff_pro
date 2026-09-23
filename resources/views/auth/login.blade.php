<x-guest-layout>
    <div class="w-full max-w-md">
        <div class="bg-white rounded-3xl border border-slate-200/90 shadow-xl shadow-slate-200/50 p-8 sm:p-10 space-y-6">
            
            <!-- Header: Logo & Titles -->
            <div class="text-center space-y-2">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-slate-950 p-2 shadow-md border border-slate-200 mb-1">
                    <img src="{{ asset('images/zaujati-logo.png') }}" alt="Zaujati Laundry" class="w-full h-full object-contain">
                </div>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight">Sign In</h1>
                <p class="text-xs text-slate-500 font-medium">Enter your credentials to access the portal</p>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-2" :status="session('status')" />

            <!-- Login Form -->
            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                <!-- Email Address -->
                <div>
                    <label for="email" class="block text-xs font-bold text-slate-700 mb-1.5">Email Address</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-xs">
                            <i class="fa-regular fa-envelope"></i>
                        </span>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                               placeholder="name@example.com"
                               class="w-full bg-slate-50/80 border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-xs font-medium text-slate-900 focus:bg-white focus:ring-2 focus:ring-[#4A154B]/20 focus:border-[#4A154B] transition" />
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-1" />
                </div>

                <!-- Password -->
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="password" class="block text-xs font-bold text-slate-700">Password</label>
                        @if (Route::has('password.request'))
                            <a class="text-[11px] font-semibold text-[#0284c7] hover:underline" href="{{ route('password.request') }}">
                                Forgot password?
                            </a>
                        @endif
                    </div>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-xs">
                            <i class="fa-solid fa-lock"></i>
                        </span>
                        <input id="password" type="password" name="password" required autocomplete="current-password"
                               placeholder="••••••••"
                               class="w-full bg-slate-50/80 border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-xs font-medium text-slate-900 focus:bg-white focus:ring-2 focus:ring-[#4A154B]/20 focus:border-[#4A154B] transition" />
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-1" />
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between pt-1">
                    <label for="remember_me" class="inline-flex items-center gap-2 cursor-pointer">
                        <input id="remember_me" type="checkbox" class="rounded border-slate-300 text-[#4A154B] focus:ring-[#4A154B]" name="remember">
                        <span class="text-xs text-slate-600 font-medium">Remember me</span>
                    </label>
                </div>

                <!-- Submit Button -->
                <div class="pt-2">
                    <button type="submit"
                            class="w-full inline-flex items-center justify-center gap-2 py-2.5 px-4 rounded-xl text-xs font-bold text-white shadow-md transition hover:opacity-95 hover:-translate-y-0.5 active:translate-y-0"
                            style="background-color: #4A154B; color: #ffffff;">
                        <span>Sign In</span>
                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </button>
                </div>
            </form>

            <!-- Divider -->
            <div class="relative my-4">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-slate-200"></div>
                </div>
                <div class="relative flex justify-center text-[10px] uppercase font-bold text-slate-400">
                    <span class="bg-white px-3">or</span>
                </div>
            </div>

            <!-- Kiosk Quick Access -->
            <div class="text-center">
                <a href="{{ route('kiosk.gateway') }}" target="_blank"
                   class="w-full inline-flex items-center justify-center gap-2 py-2.5 px-4 rounded-xl text-xs font-bold bg-slate-50 hover:bg-slate-100 text-slate-700 border border-slate-200 transition">
                    <i class="fa-solid fa-camera text-sky-600"></i>
                    <span>Open Face Attendance Kiosk</span>
                </a>
            </div>

        </div>
    </div>
</x-guest-layout>
