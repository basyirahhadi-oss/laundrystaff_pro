@php
    $user = auth()->user();
    $isAdmin = $user && $user->role === 'admin';
@endphp

<!-- CATEGORY 1: MAIN -->
<div>
    <p class="px-3 text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-2">
        Main
    </p>
    <div class="space-y-1">
        @if ($isAdmin)
            {{-- Attendance --}}
            @php $isActive = request()->routeIs('admin.attendance.*'); @endphp
            <a href="{{ route('admin.attendance.index') }}"
               class="group flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all {{ $isActive ? 'bg-white/10 text-white font-semibold shadow-xs border border-white/5' : 'text-slate-400 hover:text-white hover:bg-white/5 font-medium border border-transparent' }}">
                <i class="fa-regular fa-clock w-5 text-center text-sm {{ $isActive ? 'text-white' : 'text-slate-400 group-hover:text-white' }}"></i>
                <span>Attendance</span>
            </a>

            {{-- Leaves --}}
            @php $isActive = request()->routeIs('admin.leaves.*'); @endphp
            <a href="{{ route('admin.leaves.index') }}"
               class="group flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all {{ $isActive ? 'bg-white/10 text-white font-semibold shadow-xs border border-white/5' : 'text-slate-400 hover:text-white hover:bg-white/5 font-medium border border-transparent' }}">
                <i class="fa-regular fa-calendar-check w-5 text-center text-sm {{ $isActive ? 'text-white' : 'text-slate-400 group-hover:text-white' }}"></i>
                <span>Leaves</span>
            </a>
        @else
            {{-- Staff Dashboard --}}
            @php $isActive = request()->routeIs('staff.dashboard'); @endphp
            <a href="{{ route('staff.dashboard') }}"
               class="group flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all {{ $isActive ? 'bg-white/10 text-white font-semibold shadow-xs border border-white/5' : 'text-slate-400 hover:text-white hover:bg-white/5 font-medium border border-transparent' }}">
                <i class="fa-regular fa-clock w-5 text-center text-sm {{ $isActive ? 'text-white' : 'text-slate-400 group-hover:text-white' }}"></i>
                <span>Dashboard</span>
            </a>

            {{-- Staff Leaves --}}
            @php $isActive = request()->routeIs('staff.leaves.index'); @endphp
            <a href="{{ route('staff.leaves.index') }}"
               class="group flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all {{ $isActive ? 'bg-white/10 text-white font-semibold shadow-xs border border-white/5' : 'text-slate-400 hover:text-white hover:bg-white/5 font-medium border border-transparent' }}">
                <i class="fa-regular fa-calendar-days w-5 text-center text-sm {{ $isActive ? 'text-white' : 'text-slate-400 group-hover:text-white' }}"></i>
                <span>My Leaves</span>
            </a>

            {{-- Apply Leave --}}
            @php $isActive = request()->routeIs('staff.leaves.create'); @endphp
            <a href="{{ route('staff.leaves.create') }}"
               class="group flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all {{ $isActive ? 'bg-white/10 text-white font-semibold shadow-xs border border-white/5' : 'text-slate-400 hover:text-white hover:bg-white/5 font-medium border border-transparent' }}">
                <i class="fa-regular fa-paper-plane w-5 text-center text-sm {{ $isActive ? 'text-white' : 'text-slate-400 group-hover:text-white' }}"></i>
                <span>Apply Leave</span>
            </a>

            {{-- My Payroll --}}
            @php $isActive = request()->routeIs('staff.payroll.index'); @endphp
            <a href="{{ route('staff.payroll.index') }}"
               class="group flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all {{ $isActive ? 'bg-white/10 text-white font-semibold shadow-xs border border-white/5' : 'text-slate-400 hover:text-white hover:bg-white/5 font-medium border border-transparent' }}">
                <i class="fa-solid fa-file-invoice-dollar w-5 text-center text-sm {{ $isActive ? 'text-white' : 'text-slate-400 group-hover:text-white' }}"></i>
                <span>My Payroll</span>
            </a>
        @endif
    </div>
</div>

