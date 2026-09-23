@php
    $user = auth()->user();
    $isAdmin = $user && $user->role === 'admin';
@endphp

<!-- ========================================================================= -->
<!-- UNIFIED TOP HEADER BAR (Right Canvas Header - Rahmah SaaS Architecture)   -->
<!-- ========================================================================= -->
<header class="sticky top-0 z-30 bg-white/95 backdrop-blur-md border-b border-slate-200/80 shadow-2xs">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between min-h-[4.5rem] py-3 gap-4">
            
            <!-- 1. Left: Mobile Drawer Trigger + Page Header Title & Subtitle -->
            <div class="flex items-center gap-3 min-w-0 flex-1">
                <!-- Mobile Hamburger Toggle -->
                <button @click="sidebarOpen = true" 
                        type="button" 
                        class="md:hidden inline-flex items-center justify-center p-2 rounded-xl text-slate-500 hover:text-slate-900 hover:bg-slate-100 focus:outline-none transition shrink-0"
                        title="Open Menu">
                    <i class="fa-solid fa-bars text-base"></i>
                </button>

                <!-- Page Header (Injected dynamically from <x-slot name="header">) -->
                <div class="min-w-0 flex-1">
                    @if (isset($header))
                        {{ $header }}
                    @else
                        <div>
                            <span class="text-xs font-bold uppercase tracking-widest text-slate-400 block mb-0.5">Workspace</span>
                            <h1 class="text-xl font-bold text-slate-900 tracking-tight">LaundryStaff Pro</h1>
                            <p class="text-xs text-slate-500">Enterprise HRMS &amp; Operations</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- 2. Right: Global Quick Actions & User Profile (Rahmah Style) -->
            <div class="flex items-center gap-2.5 sm:gap-3 shrink-0">
                
                <!-- Quick Kiosk Terminal Action -->
                <a href="{{ route('kiosk.gateway') }}" 
                   target="_blank"
                   title="Open Biometric Kiosk Terminal in new tab"
                   class="hidden sm:inline-flex items-center gap-2 px-3 py-1.5 rounded-xl text-xs font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 border border-slate-200/70 transition active:scale-98">
                    <i class="fa-solid fa-camera text-slate-500 text-xs"></i>
                    <span>Kiosk</span>
                </a>

                <!-- User Profile Dropdown Pill (Rahmah Style) -->
                <x-dropdown align="right" width="56">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center gap-2.5 px-2.5 py-1.5 rounded-xl border border-slate-200/90 bg-white hover:bg-slate-50 text-slate-800 text-sm font-medium focus:outline-none transition shadow-2xs">
                            <div class="w-8 h-8 rounded-lg text-white flex items-center justify-center font-bold text-xs uppercase bg-[#0B1527] shadow-xs">
                                {{ substr($user->name ?? 'A', 0, 1) }}
                            </div>
                            <div class="text-left hidden sm:block">
                                <div class="font-semibold text-slate-900 leading-tight text-xs">{{ $user->name ?? 'User' }}</div>
                                <div class="text-[10px] text-slate-400 capitalize leading-tight">
                                    {{ $isAdmin ? 'Administrator' : 'Staff Member' }}
                                </div>
                            </div>
                            <i class="fa-solid fa-chevron-down text-[10px] text-slate-400 hidden sm:inline ml-0.5"></i>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="px-4 py-3 border-b border-slate-100 text-sm">
                            <p class="font-bold text-slate-900 text-sm">{{ $user->name }}</p>
                            <p class="text-slate-400 text-xs truncate">{{ $user->email }}</p>
                            <span class="inline-block mt-1.5 px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-slate-100 text-slate-600">
                                Role: {{ $user->role }}
                            </span>
                        </div>

                        <x-dropdown-link :href="route('profile.edit')" class="flex items-center gap-2.5 text-sm text-slate-700 hover:bg-slate-50 py-2.5">
                            <i class="fa-regular fa-user text-slate-400 text-xs"></i>
                            <span>{{ __('Profile Settings') }}</span>
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();"
                                    class="flex items-center gap-2.5 text-sm text-rose-600 hover:bg-rose-50 font-medium py-2.5">
                                <i class="fa-solid fa-arrow-right-from-bracket text-rose-400 text-xs"></i>
                                <span>{{ __('Sign Out') }}</span>
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

        </div>
    </div>
