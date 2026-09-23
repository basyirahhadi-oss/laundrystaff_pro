<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('header', null, []); ?> 
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <span class="text-xs font-bold uppercase tracking-widest text-slate-400 block mb-1">
                    Employee Self-Service
                </span>
                <h1 class="text-xl font-bold text-slate-900 tracking-tight">
                    My Analytics &amp; Performance
                </h1>
                <p class="text-xs sm:text-sm font-medium text-slate-500 mt-1">
                    Personal attendance records, punctuality score, and monthly working hours.
                </p>
            </div>

            <!-- Month Filter -->
            <div class="flex items-center gap-2">
                <form method="GET" action="<?php echo e(route('staff.analytics.index')); ?>" class="flex items-center gap-2">
                    <div class="relative">
                        <input type="month" name="month" value="<?php echo e($selectedMonth); ?>" onchange="this.form.submit()"
                               class="bg-white border border-slate-200 rounded-xl px-3 py-2 text-xs font-bold text-slate-800 shadow-sm focus:ring-2 focus:ring-[#4A154B]/20 focus:border-[#4A154B] transition cursor-pointer">
                    </div>
                </form>

                <a href="<?php echo e(route('staff.dashboard')); ?>"
                   class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold text-slate-700 bg-white hover:bg-slate-50 border border-slate-200 shadow-sm transition">
                    <i class="fa-solid fa-clock text-slate-400 text-[11px]"></i>
                    <span>Punch Clock</span>
                </a>
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-8 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- 1. TOP 4 BENTO PERFORMANCE KPI CARDS -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                
                <!-- KPI 1: Punctuality Score -->
                <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-slate-500">Punctuality Score</span>
                        <div class="w-8 h-8 rounded-lg bg-<?php echo e($performanceTier['color']); ?>-50 text-<?php echo e($performanceTier['color']); ?>-600 flex items-center justify-center text-xs">
                            <i class="fa-solid <?php echo e($performanceTier['icon']); ?>"></i>
                        </div>
                    </div>
                    <div class="mt-3 flex items-baseline gap-2">
                        <span class="text-2xl font-bold text-slate-900"><?php echo e($punctualityRate); ?>%</span>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold uppercase bg-<?php echo e($performanceTier['color']); ?>-50 text-<?php echo e($performanceTier['color']); ?>-700 border border-<?php echo e($performanceTier['color']); ?>-200">
                            Grade <?php echo e($performanceTier['grade']); ?>

                        </span>
                    </div>
                    <p class="text-xs text-slate-400 mt-1 font-normal"><?php echo e($performanceTier['label']); ?> (<?php echo e($presentCount); ?> of <?php echo e($totalShifts); ?> on time)</p>
                </div>

                <!-- KPI 2: Worked Hours -->
                <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-slate-500">Shift Hours (<?php echo e($monthDate->format('M Y')); ?>)</span>
                        <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center text-xs">
                            <i class="fa-solid fa-briefcase"></i>
                        </div>
                    </div>
                    <div class="mt-3 flex items-baseline gap-2">
                        <span class="text-2xl font-bold text-slate-900"><?php echo e($totalWorkedHours); ?></span>
                        <span class="text-xs text-slate-500 font-medium">hrs</span>
                        <span class="text-[10px] text-slate-400 ml-auto"><?php echo e($hoursProgress); ?>% of 160h</span>
                    </div>
                    <!-- Mini Progress Bar -->
                    <div class="w-full bg-slate-100 rounded-full h-1.5 mt-2 overflow-hidden">
                        <div class="bg-indigo-600 h-1.5 rounded-full" style="width: <?php echo e($hoursProgress); ?>%"></div>
                    </div>
                    <p class="text-xs text-slate-400 mt-1.5">Avg <?php echo e($avgDailyHours); ?> hrs / shift · <?php echo e($estimatedOTHours); ?> hrs OT</p>
                </div>

                <!-- KPI 3: Estimated Earnings -->
                <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-slate-500">Est. Shift Earnings</span>
                        <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs">
                            <i class="fa-solid fa-money-bill-wave"></i>
                        </div>
                    </div>
                    <div class="mt-3 flex items-baseline gap-1">
                        <span class="text-xs font-bold text-emerald-600">RM</span>
                        <span class="text-2xl font-bold text-slate-900"><?php echo e(number_format($estimatedGrossEarnings, 2)); ?></span>
                    </div>
                    <p class="text-xs text-slate-400 mt-1">Rate: RM <?php echo e(number_format($hourlyRate, 2)); ?> / hr</p>
                </div>

                <!-- KPI 4: Annual Leave Balance -->
                <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-slate-500">Annual Leave (AL)</span>
                        <div class="w-8 h-8 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center text-xs">
                            <i class="fa-solid fa-calendar-check"></i>
                        </div>
                    </div>
                    <div class="mt-3 flex items-baseline gap-2">
                        <span class="text-2xl font-bold text-slate-900"><?php echo e($annualLeaveBalance); ?></span>
                        <span class="text-xs text-slate-500 font-medium">/ <?php echo e($annualLeaveTotal); ?> Days</span>
                    </div>
                    <p class="text-xs text-slate-400 mt-1"><?php echo e($annualLeaveApproved); ?> days taken this year</p>
                </div>

            </div>

            <!-- 2. ATTENDANCE DISTRIBUTION & LEAVE ENTITLEMENT CARDS -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- Left 2 Cols: Monthly Shift Status Distribution -->
                <div class="lg:col-span-2 bg-white rounded-3xl border border-slate-200/80 p-6 shadow-sm space-y-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-base font-bold text-slate-900 tracking-tight">Monthly Shift Attendance Summary</h2>
                            <p class="text-xs text-slate-400 mt-0.5">Distribution of work shifts for <?php echo e($monthDate->format('F Y')); ?></p>
                        </div>
                        <span class="text-xs font-mono-nums font-bold px-2.5 py-1 rounded-xl bg-slate-100 text-slate-700">
                            Total: <?php echo e($totalShifts); ?> recorded shifts
                        </span>
                    </div>

                    <!-- Multi-color Segmented Distribution Bar -->
                    <?php
                        $pctPresent = $totalShifts > 0 ? round(($presentCount / $totalShifts) * 100, 1) : 0;
                        $pctLate = $totalShifts > 0 ? round(($lateCount / $totalShifts) * 100, 1) : 0;
                        $pctLeave = $totalShifts > 0 ? round(($onLeaveCount / $totalShifts) * 100, 1) : 0;
                        $pctAbsent = $totalShifts > 0 ? round(($absentCount / $totalShifts) * 100, 1) : 0;
                    ?>
                    <div class="w-full bg-slate-100 rounded-2xl h-4 flex overflow-hidden p-0.5 gap-0.5">
                        <?php if($pctPresent > 0): ?>
                            <div class="bg-emerald-500 h-full rounded-l" style="width: <?php echo e($pctPresent); ?>%" title="Present: <?php echo e($presentCount); ?> (<?php echo e($pctPresent); ?>%)"></div>
                        <?php endif; ?>
                        <?php if($pctLate > 0): ?>
                            <div class="bg-amber-400 h-full" style="width: <?php echo e($pctLate); ?>%" title="Late: <?php echo e($lateCount); ?> (<?php echo e($pctLate); ?>%)"></div>
                        <?php endif; ?>
                        <?php if($pctLeave > 0): ?>
                            <div class="bg-purple-500 h-full" style="width: <?php echo e($pctLeave); ?>%" title="On Leave / MC: <?php echo e($onLeaveCount); ?> (<?php echo e($pctLeave); ?>%)"></div>
                        <?php endif; ?>
                        <?php if($pctAbsent > 0): ?>
                            <div class="bg-rose-500 h-full rounded-r" style="width: <?php echo e($pctAbsent); ?>%" title="Absent: <?php echo e($absentCount); ?> (<?php echo e($pctAbsent); ?>%)"></div>
                        <?php endif; ?>
                    </div>

                    <!-- 4 Status Tiles with exact counts -->
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 pt-2">
                        <div class="p-3 rounded-2xl bg-emerald-50/60 border border-emerald-100 text-center">
                            <span class="text-xs font-semibold text-emerald-800 uppercase tracking-wide">Present (On-Time)</span>
                            <p class="text-2xl font-bold text-emerald-700 mt-1"><?php echo e($presentCount); ?></p>
                            <span class="text-[10px] text-emerald-600 font-medium"><?php echo e($pctPresent); ?>%</span>
                        </div>

                        <div class="p-3 rounded-2xl bg-amber-50/60 border border-amber-100 text-center">
                            <span class="text-xs font-semibold text-amber-800 uppercase tracking-wide">Late Punch-In</span>
                            <p class="text-2xl font-bold text-amber-700 mt-1"><?php echo e($lateCount); ?></p>
                            <span class="text-[10px] text-amber-600 font-medium"><?php echo e($pctLate); ?>%</span>
                        </div>

                        <div class="p-3 rounded-2xl bg-purple-50/60 border border-purple-100 text-center">
                            <span class="text-xs font-semibold text-purple-800 uppercase tracking-wide">Approved Leave / MC</span>
                            <p class="text-2xl font-bold text-purple-700 mt-1"><?php echo e($onLeaveCount); ?></p>
                            <span class="text-[10px] text-purple-600 font-medium"><?php echo e($pctLeave); ?>%</span>
                        </div>

                        <div class="p-3 rounded-2xl bg-rose-50/60 border border-rose-100 text-center">
                            <span class="text-xs font-semibold text-rose-800 uppercase tracking-wide">Unexcused Absent</span>
                            <p class="text-2xl font-bold text-rose-700 mt-1"><?php echo e($absentCount); ?></p>
                            <span class="text-[10px] text-rose-600 font-medium"><?php echo e($pctAbsent); ?>%</span>
                        </div>
                    </div>
                </div>

                <!-- Right 1 Col: Statutory Leave Allowance Card -->
                <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-sm space-y-4 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <h2 class="text-base font-bold text-slate-900 tracking-tight">Leave Entitlements (<?php echo e($monthDate->year); ?>)</h2>
                            <a href="<?php echo e(route('staff.leaves.create')); ?>"
                               class="text-xs font-bold text-[#4A154B] hover:text-[#E11D74] transition inline-flex items-center gap-1">
                                <span>Apply</span>
                                <i class="fa-solid fa-plus text-[10px]"></i>
                            </a>
                        </div>

                        <div class="space-y-3.5 text-xs">
                            <!-- Annual Leave -->
                            <div class="p-3 rounded-2xl bg-slate-50 border border-slate-200/70">
                                <div class="flex justify-between items-center mb-1">
                                    <span class="font-bold text-slate-800">Annual Leave (AL)</span>
                                    <span class="font-mono-nums font-bold text-slate-900"><?php echo e($annualLeaveBalance); ?> / <?php echo e($annualLeaveTotal); ?> Left</span>
                                </div>
                                <div class="w-full bg-slate-200 rounded-full h-1.5 overflow-hidden">
                                    <div class="bg-[#4A154B] h-1.5 rounded-full" style="width: <?php echo e(($annualLeaveApproved / $annualLeaveTotal) * 100); ?>%"></div>
                                </div>
                                <span class="text-[10px] text-slate-400 mt-1 block"><?php echo e($annualLeaveApproved); ?> days utilized</span>
                            </div>

                            <!-- Medical MC -->
                            <div class="p-3 rounded-2xl bg-slate-50 border border-slate-200/70">
                                <div class="flex justify-between items-center mb-1">
                                    <span class="font-bold text-slate-800">Medical Leave (MC)</span>
                                    <span class="font-mono-nums font-bold text-slate-900"><?php echo e($mcBalance); ?> / <?php echo e($mcTotal); ?> Left</span>
                                </div>
                                <div class="w-full bg-slate-200 rounded-full h-1.5 overflow-hidden">
                                    <div class="bg-indigo-600 h-1.5 rounded-full" style="width: <?php echo e(($mcApproved / $mcTotal) * 100); ?>%"></div>
                                </div>
                                <span class="text-[10px] text-slate-400 mt-1 block"><?php echo e($mcApproved); ?> days utilized (AES-256 Verified)</span>
                            </div>

                            <!-- Emergency / Unpaid -->
                            <div class="flex items-center justify-between px-2 pt-1 text-[11px] text-slate-500">
                                <span>Emergency Leaves Taken:</span>
                                <span class="font-bold font-mono-nums text-slate-800"><?php echo e($emergencyApproved); ?> days</span>
                            </div>
                            <?php if($pendingLeavesCount > 0): ?>
                                <div class="flex items-center justify-between px-2 text-[11px] text-amber-600 font-semibold bg-amber-50 p-2 rounded-xl border border-amber-200">
                                    <span>Pending Approval:</span>
                                    <span class="font-bold font-mono-nums"><?php echo e($pendingLeavesCount); ?> applications</span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <a href="<?php echo e(route('staff.leaves.index')); ?>"
                       class="w-full text-center py-2 px-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition">
                        View Leave History &rarr;
                    </a>
                </div>

            </div>

            <!-- 3. DAILY ATTENDANCE & SHIFT LOG (MONTHLY) -->
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h2 class="text-base font-bold text-slate-900 tracking-tight">Shift Log &amp; Worked Hours Detail</h2>
                        <p class="text-xs text-slate-400 mt-0.5">Daily biometric clock-in timestamps for <?php echo e($monthDate->format('F Y')); ?></p>
                    </div>
                    <span class="text-xs text-slate-400 font-mono-nums"><?php echo e($monthlyAttendances->count()); ?> total days</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead class="bg-slate-50/80 text-slate-400 font-bold uppercase tracking-wider border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-3.5">Shift Date</th>
                                <th class="px-6 py-3.5">Clock In</th>
                                <th class="px-6 py-3.5">Clock Out</th>
                                <th class="px-6 py-3.5">Worked Duration</th>
                                <th class="px-6 py-3.5">Shift Status</th>
                                <th class="px-6 py-3.5">Remarks</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            <?php $__empty_1 = true; $__currentLoopData = $monthlyAttendances; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr class="hover:bg-slate-50/70 transition-colors">
                                    <td class="px-6 py-3.5 font-medium text-slate-900 font-mono-nums">
                                        <?php echo e($row->date->format('d M Y')); ?>

                                        <span class="text-[10px] text-slate-400 font-sans block"><?php echo e($row->date->format('l')); ?></span>
                                    </td>
                                    <td class="px-6 py-3.5 font-mono-nums">
                                        <?php if($row->clock_in_time): ?>
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-lg bg-emerald-50 text-emerald-700 font-semibold border border-emerald-200/50">
                                                <i class="fa-solid fa-arrow-right-to-bracket text-[10px]"></i>
                                                <?php echo e($row->clock_in_time->format('h:i A')); ?>

                                            </span>
                                        <?php else: ?>
                                            <span class="text-slate-300">—</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-3.5 font-mono-nums">
                                        <?php if($row->clock_out_time): ?>
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-lg bg-indigo-50 text-indigo-700 font-semibold border border-indigo-200/50">
                                                <i class="fa-solid fa-arrow-right-from-bracket text-[10px]"></i>
                                                <?php echo e($row->clock_out_time->format('h:i A')); ?>

                                            </span>
                                        <?php else: ?>
                                            <span class="text-slate-400 italic">Pending Clock Out</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-3.5 font-mono-nums">
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-lg bg-slate-100 text-slate-800 font-bold">
                                            <i class="fa-regular fa-clock text-[10px] text-slate-400"></i>
                                            <?php echo e($row->worked_hours); ?> hrs
                                        </span>
                                    </td>
                                    <td class="px-6 py-3.5">
                                        <?php
                                            $badgeClass = match($row->status) {
                                                'present' => 'bg-emerald-50 text-emerald-700 border-emerald-200/60',
                                                'late'    => 'bg-amber-50 text-amber-700 border-amber-200/60',
                                                'absent'  => 'bg-rose-50 text-rose-700 border-rose-200/60',
                                                'on_leave', 'mc' => 'bg-purple-50 text-purple-700 border-purple-200/60',
                                                default   => 'bg-slate-100 text-slate-700 border-slate-200/60',
                                            };
                                        ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider border <?php echo e($badgeClass); ?>">
                                            <?php echo e(ucfirst(str_replace('_', ' ', $row->status))); ?>

                                        </span>
                                    </td>
                                    <td class="px-6 py-3.5 text-slate-500 italic text-[11px]">
                                        <?php echo e($row->remarks ?? '—'); ?>

                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                                        <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 mx-auto flex items-center justify-center text-xl mb-3">
                                            <i class="fa-solid fa-calendar-xmark"></i>
                                        </div>
                                        <p class="font-bold text-slate-700">No shift records found for <?php echo e($monthDate->format('F Y')); ?>.</p>
                                        <p class="text-xs text-slate-400 mt-1">Clock in daily using the Kiosk camera or Employee punch clock.</p>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- 4. RECENT PAYROLL & STATUTORY COMPENSATION LEDGERS (IF AVAILABLE) -->
            <?php if($recentPayrolls->isNotEmpty()): ?>
                <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                        <div>
                            <h2 class="text-base font-bold text-slate-900 tracking-tight">Official Payroll Statements &amp; Statutory Ledgers</h2>
                            <p class="text-xs text-slate-400 mt-0.5">Historical pay vouchers with EPF (11%), SOCSO, and EIS breakdown</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-bold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-xl border border-emerald-200">
                                🇲🇾 LHDN / KWSP Verified
                            </span>
                            <a href="<?php echo e(route('staff.payroll.index')); ?>"
                               class="inline-flex items-center gap-1.5 px-3 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition">
                                <span>My Payroll</span>
                                <i class="fa-solid fa-arrow-right text-[10px]"></i>
                            </a>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse text-xs">
                            <thead class="bg-slate-50/80 text-slate-400 font-bold uppercase tracking-wider border-b border-slate-100">
                                <tr>
                                    <th class="px-6 py-3.5">Payroll Month</th>
                                    <th class="px-6 py-3.5">Basic Wage</th>
                                    <th class="px-6 py-3.5">Overtime (OT)</th>
                                    <th class="px-6 py-3.5">EPF (11%)</th>
                                    <th class="px-6 py-3.5">SOCSO</th>
                                    <th class="px-6 py-3.5">EIS</th>
                                    <th class="px-6 py-3.5">Net Pay</th>
                                    <th class="px-6 py-3.5 text-right">Payslip</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-slate-700 font-mono-nums">
                                <?php $__currentLoopData = $recentPayrolls; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr class="hover:bg-slate-50/70 transition-colors">
                                        <td class="px-6 py-3.5 font-bold text-slate-900 font-sans">
                                            <?php echo e($p->month_year); ?>

                                        </td>
                                        <td class="px-6 py-3.5">RM <?php echo e(number_format($p->basic_salary, 2)); ?></td>
                                        <td class="px-6 py-3.5 text-emerald-600 font-bold">+RM <?php echo e(number_format($p->ot_pay, 2)); ?> (<?php echo e($p->ot_hours); ?>h)</td>
                                        <td class="px-6 py-3.5 text-rose-500 font-medium">-RM <?php echo e(number_format($p->epf_deduction, 2)); ?></td>
                                        <td class="px-6 py-3.5 text-rose-500 font-medium">-RM <?php echo e(number_format($p->socso_deduction, 2)); ?></td>
                                        <td class="px-6 py-3.5 text-rose-500 font-medium">-RM <?php echo e(number_format($p->eis_deduction, 2)); ?></td>
                                        <td class="px-6 py-3.5 font-bold">
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-xl bg-emerald-50 text-emerald-800 border border-emerald-200/60 font-bold">
                                                RM <?php echo e(number_format($p->net_salary, 2)); ?>

                                            </span>
                                        </td>
                                        <td class="px-6 py-3.5 text-right font-sans">
                                            <a href="<?php echo e(route('staff.payroll.print', $p->id)); ?>" target="_blank"
                                               class="inline-flex items-center gap-1.5 px-3 py-1 bg-slate-100 hover:bg-slate-200 text-slate-800 rounded-lg text-xs font-bold transition">
                                                <i class="fa-solid fa-print text-[10px]"></i>
                                                <span>Print Payslip</span>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif; ?>

        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php /**PATH C:\laragon\www\laundrystaff-pro\resources\views/staff/analytics.blade.php ENDPATH**/ ?>