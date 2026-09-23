<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <span class="text-xs font-bold uppercase tracking-widest text-slate-400 block mb-1">
                    Financial Ledgers
                </span>
                <h1 class="text-xl font-bold text-slate-900 tracking-tight">
                    Payroll Calculator
                </h1>
                <p class="text-xs sm:text-sm font-medium text-slate-500 mt-0.5">
                    Real-time computation for base salary, overtime rates, and statutory withholdings.
                </p>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('staff.index') }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 shadow-sm transition">
                    <i class="fa-solid fa-arrow-left text-slate-400"></i>
                    <span>Back to Directory</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

                {{-- LEFT: CALCULATION INPUTS --}}
                <div class="lg:col-span-5 space-y-6">
                    <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-sm">
                        <div class="flex items-center gap-3 pb-4 mb-4 border-b border-slate-100">
                            <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-sm font-bold flex-shrink-0">
                                <i class="fa-solid fa-money-bill-transfer"></i>
                            </div>
                            <div>
                                <h3 class="text-sm font-bold text-slate-900">{{ $staff->full_name }}</h3>
                                <p class="text-[11px] text-slate-400">{{ $staff->staff_id }} · {{ $staff->position }}</p>
                            </div>
                        </div>

                        {{-- EMPLOYEE RATE CARD --}}
                        <div class="bg-slate-50 rounded-xl p-4 border border-slate-200/70 mb-5 flex items-center justify-between">
                            <div>
                                <span class="text-[10px] font-semibold uppercase tracking-wider text-slate-400 block">Base Monthly Wage</span>
                                <span class="text-lg font-bold text-slate-900">RM {{ number_format($staff->salary_rate, 2) }}</span>
                                <input type="hidden" id="base_salary" value="{{ $staff->salary_rate }}">
                            </div>
                            <span class="text-xs font-bold px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-200/50">
                                Standard Shift (26d × 8h)
                            </span>
                        </div>

                        <form action="{{ route('staff.payroll.store', $staff->staff_id) }}" method="POST" class="space-y-4">
                            @csrf

                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">Payroll Month</label>
                                    <select name="month" id="select_month" required
                                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-medium text-slate-800 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                                        @foreach(['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'] as $m)
                                            <option value="{{ $m }}" {{ date('F') == $m ? 'selected' : '' }}>{{ $m }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">Year</label>
                                    <select name="year" id="select_year" required
                                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-medium text-slate-800 font-mono-nums focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                                        <option value="2026" selected>2026</option>
                                        <option value="2027">2027</option>
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">Overtime (OT) Duration</label>
                                <div class="relative">
                                    <input type="number" step="0.1" name="ot_hours" id="ot_hours" value="0" min="0" required
                                        class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-3 pr-14 py-2 text-xs font-bold text-slate-800 font-mono-nums focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition"
                                        placeholder="0.0">
                                    <span class="absolute inset-y-0 right-0 pr-3 flex items-center text-xs font-bold text-slate-400 pointer-events-none">Hours</span>
                                </div>
                                <p class="text-[10px] text-slate-400 mt-1">Overtime rate calculated at 1.5× hourly wage multiplier.</p>
                            </div>

                            <button type="submit"
                                class="w-full inline-flex items-center justify-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs py-3 rounded-xl shadow-sm shadow-indigo-600/20 transition">
                                <i class="fa-solid fa-lock text-xs"></i>
                                <span>Commit &amp; Issue Cryptographic Payslip</span>
                            </button>
                        </form>
                    </div>
                </div>

                {{-- RIGHT: LIVE PAYSLIP VOUCHER PREVIEW --}}
                <div class="lg:col-span-7">
                    <div class="bg-white rounded-2xl border border-slate-200/80 p-8 shadow-sm">
                        <!-- VOUCHER HEADER -->
                        <div class="flex items-start justify-between border-b border-slate-100 pb-5 mb-5">
                            <div>
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-lg bg-indigo-600 text-white flex items-center justify-center text-xs font-bold">
                                        <i class="fa-solid fa-shirt"></i>
                                    </div>
                                    <span class="font-extrabold text-base text-slate-900 tracking-tight">Zaujati Laundry Hub</span>
                                </div>
                                <p class="text-[11px] text-slate-400 mt-0.5">Automated Employment Statement &amp; Salary Voucher</p>
                            </div>
                            <div class="text-right">
                                <span class="inline-block px-2.5 py-1 rounded-full text-[10px] font-semibold uppercase bg-emerald-50 text-emerald-700 border border-emerald-200/60">
                                    ● Live Calculation
                                </span>
                                <p class="text-xs font-semibold text-slate-800 mt-1" id="payslip_date">{{ date('F') }} 2026</p>
                            </div>
                        </div>

                        <!-- EMPLOYEE META -->
                        <div class="bg-slate-50 rounded-xl p-4 border border-slate-200/60 mb-6 grid grid-cols-2 sm:grid-cols-3 gap-3 text-xs">
                            <div>
                                <span class="text-[10px] font-semibold uppercase tracking-wider text-slate-400 block">Staff Name</span>
                                <span class="font-semibold text-slate-800 text-xs">{{ $staff->full_name }}</span>
                            </div>
                            <div>
                                <span class="text-[10px] font-semibold uppercase tracking-wider text-slate-400 block">Employee Code</span>
                                <span class="font-semibold text-indigo-700">{{ $staff->staff_id }}</span>
                            </div>
                            <div>
                                <span class="text-[10px] font-semibold uppercase tracking-wider text-slate-400 block">Position</span>
                                <span class="font-medium text-slate-700">{{ $staff->position }}</span>
                            </div>
                        </div>

                        <!-- BREAKDOWN TABLE -->
                        <div class="overflow-hidden rounded-xl border border-slate-200/80">
                            <table class="w-full text-xs text-left">
                                <thead class="bg-slate-50 text-slate-400 font-semibold uppercase tracking-wider border-b border-slate-200/80">
                                    <tr>
                                        <th class="px-5 py-3">Earnings &amp; Deductions Breakdown</th>
                                        <th class="px-5 py-3 text-right">Computed Amount</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    <tr>
                                        <td class="px-5 py-3 font-sans text-slate-700 font-medium">Basic Base Wage Rate</td>
                                        <td class="px-5 py-3 text-right font-semibold text-slate-900">RM {{ number_format($staff->salary_rate, 2) }}</td>
                                    </tr>
                                    <tr class="bg-emerald-50/40">
                                        <td class="px-5 py-3 font-sans text-emerald-800 font-medium">
                                            Overtime Compensation (<span id="preview_ot_hours">0</span> hrs @ 1.5×)
                                        </td>
                                        <td class="px-5 py-3 text-right font-semibold text-emerald-700" id="display_ot_earnings">RM 0.00</td>
                                    </tr>
                                    <tr class="bg-rose-50/30">
                                        <td class="px-5 py-3 font-sans text-rose-800 font-medium">KWSP / EPF Statutory Employee Share (11%)</td>
                                        <td class="px-5 py-3 text-right font-semibold text-rose-600" id="display_epf">RM 0.00</td>
                                    </tr>
                                    <tr class="bg-rose-50/30">
                                        <td class="px-5 py-3 font-sans text-rose-800 font-medium">PERKESO / SOCSO Employee Deduction (0.5%)</td>
                                        <td class="px-5 py-3 text-right font-semibold text-rose-600" id="display_socso">RM 0.00</td>
                                    </tr>
                                    <tr class="bg-rose-50/30">
                                        <td class="px-5 py-3 font-sans text-rose-800 font-medium">SIP / EIS Employment Insurance (0.2%)</td>
                                        <td class="px-5 py-3 text-right font-semibold text-rose-600" id="display_eis">RM 0.00</td>
                                    </tr>
                                    <tr class="bg-indigo-50/80 border-t-2 border-indigo-200">
                                        <td class="px-5 py-4 font-sans font-bold text-sm text-indigo-950 uppercase tracking-wide">
                                            Net Payable Take-Home Salary
                                        </td>
                                        <td class="px-5 py-4 text-right font-bold text-xl text-indigo-900" id="display_net_salary">
                                            RM {{ number_format($staff->salary_rate, 2) }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- STATUTORY FOOTER -->
                        <div class="mt-4 pt-4 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-400">
                            <span>Statutory Act: Employment Act 1955 Compliant</span>
                            <span>SHA-256 Chained Hash Log Ready</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const baseSalaryInput = document.getElementById('base_salary');
            const otHoursInput = document.getElementById('ot_hours');
            const selectMonth = document.getElementById('select_month');
            const selectYear = document.getElementById('select_year');

            const previewOtHours = document.getElementById('preview_ot_hours');
            const displayOtEarnings = document.getElementById('display_ot_earnings');
            const displayEpf = document.getElementById('display_epf');
            const displaySocso = document.getElementById('display_socso');
            const displayEis = document.getElementById('display_eis');
            const displayNetSalary = document.getElementById('display_net_salary');
            const payslipDate = document.getElementById('payslip_date');

            function liveCalculate() {
                const baseSalary = parseFloat(baseSalaryInput.value) || 0;
                const otHours = parseFloat(otHoursInput.value) || 0;

                const otRatePerHour = (baseSalary / 26 / 8) * 1.5;
                const otEarnings = otHours * otRatePerHour;

                const epf = baseSalary * 0.11;
                const socso = baseSalary * 0.005;
                const eis = baseSalary * 0.002;

                const netSalary = (baseSalary + otEarnings) - (epf + socso + eis);

                previewOtHours.innerText = otHours.toFixed(1);
                displayOtEarnings.innerText = '+RM ' + otEarnings.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                displayEpf.innerText = '-RM ' + epf.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                displaySocso.innerText = '-RM ' + socso.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                displayEis.innerText = '-RM ' + eis.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                displayNetSalary.innerText = 'RM ' + netSalary.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            }

            function updatePayslipDate() {
                payslipDate.innerText = selectMonth.value + ' ' + selectYear.value;
            }

            otHoursInput.addEventListener('input', liveCalculate);
            selectMonth.addEventListener('change', updatePayslipDate);
            selectYear.addEventListener('change', updatePayslipDate);

            liveCalculate();
        });
    </script>
</x-app-layout>