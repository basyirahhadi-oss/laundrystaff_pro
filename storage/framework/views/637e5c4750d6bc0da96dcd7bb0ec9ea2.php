<?php
    $user = auth()->user();
    $isAdmin = $user && $user->role === 'admin';
?>

<!-- ========================================================================= -->
<!-- UNIFIED TOP HEADER BAR (Right Canvas Header - Rahmah SaaS Architecture)   -->
<!-- ========================================================================= -->
<header class="sticky top-0 z-30 bg-white/95 backdrop-blur-md border-b border-slate-200/80 shadow-2xs">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between min-h-[4.5rem] py-3 gap-4">
            
            <!-- 1. Left: Mobile Drawer Trigger + Page Header Title & Subtitle -->
            <div class="flex items-center gap-3 min-w-0 flex-1">
                <!-- Mobile Hamburger Toggle -->
                <button @click="sidebarOpen = true" 
                        type="button" 
                        class="md:hidden inline-flex items-center justify-center p-2 rounded-xl text-slate-500 hover:text-slate-900 hover:bg-slate-100 focus:outline-none transition shrink-0"
                        title="Open Menu">
                    <i class="fa-solid fa-bars text-base"></i>
                </button>

                <!-- Page Header (Injected dynamically from  <?php $__env->slot('header', null, []); ?> ) -->
                <div class="min-w-0 flex-1">
                    <?php if(isset($header)): ?>
                        <?php echo e($header); ?>

                    <?php else: ?>
                        <div>
                            <span class="text-xs font-bold uppercase tracking-widest text-slate-400 block mb-0.5">Workspace</span>
                            <h1 class="text-xl font-bold text-slate-900 tracking-tight">LaundryStaff Pro</h1>
                            <p class="text-xs text-slate-500">Enterprise HRMS &amp; Operations</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- 2. Right: Global Quick Actions & User Profile (Rahmah Style) -->
            <div class="flex items-center gap-2.5 sm:gap-3 shrink-0">
                
                <!-- Quick Kiosk Terminal Action -->
                <a href="<?php echo e(route('kiosk.gateway')); ?>" 
                   target="_blank"
                   title="Open Biometric Kiosk Terminal in new tab"
                   class="hidden sm:inline-flex items-center gap-2 px-3 py-1.5 rounded-xl text-xs font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 border border-slate-200/70 transition active:scale-98">
                    <i class="fa-solid fa-camera text-slate-500 text-xs"></i>
                    <span>Kiosk</span>
                </a>

                <!-- User Profile Dropdown Pill (Rahmah Style) -->
                <?php if (isset($component)) { $__componentOriginaldf8083d4a852c446488d8d384bbc7cbe = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldf8083d4a852c446488d8d384bbc7cbe = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dropdown','data' => ['align' => 'right','width' => '56']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dropdown'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['align' => 'right','width' => '56']); ?>
                     <?php $__env->slot('trigger', null, []); ?> 
                        <button class="inline-flex items-center gap-2.5 px-2.5 py-1.5 rounded-xl border border-slate-200/90 bg-white hover:bg-slate-50 text-slate-800 text-sm font-medium focus:outline-none transition shadow-2xs">
                            <div class="w-8 h-8 rounded-lg text-white flex items-center justify-center font-bold text-xs uppercase bg-[#0B1527] shadow-xs">
                                <?php echo e(substr($user->name ?? 'A', 0, 1)); ?>

                            </div>
                            <div class="text-left hidden sm:block">
                                <div class="font-semibold text-slate-900 leading-tight text-xs"><?php echo e($user->name ?? 'User'); ?></div>
                                <div class="text-[10px] text-slate-400 capitalize leading-tight">
                                    <?php echo e($isAdmin ? 'Administrator' : 'Staff Member'); ?>

                                </div>
                            </div>
                            <i class="fa-solid fa-chevron-down text-[10px] text-slate-400 hidden sm:inline ml-0.5"></i>
                        </button>
                     <?php $__env->endSlot(); ?>

                     <?php $__env->slot('content', null, []); ?> 
                        <div class="px-4 py-3 border-b border-slate-100 text-sm">
                            <p class="font-bold text-slate-900 text-sm"><?php echo e($user->name); ?></p>
                            <p class="text-slate-400 text-xs truncate"><?php echo e($user->email); ?></p>
                            <span class="inline-block mt-1.5 px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-slate-100 text-slate-600">
                                Role: <?php echo e($user->role); ?>

                            </span>
                        </div>

                        <?php if (isset($component)) { $__componentOriginal68cb1971a2b92c9735f83359058f7108 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal68cb1971a2b92c9735f83359058f7108 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dropdown-link','data' => ['href' => route('profile.edit'),'class' => 'flex items-center gap-2.5 text-sm text-slate-700 hover:bg-slate-50 py-2.5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dropdown-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('profile.edit')),'class' => 'flex items-center gap-2.5 text-sm text-slate-700 hover:bg-slate-50 py-2.5']); ?>
                            <i class="fa-regular fa-user text-slate-400 text-xs"></i>
                            <span><?php echo e(__('Profile Settings')); ?></span>
                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal68cb1971a2b92c9735f83359058f7108)): ?>
