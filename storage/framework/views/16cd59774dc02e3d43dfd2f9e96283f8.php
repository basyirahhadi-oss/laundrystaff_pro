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
     <?php $__env->endSlot(); ?>

    <div class="py-6 sm:py-8 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- 1. CLEAN 4 KPI STAT CARDS (Uniform & Uncluttered) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                
                <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs">
                    <div class="flex items-center justify-between mb-2.5">
                        <span class="text-xs font-semibold text-slate-500">Attendance Rate</span>
                        <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs">
                            <i class="fa-solid fa-percent"></i>
                        </div>
                    </div>
                    <div class="flex items-baseline gap-2 font-mono-nums">
                        <span class="text-2xl font-bold text-slate-900"><?php echo e($attendanceRate); ?>%</span>
                        <span class="text-xs font-medium text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-md">On Target</span>
                    </div>
                    <p class="text-xs text-slate-400 mt-1">Operational presence this month</p>
                </div>

                
                <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs">
                    <div class="flex items-center justify-between mb-2.5">
                        <span class="text-xs font-semibold text-slate-500">Approved Leave</span>
                        <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center text-xs">
                            <i class="fa-regular fa-calendar-xmark"></i>
                        </div>
                    </div>
                    <div class="flex items-baseline gap-2 font-mono-nums">
                        <span class="text-2xl font-bold text-slate-900"><?php echo e($leaveDaysThisMonth); ?></span>
                        <span class="text-xs text-slate-500 font-sans">Days Out</span>
                    </div>
                    <p class="text-xs text-slate-400 mt-1">Authorized absence this month</p>
                </div>

                
                <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs">
                    <div class="flex items-center justify-between mb-2.5">
                        <span class="text-xs font-semibold text-slate-500">Payroll Total</span>
                        <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center text-xs">
                            <i class="fa-solid fa-file-invoice-dollar"></i>
                        </div>
                    </div>
                    <div class="flex items-baseline gap-1 font-mono-nums">
                        <span class="text-xs font-bold text-slate-400">RM</span>
                        <span class="text-xl font-bold text-slate-900"><?php echo e(number_format($payrollProcessed, 2)); ?></span>
                    </div>
                    <p class="text-xs text-slate-400 mt-1">Total wage commitment</p>
                </div>

                
                <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs">
                    <div class="flex items-center justify-between mb-2.5">
                        <span class="text-xs font-semibold text-slate-500">Late Check-Ins</span>
                        <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center text-xs">
                            <i class="fa-regular fa-clock"></i>
                        </div>
                    </div>
                    <div class="flex items-baseline gap-2 font-mono-nums">
                        <span class="text-2xl font-bold text-slate-900"><?php echo e($lateArrivals); ?></span>
                        <span class="text-xs text-amber-700 bg-amber-50 px-2 py-0.5 rounded-md font-sans">Incidents</span>
                    </div>
                    <p class="text-xs text-slate-400 mt-1">Recorded shift tardiness</p>
                </div>
            </div>

            <!-- 2. LEAVE DISTRIBUTION & ATTENDANCE BREAKDOWN -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6">
                    <div class="flex items-center justify-between pb-3.5 mb-4 border-b border-slate-100">
                        <div>
                            <h3 class="text-sm font-bold text-slate-900">Leave Distribution</h3>
                            <p class="text-xs text-slate-400">Monthly breakdown by leave type</p>
                        </div>
                        <span class="text-xs font-medium text-slate-400">Current Month</span>
                    </div>

                    <div class="space-y-4">
                        <?php $__currentLoopData = ['annual' => 'Annual Leave', 'mc' => 'Medical Leave (MC)', 'emergency' => 'Emergency Leave', 'unpaid' => 'Unpaid Leave']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php $row = $leaveTypeDistribution[$key]; ?>
                            <div>
                                <div class="flex justify-between text-xs font-medium text-slate-700 mb-1.5">
                                    <span><?php echo e($label); ?></span>
                                    <span class="font-mono-nums text-slate-900 font-semibold"><?php echo e($row['count']); ?> requests (<?php echo e($row['percent']); ?>%)</span>
                                </div>
                                <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
                                    <div class="h-full bg-indigo-600 rounded-full transition-all" style="width: <?php echo e($row['percent']); ?>%"></div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>

                
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6">
                    <div class="flex items-center justify-between pb-3.5 mb-4 border-b border-slate-100">
                        <div>
                            <h3 class="text-sm font-bold text-slate-900">Attendance Status Breakdown</h3>
                            <p class="text-xs text-slate-400">Distribution of all logged shifts</p>
                        </div>
                        <span class="text-xs font-medium text-slate-400">All Shifts</span>
                    </div>

                    <div class="space-y-4">
                        <?php $__currentLoopData = ['present' => ['Present On Time', 'bg-emerald-500'], 'late' => ['Late Arrival', 'bg-amber-500'], 'absent' => ['Absent Without Notice', 'bg-rose-500'], 'on_leave' => ['Scheduled Leave', 'bg-sky-500'], 'mc' => ['Medical Leave (MC)', 'bg-purple-600']]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => [$label, $color]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php $row = $attendanceOverview[$key]; ?>
                            <div>
                                <div class="flex justify-between text-xs font-medium text-slate-700 mb-1.5">
                                    <span><?php echo e($label); ?></span>
                                    <span class="font-mono-nums text-slate-900 font-semibold"><?php echo e($row['count']); ?> scans (<?php echo e($row['percent']); ?>%)</span>
                                </div>
                                <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
                                    <div class="h-full <?php echo e($color); ?> rounded-full transition-all" style="width: <?php echo e($row['percent']); ?>%"></div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                            <?php $__empty_1 = true; $__currentLoopData = $staffRequiringAttention; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr class="hover:bg-slate-50/60 transition-colors">
                                    <td class="px-5 py-3.5 font-semibold text-slate-900 flex items-center gap-2.5">
                                        <div class="w-7 h-7 rounded-lg bg-slate-100 text-slate-700 font-bold flex items-center justify-center text-xs">
                                            <?php echo e(substr($item['name'], 0, 2)); ?>

                                        </div>
                                        <span><?php echo e($item['name']); ?></span>
                                    </td>
                                    <td class="px-5 py-3.5">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-50 text-amber-800 border border-amber-200/70">
                                            <?php echo e($item['reason']); ?>

                                        </span>
                                    </td>
                                    <td class="px-5 py-3.5 text-right">
                                        <a href="<?php echo e(route('admin.attendance.index')); ?>"
                                           class="inline-flex items-center gap-1 px-3 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-xs font-semibold transition">
                                            <span>View Records</span>
                                            <i class="fa-solid fa-arrow-right text-[10px] text-slate-400"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="3" class="px-6 py-8 text-center text-slate-400">
                                        <div class="flex items-center justify-center gap-2 text-emerald-700 font-medium text-xs">
                                            <i class="fa-solid fa-circle-check text-emerald-500"></i>
                                            <span>All personnel in full attendance and punctuality compliance.</span>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

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
<?php endif; ?><?php /**PATH C:\laragon\www\laundrystaff-pro\resources\views/admin/reports/index.blade.php ENDPATH**/ ?>