<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Clock In — LaundryStaff Enterprise</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <style>
        body { 
            font-family: 'Plus Jakarta Sans', 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            letter-spacing: -0.011em;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        .font-mono-nums { font-family: 'JetBrains Mono', monospace; }
        
        .corporate-card {
            background: rgba(15, 23, 42, 0.88);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.7),
                        0 0 0 1px rgba(255, 255, 255, 0.05),
                        inset 0 1px 0 0 rgba(255, 255, 255, 0.1);
        }
    </style>
</head>
<body class="bg-[#090d16] text-slate-100 min-h-screen flex flex-col antialiased selection:bg-indigo-600 selection:text-white relative overflow-x-hidden">

    <!-- Highly Visible Aesthetic Modern Laundry Wallpaper -->
    <div class="fixed inset-0 z-0 bg-cover bg-center pointer-events-none scale-105" 
         style="background-image: url('{{ asset('images/laundry-modern-bg.jpg') }}'); filter: brightness(0.85) contrast(1.05);"></div>
    <div class="fixed inset-0 z-0 bg-gradient-to-b from-[#070b14]/75 via-[#070b14]/55 to-[#070b14]/85 pointer-events-none"></div>
    <div class="fixed inset-0 z-0 bg-gradient-to-r from-[#070b14]/70 via-transparent to-[#070b14]/70 pointer-events-none"></div>

    {{-- TOP NAVIGATION HEADER --}}
    <header class="bg-slate-950/60 backdrop-blur-md border-b border-slate-800/80 sticky top-0 z-50">
        <div class="max-w-5xl mx-auto px-6 py-3.5 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-slate-900 border border-slate-700/80 p-1 flex items-center justify-center shadow-sm">
                    <img src="{{ asset('images/zaujati-logo.png') }}" alt="Zaujati" class="w-full h-full object-contain">
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="font-bold text-sm text-white tracking-tight">LaundryStaff</span>
                        <span class="px-2 py-0.5 rounded text-[10px] font-semibold uppercase tracking-wider text-slate-300 bg-slate-800 border border-slate-700">Enterprise</span>
                        <span class="text-[10px] font-semibold uppercase tracking-wider px-2 py-0.5 rounded text-indigo-300 bg-indigo-950/80 border border-indigo-800/60">
                            Admin Punch Gateway
                        </span>
                    </div>
                    <p class="text-[11px] text-slate-400 font-mono-nums">Terminal MY-KUL-01 · Authorized Verification</p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('login') }}" 
                   class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg text-xs font-medium bg-slate-800 hover:bg-slate-700 text-slate-200 transition border border-slate-700/80 shadow-xs">
                    <i class="fa-solid fa-arrow-left text-[11px] text-slate-400"></i>
                    <span>Portal Sign In</span>
                </a>
                <a href="{{ route('kiosk.gateway') }}" 
                   class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg text-xs font-medium bg-slate-800 hover:bg-slate-700 text-slate-200 transition border border-slate-700/80 shadow-xs">
                    <i class="fa-solid fa-camera text-[11px] text-slate-400"></i>
                    <span>Face Kiosk</span>
                </a>
            </div>
        </div>
    </header>

    {{-- MAIN CONTENT AREA --}}
    <main class="flex-1 flex items-center justify-center px-4 sm:px-6 py-10 relative z-10">
        <div class="max-w-md w-full">

            {{-- LIVE TIME BANNER --}}
            <div class="mb-4 flex items-center justify-between px-2 text-xs">
                <div class="flex items-center gap-2 text-slate-400">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    <span class="font-medium text-slate-300">Terminal Ready</span>
                </div>
                <div id="live-clock" class="font-mono-nums font-semibold text-slate-300 tracking-wider text-xs">
                    --:--:-- --
                </div>
            </div>

            {{-- CORPORATE CARD --}}
            <div class="corporate-card rounded-2xl p-7 sm:p-9 relative">

                {{-- CARD HEADER WITH SHIELD BADGE --}}
                <div class="flex items-start gap-4 mb-6">
                    <div class="w-12 h-12 rounded-xl bg-slate-900 border border-slate-700/80 flex items-center justify-center shrink-0 shadow-sm">
                        <i class="fa-solid fa-shield-halved text-lg text-indigo-400"></i>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-white tracking-tight">Admin Shift Check-In</h1>
                        <p class="text-xs text-slate-400 mt-1 leading-relaxed">
                            Verify your administrator credentials to record attendance and access operational controls.
                        </p>
                    </div>
                </div>

                {{-- NOTIFICATIONS & ALERTS --}}
                @if(session('error'))
                    <div class="mb-4 rounded-xl bg-rose-950/70 border border-rose-800/80 text-rose-300 text-xs px-3.5 py-2.5 flex items-start gap-2.5">
                        <i class="fa-solid fa-circle-exclamation text-rose-400 text-xs mt-0.5 shrink-0"></i>
                        <span class="font-medium leading-relaxed">{{ session('error') }}</span>
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-4 rounded-xl bg-rose-950/70 border border-rose-800/80 text-rose-300 text-xs px-3.5 py-2.5 flex items-start gap-2.5">
                        <i class="fa-solid fa-circle-exclamation text-rose-400 text-xs mt-0.5 shrink-0"></i>
                        <span class="font-medium leading-relaxed">{{ $errors->first() }}</span>
                    </div>
                @endif

                {{-- LOGIN FORM --}}
                <form action="{{ route('kiosk.admin-confirm') }}" method="POST" class="space-y-4">
                    @csrf

                    {{-- EMAIL FIELD --}}
                    <div>
                        <label for="email" class="block text-xs font-semibold text-slate-300 mb-1.5 flex items-center justify-between">
                            <span>Admin Email</span>
                            <span class="text-[11px] text-slate-500 font-normal">e.g. admin@zaujati.com</span>
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500 group-focus-within:text-slate-300 transition-colors">
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
                                   class="w-full bg-slate-950/80 border border-slate-800 hover:border-slate-700 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500/40 rounded-xl pl-10 pr-4 py-2.5 text-xs text-white placeholder:text-slate-500 transition shadow-xs outline-none">
                        </div>
                    </div>

                    {{-- PASSWORD FIELD WITH TOGGLE --}}
                    <div>
                        <label for="password" class="block text-xs font-semibold text-slate-300 mb-1.5 flex items-center justify-between">
                            <span>Security Password</span>
                            <span class="text-[11px] text-slate-500 font-normal">Bcrypt Verified</span>
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500 group-focus-within:text-slate-300 transition-colors">
                                <i class="fa-regular fa-lock text-xs"></i>
                            </div>
                            <input type="password" 
                                   name="password" 
                                   id="password" 
                                   required 
                                   autocomplete="current-password"
                                   placeholder="••••••••••••"
                                   class="w-full bg-slate-950/80 border border-slate-800 hover:border-slate-700 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500/40 rounded-xl pl-10 pr-10 py-2.5 text-xs text-white placeholder:text-slate-500 transition shadow-xs outline-none font-mono-nums">
                            <button type="button" 
                                    id="toggle-password" 
                                    class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-500 hover:text-slate-300 transition"
                                    title="Toggle password visibility">
                                <i class="fa-regular fa-eye text-xs" id="eye-icon"></i>
                            </button>
                        </div>
                    </div>

                    {{-- ACTION BUTTON --}}
                    <div class="pt-2">
                        <button type="submit" 
                                class="w-full text-white font-semibold text-xs py-2.5 rounded-xl shadow-md shadow-indigo-600/20 bg-indigo-600 hover:bg-indigo-500 active:bg-indigo-700 transition-all flex items-center justify-center gap-2 cursor-pointer group">
                            <i class="fa-solid fa-fingerprint text-xs"></i>
                            <span>Verify Identity &amp; Clock In</span>
                            <i class="fa-solid fa-arrow-right text-[11px] group-hover:translate-x-0.5 transition-transform"></i>
                        </button>
                    </div>
                </form>

                {{-- SECURITY BADGES ROW --}}
                <div class="mt-6 pt-5 border-t border-slate-800/80 grid grid-cols-3 gap-2 text-center text-[10px] text-slate-400">
                    <div class="p-2 rounded-xl bg-slate-950/60 border border-slate-800 flex flex-col items-center gap-1">
                        <i class="fa-solid fa-link text-slate-400 text-xs"></i>
                        <span class="font-medium">Audit Ledger</span>
                    </div>
                    <div class="p-2 rounded-xl bg-slate-950/60 border border-slate-800 flex flex-col items-center gap-1">
                        <i class="fa-solid fa-shield text-emerald-500 text-xs"></i>
                        <span class="font-medium">Rate Protected</span>
                    </div>
                    <div class="p-2 rounded-xl bg-slate-950/60 border border-slate-800 flex flex-col items-center gap-1">
                        <i class="fa-solid fa-clock text-slate-400 text-xs"></i>
                        <span class="font-medium">Auto Timestamp</span>
                    </div>
                </div>

                <p class="text-[11px] text-slate-500 mt-4 text-center leading-relaxed">
                    Recording attendance registers your shift time into the security audit log and redirects to the management dashboard.
                </p>

            </div>

            {{-- FOOTER HELP --}}
            <div class="mt-6 text-center">
                <a href="{{ route('kiosk.scan') }}" class="inline-flex items-center gap-1.5 text-xs font-medium text-slate-400 hover:text-slate-200 transition">
                    <i class="fa-solid fa-camera text-[11px]"></i>
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