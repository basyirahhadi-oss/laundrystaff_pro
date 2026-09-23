<?php
    $user = auth()->user();
    $isAdmin = $user && $user->role === 'admin';
?>

<!-- CATEGORY 1: MAIN -->
<div>
    <p class="px-3 text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-2">
        Main
    </p>
    <div class="space-y-1">
        <?php if($isAdmin): ?>
            
            <?php $isActive = request()->routeIs('admin.attendance.*'); ?>
            <a href="<?php echo e(route('admin.attendance.index')); ?>"
               class="group flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all <?php echo e($isActive ? 'bg-white/10 text-white font-semibold shadow-xs border border-white/5' : 'text-slate-400 hover:text-white hover:bg-white/5 font-medium border border-transparent'); ?>">
                <i class="fa-regular fa-clock w-5 text-center text-sm <?php echo e($isActive ? 'text-white' : 'text-slate-400 group-hover:text-white'); ?>"></i>
                <span>Attendance</span>
            </a>

            
            <?php $isActive = request()->routeIs('admin.leaves.*'); ?>
            <a href="<?php echo e(route('admin.leaves.index')); ?>"
               class="group flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all <?php echo e($isActive ? 'bg-white/10 text-white font-semibold shadow-xs border border-white/5' : 'text-slate-400 hover:text-white hover:bg-white/5 font-medium border border-transparent'); ?>">
                <i class="fa-regular fa-calendar-check w-5 text-center text-sm <?php echo e($isActive ? 'text-white' : 'text-slate-400 group-hover:text-white'); ?>"></i>
                <span>Leaves</span>
            </a>
        <?php else: ?>
            
            <?php $isActive = request()->routeIs('staff.dashboard'); ?>
            <a href="<?php echo e(route('staff.dashboard')); ?>"
               class="group flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all <?php echo e($isActive ? 'bg-white/10 text-white font-semibold shadow-xs border border-white/5' : 'text-slate-400 hover:text-white hover:bg-white/5 font-medium border border-transparent'); ?>">
                <i class="fa-regular fa-clock w-5 text-center text-sm <?php echo e($isActive ? 'text-white' : 'text-slate-400 group-hover:text-white'); ?>"></i>
                <span>Dashboard</span>
            </a>

            
            <?php $isActive = request()->routeIs('staff.leaves.index'); ?>
            <a href="<?php echo e(route('staff.leaves.index')); ?>"
               class="group flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all <?php echo e($isActive ? 'bg-white/10 text-white font-semibold shadow-xs border border-white/5' : 'text-slate-400 hover:text-white hover:bg-white/5 font-medium border border-transparent'); ?>">
                <i class="fa-regular fa-calendar-days w-5 text-center text-sm <?php echo e($isActive ? 'text-white' : 'text-slate-400 group-hover:text-white'); ?>"></i>
                <span>My Leaves</span>
            </a>

            
            <?php $isActive = request()->routeIs('staff.leaves.create'); ?>
            <a href="<?php echo e(route('staff.leaves.create')); ?>"
               class="group flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all <?php echo e($isActive ? 'bg-white/10 text-white font-semibold shadow-xs border border-white/5' : 'text-slate-400 hover:text-white hover:bg-white/5 font-medium border border-transparent'); ?>">
                <i class="fa-regular fa-paper-plane w-5 text-center text-sm <?php echo e($isActive ? 'text-white' : 'text-slate-400 group-hover:text-white'); ?>"></i>
                <span>Apply Leave</span>
            </a>

            
            <?php $isActive = request()->routeIs('staff.payroll.index'); ?>
            <a href="<?php echo e(route('staff.payroll.index')); ?>"
               class="group flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all <?php echo e($isActive ? 'bg-white/10 text-white font-semibold shadow-xs border border-white/5' : 'text-slate-400 hover:text-white hover:bg-white/5 font-medium border border-transparent'); ?>">
                <i class="fa-solid fa-file-invoice-dollar w-5 text-center text-sm <?php echo e($isActive ? 'text-white' : 'text-slate-400 group-hover:text-white'); ?>"></i>
                <span>My Payroll</span>
            </a>
        <?php endif; ?>
    </div>
</div>

<!-- CATEGORY 2: MANAGEMENT (Admin only) -->
<?php if($isAdmin): ?>
<div>
    <p class="px-3 text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-2">
        Management
    </p>
    <div class="space-y-1">
        
        <?php $isActive = request()->routeIs('staff.index') || request()->routeIs('staff.edit'); ?>
        <a href="<?php echo e(route('staff.index')); ?>"
           class="group flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all <?php echo e($isActive ? 'bg-white/10 text-white font-semibold shadow-xs border border-white/5' : 'text-slate-400 hover:text-white hover:bg-white/5 font-medium border border-transparent'); ?>">
            <i class="fa-solid fa-users w-5 text-center text-sm <?php echo e($isActive ? 'text-white' : 'text-slate-400 group-hover:text-white'); ?>"></i>
            <span>Staff Directory</span>
        </a>

        
        <?php $isActive = request()->routeIs('staff.payroll.*'); ?>
        <a href="<?php echo e(route('staff.payroll.history')); ?>"
           class="group flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all <?php echo e($isActive ? 'bg-white/10 text-white font-semibold shadow-xs border border-white/5' : 'text-slate-400 hover:text-white hover:bg-white/5 font-medium border border-transparent'); ?>">
            <i class="fa-solid fa-file-invoice-dollar w-5 text-center text-sm <?php echo e($isActive ? 'text-white' : 'text-slate-400 group-hover:text-white'); ?>"></i>
            <span>Payroll</span>
        </a>
    </div>
