<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <span class="text-xs font-bold uppercase tracking-widest text-slate-400 block mb-1">
                    Operations Management
                </span>
                <h1 class="text-xl font-bold text-slate-900 tracking-tight">
                    Attendance Records
                </h1>
                <p class="text-xs sm:text-sm font-medium text-slate-500 mt-1">
                    Review employee check-in logs, worked hours, and shift adjustments.
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-6 sm:py-8 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- ================================================================= -->
            <!-- 1. WELCOME HERO BANNER (Curved Blue Gradient Card with Waves)     -->
            <!-- ================================================================= -->
            <div class="relative overflow-hidden rounded-[24px] shadow-lg shadow-blue-500/10 text-white"
                 style="background: linear-gradient(135deg, #1d4ed8 0%, #2563eb 55%, #0284c7 100%);">
                
                <!-- Fluid Laundry Ripple / Bubble Texture Overlay -->
                <div class="absolute inset-0 opacity-20 mix-blend-overlay pointer-events-none bg-cover bg-center"
                     style="background-image: url('{{ asset('images/laundry-wave-banner.jpg') }}');"></div>
                
                <!-- Soft Glow Accents -->
                <div class="absolute -right-10 -bottom-10 w-72 h-72 rounded-full bg-cyan-400/20 blur-3xl pointer-events-none"></div>

                <div class="relative z-10 p-6 sm:p-8 flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div class="space-y-1.5 max-w-2xl">
                        <h2 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight leading-tight">
                            Welcome Back! {{ strtoupper(auth()->user()->name) }}
                        </h2>
                        <p class="text-xs sm:text-sm text-blue-100/90 font-medium flex flex-wrap items-center gap-2">
                            <span>Zaujati Laundry Operations HQ</span>
                            <span>•</span>
                            <span>Administrator Control Terminal</span>
                            <span>•</span>
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-white/15 text-white text-[11px] font-semibold backdrop-blur-xs">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                Attendance Engine Active
                            </span>
                        </p>
                    </div>

                    <!-- Operational Status Indicators -->
                    <div class="flex items-center gap-3 shrink-0">
                        <div class="flex flex-col sm:flex-row items-end sm:items-center gap-2">
                            <div class="bg-white/10 backdrop-blur-md px-3.5 py-1.5 rounded-xl border border-white/20 text-xs">
                                <div class="text-[10px] font-bold text-blue-200 uppercase tracking-wider">Audit Security</div>
                                <div class="font-bold text-white text-xs flex items-center gap-1.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                    <span>SHA-256 Chained</span>
                                </div>
                            </div>
                            <div class="bg-white/10 backdrop-blur-md px-3.5 py-1.5 rounded-xl border border-white/20 text-xs text-white font-mono-nums font-semibold">
                                <i class="fa-regular fa-clock text-[10px] text-blue-200 mr-1"></i>
                                {{ now()->format('h:i A · d M Y') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ================================================================= -->
            <!-- 2. FOUR METRIC / KPI STAT CARDS (Pastel Squircle Style)           -->
            <!-- ================================================================= -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                
                {{-- CARD 1: MONTHLY RECORDS (Purple Pastel Squircle) --}}
                <div class="portal-card p-5 flex items-center justify-between">
                    <div>
                        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block mb-1">
                            TOTAL RECORDS
                        </span>
                        <div class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">
                            {{ $attendances->total() }}
                        </div>
                    </div>
                    <div class="w-12 h-12 rounded-[16px] bg-[#f5f3ff] dark:bg-purple-950/50 text-[#7c3aed] dark:text-purple-300 flex items-center justify-center text-lg shrink-0">
                        <i class="fa-solid fa-list-check"></i>
                    </div>
                </div>

                {{-- CARD 2: ACTIVE SHIFTS (Amber Pastel Squircle) --}}
                <div class="portal-card p-5 flex items-center justify-between">
                    <div>
                        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block mb-1">
                            ACTIVE NOW
                        </span>
                        <div class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">
                            {{ $attendances->whereNull('clock_out_time')->count() }}
                        </div>
                    </div>
                    <div class="w-12 h-12 rounded-[16px] bg-[#fffbeb] dark:bg-amber-950/50 text-[#d97706] dark:text-amber-300 flex items-center justify-center text-lg shrink-0">
                        <i class="fa-solid fa-hourglass-half"></i>
                    </div>
                </div>

                {{-- CARD 3: COMPLETED SHIFTS (Green Pastel Squircle) --}}
                <div class="portal-card p-5 flex items-center justify-between">
                    <div>
                        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block mb-1">
                            COMPLETED
                        </span>
                        <div class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">
                            {{ $attendances->whereNotNull('clock_out_time')->count() }}
                        </div>
                    </div>
                    <div class="w-12 h-12 rounded-[16px] bg-[#ecfdf5] dark:bg-emerald-950/50 text-[#059669] dark:text-emerald-300 flex items-center justify-center text-lg shrink-0">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                </div>

                {{-- CARD 4: REPLAY PROTECTION (Rose Pastel Squircle) --}}
                <div class="portal-card p-5 flex items-center justify-between">
                    <div>
                        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block mb-1">
                            SECURITY NONCE
                        </span>
                        <div class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">
                            HMAC-60s
                        </div>
                    </div>
                    <div class="w-12 h-12 rounded-[16px] bg-[#fff1f2] dark:bg-rose-950/50 text-[#e11d48] dark:text-rose-300 flex items-center justify-center text-lg shrink-0">
                        <i class="fa-solid fa-shield-halved"></i>
                    </div>
                </div>

            </div>

            {{-- 2. REFINED FILTER BAR --}}
            {{-- 2. REFINED FILTER BAR WITH MULTI-STAFF SELECTION, DELETE ALL & PANGKAH (✕) CHIPS --}}
            <div class="portal-card p-5">
                <form method="GET" action="{{ route('admin.attendance.index') }}" class="flex flex-col gap-3.5" id="attendance-filter-form">
                    <div class="flex flex-col sm:flex-row flex-wrap items-stretch sm:items-end gap-3.5">
                        <div class="w-full sm:w-auto min-w-[170px]">
                            <label class="block text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1.5">
                                Month
                            </label>
                            <input type="month" name="month" value="{{ $month }}"
                                class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl px-3.5 py-2 text-xs font-medium text-slate-800 dark:text-slate-100 focus:bg-white dark:focus:bg-slate-950 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition">
                        </div>

                        <div class="flex-1 min-w-[260px]">
                            <div class="flex items-center justify-between mb-1.5">
                                <label class="block text-xs font-semibold text-slate-600 dark:text-slate-300 flex items-center gap-1.5">
                                    <span>Staff Member</span>
                                    <span id="staff-count-badge" class="px-1.5 py-0.2 rounded-full text-[10px] font-bold bg-blue-100 dark:bg-blue-950 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-800 hidden"></span>
                                </label>
                                <div class="flex items-center gap-2">
                                    <button type="button" onclick="selectAllStaff()"
                                        class="text-[11px] font-semibold text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 inline-flex items-center gap-1 cursor-pointer transition">
                                        <i class="fa-solid fa-check-double text-[10px]"></i>
                                        <span>Select All</span>
                                    </button>
                                    <span class="text-slate-300 dark:text-slate-700">&bull;</span>
                                    <button type="button" onclick="deleteAllStaff()"
                                        class="text-[11px] font-semibold text-rose-500 hover:text-rose-600 inline-flex items-center gap-1 cursor-pointer transition"
                                        title="Clear all selected staff members">
                                        <i class="fa-solid fa-trash-can text-[10px]"></i>
                                        <span>Delete All</span>
                                    </button>
                                </div>
                            </div>
                            <select id="staff-select-picker" onchange="handleStaffSelectChange(this)"
                                class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl px-3.5 py-2 text-xs font-medium text-slate-800 dark:text-slate-100 focus:bg-white dark:focus:bg-slate-950 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition cursor-pointer">
                                <option value="" disabled selected>+ Select staff to add to filter...</option>
                                <option value="ALL">👥 Select All Staff Members</option>
                                @foreach ($staffList as $staff)
                                    <option value="{{ $staff->id }}">
                                        {{ $staff->name }} ({{ ucfirst($staff->role) }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex items-center gap-2 pt-1 sm:pt-0">
                            <button type="submit"
                                class="inline-flex items-center justify-center gap-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold px-4 py-2 rounded-xl transition shadow-sm cursor-pointer">
                                <i class="fa-solid fa-filter text-[10px]"></i>
                                <span>Apply Filter</span>
                            </button>

                            @if (!empty($selectedStaffIds) || $month !== now()->format('Y-m'))
                                <a href="{{ route('admin.attendance.index') }}"
                                   class="inline-flex items-center justify-center px-3.5 py-2 rounded-xl text-xs font-semibold text-slate-600 dark:text-slate-400 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 transition">
                                    Reset
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- Hidden inputs container for form submission -->
                    <div id="staff-hidden-inputs"></div>

                    <!-- Selected Staff Badge Pills (Click 'x' to remove) -->
                    <div class="pt-2.5 border-t border-slate-100 dark:border-slate-800/80">
                        <div class="flex items-center justify-between gap-2 mb-1.5">
                            <span class="text-[11px] font-semibold text-slate-500 dark:text-slate-400 flex items-center gap-1.5">
                                <i class="fa-solid fa-users-viewfinder text-[10px] text-blue-500"></i>
                                <span>Filtered Staff Members (Click <span class="font-bold text-rose-500">✕</span> to remove):</span>
                            </span>
                            <span id="staff-tags-status" class="text-[11px] text-slate-400"></span>
                        </div>
                        <div id="staff-tags-container" class="flex flex-wrap items-center gap-1.5 min-h-[28px]">
                            <!-- Rendered dynamically via JavaScript -->
                        </div>
                    </div>
                </form>
            </div>

            {{-- 3. CLEAN ATTENDANCE TABLE --}}
            <div class="portal-card overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2">
                    <div>
                        <h2 class="text-base font-bold text-slate-900 tracking-tight">Shift Logs</h2>
                        <p class="text-xs text-slate-400 mt-0.5">Records for {{ \Carbon\Carbon::createFromFormat('Y-m', $month)->format('F Y') }}</p>
                    </div>
                    <span class="text-xs font-medium px-2.5 py-1 rounded-full bg-slate-100 text-slate-600 font-mono-nums">
                        Showing {{ $attendances->count() }} of {{ $attendances->total() }} records
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-xs text-left">
                        <thead class="bg-slate-50/80 text-slate-500 font-semibold uppercase text-[11px] tracking-wider border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-3.5">Employee</th>
                                <th class="px-6 py-3.5">Shift Date</th>
                                <th class="px-6 py-3.5">Clock In</th>
                                <th class="px-6 py-3.5">Clock Out</th>
                                <th class="px-6 py-3.5">Duration</th>
                                <th class="px-6 py-3.5">Status</th>
                                <th class="px-6 py-3.5 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            @forelse ($attendances as $row)
                                <tr class="hover:bg-slate-50/60 transition-colors group">
                                    <td class="px-6 py-4 font-medium text-slate-900">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-700 flex items-center justify-center font-bold text-xs flex-shrink-0">
                                                {{ substr($row->staff->name ?? 'U', 0, 2) }}
                                            </div>
                                            <div>
                                                <div class="font-semibold text-slate-900">{{ $row->staff->name ?? 'Unlinked Staff' }}</div>
                                                <div class="text-[11px] text-slate-400">{{ $row->staff->email ?? '—' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 font-mono-nums text-slate-700">
                                        <div class="font-medium text-slate-900">{{ $row->date->format('d M Y') }}</div>
                                        <div class="text-[11px] text-slate-400">{{ $row->date->format('l') }}</div>
                                    </td>
                                    <td class="px-6 py-4 font-mono-nums">
                                        @if ($row->clock_in_time)
                                            <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-700 font-medium">
                                                {{ $row->clock_in_time->format('h:i A') }}
                                            </span>
                                        @else
                                            <span class="text-slate-300">—</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 font-mono-nums">
                                        @if ($row->clock_out_time)
                                            <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md bg-slate-100 text-slate-700 font-medium">
                                                {{ $row->clock_out_time->format('h:i A') }}
                                            </span>
                                        @else
                                            <span class="text-slate-400 italic">Active Shift</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 font-mono-nums font-medium text-slate-900">
                                        {{ $row->worked_hours ? $row->worked_hours . ' hrs' : '—' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @php
                                            $badgeClass = match($row->status) {
                                                'present' => 'bg-emerald-50 text-emerald-700 border-emerald-200/70',
                                                'late'    => 'bg-amber-50 text-amber-700 border-amber-200/70',
                                                'absent'  => 'bg-rose-50 text-rose-700 border-rose-200/70',
                                                'on_leave', 'mc' => 'bg-purple-50 text-purple-700 border-purple-200/70',
                                                default   => 'bg-slate-100 text-slate-700 border-slate-200/70',
                                            };
                                            $label = match($row->status) {
                                                'present'  => 'Present',
                                                'late'     => 'Late',
                                                'absent'   => 'Absent',
                                                'on_leave' => 'On Leave',
                                                'mc'       => 'Medical Leave',
                                                default    => ucfirst(str_replace('_', ' ', $row->status)),
                                            };
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold border {{ $badgeClass }}">
                                            {{ $label }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right whitespace-nowrap">
                                        <div class="inline-flex items-center gap-2 justify-end">
                                            <button type="button"
                                                onclick="document.getElementById('edit-{{ $row->id }}').classList.toggle('hidden')"
                                                class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold text-slate-700 hover:bg-slate-100 transition">
                                                <i class="fa-solid fa-pen text-[10px] text-slate-400"></i>
                                                <span>Edit</span>
                                            </button>

                                            <form action="{{ route('admin.attendance.destroy', $row->id) }}" method="POST"
                                                  onsubmit="return confirm('Delete attendance entry for {{ addslashes($row->staff->name ?? 'this staff') }} on {{ $row->date->format('d M Y') }}? This action is audited.');"
                                                  class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold text-rose-600 hover:bg-rose-50 transition"
                                                        title="Delete Attendance Record">
                                                    <i class="fa-regular fa-trash-can text-[10px]"></i>
                                                    <span>Delete</span>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>

                                {{-- EXPANDABLE INLINE EDIT DRAWER --}}
                                <tr id="edit-{{ $row->id }}" class="hidden bg-slate-50/70 border-y border-slate-200">
                                    <td colspan="7" class="px-6 py-4">
                                        <form action="{{ route('admin.attendance.update', $row) }}" method="POST"
                                              class="flex flex-col md:flex-row flex-wrap items-stretch md:items-end gap-3 bg-white p-4 rounded-xl border border-slate-200 shadow-xs">
                                            @csrf
                                            @method('PUT')
                                            <div>
                                                <label class="block text-xs font-semibold text-slate-500 mb-1">Clock In Time</label>
                                                <input type="time" name="clock_in_time"
                                                    value="{{ $row->clock_in_time?->format('H:i') }}"
                                                    class="border border-slate-200 rounded-lg px-3 py-1.5 text-xs text-slate-800 font-mono-nums">
                                            </div>
                                            <div>
                                                <label class="block text-xs font-semibold text-slate-500 mb-1">Clock Out Time</label>
                                                <input type="time" name="clock_out_time"
                                                    value="{{ $row->clock_out_time?->format('H:i') }}"
                                                    class="border border-slate-200 rounded-lg px-3 py-1.5 text-xs text-slate-800 font-mono-nums">
                                            </div>
                                            <div>
                                                <label class="block text-xs font-semibold text-slate-500 mb-1">Status</label>
                                                <select name="status" class="border border-slate-200 rounded-lg px-3 py-1.5 text-xs text-slate-800 font-medium">
                                                    @foreach (['present', 'late', 'absent', 'on_leave', 'mc'] as $s)
                                                        <option value="{{ $s }}" {{ $row->status === $s ? 'selected' : '' }}>
                                                            {{ ucfirst(str_replace('_', ' ', $s)) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="flex-1 min-w-[200px]">
                                                <label class="block text-xs font-semibold text-slate-500 mb-1">Audit Remark</label>
                                                <input type="text" name="remarks" value="{{ $row->remarks }}"
                                                    placeholder="Reason for manual adjustment"
                                                    class="w-full border border-slate-200 rounded-lg px-3 py-1.5 text-xs text-slate-800">
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <button type="submit"
                                                    class="inline-flex items-center gap-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold px-4 py-2 rounded-lg transition shadow-xs">
                                                    <span>Save Changes</span>
                                                </button>
                                                <button type="button"
                                                    onclick="document.getElementById('edit-{{ $row->id }}').classList.add('hidden')"
                                                    class="bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold px-3 py-2 rounded-lg transition">
                                                    Cancel
                                                </button>
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                                        <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-400 mx-auto flex items-center justify-center text-lg mb-2.5">
                                            <i class="fa-regular fa-calendar-xmark"></i>
                                        </div>
                                        <p class="font-semibold text-slate-700 text-sm">No attendance records found for this month.</p>
                                        <p class="text-xs text-slate-400 mt-0.5">Staff clock-in scans will automatically record here.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- PAGINATION --}}
                <div class="px-6 py-4 border-t border-slate-100">
                    {{ $attendances->links() }}
                </div>
            </div>

        </div>
    </div>
    @include('partials.flash-alerts')

    {{-- INTERACTIVE STAFF FILTER WITH DELETE ALL & PANGKAH (✕) CHIPS --}}
    <script>
        const allStaffData = @json($staffList->map(fn($s) => [
            'id' => (int) $s->id,
            'name' => $s->name,
            'role' => ucfirst($s->role)
        ]));

        let selectedStaffSet = new Set(@json(array_map('intval', $selectedStaffIds ?? [])));

        function renderStaffTags() {
            const container = document.getElementById('staff-tags-container');
            const hiddenInputsContainer = document.getElementById('staff-hidden-inputs');
            const countBadge = document.getElementById('staff-count-badge');
            const statusEl = document.getElementById('staff-tags-status');

            if (!container || !hiddenInputsContainer) return;

            container.innerHTML = '';
            hiddenInputsContainer.innerHTML = '';

            if (selectedStaffSet.size === 0) {
                if (countBadge) countBadge.classList.add('hidden');
                if (statusEl) statusEl.textContent = 'Showing records for ALL staff members';
                container.innerHTML = `
                    <div class="text-xs text-slate-400 dark:text-slate-500 italic py-0.5 flex items-center gap-1.5">
                        <i class="fa-solid fa-circle-info text-[11px] text-blue-500/70"></i>
                        <span>All staff members are currently included. Select from the dropdown or click <strong>Select All</strong> to start excluding / crossing out names.</span>
                    </div>
                `;
                return;
            }

            if (countBadge) {
                countBadge.classList.remove('hidden');
                countBadge.textContent = `${selectedStaffSet.size} selected`;
            }

            if (statusEl) {
                statusEl.textContent = `${selectedStaffSet.size} of ${allStaffData.length} staff selected`;
            }

            selectedStaffSet.forEach(staffId => {
                const staff = allStaffData.find(s => s.id === staffId);
                if (!staff) return;

                // Hidden input for GET form submission
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'staff_ids[]';
                input.value = staff.id;
                hiddenInputsContainer.appendChild(input);

                // Badge Pill with 'x' button
                const pill = document.createElement('span');
                pill.className = 'inline-flex items-center gap-1.5 pl-2.5 pr-1.5 py-1 rounded-lg text-xs font-semibold bg-blue-50 dark:bg-blue-950/70 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-800 shadow-2xs transition-all hover:bg-blue-100 dark:hover:bg-blue-900/80';
                pill.innerHTML = `
                    <i class="fa-regular fa-user text-[10px] text-blue-500 dark:text-blue-400"></i>
                    <span>${escapeHtml(staff.name)}</span>
                    <span class="text-[10px] text-blue-600/70 dark:text-blue-400/60 font-normal">(${escapeHtml(staff.role)})</span>
                    <button type="button" 
                            onclick="removeStaff(${staff.id})" 
                            class="ml-0.5 w-4 h-4 rounded flex items-center justify-center text-blue-400 hover:text-rose-600 hover:bg-rose-100 dark:hover:bg-rose-900/60 transition cursor-pointer" 
                            title="Remove ${escapeHtml(staff.name)} from filter">
                        <i class="fa-solid fa-xmark text-[11px]"></i>
                    </button>
                `;
                container.appendChild(pill);
            });

            if (selectedStaffSet.size > 1) {
                const clearBtn = document.createElement('button');
                clearBtn.type = 'button';
                clearBtn.onclick = deleteAllStaff;
                clearBtn.className = 'text-[11px] font-semibold text-rose-500 hover:text-rose-600 dark:text-rose-400 px-2 py-1 rounded-lg inline-flex items-center gap-1 cursor-pointer transition ml-1 hover:underline';
                clearBtn.innerHTML = '<i class="fa-solid fa-trash-can text-[10px]"></i> <span>Clear All</span>';
                container.appendChild(clearBtn);
            }
        }

        function removeStaff(staffId) {
            selectedStaffSet.delete(staffId);
            renderStaffTags();
        }

        function addStaff(staffId) {
            selectedStaffSet.add(staffId);
            renderStaffTags();
        }

        function selectAllStaff() {
            allStaffData.forEach(s => selectedStaffSet.add(s.id));
            renderStaffTags();
        }

        function deleteAllStaff() {
            selectedStaffSet.clear();
            renderStaffTags();
        }

        function handleStaffSelectChange(selectEl) {
            const val = selectEl.value;
            if (val === 'ALL') {
                selectAllStaff();
            } else if (val) {
                addStaff(parseInt(val, 10));
            }
            selectEl.selectedIndex = 0;
        }

        function escapeHtml(str) {
            return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
        }

        document.addEventListener('DOMContentLoaded', function() {
            renderStaffTags();
        });
    </script>
</x-app-layout>