<?php $attributes = $__attributesOriginal68cb1971a2b92c9735f83359058f7108; ?>
<?php unset($__attributesOriginal68cb1971a2b92c9735f83359058f7108); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal68cb1971a2b92c9735f83359058f7108)): ?>
<?php $component = $__componentOriginal68cb1971a2b92c9735f83359058f7108; ?>
<?php unset($__componentOriginal68cb1971a2b92c9735f83359058f7108); ?>
<?php endif; ?>

                        <!-- Authentication -->
                        <form method="POST" action="<?php echo e(route('logout')); ?>">
                            <?php echo csrf_field(); ?>
                            <?php if (isset($component)) { $__componentOriginal68cb1971a2b92c9735f83359058f7108 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal68cb1971a2b92c9735f83359058f7108 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.dropdown-link','data' => ['href' => route('logout'),'onclick' => 'event.preventDefault(); this.closest(\'form\').submit();','class' => 'flex items-center gap-2.5 text-sm text-rose-600 hover:bg-rose-50 font-medium py-2.5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dropdown-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('logout')),'onclick' => 'event.preventDefault(); this.closest(\'form\').submit();','class' => 'flex items-center gap-2.5 text-sm text-rose-600 hover:bg-rose-50 font-medium py-2.5']); ?>
                                <i class="fa-solid fa-arrow-right-from-bracket text-rose-400 text-xs"></i>
                                <span><?php echo e(__('Sign Out')); ?></span>
                             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal68cb1971a2b92c9735f83359058f7108)): ?>
<?php $attributes = $__attributesOriginal68cb1971a2b92c9735f83359058f7108; ?>
<?php unset($__attributesOriginal68cb1971a2b92c9735f83359058f7108); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal68cb1971a2b92c9735f83359058f7108)): ?>
<?php $component = $__componentOriginal68cb1971a2b92c9735f83359058f7108; ?>
<?php unset($__componentOriginal68cb1971a2b92c9735f83359058f7108); ?>
<?php endif; ?>
                        </form>
                     <?php $__env->endSlot(); ?>
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaldf8083d4a852c446488d8d384bbc7cbe)): ?>
<?php $attributes = $__attributesOriginaldf8083d4a852c446488d8d384bbc7cbe; ?>
<?php unset($__attributesOriginaldf8083d4a852c446488d8d384bbc7cbe); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaldf8083d4a852c446488d8d384bbc7cbe)): ?>
<?php $component = $__componentOriginaldf8083d4a852c446488d8d384bbc7cbe; ?>
<?php unset($__componentOriginaldf8083d4a852c446488d8d384bbc7cbe); ?>
<?php endif; ?>
            </div>

        </div>
    </div>
</header>

