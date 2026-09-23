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
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <span class="text-xs font-bold uppercase tracking-widest text-slate-400 block mb-1">
                    Employee Self-Service
                </span>
                <h1 class="text-xl font-bold text-slate-900 tracking-tight">
                    My Leave Requests
                </h1>
                <p class="text-xs sm:text-sm font-medium text-slate-500 mt-1">
                    View submitted applications, medical attachments, and approval statuses.
                </p>
            </div>

            <div>
                <a href="<?php echo e(route('staff.leaves.create')); ?>"
                   class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold bg-indigo-600 hover:bg-indigo-700 text-white shadow-sm shadow-indigo-600/20 transition">
                    <i class="fa-solid fa-plus text-xs"></i>
                    <span>Apply for Leave</span>
                </a>
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-6 sm:py-8 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h2 class="text-sm font-bold text-slate-900">Leave Applications Log</h2>
                        <p class="text-xs text-slate-500 mt-0.5">Track your submitted requests and approval status</p>
                    </div>
                    <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-slate-100 text-slate-600">
                        <?php echo e($leaves->total() ?? $leaves->count()); ?> Records
                    </span>
                </div>

                <div class="overflow-x-auto w-full">
                    <table class="w-full text-xs text-left">
                        <thead class="bg-slate-50/80 text-slate-500 font-semibold uppercase text-[11px] tracking-wider border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-3.5">Leave Type</th>
                                <th class="px-6 py-3.5">Dates Scheduled</th>
                                <th class="px-6 py-3.5">Duration</th>
                                <th class="px-6 py-3.5">Attachment</th>
                                <th class="px-6 py-3.5">Status</th>
                                <th class="px-6 py-3.5">Manager Notes</th>
                                <th class="px-6 py-3.5 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            <?php $__empty_1 = true; $__currentLoopData = $leaves; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $leave): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr class="hover:bg-slate-50/60 transition-colors">
                                    <td class="px-6 py-3.5 font-medium text-slate-900">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-xs font-medium bg-slate-100 text-slate-800">
                                            <?php echo e($leave->leave_type_label); ?>

                                        </span>
                                    </td>
                                    <td class="px-6 py-3.5 font-medium text-slate-700">
                                        <?php echo e($leave->start_date->format('d M Y')); ?> – <?php echo e($leave->end_date->format('d M Y')); ?>

                                    </td>
                                    <td class="px-6 py-3.5 font-semibold text-indigo-700">
                                        <?php echo e($leave->duration_in_days); ?> <?php echo e(Str::plural('Day', $leave->duration_in_days)); ?>

                                    </td>
                                    <td class="px-6 py-3.5">
                                        <?php if($leave->attachment): ?>
                                            <a href="<?php echo e(route('secure.leave.attachment', $leave)); ?>" target="_blank"
                                                class="inline-flex items-center gap-1.5 px-2 py-1 bg-purple-50 text-purple-700 hover:bg-purple-100 rounded-lg text-xs font-medium transition border border-purple-200/60">
                                                <i class="fa-solid fa-lock text-[10px]"></i>
                                                <span>Encrypted</span>
                                            </a>
                                        <?php else: ?>
                                            <span class="text-slate-400 text-xs">—</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-3.5">
                                        <?php
                                            $pillColor = match($leave->status) {
                                                'approved' => 'bg-emerald-50 text-emerald-700 border-emerald-200/60',
                                                'rejected' => 'bg-rose-50 text-rose-700 border-rose-200/60',
                                                default    => 'bg-amber-50 text-amber-700 border-amber-200/60',
                                            };
                                        ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold border <?php echo e($pillColor); ?>">
                                            <?php echo e(ucfirst($leave->status)); ?>

                                        </span>
                                    </td>
                                    <td class="px-5 py-3.5 font-sans text-slate-500 italic">
                                        <?php echo e($leave->admin_remarks ?? '—'); ?>

                                    </td>
                                    <td class="px-5 py-3.5 text-right font-sans">
                                        <?php if($leave->status === 'pending'): ?>
                                            <form action="<?php echo e(route('staff.leaves.destroy', $leave)); ?>" method="POST"
                                                  onsubmit="return confirm('Cancel this leave application?');" class="inline-block">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit"
                                                    class="inline-flex items-center px-2.5 py-1 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-100 text-[11px] font-bold transition border border-rose-200/50">
                                                    Cancel Request
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center text-slate-400 font-sans">
                                        <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 mx-auto flex items-center justify-center text-xl mb-3">
                                            <i class="fa-solid fa-calendar-check"></i>
                                        </div>
                                        <p class="font-bold text-slate-700">No leave applications submitted yet.</p>
                                        <p class="text-xs text-slate-400 mt-1">Need time off? Click "Apply for Leave" above.</p>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="pt-2">
                <?php echo e($leaves->links()); ?>

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
<?php /**PATH C:\laragon\www\laundrystaff-pro\resources\views/staff/leaves/index.blade.php ENDPATH**/ ?>