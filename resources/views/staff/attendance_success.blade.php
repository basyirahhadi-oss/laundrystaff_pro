<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Recorded — LaundryStaff Pro</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    <style>body { font-family: 'Figtree', sans-serif; }</style>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center p-4">

    <div class="max-w-sm w-full bg-white rounded-2xl border border-slate-200 shadow-sm p-8 text-center">

        <div class="mx-auto w-14 h-14 rounded-full bg-emerald-50 flex items-center justify-center mb-5">
            <svg class="w-7 h-7 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
            </svg>
        </div>

        <span class="inline-block text-xs font-semibold uppercase tracking-wide text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-full mb-3">
            Verified
        </span>

        <h1 class="text-xl font-bold text-slate-900 mb-1">{{ $type }} Successful</h1>
        <p class="text-sm text-slate-500 mb-6">Your attendance has been recorded.</p>

        <div class="w-20 h-20 mx-auto rounded-full overflow-hidden bg-slate-100 border border-slate-200 mb-4">
            @if($picture)
                <img src="{{ asset('uploads/staff/' . $picture) }}" class="w-full h-full object-cover">
            @else
                <svg class="w-full h-full text-slate-300 p-4" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5-3-8-3z"/>
                </svg>
            @endif
        </div>

        <h2 class="text-lg font-bold text-slate-800">{{ $name }}</h2>
        <p class="text-xs text-slate-500 mb-6">{{ $role }}</p>

        <div class="grid grid-cols-2 gap-3 bg-slate-50 rounded-xl border border-slate-200 p-4 mb-6">
            <div class="text-left border-r border-slate-200 pr-2">
                <span class="text-[10px] uppercase font-bold text-slate-400 block tracking-wide mb-0.5">Time</span>
                <span class="text-sm font-bold text-slate-700">{{ $time }}</span>
            </div>
            <div class="text-left pl-2">
                <span class="text-[10px] uppercase font-bold text-slate-400 block tracking-wide mb-0.5">Date</span>
                <span class="text-sm font-bold text-slate-700">{{ $date }}</span>
            </div>
        </div>

        <a href="{{ route('kiosk.gateway') }}"
           class="inline-flex w-full justify-center items-center rounded-lg bg-purple-800 hover:bg-purple-900 text-white text-sm font-semibold py-2.5 transition-colors">
            Scan Again
        </a>

        <p class="text-xs text-slate-400 mt-4">
            Closing automatically in <span id="countdown" class="font-semibold text-slate-600">5</span>s...
        </p>
    </div>

    <script>
        let timeLeft = 5;
        const countdownEl = document.getElementById('countdown');
        const timer = setInterval(() => {
            if (timeLeft <= 0) {
                clearInterval(timer);
                window.location.href = "{{ route('kiosk.gateway') }}";
            } else {
                countdownEl.textContent = timeLeft;
            }
            timeLeft -= 1;
        }, 1000);
    </script>
</body>
</html>