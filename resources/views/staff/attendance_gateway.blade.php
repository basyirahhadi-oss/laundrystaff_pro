<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kiosk Terminal Gateway — LaundryStaff Pro</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <style>
        body { 
            font-family: 'Plus Jakarta Sans', 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
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
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        .animate-float-slow {
            animation: floatSlow 6s ease-in-out infinite;
        }
    </style>
</head>
<body class="bg-[#070b14] text-slate-100 min-h-screen flex flex-col antialiased selection:bg-pink-600 selection:text-white relative overflow-hidden">

    <!-- Highly Visible Aesthetic Modern Laundry Wallpaper -->
    <div class="fixed inset-0 z-0 bg-cover bg-center pointer-events-none scale-105" 
         style="background-image: url('{{ asset('images/laundry-modern-bg.jpg') }}'); filter: brightness(0.85) contrast(1.05);"></div>
    <div class="fixed inset-0 z-0 bg-gradient-to-t from-[#070b14]/90 via-[#070b14]/75 to-[#070b14]/65 backdrop-blur-xs pointer-events-none"></div>

    <!-- Tech Grid & Dot Overlay (Corak & Bentuk) -->
    <div class="fixed inset-0 z-0 bg-tech-grid opacity-35 pointer-events-none"></div>
    <div class="fixed inset-0 z-0 bg-tech-dots opacity-45 pointer-events-none"></div>

    {{-- AMBIENT GLOWS --}}
    <div class="absolute -top-40 -right-40 w-96 h-96 bg-[#4A154B]/40 rounded-full blur-[130px] pointer-events-none"></div>
    <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-[#E11D74]/30 rounded-full blur-[130px] pointer-events-none"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-cyan-950/20 rounded-full blur-[150px] pointer-events-none"></div>

    {{-- FLOATING GEOMETRIC SHAPES --}}
    <div class="fixed top-24 left-[10%] w-14 h-14 rounded-2xl border border-pink-500/30 bg-pink-500/10 backdrop-blur-md animate-float-slow hidden md:block pointer-events-none"></div>
    <div class="fixed bottom-24 right-[10%] w-16 h-16 rounded-3xl border border-cyan-400/30 bg-cyan-400/10 backdrop-blur-md animate-float-slow hidden md:block pointer-events-none"></div>

    <header class="bg-slate-950/80 backdrop-blur-md border-b border-white/10 sticky top-0 z-50">
        <div class="max-w-4xl mx-auto px-6 py-3.5 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-black border border-pink-500/30 p-1 flex items-center justify-center shrink-0 shadow-sm overflow-hidden">
                    <img src="{{ asset('images/zaujati-logo.png') }}" alt="Zaujati Laundry" class="w-full h-full object-contain">
                </div>
                <div>
                    <div class="flex items-center gap-1.5">
                        <span class="font-extrabold text-sm text-white tracking-tight">LaundryStaff</span>
                        <span class="px-1.5 py-0.5 rounded text-[10px] font-bold uppercase text-white tracking-wide" style="background-color: #4A154B;">PRO</span>
                    </div>
                    <span class="text-[10px] text-slate-400 font-mono-nums block">Shop Floor Attendance Gateway</span>
                </div>
            </div>
            @auth
                <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-xl text-xs font-bold bg-white/10 hover:bg-white/20 text-slate-200 transition border border-white/10">
                    <i class="fa-solid fa-arrow-left text-[10px]"></i>
                    <span>Back to Portal</span>
                </a>
            @endauth
        </div>
    </header>

    <main class="flex-1 flex items-center justify-center px-4 sm:px-6 py-8 relative z-10">
        <div class="max-w-md w-full text-center space-y-6">

            <!-- OFFICIAL ZAUJATI LOGO HERO BADGE -->
            <div class="relative inline-block">
                <div class="w-28 h-28 mx-auto rounded-full p-1 bg-gradient-to-tr from-[#E11D74] via-[#4A154B] to-[#00E5FF] shadow-2xl shadow-[#E11D74]/35">
                    <div class="w-full h-full rounded-full bg-slate-950 p-2.5 flex items-center justify-center overflow-hidden">
                        <img src="{{ asset('images/zaujati-logo.png') }}" alt="Zaujati Laundry" class="w-full h-full object-contain">
                    </div>
                </div>
                <span class="absolute -bottom-2.5 left-1/2 -translate-x-1/2 px-3 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-emerald-500 text-white shadow-md flex items-center gap-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
                    Terminal Online
                </span>
            </div>

            <div class="space-y-1.5 pt-2">
                <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight">Attendance Check-In</h1>
                <p class="text-xs text-slate-300">Select your verification method to record your shift entry or exit.</p>
            </div>

            @if(session('error'))
                <div class="rounded-2xl bg-rose-950/70 border border-rose-800/80 text-rose-300 text-sm px-4 py-3 text-left flex items-center gap-2.5 shadow-sm">
                    <i class="fa-solid fa-circle-exclamation text-rose-400"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif
            @if(session('info'))
                <div class="rounded-2xl bg-sky-950/70 border border-sky-800/80 text-sky-300 text-sm px-4 py-3 text-left flex items-center gap-2.5 shadow-sm">
                    <i class="fa-solid fa-circle-info text-sky-400"></i>
                    <span>{{ session('info') }}</span>
                </div>
            @endif

            <div class="space-y-3 pt-1">
                <!-- STAFF BIOMETRIC SCAN OPTION -->
                <a href="{{ route('kiosk.scan') }}"
                   class="group block w-full bg-slate-900/90 hover:bg-slate-900 rounded-3xl border border-blue-500/30 hover:border-blue-500/70 shadow-xl transition-all p-5 text-left relative overflow-hidden backdrop-blur-md hover:scale-[1.01]">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-2xl bg-blue-600/20 border border-blue-500/30 group-hover:bg-blue-600 text-blue-400 group-hover:text-white flex items-center justify-center text-xl transition-all flex-shrink-0">
                            <i class="fa-solid fa-camera"></i>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center gap-2">
                                <p class="font-extrabold text-white text-sm">Staff Biometric Scan</p>
                                <span class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">Face ID</span>
                            </div>
                            <p class="text-xs text-slate-300 mt-1">Automatic landmark recognition &amp; EAR blink liveness check.</p>
                        </div>
                        <i class="fa-solid fa-arrow-right text-slate-500 group-hover:text-blue-400 group-hover:translate-x-1 transition-all"></i>
                    </div>
                </a>

                <!-- ADMIN PASSCODE OPTION -->
                <a href="{{ route('kiosk.admin-login') }}"
                   class="group block w-full bg-slate-900/60 hover:bg-slate-900/90 rounded-3xl border border-white/10 hover:border-white/20 shadow-md transition-all p-4.5 text-left backdrop-blur-md">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-white/10 text-slate-300 group-hover:text-white flex items-center justify-center text-base transition-colors flex-shrink-0">
                            <i class="fa-solid fa-key"></i>
                        </div>
                        <div class="flex-1">
                            <p class="font-bold text-slate-200 text-xs">Manager / Admin Passcode</p>
                            <p class="text-[11px] text-slate-400 mt-0.5">Authenticate using authorized administrative credentials.</p>
                        </div>
                        <i class="fa-solid fa-chevron-right text-slate-500 text-xs"></i>
                    </div>
                </a>
            </div>

            <p class="text-xs text-slate-400">Zaujati Laundry Operations · Biometric Security &amp; Audit Compliance</p>
        </div>
    </main>
</body>
</html>