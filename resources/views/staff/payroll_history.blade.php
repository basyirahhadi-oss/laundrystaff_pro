<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <span class="text-xs font-bold uppercase tracking-widest text-slate-400 block mb-1">
                    Financial Ledgers
                </span>
                <h1 class="text-xl font-bold text-slate-900 tracking-tight">
                    Payroll Records
                </h1>
                <p class="text-xs sm:text-sm font-medium text-slate-500 mt-0.5">
                    Wage computation statements and statutory contribution records (EPF, SOCSO, EIS).
                </p>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('staff.index') }}"
                   class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-semibold bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 transition shadow-xs">
                    <i class="fa-solid fa-users text-slate-400"></i>
                    <span>Staff Directory</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 sm:py-8 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- 1. CLEAN & UNIFIED STAT CARDS (No Rainbow Borders) --}}
            @php
                $totalNet = $payrolls->sum('net_salary');
                $totalBasic = $payrolls->sum('basic_salary');
                $totalOt = $payrolls->sum('ot_pay');
                $totalStatutory = $payrolls->sum('epf_deduction') + $payrolls->sum('socso_deduction') + $payrolls->sum('eis_deduction');
            @endphp
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                {{-- CARD 1: NET PAY --}}
                <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs">
                    <div class="flex items-center justify-between mb-2.5">
                        <span class="text-xs font-semibold text-slate-500">Net Disbursed</span>
                        <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center text-xs">
                            <i class="fa-regular fa-credit-card"></i>
                        </div>
                    </div>
                    <div class="text-xl font-bold text-slate-900 font-mono-nums">
                        RM {{ number_format($totalNet, 2) }}
                    </div>
                    <p class="text-xs text-slate-400 mt-1">Processed take-home pay</p>
                </div>

                {{-- CARD 2: OVERTIME --}}
                <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs">
                    <div class="flex items-center justify-between mb-2.5">
                        <span class="text-xs font-semibold text-slate-500">Overtime Pay</span>
                        <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs">
                            <i class="fa-regular fa-clock"></i>
                        </div>
                    </div>
                    <div class="text-xl font-bold text-slate-900 font-mono-nums">
                        RM {{ number_format($totalOt, 2) }}
                    </div>
                    <p class="text-xs text-slate-400 mt-1">Approved extra shift hours</p>
                </div>

                {{-- CARD 3: STATUTORY --}}
                <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs">
                    <div class="flex items-center justify-between mb-2.5">
                        <span class="text-xs font-semibold text-slate-500">Statutory Deductions</span>
                        <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center text-xs">
                            <i class="fa-solid fa-building-columns"></i>
                        </div>
                    </div>
                    <div class="text-xl font-bold text-slate-900 font-mono-nums">
                        RM {{ number_format($totalStatutory, 2) }}
                    </div>
                    <p class="text-xs text-slate-400 mt-1">EPF + SOCSO + EIS totals</p>
                </div>

                {{-- CARD 4: AUDIT INTEGRITY --}}
                <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs">
                    <div class="flex items-center justify-between mb-2.5">
                        <span class="text-xs font-semibold text-slate-500">Ledger Security</span>
                        <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center text-xs">
                            <i class="fa-solid fa-lock"></i>
                        </div>
                    </div>
                    <div class="text-xl font-bold text-slate-900 font-mono-nums">
                        SHA-256 Locked
                    </div>
                    <p class="text-xs text-slate-400 mt-1">Verified audit trail records</p>
                </div>
            </div>

            {{-- 2. PAYROLL TABLE --}}
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2">
                    <div>
                        <h2 class="text-base font-bold text-slate-900 tracking-tight">Generated Payslips</h2>
                        <p class="text-xs text-slate-400">Official employment salary statements</p>
                    </div>
                    <span class="text-xs font-medium px-2.5 py-1 rounded-full bg-slate-100 text-slate-600 font-mono-nums">
                        {{ $payrolls->count() }} Payslips
                    </span>
                </div>

                <div class="overflow-x-auto w-full">
                    <table class="w-full text-xs text-left">
                        <thead class="bg-slate-50/80 text-slate-500 font-semibold uppercase text-[11px] tracking-wider border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-3.5">Employee</th>
                                <th class="px-6 py-3.5">Period</th>
                                <th class="px-6 py-3.5">Basic Pay</th>
                                <th class="px-6 py-3.5">Overtime</th>
                                <th class="px-6 py-3.5">EPF (11%)</th>
                                <th class="px-6 py-3.5">SOCSO &amp; EIS</th>
                                <th class="px-6 py-3.5">Net Salary</th>
                                <th class="px-6 py-3.5 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            @forelse($payrolls as $p)
                                <tr class="hover:bg-slate-50/60 transition-colors group">
                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-slate-900">{{ $p->full_name }}</div>
                                        <div class="text-[11px] text-slate-400 font-mono-nums">{{ $p->staff_id }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-slate-100 text-slate-700 text-xs font-medium">
                                            {{ $p->month_year }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 font-mono-nums text-slate-700">
                                        RM {{ number_format($p->basic_salary, 2) }}
                                    </td>
                                    <td class="px-6 py-4 font-mono-nums">
                                        <span class="text-emerald-700 font-medium">+RM {{ number_format($p->ot_pay, 2) }}</span>
                                        <span class="text-[11px] text-slate-400 block font-sans">({{ $p->ot_hours }} hrs)</span>
                                    </td>
                                    <td class="px-6 py-4 font-mono-nums text-slate-600">
                                        -RM {{ number_format($p->epf_deduction, 2) }}
                                    </td>
                                    <td class="px-6 py-4 font-mono-nums text-slate-600">
                                        -RM {{ number_format($p->socso_deduction + $p->eis_deduction, 2) }}
                                    </td>
                                    <td class="px-6 py-4 font-mono-nums">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-800 border border-emerald-200/70 font-bold">
                                            RM {{ number_format($p->net_salary, 2) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right whitespace-nowrap">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('staff.payroll.print', $p->id) }}" target="_blank"
                                               class="inline-flex items-center gap-1.5 px-3 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-xs font-semibold transition">
                                                <i class="fa-solid fa-print text-[10px]"></i>
                                                <span>Print Slip</span>
                                            </a>

                                            @if(auth()->user()->role === 'admin' || auth()->user()->email == 'admin@zaujati.com')
                                                <form action="{{ route('staff.payroll.destroy', $p->id) }}" method="POST"
                                                      onsubmit="return confirm('Delete payroll record for {{ addslashes($p->full_name) }}?');" class="inline-block">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="inline-flex items-center gap-1 px-2.5 py-1 text-rose-600 hover:bg-rose-50 rounded-lg text-xs font-semibold transition"
                                                        title="Delete Record">
                                                        <i class="fa-regular fa-trash-can text-[10px]"></i>
                                                        <span>Delete</span>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-12 text-center text-slate-400">
                                        <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-400 mx-auto flex items-center justify-center text-base mb-2.5">
                                            <i class="fa-regular fa-file-lines"></i>
                                        </div>
                                        <p class="font-semibold text-slate-700 text-sm">No payroll runs processed yet.</p>
                                        <p class="text-xs text-slate-400 mt-0.5">Go to Staff Directory and click "Payroll" on any employee to generate their statement.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
    @include('partials.flash-alerts')
</x-app-layout>