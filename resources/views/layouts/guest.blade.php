<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'LaundryStaff Pro') }} — Zaujati Laundry Portal</title>

        <!-- Google Fonts: Inter & JetBrains Mono -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">
        
        <!-- FontAwesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

        <!-- Dark Mode Persistence & Initialization -->
        <script>
            (function() {
                const savedTheme = localStorage.getItem('theme');
                if (savedTheme === 'dark' || (!savedTheme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
            })();

            window.toggleDarkMode = function() {
                const isDark = document.documentElement.classList.toggle('dark');
                localStorage.setItem('theme', isDark ? 'dark' : 'light');
                window.dispatchEvent(new CustomEvent('theme-changed', { detail: { isDark } }));
            };
        </script>

        <!-- Tailwind CSS CDN -->
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                darkMode: 'class',
                theme: {
                    extend: {
                        fontFamily: {
                            sans: ['Inter', '-apple-system', 'BlinkMacSystemFont', '"SF Pro Display"', '"SF Pro Text"', 'sans-serif'],
                            mono: ['"JetBrains Mono"', 'monospace'],
                        },
                        colors: {
                            zaujati: {
                                purple: '#4A154B',
                                pink: '#E11D74',
                                dark: '#2D0C2E',
                                light: '#FDF2F8',
                            }
                        }
                    }
                }
            }
        </script>

        <style>
            body { 
                font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'SF Pro Display', 'SF Pro Text', sans-serif;
                letter-spacing: -0.018em;
                -webkit-font-smoothing: antialiased;
                -moz-osx-font-smoothing: grayscale;
                text-rendering: optimizeLegibility;
            }
            h1, h2, h3, h4, h5, h6 {
                letter-spacing: -0.03em;
            }
            .font-mono-nums { font-family: 'JetBrains Mono', monospace; }

            /* Guest Dark Mode */
            html.dark body {
                background: linear-gradient(135deg, #070D18 0%, #0A1122 50%, #160A18 100%) !important;
                color: #F8FAFC !important;
            }
            html.dark .bg-white {
                background-color: #0F172A !important;
                border-color: #1E293B !important;
                color: #F8FAFC !important;
            }
            html.dark input[type="text"],
            html.dark input[type="email"],
            html.dark input[type="password"] {
                background-color: #070D18 !important;
                border-color: #334155 !important;
                color: #F8FAFC !important;
            }
            html.dark input::placeholder {
                color: #64748B !important;
            }
            html.dark .text-slate-900,
            html.dark .text-slate-800,
            html.dark .text-slate-700 {
                color: #F8FAFC !important;
            }
            html.dark .text-slate-600,
            html.dark .text-slate-500 {
                color: #94A3B8 !important;
            }
        </style>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-gradient-to-br from-slate-100 via-slate-50 to-purple-50/30 dark:bg-gradient-to-br dark:from-[#070D18] dark:via-[#0A1122] dark:to-[#160A18] text-slate-900 dark:text-slate-100 antialiased min-h-screen flex flex-col justify-between selection:bg-[#4A154B] selection:text-white">
        
        <!-- Top Minimal Navbar -->
        <header class="w-full py-4 px-6 flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <img src="{{ asset('images/zaujati-logo.png') }}" alt="Zaujati Laundry" class="w-8 h-8 rounded-xl object-contain bg-slate-950 p-1 shadow border border-slate-200 dark:border-slate-800">
                <span class="font-extrabold text-sm text-slate-900 dark:text-white tracking-tight">LaundryStaff <span class="text-xs px-1.5 py-0.5 rounded text-white font-black" style="background-color: #4A154B;">PRO</span></span>
            </div>
            <div class="flex items-center gap-3">
                <button type="button" 
                        onclick="window.toggleDarkMode()"
                        class="inline-flex items-center justify-center w-8 h-8 rounded-xl border border-slate-200/90 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-600 dark:text-amber-400 hover:bg-slate-100 dark:hover:bg-slate-700 transition shadow-2xs focus:outline-none"
                        title="Tukar Mod Malam / Siang">
                    <i class="fa-solid fa-moon text-xs dark:hidden"></i>
                    <i class="fa-solid fa-sun text-xs text-amber-400 hidden dark:inline"></i>
                </button>
                <div class="text-[11px] text-slate-400 font-medium hidden sm:block">
                    Zaujati Laundry Operations
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-1 flex items-center justify-center p-4 sm:p-6">
            {{ $slot }}
        </main>

        <!-- Bottom Simple Footer -->
        <footer class="py-4 text-center text-[11px] text-slate-400">
            <p>© {{ date('Y') }} Zaujati Laundry · LaundryStaff Pro Operations System</p>
        </footer>

    </body>
</html>
