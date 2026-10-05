@php
    $user = auth()->user();
    $isAdmin = $user && $user->role === 'admin';
@endphp

<!-- ========================================================================= -->
<!-- 1. MOBILE SLIDE-OUT DRAWER OVERLAY (Alpine.js controlled)                  -->
<!-- ========================================================================= -->
<div x-show="sidebarOpen" 
     class="relative z-50 md:hidden" 
     role="dialog" 
     aria-modal="true"
     x-cloak>
    
    <!-- Backdrop Blur -->
    <div x-show="sidebarOpen"
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="sidebarOpen = false"
         class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm"></div>

    <div class="fixed inset-0 flex">
        <div x-show="sidebarOpen"
             x-transition:enter="transition ease-in-out duration-300 transform"
             x-transition:enter-start="-translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="transition ease-in-out duration-300 transform"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="-translate-x-full"
             class="relative mr-16 flex w-full max-w-xs flex-1">
            
            <!-- Mobile Close Button -->
            <div class="absolute top-0 right-0 -mr-12 pt-4">
                <button @click="sidebarOpen = false" 
                        type="button" 
                        class="ml-1 flex h-10 w-10 items-center justify-center rounded-xl bg-white/10 text-white hover:bg-white/20 focus:outline-none transition">
                    <span class="sr-only">Close sidebar</span>
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <!-- Mobile Drawer Sidebar Body -->
            <div class="flex flex-col flex-1 bg-[#0B1527] border-r border-slate-800/80 px-4 py-6 overflow-y-auto">
                
                <!-- Brand Header -->
                <div class="flex items-center gap-3 px-2 pb-6 border-b border-slate-800/80">
                    <div class="w-10 h-10 rounded-2xl bg-cyan-500/10 border border-cyan-500/30 p-1 flex items-center justify-center shrink-0 shadow-sm text-cyan-400">
                        <i class="fa-solid fa-soap text-lg"></i>
                    </div>
                    <div class="flex flex-col min-w-0">
                        <div class="flex items-center gap-1.5">
                            <span class="font-extrabold text-base text-white tracking-tight">LaundryStaff</span>
                            <span class="px-1.5 py-0.5 rounded text-[10px] font-bold uppercase text-white tracking-wide" style="background-color: #4A154B;">PRO</span>
                        </div>
                        <span class="text-[11px] text-slate-400 font-medium">
                            {{ $isAdmin ? 'Admin Panel' : 'Staff Portal' }}
                        </span>
                    </div>
                </div>

                <!-- Navigation List -->
                <nav class="flex-1 space-y-6 mt-6">
                    @include('layouts.sidebar-links')
                </nav>

                <!-- Help / Support Card (Mobile) -->
                <div class="mt-6 p-4 rounded-2xl bg-gradient-to-br from-slate-900/90 to-[#10223f] border border-blue-500/20 text-white relative overflow-hidden">
                    <div class="flex items-start gap-2">
                        <span class="text-blue-400 text-xs">✦</span>
                        <div>
                            <p class="text-[11px] font-semibold text-slate-300">Need help?</p>
                            <a href="{{ route('kiosk.gateway') }}" target="_blank" class="text-xs font-bold text-white hover:text-blue-300 flex items-center gap-1.5 mt-0.5">
                                <span>Contact Support</span>
                                <i class="fa-solid fa-arrow-up-right-from-square text-[9px] text-blue-400"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Mobile User Footer -->
                <div class="pt-4 mt-6 border-t border-slate-800/80">
                    <div class="flex items-center gap-3 px-2">
                        @if($user && $user->profile_picture_url)
                            <img src="{{ $user->profile_picture_url }}" 
                                 alt="{{ $user->name }}" 
                                 class="w-9 h-9 rounded-xl object-cover border border-white/10 shrink-0 shadow-xs">
                        @else
                            <div class="w-9 h-9 rounded-xl bg-blue-600 text-white flex items-center justify-center font-bold text-xs uppercase shrink-0">
                                {{ substr($user->name ?? 'U', 0, 2) }}
                            </div>
                        @endif
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-semibold text-white truncate">{{ $user->name ?? 'User' }}</p>
                            <p class="text-[10px] text-slate-400 capitalize">{{ $user->role ?? 'staff' }}</p>
                        </div>
                    </div>
                    <!-- Mobile Night Mode Toggle -->
                    <button type="button" 
                            onclick="window.toggleDarkMode()"
                            class="mt-3 w-full flex items-center justify-between px-3 py-2 rounded-xl bg-white/5 border border-white/5 text-xs text-slate-300 hover:text-amber-400 transition">
                        <span class="flex items-center gap-2">
                            <i class="fa-solid fa-moon dark:hidden text-slate-400"></i>
                            <i class="fa-solid fa-sun hidden dark:inline text-amber-400"></i>
                            <span>Theme</span>
                        </span>
                        <span class="text-[10px] font-bold uppercase px-1.5 py-0.5 rounded bg-white/10 dark:hidden text-slate-300">Dark</span>
                        <span class="text-[10px] font-bold uppercase px-1.5 py-0.5 rounded bg-amber-500/20 text-amber-400 hidden dark:inline">Light</span>
                    </button>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- ========================================================================= -->
