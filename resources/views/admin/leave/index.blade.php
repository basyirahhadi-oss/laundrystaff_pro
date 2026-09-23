<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <span class="text-xs font-bold uppercase tracking-widest text-slate-400 block mb-1">
                    Personnel Operations
                </span>
                <h1 class="text-xl font-bold text-slate-900 tracking-tight">
                    Leave Approvals
                </h1>
                <p class="text-xs sm:text-sm font-medium text-slate-500 mt-1">
                    Review and authorize employee leave requests, inspect attachments, and manage absence records.
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- STATUS FILTER TABS --}}
            <div class="flex items-center gap-2 overflow-x-auto pb-1">
                @foreach (['pending' => 'Pending Approval', 'approved' => 'Approved Leaves', 'rejected' => 'Rejected', 'all' => 'All Applications'] as $key => $label)
                    <a href="{{ route('admin.leaves.index', ['status' => $key]) }}"
                       class="inline-flex items-center gap-2 text-xs font-bold px-4 py-2.5 rounded-xl transition-all {{ $status === $key ? 'bg-[#4A154B] text-white shadow-sm shadow-[#4A154B]/20' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                        @if ($key === 'pending')
                            <span class="w-2 h-2 rounded-full {{ $status === $key ? 'bg-[#E11D74]' : 'bg-amber-500' }} animate-pulse"></span>
                        @endif
                        <span>{{ $label }}</span>
                    </a>
                @endforeach
            </div>

            {{-- LEAVE CARDS LIST --}}
            <div class="space-y-4">
                @forelse ($leaves as $leave)
                    <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-sm hover:shadow transition">
                        <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4">
                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-700 border border-indigo-100 flex items-center justify-center font-bold text-sm flex-shrink-0">
                                    {{ substr($leave->staff->name ?? 'U', 0, 2) }}
                                </div>
                                <div>
                                    <div class="flex items-center gap-2.5 flex-wrap">
                                        <h3 class="font-bold text-slate-900 text-sm">{{ $leave->staff->name ?? 'Unlinked Staff' }}</h3>
                                        <span class="text-xs text-slate-400">·</span>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-medium bg-slate-100 text-slate-700">
                                            {{ $leave->leave_type_label }}
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-3 text-xs text-slate-500 mt-1 flex-wrap">
                                        <span class="inline-flex items-center gap-1 font-medium text-slate-700">
                                            <i class="fa-regular fa-calendar text-slate-400"></i>
                                            {{ $leave->start_date->format('d M Y') }} – {{ $leave->end_date->format('d M Y') }}
                                        </span>
                                        <span>·</span>
                                        <span class="font-semibold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded">
                                            {{ $leave->duration_in_days }} {{ Str::plural('Day', $leave->duration_in_days) }}
                                        </span>
                                        <span>·</span>
                                        <span class="text-slate-400 text-[11px]">Applied {{ $leave->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                            </div>

                            {{-- STATUS BADGE --}}
                            @php
                                $statusBadge = match($leave->status) {
                                    'approved' => 'bg-emerald-50 text-emerald-700 border-emerald-200/60',
                                    'rejected' => 'bg-rose-50 text-rose-700 border-rose-200/60',
                                    default    => 'bg-amber-50 text-amber-700 border-amber-200/60',
                                };
                            @endphp
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold uppercase tracking-wider border {{ $statusBadge }} self-start">
                                {{ ucfirst($leave->status) }}
                            </span>
                        </div>

                        {{-- REASON BOX --}}
                        <div class="mt-4 bg-slate-50 rounded-xl p-3.5 border border-slate-100 text-xs text-slate-700">
                            <span class="font-bold text-slate-400 uppercase text-[10px] block mb-0.5">Reason for Absence:</span>
                            <p class="leading-relaxed">{{ $leave->reason }}</p>
                        </div>

                        {{-- ENCRYPTED ATTACHMENT BADGE --}}
                        @if ($leave->attachment)
                            <div class="mt-3">
                                <a href="{{ route('secure.leave.attachment', $leave) }}" target="_blank"
                                   class="inline-flex items-center gap-2 px-3 py-1.5 bg-purple-50 text-purple-700 rounded-xl text-xs font-bold hover:bg-purple-100 transition border border-purple-200/80 shadow-sm group">
                                    <i class="fa-solid fa-lock text-purple-600 group-hover:scale-110 transition-transform"></i>
                                    <span>View Encrypted Attachment</span>
                                    <span class="text-[10px] font-mono uppercase bg-purple-200/70 text-purple-800 px-1.5 py-0.5 rounded">AES-256</span>
                                    <i class="fa-solid fa-arrow-up-right-from-square text-[10px] text-purple-400"></i>
                                </a>
                            </div>
                        @endif

                        {{-- ACTION BUTTONS --}}
                        <div class="mt-5 pt-4 border-t border-slate-100 flex flex-col md:flex-row items-stretch md:items-end justify-between gap-3">
                            @if ($leave->status === 'pending')
                                <form action="{{ route('admin.leaves.approve', $leave) }}" method="POST" class="flex-1 flex flex-col sm:flex-row items-stretch sm:items-end gap-3">
                                    @csrf
                                    <div class="flex-1">
                                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Approval Note / Remarks (Optional)</label>
                                        <input type="text" name="admin_remarks" placeholder="e.g. Approved, please hand over duties before departure"
                                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-medium text-slate-800 focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition">
                                    </div>
                                    <button type="submit"
                                        class="inline-flex items-center justify-center gap-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold px-5 py-2.5 rounded-xl shadow-sm shadow-emerald-600/20 transition">
                                        <i class="fa-solid fa-check"></i>
                                        <span>Approve Leave</span>
                                    </button>
                                </form>

                                <form action="{{ route('admin.leaves.reject', $leave) }}" method="POST"
                                      onsubmit="return confirm('Confirm rejection of this leave application?');">
                                    @csrf
                                    <button type="submit"
                                        class="inline-flex items-center justify-center gap-1.5 bg-amber-50 hover:bg-amber-100 text-amber-800 text-xs font-bold px-4 py-2.5 rounded-xl border border-amber-200 transition">
                                        <i class="fa-solid fa-xmark"></i>
                                        <span>Reject</span>
                                    </button>
                                </form>
                            @elseif ($leave->admin_remarks)
                                <div class="flex-1 text-xs text-slate-500 flex items-center gap-2">
                                    <span class="font-bold text-slate-700">Manager Remarks:</span>
                                    <span class="italic text-slate-600">{{ $leave->admin_remarks }}</span>
                                </div>
                            @else
                                <div></div>
                            @endif

                            <!-- DELETE LEAVE APPLICATION -->
                            <form action="{{ route('admin.leaves.destroy', $leave) }}" method="POST"
                                  onsubmit="return confirm('Are you sure you want to permanently delete this leave application for {{ addslashes($leave->staff->name ?? 'this staff') }}?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="inline-flex items-center justify-center gap-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 text-xs font-bold px-3.5 py-2.5 rounded-xl border border-rose-200 transition"
                                    title="Delete leave record">
                                    <i class="fa-solid fa-trash-can"></i>
                                    <span>Delete</span>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-2xl border border-slate-200/80 p-12 text-center text-slate-400">
                        <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 mx-auto flex items-center justify-center text-xl mb-3">
                            <i class="fa-solid fa-inbox"></i>
                        </div>
                        <p class="font-bold text-slate-700">No {{ $status !== 'all' ? $status : '' }} leave applications found.</p>
                        <p class="text-xs text-slate-400 mt-1">When staff submit leave requests, they will appear here for review.</p>
                    </div>
                @endforelse
            </div>

            {{-- PAGINATION --}}
            <div class="pt-2">
                {{ $leaves->links() }}
            </div>

        </div>
    </div>
    @include('partials.flash-alerts')
</x-app-layout>
