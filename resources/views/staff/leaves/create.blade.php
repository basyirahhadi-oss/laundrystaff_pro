<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <span class="text-xs font-bold uppercase tracking-widest text-slate-400 block mb-1">
                    Employee Self-Service
                </span>
                <h1 class="text-xl font-bold text-slate-900 tracking-tight">
                    Apply for Leave
                </h1>
                <p class="text-xs sm:text-sm font-medium text-slate-500 mt-1">
                    Submit requests for annual, medical (MC), emergency, or unpaid leave.
                </p>
            </div>

            <div>
                <a href="{{ route('staff.leaves.index') }}"
                   class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-bold bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 shadow-sm transition">
                    <i class="fa-solid fa-arrow-left text-slate-400"></i>
                    <span>Back to Requests</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50 min-h-screen">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white rounded-2xl border border-slate-200/80 p-8 shadow-sm">
                <!-- SECURITY CALLOUT -->
                <div class="bg-purple-50/60 border border-purple-200/70 rounded-xl p-4 mb-6 flex items-start gap-3">
                    <div class="w-8 h-8 rounded-lg bg-purple-100 text-purple-700 flex items-center justify-center text-xs flex-shrink-0 mt-0.5">
                        <i class="fa-solid fa-shield-halved"></i>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-purple-900 uppercase tracking-wide">AES-256 Confidential Medical Privacy</h4>
                        <p class="text-[11px] text-purple-700 mt-0.5">
                            Any MC slips or medical certificates uploaded are automatically encrypted at rest using AES-256-CBC cipher envelopes before storage.
                        </p>
                    </div>
                </div>

                <form action="{{ route('staff.leaves.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                    @csrf

                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">Leave Category</label>
                        <select name="leave_type" required
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-medium text-slate-800 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                            <option value="">-- Select Leave Category --</option>
                            <option value="annual" {{ old('leave_type') === 'annual' ? 'selected' : '' }}>Annual Leave (Cuti Tahunan)</option>
                            <option value="mc" {{ old('leave_type') === 'mc' ? 'selected' : '' }}>Medical Leave (Cuti Sakit / MC)</option>
                            <option value="emergency" {{ old('leave_type') === 'emergency' ? 'selected' : '' }}>Emergency Leave (Cuti Kecemasan)</option>
                            <option value="unpaid" {{ old('leave_type') === 'unpaid' ? 'selected' : '' }}>Unpaid Leave (Cuti Tanpa Gaji)</option>
                        </select>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">Start Date</label>
                            <input type="date" name="start_date" required value="{{ old('start_date') }}"
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-medium text-slate-800 font-mono-nums focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">End Date</label>
                            <input type="date" name="end_date" required value="{{ old('end_date') }}"
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-medium text-slate-800 font-mono-nums focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">Reason for Absence</label>
                        <textarea name="reason" rows="3" required
                            placeholder="Briefly explain the reason for your time off request..."
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-medium text-slate-800 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">{{ old('reason') }}</textarea>
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">
                            Supporting Document <span class="text-slate-400 normal-case font-normal">(MC slip, clinic receipt, etc. — Optional)</span>
                        </label>
                        <input type="file" name="attachment" accept=".pdf,.jpg,.jpeg,.png"
                            class="w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-[11px] file:font-bold file:uppercase file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100 border border-slate-200 rounded-xl p-1 bg-slate-50" />
                        <p class="text-[10px] text-slate-400 mt-1">Accepted: PDF, JPG, PNG (Max 5MB). Automatically encrypted.</p>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                        <a href="{{ route('staff.leaves.index') }}"
                           class="px-5 py-2.5 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100 transition">
                            Cancel
                        </a>
                        <button type="submit"
                            class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs py-2.5 px-6 rounded-xl shadow-sm shadow-indigo-600/20 transition">
                            <i class="fa-solid fa-paper-plane text-xs"></i>
                            <span>Submit Application</span>
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>

    @include('partials.flash-alerts')
</x-app-layout>
