<x-app-layout>
    <x-slot name="header">
        <div>
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block mb-0.5">
                Staff Portal
            </span>
            <h1 class="text-xl font-bold text-slate-900 dark:text-white tracking-tight">
                Operations Dashboard
            </h1>
        </div>
    </x-slot>

    <div class="py-6 sm:py-8 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- ================================================================= -->
            <!-- 1. WELCOME HERO BANNER (Exact SISWI HUB Curved Blue Gradient Card)  -->
            <!-- ================================================================= -->
            <div class="relative overflow-hidden rounded-[24px] shadow-lg shadow-blue-500/10 text-white"
                 style="background: linear-gradient(135deg, #1d4ed8 0%, #2563eb 55%, #0284c7 100%);">
                
                <!-- Fluid Laundry Ripple / Bubble Texture Overlay -->
                <div class="absolute inset-0 opacity-20 mix-blend-overlay pointer-events-none bg-cover bg-center"
                     style="background-image: url('{{ asset('images/laundry-wave-banner.jpg') }}');"></div>
                
                <!-- Soft Glow Accents -->
                <div class="absolute -right-10 -bottom-10 w-72 h-72 rounded-full bg-cyan-400/20 blur-3xl pointer-events-none"></div>
                <div class="absolute -left-10 -top-10 w-60 h-60 rounded-full bg-blue-400/20 blur-2xl pointer-events-none"></div>

                <div class="relative z-10 p-6 sm:p-8 flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div class="space-y-1.5 max-w-2xl">
                        <h2 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight leading-tight">
                            Welcome Back! {{ strtoupper(auth()->user()->name) }}
                        </h2>
                        <p class="text-xs sm:text-sm text-blue-100/90 font-medium flex flex-wrap items-center gap-2">
                            <span>Zaujati Laundry Operations HQ</span>
                            <span>•</span>
                            <span>ID: {{ auth()->user()->staff_id ?? ('STF' . str_pad(auth()->user()->id, 3, '0', STR_PAD_LEFT)) }}</span>
                            <span>•</span>
                            <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full bg-white/15 text-white text-[11px] font-semibold backdrop-blur-xs">
                                <span class="w-1.5 h-1.5 rounded-full {{ $todayAttendance ? ($todayAttendance->clock_out_time ? 'bg-slate-300' : 'bg-emerald-400 animate-pulse') : 'bg-amber-300' }}"></span>
                                {{ !$todayAttendance ? 'Shift Ready' : (!$todayAttendance->clock_out_time ? 'Working In Progress' : 'Shift Completed') }}
                            </span>
                        </p>
                    </div>

                    <!-- 3D Smart Laundry Washer Illustration & Floor Status -->
                    <div class="flex items-center gap-4 shrink-0">
                        <div class="relative group">
                            <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-2xl p-1 bg-white/20 backdrop-blur-md border border-white/30 shadow-xl overflow-hidden group-hover:scale-105 transition-transform duration-300">
                                <img src="{{ asset('images/laundry-3d-washer.jpg') }}" alt="Laundry Operations" class="w-full h-full object-cover rounded-xl">
                            </div>
                            <span class="absolute -top-1 -right-1 flex h-3.5 w-3.5">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-cyan-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-3.5 w-3.5 bg-cyan-300 border-2 border-blue-600"></span>
                            </span>
                        </div>

                        <div class="hidden sm:flex flex-col gap-2">
                            <div class="bg-white/10 backdrop-blur-md px-3.5 py-1.5 rounded-xl border border-white/20 text-xs">
                                <div class="text-[10px] font-bold text-blue-200 uppercase tracking-wider">Floor Operations</div>
                                <div class="font-bold text-white text-xs flex items-center gap-1.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                    <span>Zaujati Hub Active</span>
                                </div>
                            </div>
                            <div class="bg-white/10 backdrop-blur-md px-3.5 py-1.5 rounded-xl border border-white/20 text-xs text-white font-mono-nums font-semibold">
                                <i class="fa-regular fa-clock text-[10px] text-blue-200 mr-1"></i>
                                {{ now()->format('h:i A · d M Y') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ================================================================= -->
            <!-- 2. FOUR METRIC / KPI STAT CARDS (Exact SISWI HUB 4-Card Row Style)  -->
            <!-- ================================================================= -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                
                {{-- CARD 1: TOTAL SHIFTS (Purple Pastel Squircle) --}}
                <div class="portal-card p-5 flex items-center justify-between">
                    <div>
                        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block mb-1">
                            TOTAL SHIFTS
                        </span>
                        <div class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">
                            {{ $monthlyShifts ?? 0 }}
                        </div>
                    </div>
                    <div class="w-12 h-12 rounded-[16px] bg-[#f5f3ff] dark:bg-purple-950/50 text-[#7c3aed] dark:text-purple-300 flex items-center justify-center text-lg shrink-0">
                        <i class="fa-solid fa-list-check"></i>
                    </div>
                </div>

                {{-- CARD 2: PROCESSING (Amber Pastel Squircle) --}}
                <div class="portal-card p-5 flex items-center justify-between">
                    <div>
                        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block mb-1">
                            PROCESSING
                        </span>
                        <div class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">
                            {{ !$todayAttendance ? '0' : (!$todayAttendance->clock_out_time ? '1' : '0') }}
                        </div>
                    </div>
                    <div class="w-12 h-12 rounded-[16px] bg-[#fffbeb] dark:bg-amber-950/50 text-[#d97706] dark:text-amber-300 flex items-center justify-center text-lg shrink-0">
                        <i class="fa-solid fa-hourglass-half"></i>
                    </div>
                </div>

                {{-- CARD 3: COMPLETED (Green Pastel Squircle) --}}
                <div class="portal-card p-5 flex items-center justify-between">
                    <div>
                        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block mb-1">
                            COMPLETED
                        </span>
                        <div class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">
                            {{ $todayAttendance && $todayAttendance->clock_out_time ? '1' : ($monthlyShifts > 0 ? max(0, $monthlyShifts - (!$todayAttendance ? 0 : (!$todayAttendance->clock_out_time ? 1 : 0))) : '0') }}
                        </div>
                    </div>
                    <div class="w-12 h-12 rounded-[16px] bg-[#ecfdf5] dark:bg-emerald-950/50 text-[#059669] dark:text-emerald-300 flex items-center justify-center text-lg shrink-0">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                </div>

                {{-- CARD 4: HOURS / RECORDED (Rose Pastel Squircle) --}}
                <div class="portal-card p-5 flex items-center justify-between">
                    <div>
                        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block mb-1">
                            WORKED HOURS
                        </span>
                        <div class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">
                            {{ $monthlyHours ?? 0 }} <span class="text-xs font-semibold text-slate-400">hrs</span>
                        </div>
                    </div>
                    <div class="w-12 h-12 rounded-[16px] bg-[#fff1f2] dark:bg-rose-950/50 text-[#e11d48] dark:text-rose-300 flex items-center justify-center text-lg shrink-0">
                        <i class="fa-regular fa-folder-open"></i>
                    </div>
                </div>

            </div>

            <!-- ================================================================= -->
            <!-- 3. TWO-COLUMN MAIN CONTENT (Exact SISWI HUB 2-Card Layout)        -->
            <!-- ================================================================= -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-stretch">
                
                <!-- ------------------------------------------------------------- -->
                <!-- LEFT CARD: RECENT SHIFT LOGS (Matches 'Recent Complaints')    -->
                <!-- ------------------------------------------------------------- -->
                <div class="portal-card p-6 flex flex-col justify-between">
                    <div>
                        <!-- Header with Title + (+ New / + Apply Leave) Pill -->
                        <div class="flex items-center justify-between pb-4 border-b border-slate-100 dark:border-slate-800">
                            <div class="flex items-center gap-2.5">
                                <i class="fa-regular fa-clock text-blue-600 text-base"></i>
                                <h3 class="text-base font-bold text-slate-900 dark:text-white">Recent Shift Activity</h3>
                            </div>
                            <a href="{{ route('staff.leaves.create') }}" 
                               class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-bold text-sky-700 bg-sky-100 hover:bg-sky-200 dark:bg-sky-950/70 dark:text-sky-300 transition">
                                <span>+ New</span>
                            </a>
                        </div>

                        <!-- Shift List Items with SISWI HUB Status Badges -->
                        <div class="divide-y divide-slate-100 dark:divide-slate-800">
                            @forelse($recentAttendances->take(3) as $att)
                                <div class="py-4 flex items-center justify-between gap-4">
                                    <div class="min-w-0">
                                        <p class="font-bold text-slate-900 dark:text-white text-sm truncate">
                                            {{ $att->clock_out_time ? 'Regular Daily Shift' : 'Active Laundry Floor Shift' }}
                                        </p>
                                        <p class="text-xs text-slate-400 mt-0.5">
                                            {{ $att->date->format('d M Y') }} · {{ $att->clock_in_time?->format('h:i a') ?? '—' }}
                                        </p>
                                    </div>
                                    <div class="shrink-0">
                                        @if(!$att->clock_out_time)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-[#fef9c3] text-[#854d0e] dark:bg-yellow-950/60 dark:text-yellow-300">
                                                In_progress
                                            </span>
                                        @elseif($att->status === 'present')
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-[#dcfce7] text-[#15803d] dark:bg-emerald-950/60 dark:text-emerald-300">
                                                Completed
                                            </span>
                                        @elseif($att->status === 'late')
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-[#ffedd5] text-[#9a3412] dark:bg-amber-950/60 dark:text-amber-300">
                                                Late
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-[#e0f2fe] text-[#0369a1] dark:bg-sky-950/60 dark:text-sky-300">
                                                Processing
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="py-8 text-center text-slate-400">
                                    <p class="text-sm font-medium">No shifts recorded yet this month.</p>
                                    <p class="text-xs mt-1">Clock in on the right to start your shift.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Footer link to view full logs -->
                    <div class="pt-4 border-t border-slate-100 dark:border-slate-800 flex justify-between items-center text-xs text-slate-400">
                        <span>Punctuality Score: <strong class="text-slate-700 dark:text-slate-200">{{ $punctualityRate ?? 100 }}%</strong></span>
                        <a href="#full-history" class="text-blue-600 hover:text-blue-700 font-semibold flex items-center gap-1">
                            <span>View Full History</span>
                            <i class="fa-solid fa-chevron-down text-[10px]"></i>
                        </a>
                    </div>
                </div>

                <!-- ------------------------------------------------------------- -->
                <!-- RIGHT CARD: ACTION REQUIRED (Matches 'Action Required')        -->
                <!-- ------------------------------------------------------------- -->
                <div class="portal-card p-6 flex flex-col justify-between">
                    <div>
                        <!-- Header with Warning Icon + View All Pill -->
                        <div class="flex items-center justify-between pb-4 border-b border-slate-100 dark:border-slate-800">
                            <div class="flex items-center gap-2.5">
                                <i class="fa-solid fa-triangle-exclamation text-amber-500 text-base"></i>
                                <h3 class="text-base font-bold text-slate-900 dark:text-white">Action Required</h3>
                            </div>
                            <a href="{{ route('staff.leaves.index') }}" 
                               class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold border border-sky-400 text-sky-600 hover:bg-sky-50 dark:hover:bg-slate-800 transition">
                                <span>View All</span>
                            </a>
                        </div>

                        <!-- Action Items -->
                        <div class="divide-y divide-slate-100 dark:divide-slate-800">
                            
                            {{-- ACTION ITEM 1: DAILY SHIFT CLOCK IN / OUT --}}
                            <div class="py-5 flex items-center justify-between gap-4">
                                <div class="min-w-0">
                                    @if(!$todayAttendance)
                                        <p class="font-bold text-slate-900 dark:text-white text-sm">Record shift check-in</p>
                                        <p class="text-xs text-slate-400 mt-0.5">Today: {{ now()->format('d M Y') }} • Entry Pending</p>
                                    @elseif(!$todayAttendance->clock_out_time)
                                        <p class="font-bold text-slate-900 dark:text-white text-sm">Active shift in progress</p>
                                        <p class="text-xs text-slate-400 mt-0.5">Clocked in at {{ $todayAttendance->clock_in_time->format('h:i A') }} • End shift when done</p>
                                    @else
                                        <p class="font-bold text-slate-900 dark:text-white text-sm">Shift completed for today</p>
                                        <p class="text-xs text-slate-400 mt-0.5">{{ $todayAttendance->worked_hours }} hrs recorded and ledger-verified</p>
                                    @endif
                                </div>
                                
                                <div class="shrink-0">
                                    @if(!$todayAttendance)
                                        <form action="{{ route('staff.clock-in') }}" method="POST">
                                            @csrf
                                            <button type="submit" 
                                                    class="inline-flex items-center justify-center px-5 py-2 rounded-full text-xs font-bold text-white shadow-sm transition active:scale-95"
                                                    style="background: #0284c7; hover:background: #0369a1;">
                                                <span>Clock In</span>
                                            </button>
                                        </form>
                                    @elseif(!$todayAttendance->clock_out_time)
                                        <form action="{{ route('staff.clock-out') }}" method="POST">
                                            @csrf
                                            <button type="submit" 
                                                    class="inline-flex items-center justify-center px-5 py-2 rounded-full text-xs font-bold text-white bg-slate-900 hover:bg-black transition active:scale-95 shadow-sm">
                                                <span>Clock Out</span>
                                            </button>
                                        </form>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-bold text-emerald-700 bg-emerald-100 dark:bg-emerald-950/60 dark:text-emerald-300">
                                            <i class="fa-solid fa-circle-check text-xs"></i>
                                            <span>Verified</span>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            {{-- ACTION ITEM 2: LEAVE STATUS / APPLICATION --}}
                            <div class="py-5 flex items-center justify-between gap-4">
                                <div class="min-w-0">
                                    <p class="font-bold text-slate-900 dark:text-white text-sm">Apply for leave or review status</p>
                                    <p class="text-xs text-slate-400 mt-0.5">
                                        {{ $pendingLeaves > 0 ? $pendingLeaves . ' applications pending manager review' : 'No pending requests · Entitlement available' }}
                                    </p>
                                </div>
                                <div class="shrink-0">
                                    <a href="{{ route('staff.leaves.create') }}" 
                                       class="inline-flex items-center justify-center px-4 py-2 rounded-full text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 transition active:scale-95 shadow-sm">
                                        <span>Apply Now</span>
                                    </a>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- Kiosk Quick Access Banner inside Action Card -->
                    <div class="mt-4 p-3 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-800 flex items-center justify-between text-xs">
                        <div class="flex items-center gap-2 text-slate-600 dark:text-slate-300">
                            <i class="fa-solid fa-camera text-sky-500"></i>
                            <span class="font-medium">Using Shop Floor Kiosk?</span>
                        </div>
                        <a href="{{ route('kiosk.gateway') }}" target="_blank" class="font-bold text-sky-600 hover:underline">
                            Open Kiosk <i class="fa-solid fa-arrow-up-right-from-square text-[10px] ml-1"></i>
                        </a>
                    </div>
                </div>

            </div>

            <!-- ================================================================= -->
            <!-- 4. DETAILED ATTENDANCE HISTORY TABLE (Neat & Clean Section)        -->
            <!-- ================================================================= -->
            <div id="full-history" class="portal-card overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                    <div>
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white tracking-tight">Verified Shift Logs</h3>
                        <p class="text-xs text-slate-400 mt-0.5">Official daily biometric and attendance log history</p>
                    </div>
                    <span class="text-xs font-medium text-slate-400">Last 10 Records</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-100 dark:divide-slate-800 text-xs text-left">
                        <thead class="bg-slate-50/70 dark:bg-slate-800/40 text-slate-400 font-bold uppercase text-[10px] tracking-wider">
                            <tr>
                                <th class="px-6 py-3.5">Shift Date</th>
                                <th class="px-6 py-3.5">Clock In</th>
                                <th class="px-6 py-3.5">Clock Out</th>
                                <th class="px-6 py-3.5">Duration</th>
                                <th class="px-6 py-3.5">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-700 dark:text-slate-300">
                            @forelse ($recentAttendances as $row)
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/40 transition-colors">
                                    <td class="px-6 py-3.5 font-semibold text-slate-900 dark:text-white">
                                        {{ $row->date->format('d M Y') }}
                                        <span class="text-[11px] text-slate-400 block font-normal">{{ $row->date->format('l') }}</span>
                                    </td>
                                    <td class="px-6 py-3.5 font-medium">
                                        {{ $row->clock_in_time?->format('h:i A') ?? '—' }}
                                    </td>
                                    <td class="px-6 py-3.5 font-medium">
                                        {{ $row->clock_out_time?->format('h:i A') ?? '—' }}
                                    </td>
                                    <td class="px-6 py-3.5 font-bold text-slate-900 dark:text-white">
                                        {{ $row->worked_hours ? $row->worked_hours . ' hrs' : '—' }}
                                    </td>
                                    <td class="px-6 py-3.5">
                                        @if(!$row->clock_out_time)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-[#fef9c3] text-[#854d0e]">
                                                In_progress
                                            </span>
                                        @elseif($row->status === 'present')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-[#dcfce7] text-[#15803d]">
                                                Completed
                                            </span>
                                        @elseif($row->status === 'late')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-[#ffedd5] text-[#9a3412]">
                                                Late
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-[#e0f2fe] text-[#0369a1]">
                                                Processing
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-slate-400">
                                        No attendance records logged yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- ================================================================= -->
            <!-- 5. CLEAN FOOTER NOTICE (Matching Reference Design)                -->
            <!-- ================================================================= -->
            <div class="pt-6 pb-2 text-center text-xs text-slate-400">
                <p>© {{ date('Y') }} LaundryStaff PRO · Zaujati Laundry Operations HQ · All Rights Reserved</p>
            </div>

        </div>
    </div>

    @include('partials.flash-alerts')
</x-app-layout>
