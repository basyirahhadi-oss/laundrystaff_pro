<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kiosk Terminal Gateway — LaundryStaff Pro</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <style>
        body { 
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'SF Pro Display', 'SF Pro Text', sans-serif;
            letter-spacing: -0.018em;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            text-rendering: optimizeLegibility;
        }
        h1, h2, h3, h4, h5, h6 { letter-spacing: -0.03em; }
    </style>
</head>
<body class="bg-slate-900 text-slate-100 min-h-screen flex flex-col antialiased selection:bg-indigo-500 selection:text-white relative overflow-hidden">

    {{-- BACKGROUND GLOWS --}}
    <div class="absolute -top-40 -right-40 w-96 h-96 bg-indigo-600/20 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-purple-600/20 rounded-full blur-3xl pointer-events-none"></div>

    <header class="bg-slate-900/90 backdrop-blur-md border-b border-slate-800 sticky top-0 z-50">
        <div class="max-w-4xl mx-auto px-6 py-3.5 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/zaujati-logo.png') }}" alt="Zaujati Laundry" class="w-9 h-9 rounded-xl object-contain bg-slate-950 p-1 border border-slate-800 shadow-md">
                <div>
                    <span class="font-extrabold text-sm text-white tracking-tight">Zaujati Laundry Hub</span>
                    <span class="text-[10px] text-slate-400 font-mono-nums block">Shop Floor Attendance Gateway</span>
                </div>
            </div>
            @auth
                <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-xl text-xs font-bold bg-slate-800 hover:bg-slate-700 text-slate-300 transition border border-slate-700/80">
                    <i class="fa-solid fa-arrow-left text-[10px]"></i>
                    <span>Back to Portal</span>
                </a>
            @endauth
        </div>
    </header>

    <main class="flex-1 flex items-center justify-center px-6 py-12 relative z-10">
        <div class="max-w-md w-full text-center space-y-6">

            <!-- LOGO HEADER -->
            <div class="space-y-3">
                <div class="w-24 h-24 mx-auto rounded-3xl bg-slate-950 p-2.5 shadow-2xl border border-slate-800 animate-pulse">
                    <img src="{{ asset('images/zaujati-logo.png') }}" alt="Zaujati Laundry" class="w-full h-full object-contain">
                </div>
                <h1 class="text-2xl font-black text-white tracking-tight">Attendance Check-In</h1>
                <p class="text-xs text-slate-400">Select your verification method to record your shift entry or exit.</p>
            </div>

            @if(session('error'))
                <div class="rounded-2xl bg-rose-950/60 border border-rose-800/80 text-rose-300 text-xs px-4 py-3 text-left flex items-center gap-2.5 shadow-sm">
                    <i class="fa-solid fa-circle-exclamation text-rose-400"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif
            @if(session('info'))
                <div class="rounded-2xl bg-sky-950/60 border border-sky-800/80 text-sky-300 text-xs px-4 py-3 text-left flex items-center gap-2.5 shadow-sm">
                    <i class="fa-solid fa-circle-info text-sky-400"></i>
                    <span>{{ session('info') }}</span>
                </div>
            @endif

            <div class="space-y-3">
                <!-- STAFF BIOMETRIC SCAN OPTION -->
                <a href="{{ route('kiosk.scan') }}"
                   class="group block w-full bg-slate-950/90 hover:bg-slate-900 rounded-3xl border border-slate-800 hover:border-indigo-500/60 shadow-xl transition-all p-6 text-left relative overflow-hidden">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-2xl bg-indigo-600/20 border border-indigo-500/30 group-hover:bg-indigo-600 text-indigo-400 group-hover:text-white flex items-center justify-center text-xl transition-all flex-shrink-0">
                            <i class="fa-solid fa-camera"></i>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center gap-2">
                                <p class="font-extrabold text-white text-sm">Staff Biometric Scan</p>
                                <span class="px-1.5 py-0.5 rounded text-[9px] font-black uppercase bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">Face ID</span>
                            </div>
                            <p class="text-xs text-slate-400 mt-1">Automatic landmark recognition &amp; EAR blink liveness check.</p>
                        </div>
                        <i class="fa-solid fa-arrow-right text-slate-600 group-hover:text-indigo-400 group-hover:translate-x-1 transition-all"></i>
                    </div>
                </a>

                <!-- ADMIN PASSCODE OPTION -->
                <a href="{{ route('kiosk.admin-login') }}"
                   class="group block w-full bg-slate-950/50 hover:bg-slate-900/80 rounded-3xl border border-slate-800/80 hover:border-slate-700 shadow-md transition-all p-5 text-left">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-slate-800 text-slate-400 group-hover:text-slate-200 flex items-center justify-center text-base transition-colors flex-shrink-0">
                            <i class="fa-solid fa-key"></i>
                        </div>
                        <div class="flex-1">
                            <p class="font-bold text-slate-200 text-xs">Manager / Admin Passcode</p>
                            <p class="text-[11px] text-slate-500 mt-0.5">Authenticate using authorized administrative credentials.</p>
                        </div>
                        <i class="fa-solid fa-chevron-right text-slate-600 text-xs"></i>
                    </div>
                </a>
            </div>

            <p class="text-[11px] text-slate-500">Zaujati Laundry Operations · Biometric Security &amp; Audit Compliance</p>
        </div>
    </main>
</body>
</html>