<!-- CATEGORY 2: MANAGEMENT (Admin only) -->
@if ($isAdmin)
<div>
    <p class="px-3 text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-2">
        Management
    </p>
    <div class="space-y-1">
        {{-- Staff Directory --}}
        @php $isActive = request()->routeIs('staff.index') || request()->routeIs('staff.edit'); @endphp
        <a href="{{ route('staff.index') }}"
           class="group flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all {{ $isActive ? 'bg-white/10 text-white font-semibold shadow-xs border border-white/5' : 'text-slate-400 hover:text-white hover:bg-white/5 font-medium border border-transparent' }}">
            <i class="fa-solid fa-users w-5 text-center text-sm {{ $isActive ? 'text-white' : 'text-slate-400 group-hover:text-white' }}"></i>
            <span>Staff Directory</span>
        </a>

        {{-- Payroll --}}
        @php $isActive = request()->routeIs('staff.payroll.*'); @endphp
        <a href="{{ route('staff.payroll.history') }}"
           class="group flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all {{ $isActive ? 'bg-white/10 text-white font-semibold shadow-xs border border-white/5' : 'text-slate-400 hover:text-white hover:bg-white/5 font-medium border border-transparent' }}">
            <i class="fa-solid fa-file-invoice-dollar w-5 text-center text-sm {{ $isActive ? 'text-white' : 'text-slate-400 group-hover:text-white' }}"></i>
            <span>Payroll</span>
        </a>
    </div>
</div>
@endif

<!-- CATEGORY 3: ANALYTICS & AUDIT -->
<div>
    <p class="px-3 text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-2">
        {{ $isAdmin ? 'Analytics & Audit' : 'Analytics' }}
    </p>
    <div class="space-y-1">
        @if ($isAdmin)
            {{-- Reports --}}
            @php $isActive = request()->routeIs('admin.reports.*'); @endphp
            <a href="{{ route('admin.reports.index') }}"
               class="group flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all {{ $isActive ? 'bg-white/10 text-white font-semibold shadow-xs border border-white/5' : 'text-slate-400 hover:text-white hover:bg-white/5 font-medium border border-transparent' }}">
                <i class="fa-solid fa-chart-pie w-5 text-center text-sm {{ $isActive ? 'text-white' : 'text-slate-400 group-hover:text-white' }}"></i>
                <span>Reports</span>
            </a>

            {{-- Security Audit --}}
            @php $isActive = request()->routeIs('admin.security.*'); @endphp
            <a href="{{ route('admin.security.index') }}"
               class="group flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all {{ $isActive ? 'bg-white/10 text-white font-semibold shadow-xs border border-white/5' : 'text-slate-400 hover:text-white hover:bg-white/5 font-medium border border-transparent' }}">
                <i class="fa-solid fa-shield-halved w-5 text-center text-sm {{ $isActive ? 'text-[#E11D74]' : 'text-slate-400 group-hover:text-white' }}"></i>
                <span>Security Audit</span>
            </a>
        @else
            {{-- Performance Analytics --}}
            @php $isActive = request()->routeIs('staff.analytics.*'); @endphp
            <a href="{{ route('staff.analytics.index') }}"
               class="group flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all {{ $isActive ? 'bg-white/10 text-white font-semibold shadow-xs border border-white/5' : 'text-slate-400 hover:text-white hover:bg-white/5 font-medium border border-transparent' }}">
                <i class="fa-solid fa-chart-line w-5 text-center text-sm {{ $isActive ? 'text-white' : 'text-slate-400 group-hover:text-white' }}"></i>
                <span>Performance</span>
            </a>
        @endif
    </div>
</div>

<!-- CATEGORY 4: SYSTEM -->
<div>
    <p class="px-3 text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-2">
        System
    </p>
    <div class="space-y-1">
        {{-- Kiosk Terminal --}}
        <a href="{{ route('kiosk.gateway') }}" 
           target="_blank"
           class="group flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm text-slate-400 hover:text-white hover:bg-white/5 font-medium transition-all border border-transparent">
            <i class="fa-solid fa-camera w-5 text-center text-sm text-slate-400 group-hover:text-white"></i>
            <span>Kiosk Terminal</span>
            <i class="fa-solid fa-arrow-up-right-from-square text-[10px] text-slate-500 ml-auto group-hover:text-slate-300"></i>
        </a>

        {{-- Profile Settings --}}
        @php $isActive = request()->routeIs('profile.edit'); @endphp
        <a href="{{ route('profile.edit') }}"
           class="group flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all {{ $isActive ? 'bg-white/10 text-white font-semibold shadow-xs border border-white/5' : 'text-slate-400 hover:text-white hover:bg-white/5 font-medium border border-transparent' }}">
            <i class="fa-regular fa-user w-5 text-center text-sm {{ $isActive ? 'text-white' : 'text-slate-400 group-hover:text-white' }}"></i>
            <span>Profile Settings</span>
        </a>

        {{-- Sign Out --}}
        <form method="POST" action="{{ route('logout') }}" class="w-full">
            @csrf
            <button type="submit"
                    class="w-full group flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm text-slate-400 hover:text-rose-400 hover:bg-rose-500/10 font-medium transition-all border border-transparent text-left">
                <i class="fa-solid fa-arrow-right-from-bracket w-5 text-center text-sm text-slate-400 group-hover:text-rose-400"></i>
                <span>Sign Out</span>
            </button>
        </form>
    </div>
</div>