</header>

<!-- ========================================================================= -->
<!-- 3. MINIMALIST MOBILE BOTTOM NAVIGATION BAR (iOS / Mobile App Style)       -->
<!-- ========================================================================= -->
<div class="md:hidden fixed bottom-0 left-0 right-0 z-50 bg-white/95 backdrop-blur-md border-t border-slate-200/80 shadow-xs px-3 py-2 safe-area-pb">
    <div class="flex items-center justify-around max-w-sm mx-auto">
        @if ($isAdmin)
            {{-- Admin Bottom Bar --}}
            <a href="{{ route('admin.attendance.index') }}" 
               class="flex flex-col items-center gap-1 py-1 px-3 rounded-xl transition {{ request()->routeIs('admin.attendance.*') ? 'text-slate-900 font-bold' : 'text-slate-400 hover:text-slate-600' }}">
                <i class="fa-regular fa-clock text-base"></i>
                <span class="text-[10px] tracking-tight">Attendance</span>
            </a>

            <a href="{{ route('admin.leaves.index') }}" 
               class="flex flex-col items-center gap-1 py-1 px-3 rounded-xl transition {{ request()->routeIs('admin.leaves.*') ? 'text-slate-900 font-bold' : 'text-slate-400 hover:text-slate-600' }}">
                <i class="fa-regular fa-calendar-check text-base"></i>
                <span class="text-[10px] tracking-tight">Leaves</span>
            </a>

            <a href="{{ route('staff.index') }}" 
               class="flex flex-col items-center gap-1 py-1 px-3 rounded-xl transition {{ request()->routeIs('staff.index') || request()->routeIs('staff.edit') ? 'text-slate-900 font-bold' : 'text-slate-400 hover:text-slate-600' }}">
                <i class="fa-solid fa-users text-base"></i>
                <span class="text-[10px] tracking-tight">Staff</span>
            </a>

            <a href="{{ route('profile.edit') }}" 
               class="flex flex-col items-center gap-1 py-1 px-3 rounded-xl transition {{ request()->routeIs('profile.edit') ? 'text-slate-900 font-bold' : 'text-slate-400 hover:text-slate-600' }}">
                <i class="fa-regular fa-user text-base"></i>
                <span class="text-[10px] tracking-tight">Profile</span>
            </a>
        @else
            {{-- Staff Bottom Bar --}}
            <a href="{{ route('staff.dashboard') }}" 
               class="flex flex-col items-center gap-1 py-1 px-3 rounded-xl transition {{ request()->routeIs('staff.dashboard') ? 'text-indigo-600 font-bold' : 'text-slate-400 hover:text-slate-600' }}">
                <i class="fa-solid fa-house text-base"></i>
                <span class="text-[10px] font-medium tracking-tight">Home</span>
            </a>

            <a href="{{ route('staff.leaves.index') }}" 
               class="flex flex-col items-center gap-1 py-1 px-3 rounded-xl transition {{ request()->routeIs('staff.leaves.*') ? 'text-indigo-600 font-bold' : 'text-slate-400 hover:text-slate-600' }}">
                <i class="fa-regular fa-calendar-days text-base"></i>
                <span class="text-[10px] font-medium tracking-tight">Leaves</span>
            </a>

            <a href="{{ route('staff.analytics.index') }}" 
               class="flex flex-col items-center gap-1 py-1 px-3 rounded-xl transition {{ request()->routeIs('staff.analytics.*') ? 'text-indigo-600 font-bold' : 'text-slate-400 hover:text-slate-600' }}">
                <i class="fa-solid fa-chart-line text-base"></i>
                <span class="text-[10px] font-medium tracking-tight">Analytics</span>
            </a>

            <a href="{{ route('profile.edit') }}" 
               class="flex flex-col items-center gap-1 py-1 px-3 rounded-xl transition {{ request()->routeIs('profile.edit') ? 'text-indigo-600 font-bold' : 'text-slate-400 hover:text-slate-600' }}">
                <i class="fa-regular fa-user text-base"></i>
                <span class="text-[10px] font-medium tracking-tight">Profile</span>
            </a>
        @endif
    </div>
</div>
