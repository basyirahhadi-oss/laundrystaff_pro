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
        <div>
            <span class="text-xs font-bold uppercase tracking-widest text-slate-400 block mb-1">
                Staff Portal
            </span>
            <h1 class="text-xl font-bold text-slate-900">
                Good <?php echo e(now()->hour < 12 ? 'morning' : (now()->hour < 18 ? 'afternoon' : 'evening')); ?>, <?php echo e(auth()->user()->name); ?>.
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">
                <?php echo e(now()->format('l, d F Y')); ?>

            </p>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-6 sm:py-8 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- 1. TOP 4 SUMMARY STAT CARDS (Clean, standard & consistent with admin layout) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                
                
                <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs">
                    <div class="flex items-center justify-between mb-2.5">
                        <span class="text-xs font-semibold text-slate-500">Today's Shift</span>
                        <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center text-xs">
                            <i class="fa-regular fa-clock"></i>
                        </div>
                    </div>
                    <div>
                        <?php if(!$todayAttendance): ?>
                            <div class="text-lg font-bold text-slate-900">Not Clocked In</div>
                            <p class="text-xs text-slate-400 mt-1">Ready to start today's shift</p>
                        <?php elseif(!$todayAttendance->clock_out_time): ?>
                            <div class="text-lg font-bold text-emerald-600 flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                In Progress
                            </div>
                            <p class="text-xs text-slate-400 mt-1">Clocked in at <?php echo e($todayAttendance->clock_in_time->format('h:i A')); ?></p>
                        <?php else: ?>
                            <div class="text-lg font-bold text-slate-900">Completed</div>
                            <p class="text-xs text-slate-400 mt-1"><?php echo e($todayAttendance->worked_hours); ?> hrs recorded today</p>
                        <?php endif; ?>
                    </div>
                </div>

                
                <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs">
                    <div class="flex items-center justify-between mb-2.5">
                        <span class="text-xs font-semibold text-slate-500">Hours This Month</span>
                        <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs">
                            <i class="fa-solid fa-briefcase"></i>
                        </div>
                    </div>
                    <div class="text-2xl font-bold text-slate-900">
                        <?php echo e($monthlyHours ?? 0); ?> <span class="text-xs font-normal text-slate-500">hrs</span>
                    </div>
                    <p class="text-xs text-slate-400 mt-1">Total shifts in <?php echo e(now()->format('F')); ?>: <?php echo e($monthlyShifts ?? 0); ?></p>
                </div>

                
                <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs">
                    <div class="flex items-center justify-between mb-2.5">
                        <span class="text-xs font-semibold text-slate-500">Punctuality Score</span>
                        <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-xs">
                            <i class="fa-solid fa-percent"></i>
                        </div>
                    </div>
                    <div class="text-2xl font-bold text-slate-900">
                        <?php echo e($punctualityRate ?? 100); ?>%
                    </div>
                    <p class="text-xs text-slate-400 mt-1">On-time arrival rate</p>
                </div>

                
                <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs">
                    <div class="flex items-center justify-between mb-2.5">
                        <span class="text-xs font-semibold text-slate-500">Pending Leaves</span>
                        <div class="w-8 h-8 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center text-xs">
                            <i class="fa-regular fa-calendar-check"></i>
                        </div>
                    </div>
                    <div class="text-2xl font-bold text-slate-900">
                        <?php echo e($pendingLeaves ?? 0); ?>

                    </div>
                    <p class="text-xs text-slate-400 mt-1">Applications awaiting review</p>
                </div>

            </div>

            <!-- 2. MAIN SHIFT CLOCK IN / OUT ACTION CARD -->
            <div class="bg-white rounded-2xl border border-slate-200/80 p-6 sm:p-8 shadow-xs">
                <?php if(!$todayAttendance): ?>
                    
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-lg shrink-0">
                                <i class="fa-regular fa-clock"></i>
                            </div>
                            <div>
                                <h2 class="text-base font-bold text-slate-900">Record Your Arrival</h2>
                                <p class="text-xs sm:text-sm text-slate-500 mt-0.5">
                                    Ready to start work? Click below to record your check-in time for today.
                                </p>
                            </div>
                        </div>

                        <form action="<?php echo e(route('staff.clock-in')); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <button type="submit"
                                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white font-semibold text-sm py-2.5 px-6 rounded-xl shadow-xs transition">
                                <i class="fa-solid fa-arrow-right-to-bracket text-xs"></i>
                                <span>Clock In Now</span>
                            </button>
                        </form>
                    </div>

                <?php elseif(!$todayAttendance->clock_out_time): ?>
                    
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg shrink-0">
                                <i class="fa-solid fa-briefcase"></i>
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/60">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                        Shift in Progress
                                    </span>
                                    <?php if($todayAttendance->status === 'late'): ?>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200/60">
                                            Late Arrival
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <h2 class="text-base font-bold text-slate-900 mt-1.5">Currently Working</h2>
                                <p class="text-xs sm:text-sm text-slate-500 mt-0.5">
                                    Clocked in at <span class="font-semibold text-slate-800"><?php echo e($todayAttendance->clock_in_time->format('h:i A')); ?></span>. Remember to clock out when your shift ends.
                                </p>
                            </div>
                        </div>

                        <form action="<?php echo e(route('staff.clock-out')); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <button type="submit"
                                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-slate-900 hover:bg-black text-white font-semibold text-sm py-2.5 px-6 rounded-xl shadow-xs transition">
                                <i class="fa-solid fa-arrow-right-from-bracket text-xs"></i>
                                <span>End Shift &amp; Clock Out</span>
                            </button>
                        </form>
                    </div>

                <?php else: ?>
                    
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center text-lg shrink-0">
                                <i class="fa-solid fa-check"></i>
                            </div>
                            <div>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-700">
                                    Shift Completed for Today
                                </span>
                                <h2 class="text-base font-bold text-slate-900 mt-1">Thank you for your work!</h2>
                                <p class="text-xs sm:text-sm text-slate-500 mt-0.5">
                                    <?php echo e($todayAttendance->clock_in_time->format('h:i A')); ?> – <?php echo e($todayAttendance->clock_out_time->format('h:i A')); ?> · <span class="font-semibold text-slate-800"><?php echo e($todayAttendance->worked_hours); ?> hours recorded</span>
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-700 bg-emerald-50 px-3 py-1.5 rounded-xl border border-emerald-200/60">
                                <i class="fa-solid fa-circle-check text-emerald-500"></i>
                                <span>Shift Verified</span>
                            </span>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- 3. RECENT ATTENDANCE HISTORY TABLE -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h2 class="text-sm font-bold text-slate-900">Recent Attendance History</h2>
                        <p class="text-xs text-slate-500 mt-0.5">Your verified check-in records for recent shifts</p>
                    </div>
                    <span class="text-xs font-medium text-slate-400">Last 10 Records</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-100 text-xs text-left">
                        <thead class="bg-slate-50/80 text-slate-500 font-semibold uppercase text-[11px] tracking-wider">
                            <tr>
                                <th class="px-6 py-3.5">Date</th>
                                <th class="px-6 py-3.5">Clock In</th>
                                <th class="px-6 py-3.5">Clock Out</th>
                                <th class="px-6 py-3.5">Worked Hours</th>
                                <th class="px-6 py-3.5">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            <?php $__empty_1 = true; $__currentLoopData = $recentAttendances; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr class="hover:bg-slate-50/60 transition-colors">
                                    <td class="px-6 py-3.5 font-medium text-slate-900">
                                        <?php echo e($row->date->format('d M Y')); ?>

                                        <span class="text-[11px] text-slate-400 block font-normal"><?php echo e($row->date->format('l')); ?></span>
                                    </td>
                                    <td class="px-6 py-3.5 font-medium text-slate-700">
                                        <?php echo e($row->clock_in_time?->format('h:i A') ?? '—'); ?>

                                    </td>
                                    <td class="px-6 py-3.5 font-medium text-slate-700">
                                        <?php echo e($row->clock_out_time?->format('h:i A') ?? '—'); ?>

                                    </td>
                                    <td class="px-6 py-3.5 font-medium text-slate-900">
                                        <?php echo e($row->worked_hours ? $row->worked_hours . ' hrs' : '—'); ?>

                                    </td>
                                    <td class="px-6 py-3.5">
                                        <?php
                                            $pillStyle = match($row->status) {
                                                'present'  => 'bg-emerald-50 text-emerald-700 border-emerald-200/60',
                                                'late'     => 'bg-amber-50 text-amber-700 border-amber-200/60',
                                                'on_leave' => 'bg-sky-50 text-sky-700 border-sky-200/60',
                                                'mc'       => 'bg-purple-50 text-purple-700 border-purple-200/60',
                                                default    => 'bg-rose-50 text-rose-700 border-rose-200/60',
                                            };
                                            $statusLabel = match($row->status) {
                                                'present'  => 'Present',
                                                'late'     => 'Late',
                                                'on_leave' => 'On Leave',
                                                'mc'       => 'Medical Leave',
                                                default    => ucfirst(str_replace('_', ' ', $row->status)),
                                            };
                                        ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold border <?php echo e($pillStyle); ?>">
                                            <?php echo e($statusLabel); ?>

                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="5" class="px-6 py-10 text-center text-slate-400">
                                        <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-400 mx-auto flex items-center justify-center text-base mb-2">
                                            <i class="fa-regular fa-clock"></i>
                                        </div>
                                        <p class="font-medium text-slate-700 text-sm">No attendance records logged yet.</p>
                                        <p class="text-xs text-slate-400 mt-0.5">Clock in above to record your first shift.</p>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <?php echo $__env->make('partials.flash-alerts', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
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
<?php /**PATH C:\laragon\www\laundrystaff-pro\resources\views/staff/dashboard.blade.php ENDPATH**/ ?>