<!-- ========================================================================= -->
<!-- 3. MINIMALIST MOBILE BOTTOM NAVIGATION BAR (iOS / Mobile App Style)       -->
<!-- ========================================================================= -->
<div class="md:hidden fixed bottom-0 left-0 right-0 z-50 bg-white/95 backdrop-blur-md border-t border-slate-200/80 shadow-xs px-3 py-2 safe-area-pb">
    <div class="flex items-center justify-around max-w-sm mx-auto">
        <?php if($isAdmin): ?>
            
            <a href="<?php echo e(route('admin.attendance.index')); ?>" 
               class="flex flex-col items-center gap-1 py-1 px-3 rounded-xl transition <?php echo e(request()->routeIs('admin.attendance.*') ? 'text-slate-900 font-bold' : 'text-slate-400 hover:text-slate-600'); ?>">
                <i class="fa-regular fa-clock text-base"></i>
                <span class="text-[10px] tracking-tight">Attendance</span>
            </a>

            <a href="<?php echo e(route('admin.leaves.index')); ?>" 
               class="flex flex-col items-center gap-1 py-1 px-3 rounded-xl transition <?php echo e(request()->routeIs('admin.leaves.*') ? 'text-slate-900 font-bold' : 'text-slate-400 hover:text-slate-600'); ?>">
                <i class="fa-regular fa-calendar-check text-base"></i>
                <span class="text-[10px] tracking-tight">Leaves</span>
            </a>

            <a href="<?php echo e(route('staff.index')); ?>" 
               class="flex flex-col items-center gap-1 py-1 px-3 rounded-xl transition <?php echo e(request()->routeIs('staff.index') || request()->routeIs('staff.edit') ? 'text-slate-900 font-bold' : 'text-slate-400 hover:text-slate-600'); ?>">
                <i class="fa-solid fa-users text-base"></i>
                <span class="text-[10px] tracking-tight">Staff</span>
            </a>

            <a href="<?php echo e(route('profile.edit')); ?>" 
               class="flex flex-col items-center gap-1 py-1 px-3 rounded-xl transition <?php echo e(request()->routeIs('profile.edit') ? 'text-slate-900 font-bold' : 'text-slate-400 hover:text-slate-600'); ?>">
                <i class="fa-regular fa-user text-base"></i>
                <span class="text-[10px] tracking-tight">Profile</span>
            </a>
        <?php else: ?>
            
            <a href="<?php echo e(route('staff.dashboard')); ?>" 
               class="flex flex-col items-center gap-1 py-1 px-3 rounded-xl transition <?php echo e(request()->routeIs('staff.dashboard') ? 'text-indigo-600 font-bold' : 'text-slate-400 hover:text-slate-600'); ?>">
                <i class="fa-solid fa-house text-base"></i>
                <span class="text-[10px] font-medium tracking-tight">Home</span>
            </a>

            <a href="<?php echo e(route('staff.leaves.index')); ?>" 
               class="flex flex-col items-center gap-1 py-1 px-3 rounded-xl transition <?php echo e(request()->routeIs('staff.leaves.*') ? 'text-indigo-600 font-bold' : 'text-slate-400 hover:text-slate-600'); ?>">
                <i class="fa-regular fa-calendar-days text-base"></i>
                <span class="text-[10px] font-medium tracking-tight">Leaves</span>
            </a>

            <a href="<?php echo e(route('staff.analytics.index')); ?>" 
               class="flex flex-col items-center gap-1 py-1 px-3 rounded-xl transition <?php echo e(request()->routeIs('staff.analytics.*') ? 'text-indigo-600 font-bold' : 'text-slate-400 hover:text-slate-600'); ?>">
                <i class="fa-solid fa-chart-line text-base"></i>
                <span class="text-[10px] font-medium tracking-tight">Analytics</span>
            </a>

            <a href="<?php echo e(route('profile.edit')); ?>" 
               class="flex flex-col items-center gap-1 py-1 px-3 rounded-xl transition <?php echo e(request()->routeIs('profile.edit') ? 'text-indigo-600 font-bold' : 'text-slate-400 hover:text-slate-600'); ?>">
                <i class="fa-regular fa-user text-base"></i>
                <span class="text-[10px] font-medium tracking-tight">Profile</span>
            </a>
        <?php endif; ?>
    </div>
</div>
<?php /**PATH C:\laragon\www\laundrystaff-pro\resources\views/layouts/navigation.blade.php ENDPATH**/ ?>