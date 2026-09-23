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

    <div class="py-8 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- 1. CLEAN KPI STATS (Calm, Unified & Clutter-Free) --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                {{-- CARD 1 --}}
                <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-semibold text-slate-500">Monthly Records</span>
                        <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center text-xs">
                            <i class="fa-regular fa-calendar-check"></i>
                        </div>
                    </div>
                    <div class="text-2xl font-bold text-slate-900 font-mono-nums">
                        {{ $attendances->total() }}
                    </div>
                    <p class="text-xs text-slate-400 mt-1">Logged for current period</p>
                </div>

                {{-- CARD 2 --}}
                <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-semibold text-slate-500">Biometric Verification</span>
                        <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs">
                            <i class="fa-regular fa-face-smile"></i>
                        </div>
                    </div>
                    <div class="text-lg font-bold text-slate-900">
                        Liveness Active
                    </div>
                    <p class="text-xs text-slate-400 mt-1">Blink &amp; EAR verification</p>
                </div>

                {{-- CARD 3 --}}
                <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-semibold text-slate-500">Audit Security</span>
                        <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center text-xs">
                            <i class="fa-solid fa-link"></i>
                        </div>
                    </div>
                    <div class="text-lg font-bold text-slate-900 font-mono-nums">
                        SHA-256 Ledger
                    </div>
                    <p class="text-xs text-slate-400 mt-1">Cryptographic tamper detection</p>
                </div>

                {{-- CARD 4 --}}
                <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-semibold text-slate-500">Replay Protection</span>
                        <div class="w-8 h-8 rounded-lg bg-sky-50 text-sky-600 flex items-center justify-center text-xs">
                            <i class="fa-solid fa-shield-halved"></i>
                        </div>
                    </div>
                    <div class="text-lg font-bold text-slate-900 font-mono-nums">
                        HMAC-60s
                    </div>
                    <p class="text-xs text-slate-400 mt-1">Single-use nonce validation</p>
                </div>
            </div>

            {{-- 2. REFINED FILTER BAR --}}
            <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs">
                <form method="GET" class="flex flex-col sm:flex-row flex-wrap items-stretch sm:items-end gap-3.5">
                    <div class="flex-1 min-w-[160px]">
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">
                            Month
                        </label>
                        <input type="month" name="month" value="{{ $month }}"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-medium text-slate-800 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                    </div>

                    <div class="flex-1 min-w-[220px]">
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">
                            Staff Member
                        </label>
                        <select name="staff_id"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-medium text-slate-800 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                            <option value="">All Staff Members</option>
                            @foreach ($staffList as $staff)
                                <option value="{{ $staff->id }}" {{ (string) $staffId === (string) $staff->id ? 'selected' : '' }}>
                                    {{ $staff->name }} ({{ ucfirst($staff->role) }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-center gap-2 pt-1 sm:pt-0">
                        <button type="submit"
                            class="inline-flex items-center justify-center gap-1.5 bg-slate-900 hover:bg-black text-white text-xs font-semibold px-4 py-2 rounded-xl transition shadow-xs">
                            <i class="fa-solid fa-filter text-[10px]"></i>
                            <span>Apply Filter</span>
                        </button>

                        @if ($staffId || $month !== now()->format('Y-m'))
                            <a href="{{ route('admin.attendance.index') }}"
                               class="inline-flex items-center justify-center px-3.5 py-2 rounded-xl text-xs font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 transition">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            {{-- 3. CLEAN ATTENDANCE TABLE --}}
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2">
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
</x-app-layout>
