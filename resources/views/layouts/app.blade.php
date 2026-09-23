<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'LaundryStaff Pro') }} · Enterprise HRMS &amp; Operations</title>

        <!-- Google Fonts: Inter (Standard, clean, readable modern font) -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

        <!-- Font Awesome 6 -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

        <!-- Tailwind CSS CDN Engine (Ensures instant 100% styling across all resolutions) -->
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        fontFamily: {
                            sans: ['Inter', '-apple-system', 'BlinkMacSystemFont', '"Segoe UI"', 'Roboto', '"Helvetica Neue"', 'Arial', 'sans-serif'],
                        },
                        colors: {
                            zaujati: {
                                purple: '#4A154B',
                                pink: '#E11D74',
                                cyan: '#0284c7',
                                slate: '#0B1527',
                            }
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
                font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
                letter-spacing: normal;
                -webkit-font-smoothing: antialiased;
                -moz-osx-font-smoothing: grayscale;
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
        </style>
    </head>
    <body class="h-full bg-slate-50 text-slate-800 antialiased selection:bg-indigo-500 selection:text-white" x-data="{ sidebarOpen: false }">
        <div class="min-h-screen bg-slate-50 flex">
            
            <!-- 1. Left Dark Navy Sidebar (Desktop Fixed + Mobile Slide-out Drawer) -->
            @include('layouts.sidebar')

            <!-- 2. Right Canvas Area (Offset by md:pl-64) -->
            <div class="md:pl-64 flex flex-col flex-1 min-w-0 min-h-screen">
                
                <!-- Unified Single Top Bar (Page Header + User Profile) -->
                @include('layouts.navigation')

                <!-- Page Content Slot -->
                <main class="flex-1 pb-24 md:pb-8">
                    {{ $slot }}
                </main>

                <!-- Professional Enterprise Footer -->
                <footer class="hidden md:block bg-white border-t border-slate-200/80 py-4 px-4 sm:px-6 lg:px-8 text-xs text-slate-500">
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
                    timer: 4000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer);
                        toast.addEventListener('mouseleave', Swal.resumeTimer);
                    }
                });

                @if (session('success'))
                    Toast.fire({
                        icon: 'success',
                        title: "{{ session('success') }}"
                    });
                @endif

                @if (session('error'))
                    Toast.fire({
                        icon: 'error',
                        title: "{{ session('error') }}"
                    });
                @endif

                @if (session('warning'))
                    Toast.fire({
                        icon: 'warning',
                        title: "{{ session('warning') }}"
                    });
                @endif

                @if (session('info'))
                    Toast.fire({
                        icon: 'info',
                        title: "{{ session('info') }}"
                    });
                @endif
            });
        </script>
    </body>
</html>