<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LaundryStaff Pro - Attendance Report</title>
    
    <!-- Tailwind CSS v4 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <!-- FontAwesome Icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-slate-50 text-gray-800 min-h-screen font-sans antialiased pb-12">

    <!-- Top Navigation Header -->
    <header class="bg-gradient-to-r from-[#4A154B] to-[#E91E63] text-white shadow-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="bg-white/20 p-2 rounded-lg backdrop-blur-md">
                    <i class="fa-solid fa-shirt text-xl"></i>
                </div>
                <div>
                    <h1 class="font-bold text-lg leading-tight">LaundryStaff Pro</h1>
                    <p class="text-xs text-purple-200">Zaujati Laundry Management System</p>
                </div>
            </div>
            
            <a href="{{ url('/dashboard') }}" class="inline-flex items-center gap-2 bg-white/10 hover:bg-white/20 text-white text-xs font-semibold px-4 py-2 rounded-full transition border border-white/20 shadow-sm backdrop-blur-sm">
                <i class="fa-solid fa-house"></i>
                <span>Back to Dashboard</span>
            </a>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8">

        <!-- Page Header & Title Bar -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
            <div>
                <h2 class="text-2xl font-extrabold text-gray-900 tracking-tight flex items-center gap-2">
                    <i class="fa-solid fa-chart-column text-[#4A154B]"></i>
                    Attendance Analytics & Report
                </h2>
                <p class="text-xs text-gray-500 mt-1">Monitor, filter, and export clock-in/out records for Zaujati Laundry staff.</p>
            </div>

            <!-- Action Buttons (Print/Export) -->
            <div class="flex items-center gap-2">
                <button onclick="window.print()" class="inline-flex items-center gap-2 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 text-xs font-semibold px-3 py-2 rounded-lg shadow-sm transition">
                    <i class="fa-solid fa-print text-gray-500"></i>
                    <span>Print Report</span>
                </button>
            </div>
        </div>

        <!-- KPI Metrics Overview Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="bg-white p-4 rounded-xl border border-gray-200/80 shadow-sm flex items-center gap-4">
                <div class="p-3 bg-purple-100 text-[#4A154B] rounded-xl">
                    <i class="fa-solid fa-clipboard-user text-xl"></i>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase">Total Logs</p>
                    <p class="text-xl font-bold text-gray-900">{{ count($attendanceRecords) }}</p>
                </div>
            </div>

            <div class="bg-white p-4 rounded-xl border border-gray-200/80 shadow-sm flex items-center gap-4">
                <div class="p-3 bg-green-100 text-green-700 rounded-xl">
                    <i class="fa-solid fa-clock text-xl"></i>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase">Total Hours Worked</p>
                    <p class="text-xl font-bold text-gray-900">
                        {{ number_format($attendanceRecords->sum('hours_worked'), 1) }} <span class="text-xs text-gray-500 font-normal">hrs</span>
                    </p>
                </div>
            </div>

            <div class="bg-white p-4 rounded-xl border border-gray-200/80 shadow-sm flex items-center gap-4">
                <div class="p-3 bg-blue-100 text-blue-700 rounded-xl">
                    <i class="fa-solid fa-user-check text-xl"></i>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase">Active Shifts</p>
                    <p class="text-xl font-bold text-gray-900">
                        {{ $attendanceRecords->whereNull('clock_out')->count() }}
                    </p>
                </div>
            </div>

            <div class="bg-white p-4 rounded-xl border border-gray-200/80 shadow-sm flex items-center gap-4">
                <div class="p-3 bg-amber-100 text-amber-700 rounded-xl">
                    <i class="fa-solid fa-calendar-check text-xl"></i>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase">Selected Month</p>
                    <p class="text-xl font-bold text-gray-900">
                        {{ date('F Y', mktime(0, 0, 0, (int)$selectedMonth, 1)) }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Filter Controls Panel -->
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-200/80 mb-6">
            <form action="{{ route('staff.attendance.report') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-end justify-between">
                
                <div class="flex flex-col sm:flex-row gap-4 w-full md:w-auto">
                    
                    <!-- Smart Staff Selector -->
                    <div class="w-full sm:w-60">
                        <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5 flex items-center gap-1.5">
                            <i class="fa-solid fa-user text-purple-600"></i> Select Staff Profile
                        </label>

                        @if(request()->filled('staff_id'))
                            <!-- Locked Dropdown for Individual Staff -->
                            <input type="hidden" name="staff_id" value="{{ request('staff_id') }}">
                            <select class="w-full bg-gray-100 border border-gray-300 rounded-xl p-2.5 text-sm text-gray-600 cursor-not-allowed font-medium" disabled>
                                @foreach($allStaff as $s)
                                    @if(request('staff_id') == $s->staff_id)
                                        <option value="{{ $s->staff_id }}" selected>👤 {{ $s->full_name }}</option>
                                    @endif
                                @endforeach
                            </select>
                        @else
                            <!-- Admin Unlocked Selection -->
                            <select name="staff_id" class="w-full bg-slate-50 border border-gray-300 rounded-xl p-2.5 text-sm focus:ring-2 focus:ring-purple-600 focus:border-transparent transition font-medium">
                                <option value="">👥 All Staff Members</option>
                                @foreach($allStaff as $s)
                                    <option value="{{ $s->staff_id }}" {{ request('staff_id') == $s->staff_id ? 'selected' : '' }}>
                                        {{ $s->full_name }} ({{ $s->staff_id }})
                                    </option>
                                @endforeach
                            </select>
                        @endif
                    </div>

                    <!-- Month Picker -->
                    <div class="w-full sm:w-48">
                        <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5 flex items-center gap-1.5">
                            <i class="fa-solid fa-calendar-days text-purple-600"></i> Month
                        </label>
                        <select name="month" class="w-full bg-slate-50 border border-gray-300 rounded-xl p-2.5 text-sm focus:ring-2 focus:ring-purple-600 focus:border-transparent transition font-medium">
                            @for($m=1; $m<=12; $m++)
                                <option value="{{ sprintf('%02d', $m) }}" {{ $selectedMonth == sprintf('%02d', $m) ? 'selected' : '' }}>
                                    📅 {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                                </option>
                            @endfor
                        </select>
                    </div>

                </div>

                <!-- Submit / Reset Actions -->
                <div class="flex gap-2 w-full md:w-auto">
                    <button type="submit" class="flex-1 md:flex-none inline-flex justify-center items-center gap-2 bg-[#4A154B] hover:bg-[#381039] text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition shadow-md hover:shadow-lg">
                        <i class="fa-solid fa-filter"></i> Apply Filter
                    </button>

                    <a href="{{ route('staff.attendance.report') }}" class="inline-flex justify-center items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold px-4 py-2.5 rounded-xl transition border border-gray-300">
                        <i class="fa-solid fa-rotate-left"></i> Reset
                    </a>
                </div>

            </form>
        </div>

        <!-- Attendance Logs Table Container -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200/80 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-100/80 border-b border-gray-200 text-[11px] font-bold uppercase tracking-wider text-gray-600">
                            <th class="p-4 pl-6">Date</th>
                            <th class="p-4">Staff Member</th>
                            <th class="p-4">Clock In</th>
                            <th class="p-4">Clock Out</th>
                            <th class="p-4">Duration</th>
                            <th class="p-4 text-right pr-6">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                        @forelse($attendanceRecords as $record)
                            <tr class="hover:bg-purple-50/40 transition">
                                <!-- Date -->
                                <td class="p-4 pl-6 font-semibold text-gray-900 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <i class="fa-regular fa-calendar text-gray-400"></i>
                                        {{ \Carbon\Carbon::parse($record->date)->format('d M Y') }}
                                    </div>
                                </td>

                                <!-- Staff Info -->
                                <td class="p-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-purple-100 text-[#4A154B] font-bold flex items-center justify-center text-xs border border-purple-200">
                                            {{ strtoupper(substr($record->full_name, 0, 2)) }}
                                        </div>
                                        <div>
                                            <span class="font-bold text-gray-900 block leading-tight">{{ $record->full_name }}</span>
                                            <span class="text-xs text-gray-400 font-mono">ID: {{ $record->staff_id }}</span>
                                        </div>
                                    </div>
                                </td>

                                <!-- Clock In -->
                                <td class="p-4 whitespace-nowrap">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-semibold rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200/80">
                                        <i class="fa-solid fa-arrow-right-to-bracket text-[10px]"></i>
                                        {{ $record->clock_in ? \Carbon\Carbon::parse($record->clock_in)->format('h:i A') : '--:--' }}
                                    </span>
                                </td>

                                <!-- Clock Out -->
                                <td class="p-4 whitespace-nowrap">
                                    @if($record->clock_out)
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-semibold rounded-full bg-rose-50 text-rose-700 border border-rose-200/80">
                                            <i class="fa-solid fa-arrow-right-from-bracket text-[10px]"></i>
                                            {{ \Carbon\Carbon::parse($record->clock_out)->format('h:i A') }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-semibold rounded-full bg-amber-50 text-amber-700 border border-amber-200/80 animate-pulse">
                                            <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                                            Active Shift
                                        </span>
                                    @endif
                                </td>

                                <!-- Duration -->
                                <td class="p-4 font-mono text-xs whitespace-nowrap">
                                    @if($record->clock_out)
                                        <span class="font-semibold text-gray-800">{{ $record->hours_worked }} hrs</span>
                                    @else
                                        <span class="text-gray-400 italic">In progress...</span>
                                    @endif
                                </td>

                                <!-- Status Pill -->
                                <td class="p-4 pr-6 text-right whitespace-nowrap">
                                    @if($record->clock_out)
                                        <span class="px-2.5 py-1 text-[11px] font-bold rounded-md bg-gray-100 text-gray-600 border border-gray-200 uppercase tracking-wider">
                                            Completed
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 text-[11px] font-bold rounded-md bg-green-100 text-green-700 border border-green-200 uppercase tracking-wider">
                                            On Duty
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-12 text-center text-gray-400">
                                    <div class="max-w-xs mx-auto">
                                        <i class="fa-solid fa-folder-open text-4xl text-gray-300 mb-3 block"></i>
                                        <p class="font-semibold text-gray-600">No logs found</p>
                                        <p class="text-xs text-gray-400 mt-1">There are no attendance records matching your filter criteria.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </main>

</body>
</html>