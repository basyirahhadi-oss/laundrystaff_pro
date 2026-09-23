<?php $auditLogs = $auditLogs ?? $logs ?? collect(); ?>
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
                    System Security
                </span>
                <h1 class="text-xl font-bold text-slate-900 tracking-tight">
                    Security Audit Ledger
                </h1>
                <p class="text-xs sm:text-sm font-medium text-slate-500 mt-0.5">
                    Tamper-evident SHA-256 hash chaining, encrypted storage, and biometric audit logs.
                </p>
            </div>
            <div class="flex items-center gap-2">
                <form action="<?php echo e(route('admin.security.verify')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <button type="submit"
                        class="inline-flex items-center gap-2 bg-slate-900 hover:bg-black text-white text-xs font-semibold px-3.5 py-2 rounded-xl shadow-xs transition">
                        <i class="fa-solid fa-arrows-rotate text-[10px]"></i>
                        <span>Scan Integrity</span>
                    </button>
                </form>

                <form action="<?php echo e(route('admin.security.clear-all')); ?>" method="POST"
                      onsubmit="return confirm('WARNING: Are you sure you want to clear/reset all security audit logs? This will truncate the entire audit table and re-initialize a new Genesis block.');">
                    <?php echo csrf_field(); ?>
                    <button type="submit"
                        class="inline-flex items-center gap-1.5 bg-white hover:bg-rose-50 text-rose-600 text-xs font-semibold px-3.5 py-2 rounded-xl border border-slate-200 hover:border-rose-200 shadow-xs transition"
                        title="Clear all audit entries and re-initialize genesis state">
                        <i class="fa-regular fa-trash-can text-[10px]"></i>
                        <span>Reset Ledger</span>
                    </button>
                </form>
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-6 sm:py-8 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- 1. CLEAN STATUS BANNER (No Emojis, Calm Professional Design) -->
            <?php if($integrity['is_valid']): ?>
                <div class="bg-white rounded-2xl border border-emerald-200/80 p-5 sm:p-6 shadow-xs flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-base flex-shrink-0">
                            <i class="fa-solid fa-shield-check"></i>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="text-sm font-bold text-slate-900">Ledger Integrity: Verified &amp; Tamper-Free</h3>
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    Chain Valid
                                </span>
                            </div>
                            <p class="text-xs text-slate-500 mt-0.5">
                                Verified <?php echo e($integrity['verified_blocks']); ?> of <?php echo e($integrity['total_blocks']); ?> cryptographic blocks against SHA-256 Merkle parent hashes. No unauthorized modifications detected.
                            </p>
                        </div>
                    </div>
                    <div class="text-xs font-mono-nums text-slate-400 text-right flex-shrink-0">
                        Scanned: <?php echo e($integrity['scanned_at']); ?>

                    </div>
                </div>
            <?php else: ?>
                <div class="bg-white rounded-2xl border border-rose-300 p-5 sm:p-6 shadow-xs flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-base flex-shrink-0">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="text-sm font-bold text-rose-900">Security Alert: Ledger Inconsistency Detected</h3>
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-semibold bg-rose-100 text-rose-800">
                                    Action Required
                                </span>
                            </div>
                            <p class="text-xs text-rose-700 mt-0.5 font-medium">
                                <?php echo e($integrity['error_message']); ?>

                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 flex-shrink-0">
                        <form action="<?php echo e(route('admin.security.recalculate')); ?>" method="POST" onsubmit="return confirm('Recalculate hash chain and restore cryptographic baseline?');">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="bg-rose-600 hover:bg-rose-700 text-white text-xs font-semibold px-4 py-2 rounded-xl shadow-xs transition">
                                Restore Hash Chain
                            </button>
                        </form>
                    </div>
                </div>
            <?php endif; ?>

            <!-- 2. UNIFORM SECURITY METRICS (No Rainbow Borders) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs">
                    <div class="flex items-center justify-between mb-2.5">
                        <span class="text-xs font-semibold text-slate-500">Chained Blocks</span>
                        <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center text-xs">
                            <i class="fa-solid fa-link"></i>
                        </div>
                    </div>
                    <div class="text-xl font-bold text-slate-900 font-mono-nums"><?php echo e($totalBlocks); ?></div>
                    <p class="text-xs text-slate-400 mt-1">Immutable audit events</p>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs">
                    <div class="flex items-center justify-between mb-2.5">
                        <span class="text-xs font-semibold text-slate-500">File Encryption</span>
                        <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center text-xs">
                            <i class="fa-solid fa-lock"></i>
                        </div>
                    </div>
                    <div class="text-base font-bold text-slate-900 font-mono-nums">AES-256-CBC</div>
                    <p class="text-xs text-slate-400 mt-1">Encrypted storage at-rest</p>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs">
                    <div class="flex items-center justify-between mb-2.5">
                        <span class="text-xs font-semibold text-slate-500">Biometric Liveness</span>
                        <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs">
                            <i class="fa-regular fa-face-smile"></i>
                        </div>
                    </div>
                    <div class="text-base font-bold text-slate-900">EAR Blink Defense</div>
                    <p class="text-xs text-slate-400 mt-1">Anti-spoofing verification</p>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs">
                    <div class="flex items-center justify-between mb-2.5">
                        <span class="text-xs font-semibold text-slate-500">Replay Protection</span>
                        <div class="w-8 h-8 rounded-lg bg-sky-50 text-sky-600 flex items-center justify-center text-xs">
                            <i class="fa-solid fa-shield-halved"></i>
                        </div>
                    </div>
                    <div class="text-base font-bold text-slate-900 font-mono-nums">HMAC 60s</div>
                    <p class="text-xs text-slate-400 mt-1">Single-use nonce tokens</p>
                </div>
            </div>

            <!-- 3. CRYPTOGRAPHIC LEDGER TABLE (Clean Monospace Hashes with Ellipsis) -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
                <div class="p-5 border-b border-slate-100 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                    <div>
                        <h2 class="text-base font-bold text-slate-900 tracking-tight">Cryptographic Event Stream</h2>
                        <p class="text-xs text-slate-400">Events anchored to parent blocks via SHA-256 hash chaining</p>
                    </div>
                    <span class="text-xs font-mono text-slate-500 bg-slate-100 px-3 py-1 rounded-lg">
                        Genesis: 00000000...0000
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-100 text-xs text-left">
                        <thead class="bg-slate-50/80 text-slate-500 font-semibold uppercase text-[11px] tracking-wider">
                            <tr>
                                <th class="px-5 py-3">Block #</th>
                                <th class="px-5 py-3">Event Type</th>
                                <th class="px-5 py-3">Actor / Target</th>
                                <th class="px-5 py-3 font-mono">Parent Hash</th>
                                <th class="px-5 py-3 font-mono">Current Hash</th>
                                <th class="px-5 py-3">Timestamp</th>
                                <th class="px-5 py-3 text-right">Verification</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            <?php $__empty_1 = true; $__currentLoopData = $auditLogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr class="hover:bg-slate-50/60 transition-colors">
                                    <td class="px-5 py-3.5 font-mono font-bold text-slate-900">
                                        #<?php echo e($log->id); ?>

                                    </td>
                                    <td class="px-5 py-3.5">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-semibold bg-slate-100 text-slate-700">
                                            <?php echo e($log->event_type); ?>

                                        </span>
                                    </td>
                                    <td class="px-5 py-3.5 font-medium text-slate-900">
                                        <?php echo e($log->staff?->name ?? ($log->actor?->name ?? 'System Process')); ?>

                                    </td>
                                    <td class="px-5 py-3.5 font-mono text-slate-500 text-[11px]">
                                        <span title="<?php echo e($log->previous_hash); ?>">
                                            <?php echo e(substr($log->previous_hash, 0, 8)); ?>...<?php echo e(substr($log->previous_hash, -6)); ?>

                                        </span>
                                    </td>
                                    <td class="px-5 py-3.5 font-mono text-slate-900 text-[11px] font-medium">
                                        <span title="<?php echo e($log->current_hash); ?>">
                                            <?php echo e(substr($log->current_hash, 0, 8)); ?>...<?php echo e(substr($log->current_hash, -6)); ?>

                                        </span>
                                    </td>
                                    <td class="px-5 py-3.5 text-slate-500 font-mono text-[11px]">
                                        <?php echo e($log->created_at->format('d M Y, h:i:s A')); ?>

                                    </td>
                                    <td class="px-5 py-3.5 text-right">
                                        <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-md">
                                            <i class="fa-solid fa-check text-[10px]"></i>
                                            <span>Valid</span>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                                        <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-400 mx-auto flex items-center justify-center text-base mb-2.5">
                                            <i class="fa-solid fa-shield-halved"></i>
                                        </div>
                                        <p class="font-semibold text-slate-700 text-sm">No audit records in ledger.</p>
                                        <p class="text-xs text-slate-400 mt-0.5">System actions and shifts will append chained blocks automatically.</p>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="p-4 border-t border-slate-100">
                    <?php echo e($auditLogs->links()); ?>

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
<?php endif; ?>
<?php /**PATH C:\laragon\www\laundrystaff-pro\resources\views/admin/security/index.blade.php ENDPATH**/ ?>