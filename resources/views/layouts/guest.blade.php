<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'LaundryStaff Pro') }} — Zaujati Laundry Portal</title>

        <!-- Google Fonts: Inter, Plus Jakarta Sans & JetBrains Mono -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@500;600;700;800&family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">
        
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
                            zaujati: {
                                purple: '#4A154B',
                                pink: '#E11D74',
                                dark: '#1e0520',
                                light: '#FDF2F8',
                                cyan: '#00E5FF',
                            }
                        },
                        animation: {
                            'float-slow': 'float 6s ease-in-out infinite',
                            'float-delayed': 'float 7s ease-in-out 2s infinite',
                            'float-reverse': 'floatRev 8s ease-in-out infinite',
                            'pulse-slow': 'pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                            'spin-very-slow': 'spin 20s linear infinite',
                            'shimmer': 'shimmer 2.5s infinite linear',
                        },
                        keyframes: {
                            float: {
                                '0%, 100%': { transform: 'translateY(0px) rotate(0deg)' },
                                '50%': { transform: 'translateY(-12px) rotate(2deg)' },
                            },
                            floatRev: {
                                '0%, 100%': { transform: 'translateY(0px) rotate(0deg)' },
                                '50%': { transform: 'translateY(14px) rotate(-2deg)' },
                            },
                            shimmer: {
                                '0%': { backgroundPosition: '-200% 0' },
                                '100%': { backgroundPosition: '200% 0' },
                            }
                        }
                    }
                }
            }
        </script>

        <style>
            body { 
                font-family: 'Plus Jakarta Sans', 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
                letter-spacing: -0.015em;
                -webkit-font-smoothing: antialiased;
                -moz-osx-font-smoothing: grayscale;
            }
            .font-mono-nums { font-family: 'JetBrains Mono', monospace; }
            
            /* Glassmorphism effects */
            .glass-card {
                background: rgba(15, 23, 42, 0.78);
                backdrop-filter: blur(20px);
                -webkit-backdrop-filter: blur(20px);
                border: 1px solid rgba(255, 255, 255, 0.12);
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.6), 
                            0 0 45px -10px rgba(225, 29, 116, 0.25),
                            inset 0 1px 1px 0 rgba(255, 255, 255, 0.15);
            }
            
            .glow-pink {
                box-shadow: 0 0 35px -5px rgba(225, 29, 116, 0.5);
            }

            .glow-cyan {
                box-shadow: 0 0 35px -5px rgba(0, 229, 255, 0.4);
            }

            /* Tech grid pattern */
            .bg-tech-grid {
                background-image: 
                    linear-gradient(to right, rgba(255, 255, 255, 0.05) 1px, transparent 1px),
                    linear-gradient(to bottom, rgba(255, 255, 255, 0.05) 1px, transparent 1px);
                background-size: 32px 32px;
            }

            /* Tech dots pattern */
            .bg-tech-dots {
                background-image: radial-gradient(rgba(225, 29, 116, 0.2) 1px, transparent 1px);
                background-size: 24px 24px;
            }
        </style>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen flex flex-col justify-between text-slate-100 antialiased selection:bg-pink-600 selection:text-white bg-[#070b14] relative overflow-x-hidden">
        
        <!-- ========================================== -->
        <!-- HIGH-FIDELITY BACKGROUND WALLPAPER & FX   -->
        <!-- ========================================== -->
        <!-- Photo Realistic Modern Laundromat Wallpaper -->
        <div class="fixed inset-0 z-0 bg-cover bg-center pointer-events-none scale-105 transition-transform duration-1000" 
             style="background-image: url('{{ asset('images/laundry-modern-bg.jpg') }}'); filter: brightness(0.85) contrast(1.05);"></div>
        
        <!-- Multi-layer Vignette & Cyberpunk Ambience Overlay -->
        <div class="fixed inset-0 z-0 bg-gradient-to-b from-[#070b14]/75 via-[#070b14]/60 to-[#070b14]/90 pointer-events-none"></div>
        <div class="fixed inset-0 z-0 bg-gradient-to-r from-[#070b14]/80 via-transparent to-[#070b14]/80 pointer-events-none"></div>
        
        <!-- Tech Dot Matrix & Grid Pattern Overlay (Corak & Bentuk) -->
        <div class="fixed inset-0 z-0 bg-tech-grid opacity-40 pointer-events-none"></div>
        <div class="fixed inset-0 z-0 bg-tech-dots opacity-50 pointer-events-none"></div>

        <!-- Atmospheric Glowing Neon Orbs -->
        <div class="fixed -top-32 -left-32 w-96 h-96 rounded-full blur-[120px] pointer-events-none opacity-50" style="background: radial-gradient(circle, #E11D74 0%, transparent 70%);"></div>
        <div class="fixed top-1/3 -right-32 w-[450px] h-[450px] rounded-full blur-[140px] pointer-events-none opacity-45" style="background: radial-gradient(circle, #4A154B 0%, #00E5FF 100%);"></div>
        <div class="fixed -bottom-32 left-1/3 w-[500px] h-[500px] rounded-full blur-[130px] pointer-events-none opacity-35" style="background: radial-gradient(circle, #0284c7 0%, transparent 70%);"></div>

        <!-- Floating Geometric Decorative Shapes (Bentuk Geometrik) -->
        <div class="fixed top-24 left-[10%] w-16 h-16 rounded-2xl border border-cyan-400/30 bg-cyan-500/10 backdrop-blur-md animate-float-slow hidden md:block pointer-events-none"></div>
        <div class="fixed bottom-32 left-[12%] w-12 h-12 rounded-full border border-pink-500/30 bg-pink-500/10 backdrop-blur-md animate-float-delayed hidden md:block pointer-events-none"></div>
        <div class="fixed top-36 right-[12%] w-20 h-20 rounded-3xl border border-purple-500/30 bg-purple-500/10 backdrop-blur-md animate-float-reverse hidden md:block pointer-events-none"></div>
        <div class="fixed bottom-24 right-[10%] w-14 h-14 rounded-2xl rotate-45 border border-cyan-400/25 bg-cyan-400/10 backdrop-blur-md animate-float-slow hidden md:block pointer-events-none"></div>

        <!-- ========================================== -->
        <!-- FOREGROUND CONTENT                         -->
        <!-- ========================================== -->
        <div class="relative z-10 flex flex-col min-h-screen justify-between">
            
            <!-- Modern Header with Brand & Live Status -->
            <header class="w-full py-4 px-6 sm:px-10 flex items-center justify-between border-b border-white/10 bg-slate-950/40 backdrop-blur-md">
                <div class="flex items-center gap-3.5">
                    <!-- Brand Icon with Gradient Ring -->
                    <div class="relative group">
                        <div class="w-10 h-10 rounded-2xl p-[1.5px] bg-gradient-to-tr from-[#E11D74] via-[#4A154B] to-[#00E5FF] shadow-lg shadow-[#E11D74]/20">
                            <div class="w-full h-full bg-slate-950 rounded-[14px] flex items-center justify-center">
                                <i class="fa-solid fa-soap text-transparent bg-clip-text bg-gradient-to-r from-[#E11D74] to-[#00E5FF] text-base"></i>
                            </div>
                        </div>
                        <span class="absolute -top-1 -right-1 flex h-2.5 w-2.5">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                        </span>
                    </div>

                    <div>
                        <div class="flex items-center gap-2">
                            <span class="font-extrabold text-base tracking-tight text-white">LaundryStaff</span>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-black tracking-wider uppercase text-white shadow-sm" style="background: linear-gradient(135deg, #4A154B 0%, #E11D74 100%);">
                                PRO
                            </span>
                        </div>
                        <p class="text-[11px] text-slate-300 font-medium">Zaujati Laundry Operations HQ</p>
                    </div>
                </div>

                <div class="flex items-center gap-3 text-xs">
                    <!-- Live Operational Status Badge -->
                    <div class="hidden sm:flex items-center gap-2 px-3 py-1.5 rounded-full bg-slate-900/80 border border-slate-700/60 shadow-xs text-slate-300">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        <span class="text-[11px] font-medium font-mono-nums">Terminal Ready</span>
                    </div>

                    <!-- Kiosk Quick Access Link -->
                    <a href="{{ route('kiosk.gateway') }}" 
                       class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-xl font-bold bg-white/10 hover:bg-white/20 text-white border border-white/15 transition shadow-sm hover:scale-[1.02] active:scale-[0.98]">
                        <i class="fa-solid fa-camera text-cyan-400 text-xs"></i>
                        <span class="hidden sm:inline">Face Kiosk</span>
                        <i class="fa-solid fa-arrow-up-right-from-square text-[10px] opacity-70"></i>
                    </a>
                </div>
            </header>

            <!-- Main Content Container -->
            <main class="flex-1 flex items-center justify-center p-4 sm:p-8 my-auto relative">
                {{ $slot }}
            </main>

            <!-- Clean Modern Footer -->
            <footer class="py-4 px-6 text-center text-xs text-slate-400/90 border-t border-white/5 bg-slate-950/40 backdrop-blur-md">
                <div class="max-w-4xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-2">
                    <p class="text-[11px] font-medium">
                        &copy; {{ date('Y') }} <span class="text-white font-semibold">LaundryStaff PRO</span> &bull; Zaujati Laundry Management Ecosystem
                    </p>
                    <div class="flex items-center gap-4 text-[11px]">
                        <span class="inline-flex items-center gap-1.5 text-slate-400">
                            <i class="fa-solid fa-shield-halved text-emerald-400 text-[10px]"></i>
                            <span>SHA-256 Vault Verified</span>
                        </span>
                        <span class="inline-flex items-center gap-1.5 text-slate-400">
                            <i class="fa-solid fa-bolt text-amber-400 text-[10px]"></i>
                            <span>Cloud V2.4</span>
                        </span>
                    </div>
                </div>
            </footer>
        </div>

    </body>
</html>
