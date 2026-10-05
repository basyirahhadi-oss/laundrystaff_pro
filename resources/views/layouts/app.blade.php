<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50 dark:bg-[#070D18]">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'LaundryStaff Pro') }} · Enterprise HRMS &amp; Operations</title>

        <!-- Google Fonts: Inter & Plus Jakarta Sans (Modern, neat, aesthetic typography) -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">

        <!-- Font Awesome 6 -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

        <!-- Dark Mode Persistence & Initialization (Instant Execution, Zero Flash) -->
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

        <!-- Tailwind CSS CDN Engine (Ensures instant 100% styling across all resolutions) -->
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                darkMode: 'class',
                theme: {
                    extend: {
                        fontFamily: {
                            sans: ['"Plus Jakarta Sans"', 'Inter', '-apple-system', 'BlinkMacSystemFont', '"Segoe UI"', 'Roboto', 'sans-serif'],
                            display: ['"Plus Jakarta Sans"', 'sans-serif'],
                        },
                        colors: {
                            portal: {
                                blue: '#2563eb',
                                cobalt: '#1d4ed8',
                                cyan: '#0284c7',
                                dark: '#0B1527',
                            },
                            zaujati: {
                                purple: '#4A154B',
                                pink: '#E11D74',
                                cyan: '#0284c7',
                                slate: '#0B1527',
                            }
                        },
                        boxShadow: {
                            'portal': '0 4px 20px -2px rgba(15, 23, 42, 0.05), 0 2px 6px -1px rgba(15, 23, 42, 0.02)',
                            'portal-hover': '0 10px 25px -3px rgba(15, 23, 42, 0.08), 0 4px 10px -2px rgba(15, 23, 42, 0.03)',
                        }
                    }
                }
            }
        </script>

        <!-- Scripts & Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <style>
            [x-cloak] { display: none !important; }
            body {
                font-family: 'Plus Jakarta Sans', 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
                letter-spacing: normal;
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
            .portal-card {
                background: rgba(255, 255, 255, 0.90);
                backdrop-filter: blur(16px);
                -webkit-backdrop-filter: blur(16px);
                border-radius: 20px;
                border: 1px solid rgba(226, 232, 240, 0.9);
                box-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.04), 0 2px 6px -1px rgba(15, 23, 42, 0.02);
                transition: transform 0.2s ease, box-shadow 0.2s ease;
            }
            html.dark .portal-card {
                background: rgba(11, 21, 39, 0.85);
                backdrop-filter: blur(16px);
                -webkit-backdrop-filter: blur(16px);
                border: 1px solid rgba(255, 255, 255, 0.1);
                box-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.45);
            }
            h1, h2, h3, h4, h5, h6 {
                letter-spacing: normal;
            }
            .font-mono-nums {
                font-family: inherit;
                font-variant-numeric: tabular-nums;
            }
            /* Custom Enterprise Scrollbars */
            ::-webkit-scrollbar {
                width: 6px;
                height: 6px;
            }
            ::-webkit-scrollbar-track {
                background: #f1f5f9;
            }
            ::-webkit-scrollbar-thumb {
                background: #cbd5e1;
                border-radius: 9999px;
            }
            ::-webkit-scrollbar-thumb:hover {
                background: #94a3b8;
            }
            .custom-sidebar-scroll::-webkit-scrollbar {
                width: 4px;
            }
            .custom-sidebar-scroll::-webkit-scrollbar-track {
                background: transparent;
            }
            .custom-sidebar-scroll::-webkit-scrollbar-thumb {
                background: #1e293b;
                border-radius: 9999px;
            }
            .custom-sidebar-scroll::-webkit-scrollbar-thumb:hover {
                background: #334155;
            }

            /* ========================================================================= */
            /* Standard Enterprise System Toast Notifications (Refined SaaS UI)         */
            /* ========================================================================= */
            .swal2-container.swal2-top-end {
                padding: 1rem !important;
            }

            .swal2-popup.swal2-toast {
                box-sizing: border-box !important;
                width: auto !important;
                max-width: 420px !important;
                padding: 12px 14px !important;
                background: #ffffff !important;
                border-radius: 12px !important;
                border: 1px solid #e2e8f0 !important;
                box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.08), 0 8px 10px -6px rgba(15, 23, 42, 0.04) !important;
                font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif !important;
                overflow: hidden !important;
            }

            /* Proportional, crisp icon (scaled down from 32px to clean 20px) */
            .swal2-popup.swal2-toast .swal2-icon {
                font-size: 10px !important; /* SweetAlert2 em-based sizing scales automatically */
                width: 2em !important;
                min-width: 2em !important;
                height: 2em !important;
                margin: 2px 10px 0 0 !important;
                border-width: 2px !important;
                align-self: flex-start !important;
                flex-shrink: 0 !important;
            }

            .swal2-popup.swal2-toast .swal2-icon.swal2-success {
                border-color: #10b981 !important;
                color: #10b981 !important;
            }
            .swal2-popup.swal2-toast .swal2-icon.swal2-success [class^='swal2-success-line'] {
                background-color: #10b981 !important;
            }
            .swal2-popup.swal2-toast .swal2-icon.swal2-success .swal2-success-ring {
                border-color: rgba(16, 185, 129, 0.25) !important;
            }

            .swal2-popup.swal2-toast .swal2-icon.swal2-error {
                border-color: #ef4444 !important;
                color: #ef4444 !important;
            }
            .swal2-popup.swal2-toast .swal2-icon.swal2-error [class^='swal2-x-mark-line'] {
                background-color: #ef4444 !important;
            }

            .swal2-popup.swal2-toast .swal2-icon.swal2-warning {
                border-color: #f59e0b !important;
                color: #f59e0b !important;
            }

            .swal2-popup.swal2-toast .swal2-icon.swal2-info {
                border-color: #0284c7 !important;
                color: #0284c7 !important;
            }

            /* Standard System Typography (Consistent Moderate 'Sederhana' 14px / 0.875rem) */
            .swal2-popup.swal2-toast,
            .swal2-popup.swal2-toast .swal2-title,
            .swal2-popup.swal2-toast .swal2-html-container,
            .swal2-popup.swal2-toast .swal2-content {
                font-size: 0.875rem !important; /* 14px - standard sederhana */
                font-weight: 500 !important;       /* Clean medium weight */
                line-height: 1.5 !important;       /* Comfortable standard line height */
                color: #1e293b !important;         /* Slate-800 crisp readable text */
                margin: 0 !important;
                padding: 0 !important;
                text-align: left !important;
                letter-spacing: normal !important;
            }

            .swal2-popup.swal2-toast .swal2-html-container {
                font-size: 0.875rem !important; /* 14px */
                font-weight: 400 !important;
                color: #475569 !important;         /* Slate-600 */
                margin-top: 3px !important;
            }

            /* Sleek Micro-Progress Bar */
            .swal2-popup.swal2-toast .swal2-timer-progress-bar-container {
                height: 3px !important;
            }
            .swal2-popup.swal2-toast .swal2-timer-progress-bar {
                height: 3px !important;
                background: #10b981 !important;
            }
            .swal2-popup.swal2-toast.swal2-icon-error .swal2-timer-progress-bar {
                background: #ef4444 !important;
            }
            .swal2-popup.swal2-toast.swal2-icon-warning .swal2-timer-progress-bar {
                background: #f59e0b !important;
            }
            .swal2-popup.swal2-toast.swal2-icon-info .swal2-timer-progress-bar {
                background: #0284c7 !important;
            }

            /* Close Button */
            .swal2-popup.swal2-toast .swal2-close {
                font-size: 16px !important;
                color: #94a3b8 !important;
                margin: -2px -2px 0 0 !important;
                padding: 2px !important;
                height: auto !important;
                width: auto !important;
                line-height: 1 !important;
                transition: color 0.15s ease !important;
            }
            .swal2-popup.swal2-toast .swal2-close:hover {
                color: #334155 !important;
            }

            /* Modal Popups (Standard moderate font size) */
            .swal2-popup:not(.swal2-toast) .swal2-title {
                font-size: 1.125rem !important; /* 18px moderate heading */
                font-weight: 700 !important;
                color: #0f172a !important;
            }
            .swal2-popup:not(.swal2-toast) .swal2-html-container {
                font-size: 0.875rem !important; /* 14px standard sederhana */
                line-height: 1.5 !important;
                color: #475569 !important;
            }
            .swal2-popup:not(.swal2-toast) .swal2-actions button {
                font-size: 0.875rem !important; /* 14px */
                font-weight: 500 !important;
                border-radius: 8px !important;
                padding: 8px 16px !important;
            }

            /* Standard Alert Banners Across System (Consistent 14px) */
            .alert, [role="alert"] {
                font-size: 0.875rem !important; /* 14px */
                line-height: 1.5 !important;
                font-weight: 500 !important;
            }

            /* ========================================================================= */
            /* Enterprise Night & Dark Mode Theme System                                 */
            /* ========================================================================= */
            html.dark {
                color-scheme: dark;
            }
            html.dark,
            html.dark body {
                background-color: #070D18 !important;
                color: #E2E8F0 !important;
            }
            html.dark .bg-slate-50 {
                background-color: #070D18 !important;
            }
            html.dark .bg-slate-100 {
                background-color: #0F172A !important;
            }
            html.dark .bg-white {
                background-color: #0B1324 !important;
                color: #E2E8F0 !important;
            }
            html.dark .border-slate-200,
            html.dark .border-slate-200\/80,
            html.dark .border-slate-200\/70,
            html.dark .border-slate-200\/60,
            html.dark .border-slate-200\/90,
            html.dark .border-slate-100 {
                border-color: #1E293B !important;
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
            html.dark .text-slate-400 {
                color: #64748B !important;
            }

            /* Header in Dark Mode */
            html.dark header {
                background-color: rgba(11, 19, 36, 0.95) !important;
                border-color: #1E293B !important;
            }

            /* Tables in Dark Mode */
            html.dark table {
                color: #E2E8F0 !important;
            }
            html.dark table thead th {
                background-color: #080E1A !important;
                color: #94A3B8 !important;
                border-color: #1E293B !important;
            }
            html.dark table tbody tr {
                border-color: #1E293B !important;
            }
            html.dark table tbody tr:hover {
                background-color: rgba(255, 255, 255, 0.02) !important;
            }
            html.dark table tbody td {
                color: #CBD5E1 !important;
                border-color: #1E293B !important;
            }

            /* Form Elements in Dark Mode */
            html.dark input[type="text"],
            html.dark input[type="email"],
            html.dark input[type="password"],
            html.dark input[type="number"],
            html.dark input[type="date"],
            html.dark input[type="datetime-local"],
            html.dark select,
            html.dark textarea {
                background-color: #070D18 !important;
                border-color: #334155 !important;
                color: #F8FAFC !important;
            }
            html.dark input::placeholder,
            html.dark textarea::placeholder {
                color: #64748B !important;
            }
            html.dark input:focus,
            html.dark select:focus,
            html.dark textarea:focus {
                border-color: #6366F1 !important;
                box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.2) !important;
            }

            /* Dropdowns & Cards in Dark Mode */
            html.dark [x-show="open"] > div,
            html.dark .dropdown-menu {
                background-color: #0B1324 !important;
                border-color: #1E293B !important;
            }

            /* Footer in Dark Mode */
            html.dark footer {
                background-color: #0B1324 !important;
                border-color: #1E293B !important;
                color: #64748B !important;
            }

            /* SweetAlert in Dark Mode */
            html.dark .swal2-popup.swal2-toast {
                background-color: #0B1324 !important;
                border-color: #334155 !important;
                box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.5) !important;
            }
            html.dark .swal2-popup.swal2-toast .swal2-title {
                color: #F8FAFC !important;
            }
            html.dark .swal2-popup.swal2-toast .swal2-html-container {
                color: #94A3B8 !important;
            }
            html.dark .swal2-popup:not(.swal2-toast) {
                background-color: #0B1324 !important;
                border: 1px solid #334155 !important;
                color: #F8FAFC !important;
            }
            html.dark .swal2-popup:not(.swal2-toast) .swal2-title {
                color: #F8FAFC !important;
            }
            html.dark .swal2-popup:not(.swal2-toast) .swal2-html-container {
                color: #94A3B8 !important;
            }

            /* Custom Dark Scrollbars */
            html.dark ::-webkit-scrollbar-track {
                background: #070D18;
            }
            html.dark ::-webkit-scrollbar-thumb {
                background: #1E293B;
            }
            html.dark ::-webkit-scrollbar-thumb:hover {
                background: #334155;
            }
        </style>
    </head>
    <body class="h-full bg-[#070b14] text-slate-800 dark:text-slate-100 antialiased selection:bg-pink-600 selection:text-white" x-data="{ sidebarOpen: false }">
        <div class="min-h-screen bg-[#070b14]/50 flex relative overflow-x-hidden">
            
            <!-- Highly Visible Aesthetic Modern Laundry Wallpaper Background -->
            <div class="fixed inset-0 pointer-events-none z-0 bg-cover bg-center bg-no-repeat opacity-45 dark:opacity-40 scale-105" 
                 style="background-image: url('{{ asset('images/laundry-modern-bg.jpg') }}'); background-attachment: fixed; filter: brightness(0.85) contrast(1.05);"></div>
            
            <!-- Atmospheric Layer Overlay -->
            <div class="fixed inset-0 pointer-events-none z-0 bg-gradient-to-b from-[#070b14]/85 via-[#070b14]/75 to-[#070b14]/90 dark:block hidden"></div>
            <div class="fixed inset-0 pointer-events-none z-0 bg-gradient-to-b from-white/80 via-slate-50/70 to-slate-100/85 dark:hidden"></div>

            <!-- Tech Grid & Dot Pattern Overlay -->
            <div class="fixed inset-0 pointer-events-none z-0 bg-tech-grid opacity-30"></div>
            <div class="fixed inset-0 pointer-events-none z-0 bg-tech-dots opacity-40"></div>

            <!-- Glowing Ambient Neon Orbs -->
            <div class="fixed -top-32 -left-32 w-96 h-96 rounded-full blur-[140px] pointer-events-none opacity-30 bg-[#E11D74]"></div>
            <div class="fixed top-1/2 -right-32 w-[450px] h-[450px] rounded-full blur-[150px] pointer-events-none opacity-25 bg-[#4A154B]"></div>
            <div class="fixed -bottom-32 left-1/3 w-[500px] h-[500px] rounded-full blur-[140px] pointer-events-none opacity-20 bg-[#0284c7]"></div>

            <!-- 1. Left Dark Navy Sidebar (Desktop Fixed + Mobile Slide-out Drawer) -->
            @include('layouts.sidebar')

            <!-- 2. Right Canvas Area (Offset by md:pl-64) -->
            <div class="md:pl-64 flex flex-col flex-1 min-w-0 min-h-screen relative z-10">
                
                <!-- Unified Single Top Bar (Page Header + User Profile) -->
                @include('layouts.navigation')

                <!-- Page Content Slot -->
                <main class="flex-1 pb-24 md:pb-8">
                    {{ $slot }}
                </main>

                <!-- Professional Enterprise Footer -->
                <footer class="hidden md:block bg-white dark:bg-[#0B1324] border-t border-slate-200/80 dark:border-slate-800 py-4 px-4 sm:px-6 lg:px-8 text-xs text-slate-500 dark:text-slate-400">
                    <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-2">
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/60">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                All Systems Operational
                            </span>
                            <span>·</span>
                            <span>LaundryStaff Pro Enterprise Edition (v2.4.0)</span>
                        </div>
                        <div class="flex items-center gap-4 text-slate-400">
                            <span>Zaujati Laundry Operations HQ</span>
                            <span>·</span>
                            <span>AES-256 Encrypted · SHA-256 Chained</span>
                        </div>
                    </div>
                </footer>
            </div>
        </div>

        <!-- Global SweetAlert2 Toast Handler for Flash Messages -->
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    showCloseButton: true,
                    timer: 5000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer);
                        toast.addEventListener('mouseleave', Swal.resumeTimer);
                    }
                });

                @if (session('success'))
                    Toast.fire({
                        icon: 'success',
                        title: @json(session('success'))
                    });
                @endif

                @if (session('error'))
                    Toast.fire({
                        icon: 'error',
                        title: @json(session('error'))
                    });
                @endif

                @if (session('warning'))
                    Toast.fire({
                        icon: 'warning',
                        title: @json(session('warning'))
                    });
                @endif

                @if (session('info'))
                    Toast.fire({
                        icon: 'info',
                        title: @json(session('info'))
                    });
                @endif

                @if (session('status'))
                    Toast.fire({
                        icon: 'info',
                        title: @json(session('status'))
                    });
                @endif

                @if ($errors->any())
                    Toast.fire({
                        icon: 'warning',
                        title: @json($errors->first())
                    });
                @endif
            });
        </script>
    </body>
</html>