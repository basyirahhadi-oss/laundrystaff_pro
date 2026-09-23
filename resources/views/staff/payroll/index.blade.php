<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <span class="text-xs font-bold uppercase tracking-widest text-[#E11D74] block mb-1">
                    Employee Portal · Compensation
                </span>
                <h1 class="text-xl font-bold text-slate-900 tracking-tight flex items-center gap-2.5">
                    <span>My Payroll &amp; Payslips</span>
                    <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full bg-[#4A154B]/10 text-[#4A154B] border border-[#4A154B]/20">
                        Official Vouchers
                    </span>
                </h1>
                <p class="text-xs sm:text-sm font-medium text-slate-500 mt-0.5">
                    Review your monthly salary vouchers, overtime (OT) breakdown, statutory contributions (EPF, SOCSO &amp; EIS), and print official payslips.
                </p>
            </div>

            {{-- Action Controls & Year Filter --}}
            <div class="flex flex-wrap items-center gap-2.5">
                <form method="GET" action="{{ route('staff.payroll.index') }}" class="flex items-center gap-2">
                    <label for="year" class="text-xs font-bold text-slate-500 uppercase tracking-wider hidden sm:inline-block">Year:</label>
                    <select name="year" id="year" onchange="this.form.submit()"
                            class="bg-white border border-slate-200 text-slate-700 text-xs font-semibold rounded-xl px-3 py-2 focus:ring-2 focus:ring-[#4A154B]/20 focus:border-[#4A154B] transition shadow-xs">
                        <option value="all" {{ $selectedYear === 'all' ? 'selected' : '' }}>All Years</option>
                        @foreach($availableYears as $yearOption)
                            <option value="{{ $yearOption }}" {{ (string)$selectedYear === (string)$yearOption ? 'selected' : '' }}>
                                {{ $yearOption }}
                            </option>
                        @endforeach
                    </select>
                </form>

                @if($latestPayroll)
                    <a href="{{ route('staff.payroll.print', $latestPayroll->id) }}" target="_blank"
                       class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-bold text-white shadow-xs transition hover:opacity-95"
                       style="background-color: #4A154B;">
                        <i class="fa-solid fa-print text-xs"></i>
                        <span>Latest Slip</span>
                    </a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-6 sm:py-8 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            @if(!$staffProfile)
                {{-- NOTICE IF USER IS NOT LINKED TO A STAFF PROFILE ROW --}}
                <div class="bg-amber-50 border border-amber-200/80 rounded-2xl p-6 text-amber-900 shadow-xs flex items-start gap-4">
                    <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center shrink-0 text-lg">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-sm">Staff Profile Not Linked</h3>
                        <p class="text-xs text-amber-800 mt-1 leading-relaxed">
                            Your user account has not yet been linked to an employee profile in the system directory. Please contact an administrator to link your Staff ID so your salary statements can be displayed.
                        </p>
                    </div>
                </div>
            @else

                {{-- 1. EMPLOYEE COMPENSATION RATE HEADER CARD --}}
                <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="flex items-center gap-3.5">
                        <div class="w-12 h-12 rounded-2xl bg-slate-900 border border-slate-800 text-white flex items-center justify-center font-bold text-base uppercase shrink-0 shadow-xs">
                            @if(!empty($staffProfile->profile_picture))
                                <img src="{{ asset('uploads/staff/' . $staffProfile->profile_picture) }}" 
                                     alt="{{ $staffProfile->full_name }}" 
                                     class="w-full h-full object-cover rounded-2xl">
                            @else
                                {{ substr($staffProfile->full_name, 0, 2) }}
                            @endif
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <h2 class="text-base font-bold text-slate-900 tracking-tight">{{ $staffProfile->full_name }}</h2>
                                <span class="px-2 py-0.5 rounded-md text-[11px] font-bold bg-slate-100 text-slate-700 font-mono">
                                    {{ $staffProfile->staff_id }}
                                </span>
                            </div>
                            <p class="text-xs text-slate-500 font-medium mt-0.5">
                                Position: <span class="text-slate-800 font-semibold">{{ $staffProfile->position }}</span>
                                @if(!empty($staffProfile->phone_number))
                                    · Phone: <span class="text-slate-600 font-mono-nums">{{ $staffProfile->phone_number }}</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center gap-4 bg-slate-50 border border-slate-200/60 rounded-xl px-4 py-3 self-stretch md:self-auto justify-between md:justify-end">
                        <div>
                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Base Monthly Wage</span>
                            <span class="text-base sm:text-lg font-bold text-slate-900 font-mono-nums">
                                RM {{ number_format($staffProfile->salary_rate, 2) }}
                            </span>
                            <span class="text-[11px] text-slate-400">/ month</span>
                        </div>
                        <div class="h-8 w-px bg-slate-200"></div>
                        <div class="text-right">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Standard Shift Schedule</span>
                            <span class="inline-flex items-center gap-1 text-xs font-bold text-emerald-700">
                                <i class="fa-regular fa-calendar-check text-[11px]"></i>
                                <span>26 Days × 8 Hours</span>
                            </span>
                        </div>
                    </div>
                </div>

                {{-- 2. FINANCIAL STATS SUMMARY METRIC CARDS --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    {{-- CARD 1: LATEST NET SALARY --}}
                    <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs">
                        <div class="flex items-center justify-between mb-2.5">
                            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Latest Net Salary</span>
                            <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs">
                                <i class="fa-regular fa-credit-card"></i>
                            </div>
                        </div>
                        <div class="text-2xl font-black text-slate-900 font-mono-nums tracking-tight">
                            @if($latestPayroll)
                                RM {{ number_format($latestPayroll->net_salary, 2) }}
                            @else
                                RM 0.00
                            @endif
                        </div>
                        <div class="flex items-center gap-1.5 mt-1.5">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            <p class="text-xs font-medium text-slate-500">
                                {{ $latestPayroll ? $latestPayroll->month_year : 'No records processed' }}
                            </p>
                        </div>
                    </div>

                    {{-- CARD 2: YTD TOTAL TAKE-HOME --}}
                    <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs">
                        <div class="flex items-center justify-between mb-2.5">
                            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Net Disbursed</span>
                            <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center text-xs">
                                <i class="fa-solid fa-wallet"></i>
                            </div>
                        </div>
                        <div class="text-2xl font-black text-slate-900 font-mono-nums tracking-tight">
                            RM {{ number_format($totalNetYtd, 2) }}
                        </div>
                        <p class="text-xs font-medium text-slate-500 mt-1.5">
                            Across {{ $payrolls->count() }} payment cycles
                        </p>
                    </div>

                    {{-- CARD 3: TOTAL OVERTIME EARNED --}}
                    <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs">
                        <div class="flex items-center justify-between mb-2.5">
                            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Overtime (OT) Allowance</span>
                            <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center text-xs">
                                <i class="fa-regular fa-clock"></i>
                            </div>
                        </div>
                        <div class="text-2xl font-black text-emerald-600 font-mono-nums tracking-tight">
                            +RM {{ number_format($totalOtYtd, 2) }}
                        </div>
                        <p class="text-xs font-medium text-slate-500 mt-1.5">
                            1.5× hourly overtime multiplier
                        </p>
                    </div>

                    {{-- CARD 4: STATUTORY CONTRIBUTIONS ACCUMULATED --}}
                    <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs">
                        <div class="flex items-center justify-between mb-2.5">
                            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Statutory Deductions</span>
                            <div class="w-8 h-8 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center text-xs">
                                <i class="fa-solid fa-shield-heart"></i>
                            </div>
                        </div>
                        <div class="text-2xl font-black text-slate-800 font-mono-nums tracking-tight">
                            RM {{ number_format($totalStatutoryYtd, 2) }}
                        </div>
                        <p class="text-xs font-medium text-slate-500 mt-1.5">
                            EPF (11%) + SOCSO + EIS
                        </p>
                    </div>
                </div>

                {{-- 3. OFFICIAL PAYSLIPS TABLE --}}
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white">
                        <div>
                            <h2 class="text-base font-bold text-slate-900 tracking-tight">Official Salary Statements</h2>
                            <p class="text-xs text-slate-400 mt-0.5">Monthly compensation and statutory contribution vouchers verified by company accounting</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-bold px-3 py-1 rounded-full bg-slate-100 text-slate-700 font-mono-nums">
                                {{ $payrolls->count() }} Payslips Issued
                            </span>
                        </div>
                    </div>

                    <div class="overflow-x-auto w-full">
                        <table class="w-full text-xs text-left">
                            <thead class="bg-slate-50/80 text-slate-500 font-bold uppercase text-[11px] tracking-wider border-b border-slate-100">
                                <tr>
                                    <th class="px-6 py-3.5">Payroll Period</th>
                                    <th class="px-6 py-3.5">Base Salary</th>
                                    <th class="px-6 py-3.5">Overtime (OT)</th>
                                    <th class="px-6 py-3.5">EPF (11%)</th>
                                    <th class="px-6 py-3.5">SOCSO</th>
                                    <th class="px-6 py-3.5">EIS (0.2%)</th>
                                    <th class="px-6 py-3.5">Net Salary</th>
                                    <th class="px-6 py-3.5 text-right">Payslip</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-slate-700">
                                @forelse($payrolls as $p)
                                    <tr class="hover:bg-slate-50/60 transition-colors">
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-slate-100 text-slate-900 text-xs font-bold tracking-tight">
                                                {{ $p->month_year }}
                                            </span>
                                            <span class="block text-[10px] text-slate-400 mt-1 font-mono-nums">
                                                Issued: {{ date('d M Y', strtotime($p->created_at)) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 font-mono-nums font-semibold text-slate-800">
                                            RM {{ number_format($p->basic_salary, 2) }}
                                        </td>
                                        <td class="px-6 py-4 font-mono-nums">
                                            @if($p->ot_hours > 0)
                                                <span class="text-emerald-600 font-bold">+RM {{ number_format($p->ot_pay, 2) }}</span>
                                                <span class="text-[10px] text-slate-400 block font-sans">({{ $p->ot_hours }} hrs)</span>
                                            @else
                                                <span class="text-slate-400">&mdash;</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 font-mono-nums text-rose-600 font-medium">
                                            -RM {{ number_format($p->epf_deduction, 2) }}
                                        </td>
                                        <td class="px-6 py-4 font-mono-nums text-rose-600 font-medium">
                                            -RM {{ number_format($p->socso_deduction, 2) }}
                                        </td>
                                        <td class="px-6 py-4 font-mono-nums text-rose-600 font-medium">
                                            -RM {{ number_format($p->eis_deduction, 2) }}
                                        </td>
                                        <td class="px-6 py-4 font-mono-nums">
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-xl bg-emerald-50 text-emerald-800 border border-emerald-200/70 font-bold text-xs">
                                                RM {{ number_format($p->net_salary, 2) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-right whitespace-nowrap">
                                            <a href="{{ route('staff.payroll.print', $p->id) }}" target="_blank"
                                               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 border border-slate-200/60 shadow-2xs transition">
                                                <i class="fa-solid fa-print text-[11px] text-slate-500"></i>
                                                <span>Print Slip</span>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-6 py-14 text-center">
                                            <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 mx-auto flex items-center justify-center text-lg mb-3">
                                                <i class="fa-regular fa-file-invoice-dollar"></i>
                                            </div>
                                            <h3 class="text-sm font-bold text-slate-800">No Salary Statements Found</h3>
                                            <p class="text-xs text-slate-400 max-w-sm mx-auto mt-1">
                                                Monthly salary statements will appear here once processed by management at the end of each payroll period.
                                            </p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- 4. TRANSPARENCY & STATUTORY GUIDELINES INFO BOX --}}
                <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-xs">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="text-sm font-bold text-slate-900 tracking-tight">Malaysian Statutory Deduction Guide</span>
                        <span class="text-[10px] font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded-md">
                            Official Statutory Contributions
                        </span>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs text-slate-600">
                        <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/60">
                            <div class="font-bold text-slate-800 flex items-center gap-1.5 mb-1">
                                <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                                <span>EPF (KWSP) · 11%</span>
                            </div>
                            <p class="text-[11px] text-slate-500 leading-relaxed">
                                Employee retirement savings deducted at 11% of monthly basic wages in compliance with the Third Schedule of the EPF Act 1991.
                            </p>
                        </div>
                        <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/60">
                            <div class="font-bold text-slate-800 flex items-center gap-1.5 mb-1">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                <span>SOCSO (PERKESO) · ~0.5%</span>
                            </div>
                            <p class="text-[11px] text-slate-500 leading-relaxed">
                                Employment Injury and Invalidity Scheme for workplace social protection, capped at a maximum statutory deduction of RM24.75.
                            </p>
                        </div>
                        <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/60">
                            <div class="font-bold text-slate-800 flex items-center gap-1.5 mb-1">
                                <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                                <span>EIS (SIP) · 0.2%</span>
                            </div>
                            <p class="text-[11px] text-slate-500 leading-relaxed">
                                Employment Insurance System providing financial allowance and job placement assistance in the event of unexpected retrenchment.
                            </p>
                        </div>
                    </div>
                </div>

            @endif

        </div>
    </div>
</x-app-layout>
