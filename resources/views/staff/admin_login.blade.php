<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Clock In — LaundryStaff Pro</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <style>
        body { 
            font-family: 'Plus Jakarta Sans', 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            letter-spacing: -0.015em;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        .font-mono-nums { font-family: 'JetBrains Mono', monospace; }
        .glow-purple {
            box-shadow: 0 0 50px -10px rgba(225, 29, 116, 0.35);
        }
        .bg-tech-grid {
            background-image: 
                linear-gradient(to right, rgba(255, 255, 255, 0.05) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(255, 255, 255, 0.05) 1px, transparent 1px);
            background-size: 32px 32px;
        }
        .bg-tech-dots {
            background-image: radial-gradient(rgba(225, 29, 116, 0.25) 1px, transparent 1px);
            background-size: 24px 24px;
        }
        @keyframes floatSlow {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-10px) rotate(2deg); }
        }
        .animate-float-slow {
            animation: floatSlow 6s ease-in-out infinite;
        }
    </style>
</head>
<body class="bg-[#070b14] text-slate-100 min-h-screen flex flex-col antialiased selection:bg-pink-600 selection:text-white relative overflow-x-hidden">

    <!-- Highly Visible Aesthetic Modern Laundry Wallpaper -->
    <div class="fixed inset-0 z-0 bg-cover bg-center pointer-events-none scale-105" 
         style="background-image: url('{{ asset('images/laundry-modern-bg.jpg') }}'); filter: brightness(0.85) contrast(1.05);"></div>
    <div class="fixed inset-0 z-0 bg-gradient-to-t from-[#070b14]/90 via-[#070b14]/75 to-[#070b14]/65 backdrop-blur-xs pointer-events-none"></div>

    <!-- Tech Grid & Dot Overlay (Corak & Bentuk) -->
    <div class="fixed inset-0 z-0 bg-tech-grid opacity-35 pointer-events-none"></div>
    <div class="fixed inset-0 z-0 bg-tech-dots opacity-45 pointer-events-none"></div>

    {{-- AMBIENT SYSTEM COLOR GLOWS --}}
    <div class="absolute -top-32 -right-32 w-96 h-96 rounded-full blur-[120px] pointer-events-none opacity-45" style="background: #4A154B;"></div>
    <div class="absolute -bottom-32 -left-32 w-96 h-96 rounded-full blur-[120px] pointer-events-none opacity-40" style="background: #E11D74;"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-cyan-950/25 rounded-full blur-[140px] pointer-events-none"></div>

    {{-- FLOATING GEOMETRIC SHAPES (Bentuk Tambahan) --}}
    <div class="fixed top-28 left-[8%] w-14 h-14 rounded-2xl border border-pink-500/30 bg-pink-500/10 backdrop-blur-md animate-float-slow hidden lg:block pointer-events-none"></div>
    <div class="fixed bottom-28 right-[8%] w-16 h-16 rounded-3xl border border-cyan-400/30 bg-cyan-400/10 backdrop-blur-md animate-float-slow hidden lg:block pointer-events-none"></div>

    {{-- TOP NAVIGATION HEADER --}}
    <header class="bg-slate-950/70 backdrop-blur-md border-b border-white/10 sticky top-0 z-50">
        <div class="max-w-5xl mx-auto px-6 py-3.5 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl p-[1.5px] bg-gradient-to-tr from-[#E11D74] to-[#00E5FF] shadow-sm">
                    <div class="w-full h-full bg-black rounded-[14px] p-1 flex items-center justify-center overflow-hidden">
                        <img src="{{ asset('images/zaujati-logo.png') }}" alt="Zaujati Laundry" class="w-full h-full object-contain">
                    </div>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="font-extrabold text-sm text-white tracking-tight">LaundryStaff</span>
                        <span class="px-1.5 py-0.5 rounded text-[10px] font-bold uppercase text-white tracking-wide" style="background: linear-gradient(135deg, #4A154B 0%, #E11D74 100%);">PRO</span>
                        <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-full bg-[#E11D74]/15 text-[#E11D74] border border-[#E11D74]/30">
                            ADMIN CHECK-IN
                        </span>
                    </div>
                    <p class="text-[11px] text-slate-300 font-mono-nums">Terminal MY-KUL-01 · Authorized Punch Gateway</p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('login') }}" 
                   class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl text-xs font-bold bg-white/10 hover:bg-white/20 text-slate-200 hover:text-white transition border border-white/10 shadow-xs">
                    <i class="fa-solid fa-arrow-left text-[11px]"></i>
                    <span>Portal Login</span>
                </a>
                <a href="{{ route('kiosk.gateway') }}" 
                   class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl text-xs font-bold bg-slate-900/90 hover:bg-slate-800 text-slate-300 hover:text-white transition border border-slate-700/80 shadow-xs group">
                    <i class="fa-solid fa-camera text-[11px] text-cyan-400"></i>
                    <span>Kiosk Gateway</span>
                </a>
            </div>
        </div>
    </header>

    {{-- MAIN CONTENT AREA --}}
    <main class="flex-1 flex items-center justify-center px-4 sm:px-6 py-10 relative z-10">
        <div class="max-w-md w-full">

            {{-- LIVE TIME BANNER --}}
            <div class="mb-5 flex items-center justify-between px-2 text-xs">
                <div class="flex items-center gap-2 text-slate-300">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span class="font-medium">System Terminal Ready</span>
                </div>
                <div id="live-clock" class="font-mono-nums font-bold text-slate-200 tracking-wider">
                    --:--:-- --
                </div>
            </div>

            {{-- GLASSMORPHISM CARD --}}
            <div class="bg-slate-950/80 backdrop-blur-2xl rounded-3xl border border-white/15 shadow-2xl p-6 sm:p-8 relative overflow-hidden glow-purple">

                {{-- TOP CORNER ACCENT GRADIENT & GEOMETRIC SHAPE --}}
                <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-[#E11D74]/25 via-[#4A154B]/15 to-transparent rounded-bl-full pointer-events-none"></div>
                <div class="absolute top-2 right-2 text-cyan-400/30 pointer-events-none">
                    <svg class="w-8 h-8" viewBox="0 0 40 40" fill="none">
                        <circle cx="20" cy="20" r="16" stroke="currentColor" stroke-width="1.5" stroke-dasharray="3 3"/>
                        <circle cx="20" cy="20" r="6" fill="#E11D74" opacity="0.6"/>
                    </svg>
                </div>

                {{-- CARD HEADER WITH SHIELD BADGE --}}
                <div class="flex items-start gap-4 mb-6 relative z-10">
                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center shrink-0 shadow-lg" style="background: linear-gradient(135deg, #4A154B 0%, #E11D74 100%); border: 1px solid rgba(255, 255, 255, 0.2);">
                        <i class="fa-solid fa-shield-halved text-xl text-white"></i>
                    </div>
                    <div>
                        <h1 class="text-lg sm:text-xl font-bold text-white tracking-tight">Admin Shift Check-In</h1>
                        <p class="text-xs text-slate-300 mt-1 leading-relaxed">
                            Verify your administrator credentials to record today's shift punch and access management tools.
                        </p>
                    </div>
                </div>

                {{-- NOTIFICATIONS & ALERTS --}}
                @if(session('error'))
                    <div class="mb-5 rounded-2xl bg-rose-950/70 border border-rose-800/80 text-rose-300 text-sm px-4 py-3 flex items-start gap-2.5 shadow-sm">
                        <i class="fa-solid fa-circle-exclamation text-rose-400 text-sm mt-0.5 shrink-0"></i>
                        <span class="font-medium leading-relaxed">{{ session('error') }}</span>
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-5 rounded-2xl bg-rose-950/70 border border-rose-800/80 text-rose-300 text-sm px-4 py-3 flex items-start gap-2.5 shadow-sm">
                        <i class="fa-solid fa-circle-exclamation text-rose-400 text-sm mt-0.5 shrink-0"></i>
                        <span class="font-medium leading-relaxed">{{ $errors->first() }}</span>
                    </div>
                @endif

                {{-- LOGIN FORM --}}
                <form action="{{ route('kiosk.admin-confirm') }}" method="POST" class="space-y-4 relative z-10">
                    @csrf

                    {{-- EMAIL FIELD --}}
                    <div>
                        <label for="email" class="block text-[11px] font-bold uppercase tracking-wider text-slate-200 mb-1.5 flex items-center justify-between">
                            <span>Admin Email</span>
                            <span class="text-[10px] text-slate-400 lowercase">e.g. admin@zaujati.com</span>
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-pink-400 transition-colors">
                                <i class="fa-regular fa-envelope text-xs"></i>
                            </div>
                            <input type="email" 
                                   name="email" 
                                   id="email"
                                   value="{{ old('email', 'admin@zaujati.com') }}" 
                                   required 
                                   autofocus 
                                   autocomplete="email"
                                   placeholder="name@company.com"
                                   class="w-full bg-slate-900/90 border border-slate-700/80 hover:border-slate-600 rounded-xl pl-9 pr-4 py-2.5 text-xs text-white placeholder:text-slate-500 focus:outline-none focus:border-[#E11D74] focus:ring-2 focus:ring-[#E11D74]/25 transition shadow-inner">
                        </div>
                    </div>

                    {{-- PASSWORD FIELD WITH TOGGLE --}}
                    <div>
                        <label for="password" class="block text-[11px] font-bold uppercase tracking-wider text-slate-200 mb-1.5 flex items-center justify-between">
                            <span>Password</span>
                            <span class="text-[10px] text-slate-400">Verified via Bcrypt</span>
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-cyan-400 transition-colors">
                                <i class="fa-solid fa-lock text-xs"></i>
                            </div>
                            <input type="password" 
                                   name="password" 
                                   id="password" 
                                   required 
                                   autocomplete="current-password"
                                   placeholder="••••••••••••"
                                   class="w-full bg-slate-900/90 border border-slate-700/80 hover:border-slate-600 rounded-xl pl-9 pr-10 py-2.5 text-xs text-white placeholder:text-slate-500 focus:outline-none focus:border-[#E11D74] focus:ring-2 focus:ring-[#E11D74]/25 transition shadow-inner font-mono-nums">
                            <button type="button" 
                                    id="toggle-password" 
                                    class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-white transition"
                                    title="Toggle password visibility">
                                <i class="fa-regular fa-eye text-xs" id="eye-icon"></i>
                            </button>
                        </div>
                    </div>

                    {{-- ACTION BUTTON --}}
                    <div class="pt-2">
                        <button type="submit" 
                                class="w-full text-white font-bold text-xs py-3.5 rounded-xl shadow-lg transition-all transform hover:-translate-y-0.5 active:translate-y-0 flex items-center justify-center gap-2 group cursor-pointer"
                                style="background: linear-gradient(135deg, #4A154B 0%, #E11D74 50%, #7B1FA2 100%); border: 1px solid rgba(255, 255, 255, 0.2); box-shadow: 0 8px 25px rgba(225, 29, 116, 0.4);">
                            <i class="fa-solid fa-fingerprint text-sm text-cyan-300 group-hover:scale-110 transition-transform"></i>
                            <span>Verify Identity &amp; Clock In</span>
                            <i class="fa-solid fa-arrow-right text-[11px] opacity-70 group-hover:translate-x-1 transition-transform ml-1"></i>
                        </button>
                    </div>
                </form>

                {{-- SECURITY BADGES ROW --}}
                <div class="mt-6 pt-5 border-t border-slate-800/80 grid grid-cols-3 gap-2 text-center text-[10px] text-slate-300">
                    <div class="p-2 rounded-xl bg-slate-900/70 border border-slate-800/80 flex flex-col items-center gap-1">
                        <i class="fa-solid fa-link text-[#E11D74] text-xs"></i>
                        <span class="font-medium">SHA-256 Ledger</span>
                    </div>
                    <div class="p-2 rounded-xl bg-slate-900/70 border border-slate-800/80 flex flex-col items-center gap-1">
                        <i class="fa-solid fa-shield-virus text-emerald-400 text-xs"></i>
                        <span class="font-medium">Rate Throttled</span>
                    </div>
                    <div class="p-2 rounded-xl bg-slate-900/70 border border-slate-800/80 flex flex-col items-center gap-1">
                        <i class="fa-solid fa-clock text-amber-400 text-xs"></i>
                        <span class="font-medium">Auto Punch In/Out</span>
                    </div>
                </div>

                <p class="text-[11px] text-slate-400 mt-4 text-center leading-relaxed">
                    Recording attendance registers your shift time into the tamper-evident security audit log and automatically redirects you to the administrator dashboard.
                </p>

            </div>

            {{-- FOOTER HELP --}}
            <div class="mt-6 text-center">
                <a href="{{ route('kiosk.scan') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-300 hover:text-white transition">
                    <i class="fa-solid fa-camera text-cyan-400 text-[11px]"></i>
                    <span>Switch to Staff Facial Recognition Scanner &rarr;</span>
                </a>
            </div>

        </div>
    </main>

    {{-- SCRIPT: LIVE CLOCK & PASSWORD TOGGLE --}}
    <script>
        // Live Clock
        function updateClock() {
            const now = new Date();
            const options = { 
                weekday: 'short', 
                year: 'numeric', 
                month: 'short', 
                day: 'numeric',
                hour: '2-digit', 
                minute: '2-digit', 
                second: '2-digit', 
                hour12: true 
            };
            const clockEl = document.getElementById('live-clock');
            if (clockEl) {
                clockEl.textContent = now.toLocaleDateString('en-US', options);
            }
        }
        setInterval(updateClock, 1000);
        updateClock();

        // Toggle Password Visibility
        const toggleBtn = document.getElementById('toggle-password');
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eye-icon');

        if (toggleBtn && passwordInput && eyeIcon) {
            toggleBtn.addEventListener('click', function () {
                const isPassword = passwordInput.getAttribute('type') === 'password';
                passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
                eyeIcon.classList.toggle('fa-eye', !isPassword);
                eyeIcon.classList.toggle('fa-eye-slash', isPassword);
            });
        }
    </script>
</body>
</html>