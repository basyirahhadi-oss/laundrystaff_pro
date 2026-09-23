<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <span class="text-xs font-bold uppercase tracking-widest text-slate-400 block mb-1">
                    Staff Management
                </span>
                <h1 class="text-xl font-bold text-slate-900 tracking-tight">
                    Edit Employee Profile
                </h1>
                <p class="text-xs sm:text-sm font-medium text-slate-500 mt-1">
                    Update profile credentials, operational position, and wage details for {{ $staff->full_name }}.
                </p>
            </div>

            <div>
                <a href="{{ route('staff.index') }}"
                   class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-bold bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 shadow-sm transition">
                    <i class="fa-solid fa-arrow-left text-slate-400"></i>
                    <span>Back to Directory</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50 min-h-screen">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white rounded-2xl border border-slate-200/80 p-8 shadow-sm">
                <form action="{{ route('staff.update', $staff->staff_id) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">Staff Identifier Code</label>
                        <input type="text" value="{{ $staff->staff_id }}" disabled
                            class="w-full bg-slate-100 border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-bold text-slate-500 font-mono-nums cursor-not-allowed">
                    </div>

                    <!-- PROFILE PICTURE CARD -->
                    <div class="bg-slate-50 rounded-xl p-4 border border-slate-200/70">
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-2">Biometric Facial Profile Photo</label>
                        <div class="flex items-center gap-4">
                            @if(!empty($staff->profile_picture) && file_exists(public_path('uploads/staff/' . $staff->profile_picture)))
                                <img src="{{ asset('uploads/staff/' . $staff->profile_picture) }}" alt="Profile"
                                     class="w-16 h-16 object-cover rounded-xl border-2 border-indigo-200 shadow-sm flex-shrink-0">
                            @else
                                <div class="w-16 h-16 rounded-xl bg-indigo-100 text-indigo-700 font-black flex items-center justify-center text-base border-2 border-indigo-200 flex-shrink-0">
                                    {{ substr($staff->full_name, 0, 2) }}
                                </div>
                            @endif
                        
                            <div class="flex-1">
                                <input type="file" name="profile_picture" accept="image/*"
                                    class="w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-[11px] file:font-bold file:uppercase file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 border border-slate-200 rounded-xl p-1 bg-white" />
                                <p class="text-[10px] text-slate-400 mt-1">Leave empty to keep existing photo. Used for kiosk face recognition.</p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">Full Legal Name</label>
                        <input type="text" name="full_name" value="{{ $staff->full_name }}" required
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-medium text-slate-800 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">Assigned Operational Position</label>
                        <select name="position" required
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-medium text-slate-800 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                            <option value="Washing Machine Operator" {{ $staff->position == 'Washing Machine Operator' ? 'selected' : '' }}>Washing Machine Operator</option>
                            <option value="Counter Staff" {{ $staff->position == 'Counter Staff' || $staff->position == 'Counter Clerk' ? 'selected' : '' }}>Counter Staff</option>
                            <option value="Laundry Supervisor" {{ $staff->position == 'Laundry Supervisor' ? 'selected' : '' }}>Laundry Supervisor</option>
                            <option value="Ironing Specialist" {{ $staff->position == 'Ironing Specialist' ? 'selected' : '' }}>Ironing Specialist</option>
                            <option value="Delivery Driver" {{ $staff->position == 'Delivery Driver' ? 'selected' : '' }}>Delivery Driver</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">Phone Number Contact</label>
                        <input type="text" name="phone_number" value="{{ $staff->phone_number }}" required
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-medium text-slate-800 font-mono-nums focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">Monthly Base Salary (RM)</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-xs font-bold text-slate-400">RM</span>
                            <input type="number" step="0.01" name="salary_rate" value="{{ $staff->salary_rate }}" required
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-xs font-bold text-slate-800 font-mono-nums focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                        <a href="{{ route('staff.index') }}"
                           class="px-5 py-2.5 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100 transition">
                            Cancel
                        </a>
                        <button type="submit"
                            class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs py-2.5 px-6 rounded-xl shadow-sm shadow-indigo-600/20 transition">
                            <i class="fa-solid fa-check text-xs"></i>
                            <span>Save Profile Changes</span>
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>

    @include('partials.flash-alerts')
</x-app-layout>