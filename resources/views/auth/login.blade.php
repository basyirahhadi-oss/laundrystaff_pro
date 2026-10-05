<x-guest-layout>
    <div class="w-full max-w-xl relative">
        
        <!-- ============================================== -->
        <!-- FLOATING GEOMETRIC BADGES (Corak & Bentuk)     -->
        <!-- ============================================== -->
        <!-- Floating Badge: Top Left -->
        <div class="absolute -top-7 -left-6 z-20 hidden sm:flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-slate-900/90 border border-pink-500/40 shadow-xl shadow-pink-500/10 backdrop-blur-md animate-float-slow">
            <span class="w-2 h-2 rounded-full bg-pink-500 animate-ping"></span>
            <span class="text-[11px] font-bold tracking-wide text-pink-300">
                <i class="fa-solid fa-sparkles text-pink-400 mr-1"></i> Smart Laundromat v2.4
            </span>
        </div>

        <!-- Floating Badge: Bottom Right -->
        <div class="absolute -bottom-6 -right-6 z-20 hidden sm:flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-slate-900/90 border border-cyan-400/40 shadow-xl shadow-cyan-500/10 backdrop-blur-md animate-float-reverse">
            <i class="fa-solid fa-shield-check text-cyan-400 text-xs"></i>
            <span class="text-[11px] font-bold tracking-wide text-cyan-300">
                Tamper-Proof Audit Active
            </span>
        </div>

        <!-- Floating Decorative Orb Behind Card -->
        <div class="absolute top-1/2 -left-12 -translate-y-1/2 w-32 h-32 bg-[#E11D74]/20 rounded-full blur-2xl pointer-events-none"></div>
        <div class="absolute top-1/3 -right-12 w-36 h-36 bg-[#00E5FF]/20 rounded-full blur-2xl pointer-events-none"></div>

        <!-- ============================================== -->
        <!-- MAIN GLASSMORPHISM LOGIN CARD                  -->
        <!-- ============================================== -->
        <div class="glass-card rounded-[32px] p-6 sm:p-10 relative overflow-hidden">
            
            <!-- Tech Pattern Decorative Corner Lines (Corak Bentuk Geometri) -->
            <div class="absolute top-0 left-0 w-20 h-20 pointer-events-none">
                <svg class="w-full h-full text-pink-500/30" viewBox="0 0 100 100" fill="none">
                    <path d="M 0 0 L 70 0 L 0 70 Z" fill="currentColor" opacity="0.15" />
                    <line x1="0" y1="20" x2="40" y2="20" stroke="currentColor" stroke-width="2" />
                    <line x1="20" y1="0" x2="20" y2="40" stroke="currentColor" stroke-width="2" />
                    <circle cx="20" cy="20" r="3" fill="#E11D74" />
                </svg>
            </div>
            
            <div class="absolute top-0 right-0 w-24 h-24 pointer-events-none">
                <svg class="w-full h-full text-cyan-400/30" viewBox="0 0 100 100" fill="none">
                    <circle cx="80" cy="20" r="30" stroke="currentColor" stroke-width="1.5" stroke-dasharray="4 4" />
                    <circle cx="80" cy="20" r="14" stroke="currentColor" stroke-width="1" />
                    <circle cx="80" cy="20" r="4" fill="#00E5FF" />
                </svg>
            </div>

            <!-- Header Section: Official Zaujati Brand Logo & Portal Title -->
            <div class="text-center relative z-10 mb-6">
                <!-- Official Zaujati Logo Badge -->
                <div class="inline-block relative mb-3 group">
                    <div class="w-28 h-28 mx-auto rounded-3xl p-1 bg-gradient-to-tr from-[#E11D74] via-[#4A154B] to-[#00E5FF] shadow-2xl shadow-[#E11D74]/40 transform group-hover:scale-105 transition-transform duration-300">
                        <div class="w-full h-full rounded-[22px] bg-black p-2 flex items-center justify-center overflow-hidden">
                            <img src="{{ asset('images/zaujati-logo.png') }}" 
                                 alt="Zaujati Laundry" 
                                 class="w-full h-full object-contain">
                        </div>
                    </div>
                </div>

                <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight">
                    Portal <span class="text-transparent bg-clip-text bg-gradient-to-r from-pink-400 via-purple-300 to-cyan-300">Sign In</span>
                </h1>
                <p class="text-xs text-slate-300 font-medium mt-1">
                    Authenticate to access your shift records, payroll &amp; operational dashboard
                </p>
            </div>

            <!-- Portal Switcher Tabs (Mudah Navigasi) -->
            <div class="grid grid-cols-2 gap-2 p-1.5 rounded-2xl bg-slate-950/60 border border-slate-800/80 mb-6">
                <button type="button" class="py-2 px-3 rounded-xl text-xs font-bold bg-gradient-to-r from-[#4A154B] to-[#E11D74] text-white shadow-md flex items-center justify-center gap-1.5 transition">
                    <i class="fa-solid fa-user-shield text-xs"></i>
                    <span>System Portal</span>
                </button>
                <a href="{{ route('kiosk.gateway') }}" class="py-2 px-3 rounded-xl text-xs font-semibold text-slate-400 hover:text-white hover:bg-slate-900/60 flex items-center justify-center gap-1.5 transition">
                    <i class="fa-solid fa-camera text-cyan-400 text-xs"></i>
                    <span>Kiosk Gateway</span>
                </a>
            </div>

            <!-- Session Status / Flash Alert -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <!-- Login Form -->
            <form method="POST" action="{{ route('login') }}" class="space-y-4 relative z-10" id="login-form">
                @csrf

                <!-- Email Field -->
                <div>
                    <label for="email" class="block text-xs font-bold text-slate-200 mb-1.5 flex items-center justify-between">
                        <span class="flex items-center gap-1.5">
                            <i class="fa-regular fa-envelope text-pink-400 text-xs"></i>
                            <span>Corporate Email</span>
                        </span>
                        <span class="text-[10px] text-slate-400 font-normal">e.g. admin@zaujati.com</span>
                    </label>
                    <div class="relative group">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500 group-focus-within:text-pink-400 transition-colors text-xs">
                            <i class="fa-solid fa-at"></i>
                        </span>
                        <input id="email" 
                               type="email" 
                               name="email" 
                               value="{{ old('email') }}" 
                               required 
                               autofocus 
                               autocomplete="username"
                               placeholder="your.name@zaujati.com"
                               class="w-full bg-slate-950/70 border border-slate-700/80 hover:border-slate-600 rounded-xl pl-10 pr-4 py-3 text-xs font-medium text-white placeholder:text-slate-500 focus:outline-none focus:border-pink-500 focus:ring-2 focus:ring-pink-500/25 transition shadow-inner" />
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-1" />
                </div>

                <!-- Password Field -->
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="password" class="block text-xs font-bold text-slate-200 flex items-center gap-1.5">
                            <i class="fa-solid fa-lock text-cyan-400 text-xs"></i>
                            <span>Access Password</span>
                        </label>
                        @if (Route::has('password.request'))
                            <a class="text-[11px] font-semibold text-pink-400 hover:text-pink-300 transition" href="{{ route('password.request') }}">
                                Forgot password?
                            </a>
                        @endif
                    </div>
                    <div class="relative group">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500 group-focus-within:text-cyan-400 transition-colors text-xs">
                            <i class="fa-solid fa-key"></i>
                        </span>
                        <input id="password" 
                               type="password" 
                               name="password" 
                               required 
                               autocomplete="current-password"
                               placeholder="••••••••••••"
                               class="w-full bg-slate-950/70 border border-slate-700/80 hover:border-slate-600 rounded-xl pl-10 pr-10 py-3 text-xs font-medium text-white placeholder:text-slate-500 focus:outline-none focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/25 transition shadow-inner font-mono-nums" />
                        <button type="button" 
                                id="toggle-pwd-btn"
                                class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-white transition"
                                title="Toggle Password Visibility">
                            <i class="fa-regular fa-eye text-xs" id="pwd-eye-icon"></i>
                        </button>
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-1" />
                </div>

                <!-- Remember Me & Security Status -->
                <div class="flex items-center justify-between pt-1">
                    <label for="remember_me" class="inline-flex items-center gap-2 cursor-pointer select-none">
                        <input id="remember_me" 
                               type="checkbox" 
                               name="remember"
                               class="w-4 h-4 rounded border-slate-700 bg-slate-900 text-pink-600 focus:ring-pink-500 focus:ring-offset-slate-900">
                        <span class="text-xs text-slate-300 font-medium">Remember on this terminal</span>
                    </label>
                    <span class="text-[11px] text-slate-400 font-mono-nums hidden sm:inline flex items-center gap-1">
                        <i class="fa-solid fa-lock text-[10px] text-emerald-400"></i> TLS 1.3
                    </span>
                </div>

                <!-- Submit Button -->
                <div class="pt-2">
                    <button type="submit"
                            class="w-full inline-flex items-center justify-center gap-2 py-3.5 px-5 rounded-xl text-xs font-extrabold text-white shadow-xl transition-all duration-200 transform hover:-translate-y-0.5 active:translate-y-0 cursor-pointer group"
                            style="background: linear-gradient(135deg, #4A154B 0%, #E11D74 50%, #7B1FA2 100%); box-shadow: 0 10px 25px -5px rgba(225, 29, 116, 0.45); border: 1px solid rgba(255, 255, 255, 0.2);">
                        <span>Sign In to Dashboard</span>
                        <i class="fa-solid fa-arrow-right text-xs group-hover:translate-x-1 transition-transform"></i>
                    </button>
                </div>
            </form>

            <!-- Quick Auto-Fill Demo Accounts -->
            <div class="mt-6 pt-4 border-t border-slate-800/80">
                <div class="flex items-center justify-between mb-2.5">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 flex items-center gap-1">
                        <i class="fa-solid fa-bolt text-amber-400 text-xs"></i> Quick Autofill Demo
                    </span>
                    <span class="text-[10px] text-slate-500">1-Click Test</span>
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <button type="button" 
                            onclick="fillCredentials('admin@zaujati.com', 'password')"
                            class="px-3 py-2 rounded-xl text-left bg-slate-900/80 hover:bg-slate-800/90 border border-slate-800 hover:border-pink-500/40 transition group">
                        <div class="text-[11px] font-bold text-white group-hover:text-pink-400 flex items-center justify-between">
                            <span>Admin Portal</span>
                            <i class="fa-solid fa-shield text-[10px] text-pink-400"></i>
                        </div>
                        <div class="text-[10px] text-slate-400 font-mono-nums truncate">admin@zaujati.com</div>
                    </button>
                    <button type="button" 
                            onclick="fillCredentials('basyirah.hadi@gmail.com', 'password')"
                            class="px-3 py-2 rounded-xl text-left bg-slate-900/80 hover:bg-slate-800/90 border border-slate-800 hover:border-cyan-400/40 transition group">
                        <div class="text-[11px] font-bold text-white group-hover:text-cyan-400 flex items-center justify-between">
                            <span>Staff Account</span>
                            <i class="fa-solid fa-user text-[10px] text-cyan-400"></i>
                        </div>
                        <div class="text-[10px] text-slate-400 font-mono-nums truncate">basyirah.hadi...</div>
                    </button>
                </div>
            </div>

            <!-- Alternative Quick Gateways -->
            <div class="mt-4 pt-3 flex items-center justify-center gap-4 text-xs">
                <a href="{{ route('kiosk.gateway') }}" 
                   class="inline-flex items-center gap-1.5 text-cyan-400 hover:text-cyan-300 font-semibold transition">
                    <i class="fa-solid fa-camera text-[11px]"></i>
                    <span>Face Attendance Kiosk</span>
                </a>
                <span class="text-slate-600">&bull;</span>
                <a href="{{ route('kiosk.admin-login') }}" 
                   class="inline-flex items-center gap-1.5 text-pink-400 hover:text-pink-300 font-semibold transition">
                    <i class="fa-solid fa-fingerprint text-[11px]"></i>
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
                
                // Add brief pulse feedback
                emailInput.classList.add('ring-2', 'ring-pink-500');
                passwordInput.classList.add('ring-2', 'ring-pink-500');
                setTimeout(() => {
                    emailInput.classList.remove('ring-2', 'ring-pink-500');
                    passwordInput.classList.remove('ring-2', 'ring-pink-500');
                }, 400);
            }
        }
    </script>
</x-guest-layout>

