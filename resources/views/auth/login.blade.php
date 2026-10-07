<x-guest-layout>
    <div class="w-full max-w-md mx-auto relative">
        
        <!-- ============================================== -->
        <!-- MAIN CORPORATE LOGIN CARD                      -->
        <!-- ============================================== -->
        <div class="corporate-card rounded-2xl p-7 sm:p-9 relative">
            
            <!-- Header Section: Zaujati Brand & Formal Title -->
            <div class="text-center mb-6">
                <!-- Clean Brand Emblem -->
                <div class="w-16 h-16 mx-auto rounded-2xl bg-slate-900 border border-slate-700/80 p-2.5 shadow-md flex items-center justify-center mb-3.5">
                    <img src="{{ asset('images/zaujati-logo.png') }}" 
                         alt="Zaujati Laundry" 
                         class="w-full h-full object-contain">
                </div>

                <h1 class="text-2xl font-bold tracking-tight text-white">
                    Staff Portal Authentication
                </h1>
                <p class="text-xs text-slate-400 mt-1 font-normal">
                    Enter your corporate credentials to access the management portal.
                </p>
            </div>

            <!-- Portal Switcher Tabs (Formal Segmented Control) -->
            <div class="grid grid-cols-2 gap-1 p-1 rounded-xl bg-slate-950/80 border border-slate-800/80 mb-5">
                <button type="button" class="py-2 px-3 rounded-lg text-xs font-semibold bg-slate-800 text-white shadow-sm border border-slate-700/60 flex items-center justify-center gap-2 transition">
                    <i class="fa-solid fa-shield text-slate-300 text-xs"></i>
                    <span>Management Portal</span>
                </button>
                <a href="{{ route('kiosk.gateway') }}" class="py-2 px-3 rounded-lg text-xs font-medium text-slate-400 hover:text-white hover:bg-slate-900/60 flex items-center justify-center gap-2 transition">
                    <i class="fa-solid fa-camera text-slate-400 text-xs"></i>
                    <span>Face Kiosk Gateway</span>
                </a>
            </div>

            <!-- Session Status / Flash Alert -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <!-- Login Form -->
            <form method="POST" action="{{ route('login') }}" class="space-y-4" id="login-form">
                @csrf

                <!-- Email Field -->
                <div>
                    <label for="email" class="block text-xs font-semibold text-slate-300 mb-1.5 flex items-center justify-between">
                        <span>Corporate Email</span>
                        <span class="text-[11px] text-slate-500 font-normal">e.g. admin@zaujati.com</span>
                    </label>
                    <div class="relative group">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500 group-focus-within:text-slate-300 transition-colors text-xs">
                            <i class="fa-regular fa-envelope"></i>
                        </span>
                        <input id="email" 
                               type="email" 
                               name="email" 
                               value="{{ old('email') }}" 
                               required 
                               autofocus 
                               autocomplete="username"
                               placeholder="your.name@zaujati.com"
                               class="w-full bg-slate-950/80 border border-slate-800 hover:border-slate-700 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500/40 rounded-xl pl-10 pr-4 py-2.5 text-xs text-white placeholder:text-slate-500 transition shadow-xs outline-none" />
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
                </div>

                <!-- Password Field -->
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="password" class="block text-xs font-semibold text-slate-300">
                            Access Password
                        </label>
                        @if (Route::has('password.request'))
                            <a class="text-xs font-medium text-indigo-400 hover:text-indigo-300 transition" href="{{ route('password.request') }}">
                                Forgot password?
                            </a>
                        @endif
                    </div>
                    <div class="relative group">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500 group-focus-within:text-slate-300 transition-colors text-xs">
                            <i class="fa-regular fa-lock"></i>
                        </span>
                        <input id="password" 
                               type="password" 
                               name="password" 
                               required 
                               autocomplete="current-password"
                               placeholder="••••••••••••"
                               class="w-full bg-slate-950/80 border border-slate-800 hover:border-slate-700 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500/40 rounded-xl pl-10 pr-10 py-2.5 text-xs text-white placeholder:text-slate-500 transition shadow-xs outline-none font-mono-nums" />
                        <button type="button" 
                                id="toggle-pwd-btn"
                                class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-500 hover:text-slate-300 transition"
                                title="Toggle Password Visibility">
                            <i class="fa-regular fa-eye text-xs" id="pwd-eye-icon"></i>
                        </button>
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
                </div>

                <!-- Remember Me & Security Status -->
                <div class="flex items-center justify-between pt-1">
                    <label for="remember_me" class="inline-flex items-center gap-2 cursor-pointer select-none">
                        <input id="remember_me" 
                               type="checkbox" 
                               name="remember"
                               class="w-4 h-4 rounded border-slate-700 bg-slate-950 text-indigo-600 focus:ring-indigo-500/30 focus:ring-offset-0">
                        <span class="text-xs text-slate-300 font-normal">Remember this device</span>
                    </label>
                    <span class="text-[11px] text-slate-400 font-mono-nums flex items-center gap-1.5">
                        <i class="fa-solid fa-lock text-[10px] text-slate-400"></i>
                        <span>256-Bit SSL</span>
                    </span>
                </div>

                <!-- Submit Button -->
                <div class="pt-2">
                    <button type="submit"
                            class="w-full inline-flex items-center justify-center gap-2 py-2.5 px-4 rounded-xl text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-500 active:bg-indigo-700 shadow-md shadow-indigo-600/20 transition-all cursor-pointer group">
                        <span>Sign In to Dashboard</span>
                        <i class="fa-solid fa-arrow-right text-[11px] group-hover:translate-x-0.5 transition-transform"></i>
                    </button>
                </div>
            </form>

            <!-- Quick Auto-Fill Demo Accounts -->
            <div class="mt-6 pt-4 border-t border-slate-800/80">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[10px] font-semibold uppercase tracking-wider text-slate-400">
                        Authorized Test Profiles
                    </span>
                    <span class="text-[10px] text-slate-500 font-medium">1-Click Fill</span>
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <button type="button" 
                            onclick="fillCredentials('admin@zaujati.com', 'password')"
                            class="p-2.5 rounded-xl text-left bg-slate-950/60 hover:bg-slate-900/90 border border-slate-800 hover:border-slate-700 transition group">
                        <div class="text-[11px] font-semibold text-slate-200 group-hover:text-white flex items-center justify-between">
                            <span>Admin Portal</span>
                            <i class="fa-solid fa-shield text-[10px] text-slate-400"></i>
                        </div>
                        <div class="text-[10px] text-slate-400 font-mono-nums truncate mt-0.5">admin@zaujati.com</div>
                    </button>
                    <button type="button" 
                            onclick="fillCredentials('basyirah.hadi@gmail.com', 'password')"
                            class="p-2.5 rounded-xl text-left bg-slate-950/60 hover:bg-slate-900/90 border border-slate-800 hover:border-slate-700 transition group">
                        <div class="text-[11px] font-semibold text-slate-200 group-hover:text-white flex items-center justify-between">
                            <span>Staff Account</span>
                            <i class="fa-solid fa-user text-[10px] text-slate-400"></i>
                        </div>
                        <div class="text-[10px] text-slate-400 font-mono-nums truncate mt-0.5">basyirah.hadi...</div>
                    </button>
                </div>
            </div>

            <!-- Alternative Quick Gateways -->
            <div class="mt-4 pt-3 flex items-center justify-center gap-4 text-xs text-slate-400">
                <a href="{{ route('kiosk.gateway') }}" 
                   class="inline-flex items-center gap-1.5 hover:text-slate-200 font-medium transition">
                    <i class="fa-solid fa-camera text-[11px] text-slate-400"></i>
                    <span>Face Attendance Kiosk</span>
                </a>
                <span class="text-slate-700">&bull;</span>
                <a href="{{ route('kiosk.admin-login') }}" 
                   class="inline-flex items-center gap-1.5 hover:text-slate-200 font-medium transition">
                    <i class="fa-solid fa-fingerprint text-[11px] text-slate-400"></i>
                    <span>Terminal Punch Gateway</span>
                </a>
            </div>

        </div>
    </div>

    <!-- Script for Password Toggle and Quick Autofill -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toggleBtn = document.getElementById('toggle-pwd-btn');
            const pwdInput = document.getElementById('password');
            const eyeIcon = document.getElementById('pwd-eye-icon');

            if (toggleBtn && pwdInput && eyeIcon) {
                toggleBtn.addEventListener('click', function () {
                    const isPwd = pwdInput.getAttribute('type') === 'password';
                    pwdInput.setAttribute('type', isPwd ? 'text' : 'password');
                    eyeIcon.classList.toggle('fa-eye', !isPwd);
                    eyeIcon.classList.toggle('fa-eye-slash', isPwd);
                });
            }
        });

        function fillCredentials(email, password) {
            const emailInput = document.getElementById('email');
            const passwordInput = document.getElementById('password');
            if (emailInput && passwordInput) {
                emailInput.value = email;
                passwordInput.value = password;
                
                // Add brief subtle border feedback
                emailInput.classList.add('ring-1', 'ring-indigo-500', 'border-indigo-500');
                passwordInput.classList.add('ring-1', 'ring-indigo-500', 'border-indigo-500');
                setTimeout(() => {
                    emailInput.classList.remove('ring-1', 'ring-indigo-500', 'border-indigo-500');
                    passwordInput.classList.remove('ring-1', 'ring-indigo-500', 'border-indigo-500');
                }, 350);
            }
        }
    </script>
</x-guest-layout>

