<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kiosk Terminal Gateway — LaundryStaff Enterprise</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <style>
        body { 
            font-family: 'Plus Jakarta Sans', 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            letter-spacing: -0.011em;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
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
<body class="bg-[#090d16] text-slate-100 min-h-screen flex flex-col antialiased selection:bg-indigo-600 selection:text-white relative overflow-hidden">

    <!-- Subdued Architectural Background -->
    <div class="fixed inset-0 z-0 bg-cover bg-center pointer-events-none opacity-25" 
         style="background-image: url('{{ asset('images/laundry-modern-bg.jpg') }}'); filter: brightness(0.6) contrast(1.15);"></div>
    <div class="fixed inset-0 z-0 bg-gradient-to-b from-[#090d16]/95 via-[#090d16]/85 to-[#090d16] pointer-events-none"></div>

    <header class="bg-slate-950/60 backdrop-blur-md border-b border-slate-800/80 sticky top-0 z-50">
        <div class="max-w-4xl mx-auto px-6 py-3.5 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-slate-900 border border-slate-700/80 p-1 flex items-center justify-center shrink-0 shadow-sm overflow-hidden">
                    <img src="{{ asset('images/zaujati-logo.png') }}" alt="Zaujati Laundry" class="w-full h-full object-contain">
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="font-bold text-sm text-white tracking-tight">LaundryStaff</span>
                        <span class="px-2 py-0.5 rounded text-[10px] font-semibold uppercase tracking-wider text-slate-300 bg-slate-800 border border-slate-700">Enterprise</span>
                    </div>
                    <span class="text-[11px] text-slate-400 font-mono-nums block">Shop Floor Attendance Gateway</span>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('login') }}" class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg text-xs font-medium bg-slate-800 hover:bg-slate-700 text-slate-200 transition border border-slate-700/80 shadow-xs">
                    <i class="fa-solid fa-arrow-left text-[10px] text-slate-400"></i>
                    <span>Portal Sign In</span>
                </a>
            </div>
        </div>
    </header>

    <main class="flex-1 flex items-center justify-center px-4 sm:px-6 py-8 relative z-10">
        <div class="max-w-md w-full text-center space-y-6">

            <!-- ZAUJATI LOGO EMBLEM -->
            <div class="relative inline-block">
                <div class="w-20 h-20 mx-auto rounded-2xl bg-slate-900 border border-slate-700/80 p-3 shadow-md flex items-center justify-center overflow-hidden">
                    <img src="{{ asset('images/zaujati-logo.png') }}" alt="Zaujati Laundry" class="w-full h-full object-contain">
                </div>
                <span class="absolute -bottom-2.5 left-1/2 -translate-x-1/2 px-2.5 py-0.5 rounded-full text-[10px] font-semibold uppercase tracking-wider bg-emerald-500 text-white shadow-sm flex items-center gap-1.5 whitespace-nowrap">
                    <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
                    Terminal Ready
                </span>
            </div>

            <div class="space-y-1 pt-1">
                <h1 class="text-2xl font-bold text-white tracking-tight">Attendance Check-In</h1>
                <p class="text-xs text-slate-400">Select your verification method to record your shift entry or exit.</p>
            </div>

            @if(session('error'))
                <div class="rounded-xl bg-rose-950/70 border border-rose-800/80 text-rose-300 text-xs px-4 py-3 text-left flex items-center gap-2.5 shadow-sm">
                    <i class="fa-solid fa-circle-exclamation text-rose-400"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif
            @if(session('info'))
                <div class="rounded-xl bg-sky-950/70 border border-sky-800/80 text-sky-300 text-xs px-4 py-3 text-left flex items-center gap-2.5 shadow-sm">
                    <i class="fa-solid fa-circle-info text-sky-400"></i>
                    <span>{{ session('info') }}</span>
                </div>
            @endif

            <div class="space-y-3 pt-1">
                <!-- STAFF BIOMETRIC SCAN OPTION -->
                <a href="{{ route('kiosk.scan') }}"
                   class="group block w-full corporate-card hover:bg-slate-900/95 rounded-2xl border border-slate-800 hover:border-slate-700 transition-all p-4.5 text-left relative overflow-hidden">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-indigo-950/80 border border-indigo-800/60 text-indigo-400 group-hover:text-indigo-300 flex items-center justify-center text-xl transition-all flex-shrink-0 shadow-sm">
                            <i class="fa-solid fa-camera"></i>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center gap-2">
                                <p class="font-bold text-white text-sm">Staff Biometric Scan</p>
                                <span class="px-2 py-0.5 rounded text-[10px] font-semibold uppercase bg-emerald-950/80 text-emerald-400 border border-emerald-800/60">Face ID</span>
                            </div>
                            <p class="text-xs text-slate-400 font-normal mt-0.5">Facial landmark recognition with EAR liveness verification.</p>
                        </div>
                        <i class="fa-solid fa-arrow-right text-slate-400 group-hover:text-white group-hover:translate-x-1 transition-all text-xs"></i>
                    </div>
                </a>

                <!-- ADMIN PASSCODE OPTION -->
                <a href="{{ route('kiosk.admin-login') }}"
                   class="group block w-full corporate-card hover:bg-slate-900/95 rounded-2xl border border-slate-800 hover:border-slate-700 transition-all p-4.5 text-left">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-slate-900 border border-slate-700/80 text-slate-300 flex items-center justify-center text-lg transition-colors flex-shrink-0 shadow-sm">
                            <i class="fa-solid fa-shield-halved text-slate-400"></i>
                        </div>
                        <div class="flex-1">
                            <p class="font-bold text-white text-sm">Manager / Admin Passcode</p>
                            <p class="text-xs text-slate-400 mt-0.5 font-normal">Authenticate using authorized administrative security credentials.</p>
                        </div>
                        <i class="fa-solid fa-arrow-right text-slate-400 group-hover:text-white group-hover:translate-x-1 transition-all text-xs"></i>
                    </div>
                </a>
            </div>

            <p class="text-[11px] text-slate-500 font-normal">Zaujati Laundry Operations &bull; Biometric Security &amp; Audit Compliance</p>
        </div>
    </main>
</body>
</html>