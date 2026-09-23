<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

        <title><?php echo e(config('app.name', 'LaundryStaff Pro')); ?> — Zaujati Laundry Portal</title>

        <!-- Google Fonts: Inter & JetBrains Mono -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">
        
        <!-- FontAwesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

        <!-- Tailwind CSS CDN -->
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        fontFamily: {
                            sans: ['Inter', '-apple-system', 'BlinkMacSystemFont', '"SF Pro Display"', '"SF Pro Text"', 'sans-serif'],
                            mono: ['"JetBrains Mono"', 'monospace'],
                        },
                        colors: {
                            zaujati: {
                                purple: '#4A154B',
                                pink: '#E11D74',
                                dark: '#2D0C2E',
                                light: '#FDF2F8',
                            }
                        }
                    }
                }
            }
        </script>

        <style>
            body { 
                font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'SF Pro Display', 'SF Pro Text', sans-serif;
                letter-spacing: -0.018em;
                -webkit-font-smoothing: antialiased;
                -moz-osx-font-smoothing: grayscale;
                text-rendering: optimizeLegibility;
            }
            h1, h2, h3, h4, h5, h6 {
                letter-spacing: -0.03em;
            }
            .font-mono-nums { font-family: 'JetBrains Mono', monospace; }
        </style>

        <!-- Scripts -->
        <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    </head>
    <body class="bg-gradient-to-br from-slate-100 via-slate-50 to-purple-50/30 text-slate-900 antialiased min-h-screen flex flex-col justify-between selection:bg-[#4A154B] selection:text-white">
        
        <!-- Top Minimal Navbar -->
        <header class="w-full py-4 px-6 flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <img src="<?php echo e(asset('images/zaujati-logo.png')); ?>" alt="Zaujati Laundry" class="w-8 h-8 rounded-xl object-contain bg-slate-950 p-1 shadow border border-slate-200">
                <span class="font-extrabold text-sm text-slate-900 tracking-tight">LaundryStaff <span class="text-xs px-1.5 py-0.5 rounded text-white font-black" style="background-color: #4A154B;">PRO</span></span>
            </div>
            <div class="text-[11px] text-slate-400 font-medium">
                Zaujati Laundry Operations
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-1 flex items-center justify-center p-4 sm:p-6">
            <?php echo e($slot); ?>

        </main>

        <!-- Bottom Simple Footer -->
        <footer class="py-4 text-center text-[11px] text-slate-400">
            <p>© <?php echo e(date('Y')); ?> Zaujati Laundry · LaundryStaff Pro Operations System</p>
        </footer>

    </body>
</html>
<?php /**PATH C:\laragon\www\laundrystaff-pro\resources\views/layouts/guest.blade.php ENDPATH**/ ?>