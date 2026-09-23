<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <span class="text-xs font-bold uppercase tracking-widest text-slate-400 block mb-1">
                    Executive Analytics
                </span>
                <h1 class="text-xl font-bold text-slate-900 tracking-tight">
                    Workforce Reports
                </h1>
                <p class="text-xs sm:text-sm font-medium text-slate-500 mt-0.5">
                    Monthly attendance metrics, absence distributions, and operational HR intelligence.
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-6 sm:py-8 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- 1. CLEAN 4 KPI STAT CARDS (Uniform & Uncluttered) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                {{-- CARD 1: ATTENDANCE RATE --}}
                <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs">
                    <div class="flex items-center justify-between mb-2.5">
                        <span class="text-xs font-semibold text-slate-500">Attendance Rate</span>
                        <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs">
                            <i class="fa-solid fa-percent"></i>
                        </div>
                    </div>
                    <div class="flex items-baseline gap-2 font-mono-nums">
                        <span class="text-2xl font-bold text-slate-900">{{ $attendanceRate }}%</span>
                        <span class="text-xs font-medium text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-md">On Target</span>
                    </div>
                    <p class="text-xs text-slate-400 mt-1">Operational presence this month</p>
                </div>

                {{-- CARD 2: APPROVED LEAVES --}}
                <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs">
                    <div class="flex items-center justify-between mb-2.5">
                        <span class="text-xs font-semibold text-slate-500">Approved Leave</span>
                        <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center text-xs">
                            <i class="fa-regular fa-calendar-xmark"></i>
                        </div>
                    </div>
                    <div class="flex items-baseline gap-2 font-mono-nums">
                        <span class="text-2xl font-bold text-slate-900">{{ $leaveDaysThisMonth }}</span>
                        <span class="text-xs text-slate-500 font-sans">Days Out</span>
                    </div>
                    <p class="text-xs text-slate-400 mt-1">Authorized absence this month</p>
                </div>

                {{-- CARD 3: PAYROLL PROCESSED --}}
                <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs">
                    <div class="flex items-center justify-between mb-2.5">
                        <span class="text-xs font-semibold text-slate-500">Payroll Total</span>
                        <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center text-xs">
                            <i class="fa-solid fa-file-invoice-dollar"></i>
                        </div>
                    </div>
                    <div class="flex items-baseline gap-1 font-mono-nums">
                        <span class="text-xs font-bold text-slate-400">RM</span>
                        <span class="text-xl font-bold text-slate-900">{{ number_format($payrollProcessed, 2) }}</span>
                    </div>
                    <p class="text-xs text-slate-400 mt-1">Total wage commitment</p>
                </div>

                {{-- CARD 4: LATE ARRIVALS --}}
                <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs">
                    <div class="flex items-center justify-between mb-2.5">
                        <span class="text-xs font-semibold text-slate-500">Late Check-Ins</span>
                        <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center text-xs">
                            <i class="fa-regular fa-clock"></i>
                        </div>
                    </div>
                    <div class="flex items-baseline gap-2 font-mono-nums">
                        <span class="text-2xl font-bold text-slate-900">{{ $lateArrivals }}</span>
                        <span class="text-xs text-amber-700 bg-amber-50 px-2 py-0.5 rounded-md font-sans">Incidents</span>
                    </div>
                    <p class="text-xs text-slate-400 mt-1">Recorded shift tardiness</p>
                </div>
            </div>

            <!-- 2. LEAVE DISTRIBUTION & ATTENDANCE BREAKDOWN -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                {{-- LEAVE BREAKDOWN --}}
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6">
                    <div class="flex items-center justify-between pb-3.5 mb-4 border-b border-slate-100">
                        <div>
                            <h3 class="text-sm font-bold text-slate-900">Leave Distribution</h3>
                            <p class="text-xs text-slate-400">Monthly breakdown by leave type</p>
                        </div>
                        <span class="text-xs font-medium text-slate-400">Current Month</span>
                    </div>

                    <div class="space-y-4">
                        @foreach(['annual' => 'Annual Leave', 'mc' => 'Medical Leave (MC)', 'emergency' => 'Emergency Leave', 'unpaid' => 'Unpaid Leave'] as $key => $label)
                            @php $row = $leaveTypeDistribution[$key]; @endphp
                            <div>
                                <div class="flex justify-between text-xs font-medium text-slate-700 mb-1.5">
                                    <span>{{ $label }}</span>
                                    <span class="font-mono-nums text-slate-900 font-semibold">{{ $row['count'] }} requests ({{ $row['percent'] }}%)</span>
                                </div>
                                <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
                                    <div class="h-full bg-indigo-600 rounded-full transition-all" style="width: {{ $row['percent'] }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- ATTENDANCE OVERVIEW --}}
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6">
                    <div class="flex items-center justify-between pb-3.5 mb-4 border-b border-slate-100">
                        <div>
                            <h3 class="text-sm font-bold text-slate-900">Attendance Status Breakdown</h3>
                            <p class="text-xs text-slate-400">Distribution of all logged shifts</p>
                        </div>
                        <span class="text-xs font-medium text-slate-400">All Shifts</span>
                    </div>

                    <div class="space-y-4">
                        @foreach(['present' => ['Present On Time', 'bg-emerald-500'], 'late' => ['Late Arrival', 'bg-amber-500'], 'absent' => ['Absent Without Notice', 'bg-rose-500'], 'on_leave' => ['Scheduled Leave', 'bg-sky-500'], 'mc' => ['Medical Leave (MC)', 'bg-purple-600']] as $key => [$label, $color])
                            @php $row = $attendanceOverview[$key]; @endphp
                            <div>
                                <div class="flex justify-between text-xs font-medium text-slate-700 mb-1.5">
                                    <span>{{ $label }}</span>
                                    <span class="font-mono-nums text-slate-900 font-semibold">{{ $row['count'] }} scans ({{ $row['percent'] }}%)</span>
                                </div>
                                <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
                                    <div class="h-full {{ $color }} rounded-full transition-all" style="width: {{ $row['percent'] }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- 3. PERSONNEL REQUIRING ATTENTION -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6">
                <div class="flex items-center justify-between pb-3.5 mb-4 border-b border-slate-100">
                    <div>
                        <h3 class="text-sm font-bold text-slate-900">Personnel Compliance Review</h3>
                        <p class="text-xs text-slate-400">Staff flagged for 3+ late check-ins or pending absence approvals</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-100 text-xs text-left">
                        <thead class="bg-slate-50/80 text-slate-500 font-semibold uppercase text-[11px] tracking-wider">
                            <tr>
                                <th class="px-5 py-3">Employee Name</th>
                                <th class="px-5 py-3">Compliance Notice</th>
                                <th class="px-5 py-3 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            @forelse($staffRequiringAttention as $item)
                                <tr class="hover:bg-slate-50/60 transition-colors">
                                    <td class="px-5 py-3.5 font-semibold text-slate-900 flex items-center gap-2.5">
                                        <div class="w-7 h-7 rounded-lg bg-slate-100 text-slate-700 font-bold flex items-center justify-center text-xs">
                                            {{ substr($item['name'], 0, 2) }}
                                        </div>
                                        <span>{{ $item['name'] }}</span>
                                    </td>
                                    <td class="px-5 py-3.5">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-50 text-amber-800 border border-amber-200/70">
                                            {{ $item['reason'] }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-3.5 text-right">
                                        <a href="{{ route('admin.attendance.index') }}"
                                           class="inline-flex items-center gap-1 px-3 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-xs font-semibold transition">
                                            <span>View Records</span>
                                            <i class="fa-solid fa-arrow-right text-[10px] text-slate-400"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-8 text-center text-slate-400">
                                        <div class="flex items-center justify-center gap-2 text-emerald-700 font-medium text-xs">
                                            <i class="fa-solid fa-circle-check text-emerald-500"></i>
                                            <span>All personnel in full attendance and punctuality compliance.</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>