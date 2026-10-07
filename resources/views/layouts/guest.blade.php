<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'LaundryStaff Pro') }} — Enterprise Portal</title>

        <!-- Google Fonts: Inter & Plus Jakarta Sans -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
        
        <!-- FontAwesome 6 -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

        <!-- Tailwind CSS CDN -->
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                darkMode: 'class',
                theme: {
                    extend: {
                        fontFamily: {
                            sans: ['"Plus Jakarta Sans"', 'Inter', '-apple-system', 'BlinkMacSystemFont', 'sans-serif'],
                            inter: ['Inter', 'sans-serif'],
                            mono: ['"JetBrains Mono"', 'monospace'],
                        },
                        colors: {
                            brand: {
                                50: '#eef2ff',
                                100: '#e0e7ff',
                                500: '#6366f1',
                                600: '#4f46e5',
                                700: '#4338ca',
                                800: '#3730a3',
                                900: '#312e81',
                            },
                            zaujati: {
                                purple: '#4A154B',
                                pink: '#E11D74',
                                dark: '#0b1120',
                            }
                        }
                    }
                }
            }
        </script>

        <style>
            body { 
                font-family: 'Plus Jakarta Sans', 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
                letter-spacing: -0.011em;
                -webkit-font-smoothing: antialiased;
                -moz-osx-font-smoothing: grayscale;
            }
            .font-mono-nums { font-family: 'JetBrains Mono', monospace; }
            
            /* Professional Enterprise Card Surface */
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

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen flex flex-col justify-between text-slate-100 antialiased selection:bg-indigo-600 selection:text-white bg-[#090d16] relative overflow-x-hidden">
        
        <!-- ========================================== -->
        <!-- HIGH-FIDELITY MODERN LAUNDROMAT WALLPAPER  -->
        <!-- ========================================== -->
        <!-- Highly Visible Aesthetic Modern Laundry Wallpaper -->
        <div class="fixed inset-0 z-0 bg-cover bg-center pointer-events-none scale-105 transition-transform duration-1000" 
             style="background-image: url('{{ asset('images/laundry-modern-bg.jpg') }}'); filter: brightness(0.85) contrast(1.05);"></div>
        
        <!-- Multi-layer Vignette Overlay (Keeps wallpaper clearly visible) -->
        <div class="fixed inset-0 z-0 bg-gradient-to-b from-[#070b14]/75 via-[#070b14]/55 to-[#070b14]/85 pointer-events-none"></div>
        <div class="fixed inset-0 z-0 bg-gradient-to-r from-[#070b14]/70 via-transparent to-[#070b14]/70 pointer-events-none"></div>

        <!-- ========================================== -->
        <!-- FOREGROUND CONTENT                         -->
        <!-- ========================================== -->
        <div class="relative z-10 flex flex-col min-h-screen justify-between">
            
            <!-- Professional Executive Header -->
            <header class="w-full py-3.5 px-6 sm:px-12 flex items-center justify-between border-b border-slate-800/80 bg-slate-950/60 backdrop-blur-md">
                <div class="flex items-center gap-3.5">
                    <!-- Clean Corporate Brand Badge -->
                    <div class="w-10 h-10 rounded-xl bg-slate-900 border border-slate-700/80 p-1 flex items-center justify-center shadow-sm">
                        <img src="{{ asset('images/zaujati-logo.png') }}" alt="Zaujati" class="w-full h-full object-contain">
                    </div>

                    <div>
                        <div class="flex items-center gap-2">
                            <span class="font-bold text-sm sm:text-base tracking-tight text-white">LaundryStaff</span>
                            <span class="px-2 py-0.5 rounded text-[10px] font-semibold tracking-wider uppercase text-slate-300 bg-slate-800 border border-slate-700">
                                Enterprise
                            </span>
                        </div>
                        <p class="text-[11px] text-slate-400 font-normal">Zaujati Laundry Operations HQ</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <!-- Operational Status Indicator -->
                    <div class="hidden sm:flex items-center gap-2 px-3 py-1.5 rounded-lg bg-slate-900/90 border border-slate-800 text-slate-300 text-xs">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        <span class="text-[11px] font-medium text-slate-300">System Operational</span>
                    </div>

                    <!-- Kiosk Gateway Link -->
                    <a href="{{ route('kiosk.gateway') }}" 
                       class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg text-xs font-medium bg-slate-800/90 hover:bg-slate-700/90 text-slate-200 border border-slate-700/80 transition shadow-sm">
                        <i class="fa-solid fa-camera text-slate-400 text-xs"></i>
                        <span>Face Kiosk</span>
                        <i class="fa-solid fa-arrow-up-right-from-square text-[10px] text-slate-400"></i>
                    </a>
                </div>
            </header>

            <!-- Main Content Container -->
            <main class="flex-1 flex items-center justify-center p-4 sm:p-8 my-auto relative">
                {{ $slot }}
            </main>

            <!-- Formal Corporate Footer -->
            <footer class="py-4 px-6 text-center text-xs text-slate-500 border-t border-slate-800/80 bg-slate-950/60 backdrop-blur-md">
                <div class="max-w-4xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-2.5">
                    <p class="text-[11px] text-slate-400 font-normal">
                        &copy; {{ date('Y') }} <span class="text-slate-300 font-medium">Zaujati Laundry Operations</span>. All rights reserved.
                    </p>
                    <div class="flex items-center gap-4 text-[11px] text-slate-400">
                        <span class="inline-flex items-center gap-1.5">
                            <i class="fa-solid fa-shield-halved text-slate-400 text-[10px]"></i>
                            <span>Enterprise Access Control</span>
                        </span>
                        <span class="text-slate-600">&bull;</span>
                        <span class="inline-flex items-center gap-1.5">
                            <i class="fa-solid fa-lock text-slate-400 text-[10px]"></i>
                            <span>TLS 1.3 Encrypted</span>
                        </span>
                    </div>
                </div>
            </footer>
        </div>

    </body>
</html>
