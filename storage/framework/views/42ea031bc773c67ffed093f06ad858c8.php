<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Clock In — LaundryStaff Pro</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <style>
        body { 
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'SF Pro Display', 'SF Pro Text', sans-serif;
            letter-spacing: -0.018em;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            text-rendering: optimizeLegibility;
        }
        h1, h2, h3, h4, h5, h6 { letter-spacing: -0.03em; }
        .font-mono-nums { font-family: 'JetBrains Mono', monospace; }
        .glow-purple {
            box-shadow: 0 0 40px -10px rgba(74, 21, 75, 0.5);
        }
    </style>
</head>
<body class="bg-[#0B1527] text-slate-100 min-h-screen flex flex-col antialiased selection:bg-[#E11D74] selection:text-white relative overflow-x-hidden">

    
    <div class="absolute -top-32 -right-32 w-96 h-96 rounded-full blur-3xl pointer-events-none opacity-40" style="background: #4A154B;"></div>
    <div class="absolute -bottom-32 -left-32 w-96 h-96 rounded-full blur-3xl pointer-events-none opacity-30" style="background: #E11D74;"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-indigo-950/20 rounded-full blur-3xl pointer-events-none"></div>

    
    <header class="bg-slate-950/80 backdrop-blur-md border-b border-slate-800/80 sticky top-0 z-50">
        <div class="max-w-5xl mx-auto px-6 py-3.5 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-slate-900 border border-slate-700/60 p-1 flex items-center justify-center shrink-0 shadow-sm">
                    <img src="<?php echo e(asset('images/zaujati-logo.png')); ?>" 
                         alt="Zaujati Laundry" 
                         class="w-full h-full object-contain">
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="font-extrabold text-sm text-white tracking-tight">LaundryStaff</span>
                        <span class="px-1.5 py-0.5 rounded text-[10px] font-bold uppercase text-white tracking-wide" style="background-color: #4A154B;">PRO</span>
                        <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-full bg-[#E11D74]/15 text-[#E11D74] border border-[#E11D74]/30">
                            ADMIN CHECK-IN
                        </span>
                    </div>
                    <p class="text-[11px] text-slate-400 font-mono-nums">Terminal MY-KUL-01 · Authorized Punch Gateway</p>
                </div>
            </div>

            <a href="<?php echo e(route('kiosk.gateway')); ?>" 
               class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-xl text-xs font-bold bg-slate-900/90 hover:bg-slate-800 text-slate-300 hover:text-white transition border border-slate-700/80 shadow-xs group">
                <i class="fa-solid fa-arrow-left text-[11px] group-hover:-translate-x-0.5 transition-transform"></i>
                <span>Back to Gateway</span>
            </a>
        </div>
    </header>

    
    <main class="flex-1 flex items-center justify-center px-4 sm:px-6 py-10 relative z-10">
        <div class="max-w-md w-full">

            
            <div class="mb-5 flex items-center justify-between px-2 text-xs">
                <div class="flex items-center gap-2 text-slate-400">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span class="font-medium">System Terminal Ready</span>
                </div>
                <div id="live-clock" class="font-mono-nums font-bold text-slate-300 tracking-wider">
                    --:--:-- --
                </div>
            </div>

            
            <div class="bg-slate-950/80 backdrop-blur-xl rounded-3xl border border-slate-800/90 shadow-2xl p-6 sm:p-8 relative overflow-hidden glow-purple">

                
                <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-[#E11D74]/20 via-[#4A154B]/10 to-transparent rounded-bl-full pointer-events-none"></div>

                
                <div class="flex items-start gap-4 mb-6">
                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center shrink-0 shadow-md" style="background-color: #4A154B; border: 1px solid rgba(225, 29, 116, 0.4);">
                        <i class="fa-solid fa-shield-halved text-lg text-white"></i>
                    </div>
                    <div>
                        <h1 class="text-lg sm:text-xl font-bold text-white tracking-tight">Admin Shift Check-In</h1>
                        <p class="text-xs text-slate-400 mt-1 leading-relaxed">
                            Verify your administrator credentials to record today's shift punch and access management tools.
                        </p>
                    </div>
                </div>

                
                <?php if(session('error')): ?>
                    <div class="mb-5 rounded-2xl bg-rose-950/60 border border-rose-800/80 text-rose-300 text-xs px-4 py-3 flex items-start gap-2.5 shadow-sm">
                        <i class="fa-solid fa-circle-exclamation text-rose-400 text-sm mt-0.5 shrink-0"></i>
                        <span class="font-medium leading-relaxed"><?php echo e(session('error')); ?></span>
                    </div>
                <?php endif; ?>

                <?php if($errors->any()): ?>
                    <div class="mb-5 rounded-2xl bg-rose-950/60 border border-rose-800/80 text-rose-300 text-xs px-4 py-3 flex items-start gap-2.5 shadow-sm">
                        <i class="fa-solid fa-circle-exclamation text-rose-400 text-sm mt-0.5 shrink-0"></i>
                        <span class="font-medium leading-relaxed"><?php echo e($errors->first()); ?></span>
                    </div>
                <?php endif; ?>

                
                <form action="<?php echo e(route('kiosk.admin-confirm')); ?>" method="POST" class="space-y-4">
                    <?php echo csrf_field(); ?>

                    
                    <div>
                        <label for="email" class="block text-[11px] font-bold uppercase tracking-wider text-slate-300 mb-1.5 flex items-center justify-between">
                            <span>Admin Email</span>
                            <span class="text-[10px] text-slate-500 lowercase">e.g. admin@zaujati.com</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                                <i class="fa-regular fa-envelope text-xs"></i>
                            </div>
                            <input type="email" 
                                   name="email" 
                                   id="email"
                                   value="<?php echo e(old('email')); ?>" 
                                   required 
                                   autofocus 
                                   autocomplete="email"
                                   placeholder="name@company.com"
                                   class="w-full bg-slate-900/90 border border-slate-700/80 rounded-xl pl-9 pr-4 py-2.5 text-xs text-white placeholder:text-slate-600 focus:outline-none focus:border-[#E11D74] focus:ring-2 focus:ring-[#E11D74]/20 transition shadow-inner">
                        </div>
                    </div>

                    
                    <div>
                        <label for="password" class="block text-[11px] font-bold uppercase tracking-wider text-slate-300 mb-1.5 flex items-center justify-between">
                            <span>Password</span>
                            <span class="text-[10px] text-slate-500">Verified via Bcrypt</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                                <i class="fa-solid fa-lock text-xs"></i>
                            </div>
                            <input type="password" 
                                   name="password" 
                                   id="password" 
                                   required 
                                   autocomplete="current-password"
                                   placeholder="••••••••••••"
                                   class="w-full bg-slate-900/90 border border-slate-700/80 rounded-xl pl-9 pr-10 py-2.5 text-xs text-white placeholder:text-slate-600 focus:outline-none focus:border-[#E11D74] focus:ring-2 focus:ring-[#E11D74]/20 transition shadow-inner font-mono-nums">
                            <button type="button" 
                                    id="toggle-password" 
                                    class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-500 hover:text-slate-300 transition"
                                    title="Toggle password visibility">
                                <i class="fa-regular fa-eye text-xs" id="eye-icon"></i>
                            </button>
                        </div>
                    </div>

                    
                    <div class="pt-2">
                        <button type="submit" 
                                class="w-full text-white font-bold text-xs py-3.5 rounded-xl shadow-lg transition-all active:scale-[0.99] flex items-center justify-center gap-2 group cursor-pointer"
                                style="background: linear-gradient(135deg, #4A154B 0%, #6E2070 100%); border: 1px solid rgba(225, 29, 116, 0.4); box-shadow: 0 4px 20px rgba(74, 21, 75, 0.4);">
                            <i class="fa-solid fa-fingerprint text-sm text-[#E11D74] group-hover:scale-110 transition-transform"></i>
                            <span>Verify Identity &amp; Clock In</span>
                            <i class="fa-solid fa-arrow-right text-[11px] opacity-70 group-hover:translate-x-1 transition-transform ml-1"></i>
                        </button>
                    </div>
                </form>

                
                <div class="mt-6 pt-5 border-t border-slate-800/80 grid grid-cols-3 gap-2 text-center text-[10px] text-slate-400">
                    <div class="p-2 rounded-xl bg-slate-900/60 border border-slate-800 flex flex-col items-center gap-1">
                        <i class="fa-solid fa-link text-[#E11D74] text-xs"></i>
                        <span class="font-medium">SHA-256 Ledger</span>
                    </div>
                    <div class="p-2 rounded-xl bg-slate-900/60 border border-slate-800 flex flex-col items-center gap-1">
                        <i class="fa-solid fa-shield-virus text-emerald-400 text-xs"></i>
                        <span class="font-medium">Rate Throttled</span>
                    </div>
                    <div class="p-2 rounded-xl bg-slate-900/60 border border-slate-800 flex flex-col items-center gap-1">
                        <i class="fa-solid fa-clock text-amber-400 text-xs"></i>
                        <span class="font-medium">Auto Punch In/Out</span>
                    </div>
                </div>

                <p class="text-[11px] text-slate-500 mt-4 text-center leading-relaxed">
                    Recording attendance registers your shift time into the tamper-evident security audit log and automatically redirects you to the administrator dashboard.
                </p>

            </div>

            
            <div class="mt-6 text-center">
                <a href="<?php echo e(route('kiosk.scan')); ?>" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-400 hover:text-white transition">
                    <i class="fa-solid fa-camera text-indigo-400 text-[11px]"></i>
                    <span>Switch to Staff Facial Recognition Scanner &rarr;</span>
                </a>
            </div>

        </div>
    </main>

    
    <script>
        // Live Clock
        function updateClock() {
            const now = new Date();
            const options = { 
                weekday: 'short', 
                year: 'numeric', 
                month: 'short', 
                day: 'numeric',
                hour: '2-digit', 
                minute: '2-digit', 
                second: '2-digit', 
                hour12: true 
            };
            const clockEl = document.getElementById('live-clock');
            if (clockEl) {
                clockEl.textContent = now.toLocaleDateString('en-US', options);
            }
        }
        setInterval(updateClock, 1000);
        updateClock();

        // Toggle Password Visibility
        const toggleBtn = document.getElementById('toggle-password');
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eye-icon');

        if (toggleBtn && passwordInput && eyeIcon) {
            toggleBtn.addEventListener('click', function () {
                const isPassword = passwordInput.getAttribute('type') === 'password';
                passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
                eyeIcon.classList.toggle('fa-eye', !isPassword);
                eyeIcon.classList.toggle('fa-eye-slash', isPassword);
            });
        }
    </script>
</body>
</html><?php /**PATH C:\laragon\www\laundrystaff-pro\resources\views/staff/admin_login.blade.php ENDPATH**/ ?>