<!-- 2. DESKTOP FIXED SIDEBAR (W-64 Dark Navy #0B1527)                         -->
<!-- ========================================================================= -->
<aside class="hidden md:flex md:w-64 md:flex-col md:fixed md:inset-y-0 z-40 bg-[#0B1527] border-r border-slate-800/80 select-none shadow-xl">
    
    <!-- Brand Header (Neat & Modern like SISWI HUB) -->
    <div class="flex items-center gap-3 px-6 h-20 border-b border-slate-800/80 shrink-0">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group focus:outline-none w-full">
            <div class="w-10 h-10 rounded-2xl bg-cyan-500/15 border border-cyan-400/30 p-1.5 flex items-center justify-center shrink-0 shadow-sm text-cyan-400 group-hover:scale-105 transition-transform">
                <i class="fa-solid fa-soap text-lg"></i>
            </div>
            <div class="flex flex-col min-w-0">
                <div class="flex items-center gap-1.5">
                    <span class="font-extrabold text-base text-white tracking-tight">LaundryStaff</span>
                    <span class="px-1.5 py-0.5 rounded text-[10px] font-bold uppercase text-white tracking-wide" style="background-color: #4A154B;">PRO</span>
                </div>
                <span class="text-[11px] text-slate-400 font-medium">
                    {{ $isAdmin ? 'Admin Panel' : 'Staff Portal' }}
                </span>
            </div>
        </a>
    </div>

    <!-- Navigation Scrollable Area -->
    <div class="flex-1 flex flex-col justify-between overflow-y-auto px-4 py-6 custom-sidebar-scroll">
        <nav class="space-y-6">
            @include('layouts.sidebar-links')
        </nav>

        <!-- Bottom Support Widget & User Section (Exact SISWI HUB style) -->
        <div class="pt-4 mt-6 border-t border-slate-800/80 space-y-3">
            
            <!-- Need Help? Contact Support Card -->
            <div class="p-3.5 rounded-2xl bg-gradient-to-br from-slate-900/90 to-[#10223f] border border-blue-500/20 text-white relative overflow-hidden shadow-md">
                <div class="flex items-start gap-2">
                    <span class="text-blue-400 text-xs">✦</span>
                    <div>
                        <p class="text-[10px] uppercase font-bold tracking-wider text-slate-400">Need help?</p>
                        <a href="mailto:support@zaujati.com" class="text-xs font-bold text-white hover:text-blue-300 transition flex items-center gap-1.5 mt-0.5">
                            <span>Contact Support</span>
                            <i class="fa-solid fa-arrow-up-right-from-square text-[9px] text-blue-400"></i>
                        </a>
                    </div>
                </div>
                <div class="flex items-center gap-2 mt-2.5 pt-2 border-t border-slate-800/80 text-[10px] text-slate-400">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span>Support Desk Online</span>
                </div>
            </div>

            <!-- Ledger Status & Theme Toggle -->
            <div class="flex items-center justify-between px-2 pt-1">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span class="text-[11px] font-medium text-slate-400">Ledger Verified</span>
                </div>
                <button type="button" 
                        onclick="window.toggleDarkMode()"
                        class="flex items-center gap-1.5 px-2 py-1 rounded-lg text-[10px] font-semibold text-slate-400 hover:text-amber-400 hover:bg-white/5 transition"
                        title="Toggle Dark / Light Mode">
                    <i class="fa-solid fa-moon dark:hidden text-[10px]"></i>
                    <i class="fa-solid fa-sun hidden dark:inline text-amber-400 text-[10px]"></i>
                    <span class="dark:hidden">Dark Mode</span>
                    <span class="hidden dark:inline text-amber-400">Light Mode</span>
                </button>
            </div>

            <!-- User Shortcut Pill -->
            <div class="flex items-center gap-3 p-2 rounded-xl bg-white/5 border border-white/5">
                @if($user && $user->profile_picture_url)
                    <img src="{{ $user->profile_picture_url }}" 
                         alt="{{ $user->name }}" 
                         class="w-8 h-8 rounded-lg object-cover border border-white/10 shrink-0 shadow-xs">
                @else
                    <div class="w-8 h-8 rounded-lg bg-blue-600 text-white flex items-center justify-center font-bold text-xs uppercase shrink-0 shadow-xs">
                        {{ substr($user->name ?? 'U', 0, 1) }}
                    </div>
                @endif
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-semibold text-white truncate leading-tight">{{ $user->name ?? 'User' }}</p>
                    <p class="text-[10px] text-slate-400 capitalize leading-tight mt-0.5">{{ $user->role ?? 'staff' }}</p>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" 
                            title="Sign Out"
                            class="p-1.5 rounded-lg text-slate-400 hover:text-rose-400 hover:bg-rose-500/10 transition">
                        <i class="fa-solid fa-arrow-right-from-bracket text-xs"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</aside>
