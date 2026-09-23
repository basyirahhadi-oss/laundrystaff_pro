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
                    Staff Management
                </span>
                <h1 class="text-xl font-bold text-slate-900 tracking-tight">
                    Personnel Directory
                </h1>
                <p class="text-xs sm:text-sm font-medium text-slate-500 mt-1">
                    Manage active laundry staff, employment roles, and biometric profiles.
                </p>
            </div>

            <div class="flex items-center gap-2">
                <a href="<?php echo e(route('staff.payroll.history')); ?>"
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold text-white shadow-md transition hover:-translate-y-0.5"
                   style="background-color: #4A154B; color: #ffffff;">
                    <i class="fa-solid fa-file-invoice-dollar text-[#E11D74]"></i>
                    <span>Payroll &amp; Statutory Ledgers</span>
                </a>
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-8 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

                
                <?php if(auth()->user()->role === 'admin' || auth()->user()->email == 'admin@zaujati.com'): ?>
                <div class="lg:col-span-4">
                    <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-sm sticky top-24">
                        <div class="flex items-center gap-3 pb-4 mb-4 border-b border-slate-100">
                            <div class="w-9 h-9 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-sm font-bold">
                                <i class="fa-solid fa-user-plus"></i>
                            </div>
                            <div>
                                <h3 class="text-sm font-bold text-slate-900">Register New Staff</h3>
                                <p class="text-[11px] text-slate-400">Onboard employee & facial biometric profile</p>
                            </div>
                        </div>

                        <form action="<?php echo e(route('staff.store')); ?>" method="POST" enctype="multipart/form-data" class="space-y-4">
                            <?php echo csrf_field(); ?>

                            <div>
                                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">Staff ID / Payroll Code</label>
                                <input type="text" name="staff_id" placeholder="e.g. STF01"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-medium text-slate-800 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition" required>
                            </div>

                            <div>
                                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">Full Legal Name</label>
                                <input type="text" name="full_name" placeholder="e.g. Siti Nurhaliza"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-medium text-slate-800 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition" required>
                            </div>

                            <div>
                                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">Assigned Operational Role</label>
                                <select name="position"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-medium text-slate-800 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition" required>
                                    <option value="Washing Machine Operator">Washing Machine Operator</option>
                                    <option value="Ironing Specialist">Ironing Specialist</option>
                                    <option value="Counter Staff">Counter Staff</option>
                                    <option value="Delivery Driver">Delivery Driver</option>
                                    <option value="Laundry Supervisor">Laundry Supervisor</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">Phone Number</label>
                                <input type="text" name="phone_number" placeholder="e.g. 0123456789"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-medium text-slate-800 font-mono-nums focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition" required>
                            </div>

                            <div>
                                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">Monthly Base Salary (RM)</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-xs font-bold text-slate-400">RM</span>
                                    <input type="number" step="0.01" name="salary_rate" placeholder="1500.00"
                                        class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-9 pr-3 py-2 text-xs font-bold text-slate-800 font-mono-nums focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition" required>
                                </div>
                            </div>

                            <div>
                                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">Facial Photo (For Kiosk AI Auth)</label>
                                <input type="file" name="profile_picture" accept="image/*"
                                    class="w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-[11px] file:font-bold file:uppercase file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 border border-slate-200 rounded-xl p-1 bg-slate-50" />
                                <p class="text-[10px] text-slate-400 mt-1">Clear front-facing photo used by kiosk face landmark algorithm.</p>
                            </div>

                            <button type="submit"
                                class="w-full inline-flex items-center justify-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs py-2.5 rounded-xl shadow-sm shadow-indigo-600/20 transition">
                                <i class="fa-solid fa-plus"></i>
                                <span>Save &amp; Enroll Employee</span>
                            </button>
                        </form>
                    </div>
                </div>
                <?php endif; ?>

                
                <div class="<?php echo e((auth()->user()->role === 'admin' || auth()->user()->email == 'admin@zaujati.com') ? 'lg:col-span-8' : 'lg:col-span-12'); ?>">
                    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b border-slate-100 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2">
                            <div>
                                <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Active Staff Members</h2>
                                <p class="text-xs text-slate-400">Total registered workforce across all laundry stations</p>
                            </div>
                            <span class="text-xs font-bold px-2.5 py-1 rounded-full bg-slate-100 text-slate-600 font-mono-nums">
                                <?php echo e($staffList->count()); ?> Employees Enrolled
                            </span>
                        </div>

                        <div class="overflow-x-auto w-full">
                            <table class="w-full text-xs text-left">
                                <thead class="bg-slate-50/80 text-slate-400 font-bold uppercase tracking-wider border-b border-slate-100">
                                    <tr>
                                        <th class="px-5 py-3.5">Employee ID</th>
                                        <th class="px-5 py-3.5">Staff Details</th>
                                        <th class="px-5 py-3.5">Position</th>
                                        <th class="px-5 py-3.5">Phone Contact</th>
                                        <th class="px-5 py-3.5">Base Salary</th>
                                        <th class="px-5 py-3.5 text-right">Operations</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 text-slate-700">
                                    <?php $__empty_1 = true; $__currentLoopData = $staffList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $staff): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr class="hover:bg-slate-50/70 transition-colors group">
                                            <td class="px-5 py-3.5 font-bold font-mono-nums text-indigo-700">
                                                <span class="px-2 py-1 rounded-lg bg-indigo-50 border border-indigo-100/80">
                                                    <?php echo e($staff->staff_id); ?>

                                                </span>
                                            </td>

                                            <td class="px-5 py-3.5">
                                                <div class="flex items-center gap-3">
                                                    <?php if(!empty($staff->profile_picture) && file_exists(public_path('uploads/staff/' . $staff->profile_picture))): ?>
                                                        <img src="<?php echo e(asset('uploads/staff/' . $staff->profile_picture)); ?>" alt="Profile"
                                                             class="w-9 h-9 rounded-xl object-cover border border-slate-200 shadow-sm flex-shrink-0">
                                                    <?php else: ?>
                                                        <div class="w-9 h-9 rounded-xl bg-slate-100 text-slate-600 font-black flex items-center justify-center text-xs flex-shrink-0 border border-slate-200">
                                                            <?php echo e(substr($staff->full_name, 0, 2)); ?>

                                                        </div>
                                                    <?php endif; ?>
                                                    <div>
                                                        <div class="font-bold text-slate-900 text-xs"><?php echo e($staff->full_name); ?></div>
                                                        <div class="text-[10px] text-emerald-600 font-semibold flex items-center gap-1">
                                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                            Biometrics Active
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>

                                            <td class="px-5 py-3.5">
                                                <span class="px-2.5 py-0.5 inline-flex text-[11px] font-bold rounded-lg bg-slate-100 text-slate-700 border border-slate-200/60">
                                                    <?php echo e($staff->position); ?>

                                                </span>
                                            </td>

                                            <td class="px-5 py-3.5 font-mono-nums text-slate-600">
                                                <?php echo e($staff->phone_number); ?>

                                            </td>

                                            <td class="px-5 py-3.5 font-bold font-mono-nums text-slate-900">
                                                RM <?php echo e(number_format($staff->salary_rate ?? $staff->salary ?? 0, 2)); ?>

                                                <span class="text-[10px] text-slate-400 font-normal block">per month</span>
                                            </td>

                                            <td class="px-5 py-3.5 text-right">
                                                <div class="flex items-center justify-end gap-1.5">
                                                    <a href="<?php echo e(route('staff.edit', $staff->staff_id)); ?>"
                                                       class="inline-flex items-center gap-1 px-2.5 py-1 bg-slate-100 hover:bg-indigo-50 hover:text-indigo-700 text-slate-600 rounded-lg text-[11px] font-bold transition">
                                                        <i class="fa-solid fa-pen-to-square text-[10px]"></i>
                                                        <span>Edit</span>
                                                    </a>

                                                    <?php if(auth()->user()->role === 'admin' || auth()->user()->email == 'admin@zaujati.com'): ?>
                                                        <a href="<?php echo e(route('staff.payroll.create', ['id' => $staff->staff_id])); ?>"
                                                           class="inline-flex items-center gap-1 px-2.5 py-1 bg-emerald-50 text-emerald-700 hover:bg-emerald-100 rounded-lg text-[11px] font-bold transition border border-emerald-200/50">
                                                            <i class="fa-solid fa-calculator text-[10px]"></i>
                                                            <span>Payroll</span>
                                                        </a>

                                                        <form action="<?php echo e(route('staff.destroy', $staff->staff_id)); ?>" method="POST" class="inline-block"
                                                              onsubmit="return confirm('Confirm deletion of <?php echo e(addslashes($staff->full_name)); ?>?');">
                                                            <?php echo csrf_field(); ?>
                                                            <?php echo method_field('DELETE'); ?>
                                                            <button type="submit"
                                                                class="inline-flex items-center gap-1 px-2.5 py-1 bg-rose-50 text-rose-600 hover:bg-rose-100 rounded-lg text-[11px] font-bold transition border border-rose-200/50"
                                                                title="Delete Staff Member">
                                                                <i class="fa-solid fa-trash-can text-[10px]"></i>
                                                                <span>Delete</span>
                                                            </button>
                                                        </form>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                                                <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 mx-auto flex items-center justify-center text-xl mb-3">
                                                    <i class="fa-solid fa-user-xmark"></i>
                                                </div>
                                                <p class="font-bold text-slate-700">No staff members enrolled yet.</p>
                                                <p class="text-xs text-slate-400 mt-1">Use the registration form on the left to add your first laundry employee.</p>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
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
<?php endif; ?><?php /**PATH C:\laragon\www\laundrystaff-pro\resources\views/staff/index.blade.php ENDPATH**/ ?>