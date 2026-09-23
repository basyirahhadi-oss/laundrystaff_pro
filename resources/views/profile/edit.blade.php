<x-app-layout>
    <x-slot name="header">
        <div>
            <span class="text-xs font-bold uppercase tracking-widest text-slate-400 block mb-1">
                Account Settings
            </span>
            <h1 class="text-xl font-bold text-slate-900 tracking-tight">
                Profile Information
            </h1>
            <p class="text-xs sm:text-sm font-medium text-slate-500 mt-1">
                Manage your account credentials, security preferences, and personal details.
            </p>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