</div>
<?php endif; ?>

<!-- CATEGORY 3: ANALYTICS & AUDIT -->
<div>
    <p class="px-3 text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-2">
        <?php echo e($isAdmin ? 'Analytics & Audit' : 'Analytics'); ?>

    </p>
    <div class="space-y-1">
        <?php if($isAdmin): ?>
            
            <?php $isActive = request()->routeIs('admin.reports.*'); ?>
            <a href="<?php echo e(route('admin.reports.index')); ?>"
               class="group flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all <?php echo e($isActive ? 'bg-white/10 text-white font-semibold shadow-xs border border-white/5' : 'text-slate-400 hover:text-white hover:bg-white/5 font-medium border border-transparent'); ?>">
                <i class="fa-solid fa-chart-pie w-5 text-center text-sm <?php echo e($isActive ? 'text-white' : 'text-slate-400 group-hover:text-white'); ?>"></i>
                <span>Reports</span>
            </a>

            
            <?php $isActive = request()->routeIs('admin.security.*'); ?>
            <a href="<?php echo e(route('admin.security.index')); ?>"
               class="group flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all <?php echo e($isActive ? 'bg-white/10 text-white font-semibold shadow-xs border border-white/5' : 'text-slate-400 hover:text-white hover:bg-white/5 font-medium border border-transparent'); ?>">
                <i class="fa-solid fa-shield-halved w-5 text-center text-sm <?php echo e($isActive ? 'text-[#E11D74]' : 'text-slate-400 group-hover:text-white'); ?>"></i>
                <span>Security Audit</span>
            </a>
        <?php else: ?>
            
            <?php $isActive = request()->routeIs('staff.analytics.*'); ?>
            <a href="<?php echo e(route('staff.analytics.index')); ?>"
               class="group flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all <?php echo e($isActive ? 'bg-white/10 text-white font-semibold shadow-xs border border-white/5' : 'text-slate-400 hover:text-white hover:bg-white/5 font-medium border border-transparent'); ?>">
                <i class="fa-solid fa-chart-line w-5 text-center text-sm <?php echo e($isActive ? 'text-white' : 'text-slate-400 group-hover:text-white'); ?>"></i>
                <span>Performance</span>
            </a>
        <?php endif; ?>
    </div>
</div>

<!-- CATEGORY 4: SYSTEM -->
<div>
    <p class="px-3 text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-2">
        System
    </p>
    <div class="space-y-1">
        
        <a href="<?php echo e(route('kiosk.gateway')); ?>" 
           target="_blank"
           class="group flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm text-slate-400 hover:text-white hover:bg-white/5 font-medium transition-all border border-transparent">
            <i class="fa-solid fa-camera w-5 text-center text-sm text-slate-400 group-hover:text-white"></i>
            <span>Kiosk Terminal</span>
            <i class="fa-solid fa-arrow-up-right-from-square text-[10px] text-slate-500 ml-auto group-hover:text-slate-300"></i>
        </a>

        
        <?php $isActive = request()->routeIs('profile.edit'); ?>
        <a href="<?php echo e(route('profile.edit')); ?>"
           class="group flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all <?php echo e($isActive ? 'bg-white/10 text-white font-semibold shadow-xs border border-white/5' : 'text-slate-400 hover:text-white hover:bg-white/5 font-medium border border-transparent'); ?>">
            <i class="fa-regular fa-user w-5 text-center text-sm <?php echo e($isActive ? 'text-white' : 'text-slate-400 group-hover:text-white'); ?>"></i>
            <span>Profile Settings</span>
        </a>

        
        <form method="POST" action="<?php echo e(route('logout')); ?>" class="w-full">
            <?php echo csrf_field(); ?>
            <button type="submit"
                    class="w-full group flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm text-slate-400 hover:text-rose-400 hover:bg-rose-500/10 font-medium transition-all border border-transparent text-left">
                <i class="fa-solid fa-arrow-right-from-bracket w-5 text-center text-sm text-slate-400 group-hover:text-rose-400"></i>
                <span>Sign Out</span>
            </button>
        </form>
    </div>
</div>
<?php /**PATH C:\laragon\www\laundrystaff-pro\resources\views/layouts/sidebar-links.blade.php ENDPATH**/ ?>