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
                    <div class="w-10 h-10 rounded-xl bg-slate-900 border border-slate-700/60 p-1 flex items-center justify-center shrink-0 shadow-xs">
                        <img src="{{ asset('images/zaujati-logo.png') }}" 
                             alt="Zaujati Laundry" 
                             class="w-full h-full object-contain">
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

                <!-- Navigation List (Shared partial/loop) -->
                <nav class="flex-1 space-y-6 mt-6">
                    @include('layouts.sidebar-links')
                </nav>

                <!-- Mobile User Footer -->
                <div class="pt-4 mt-6 border-t border-slate-800/80">
                    <div class="flex items-center gap-3 px-2">
                        <div class="w-9 h-9 rounded-xl bg-white/10 text-white flex items-center justify-center font-bold text-xs uppercase shrink-0">
                            {{ substr($user->name ?? 'U', 0, 2) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-semibold text-white truncate">{{ $user->name ?? 'User' }}</p>
                            <p class="text-[10px] text-slate-400 capitalize">{{ $user->role ?? 'staff' }}</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- ========================================================================= -->
<!-- 2. DESKTOP FIXED SIDEBAR (W-64 Dark Navy #0B1527)                         -->
<!-- ========================================================================= -->
<aside class="hidden md:flex md:w-64 md:flex-col md:fixed md:inset-y-0 z-40 bg-[#0B1527] border-r border-slate-800/80 select-none">
    
    <!-- Brand Header -->
    <div class="flex items-center gap-3 px-6 h-18 border-b border-slate-800/80 shrink-0">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group focus:outline-none w-full">
            <div class="w-10 h-10 rounded-xl bg-slate-900/90 border border-slate-700/60 p-1.5 flex items-center justify-center shrink-0 shadow-sm group-hover:border-slate-500 transition-colors">
                <img src="{{ asset('images/zaujati-logo.png') }}" 
                     alt="Zaujati Laundry" 
                     class="w-full h-full object-contain">
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

        <!-- Bottom System Status & User Shortcut -->
        <div class="pt-4 mt-6 border-t border-slate-800/80 space-y-3">
            <div class="flex items-center justify-between px-2">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span class="text-[11px] font-medium text-slate-400">Ledger Verified</span>
                </div>
                <span class="text-[10px] font-mono text-slate-500 font-medium">v2.4.0</span>
            </div>

            <div class="flex items-center gap-3 p-2 rounded-xl bg-white/5 border border-white/5">
                <div class="w-8 h-8 rounded-lg bg-[#4A154B] text-white flex items-center justify-center font-bold text-xs uppercase shrink-0 shadow-xs">
                    {{ substr($user->name ?? 'U', 0, 1) }}
                </div>
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
