<x-guest-layout>
    <div class="w-full max-w-md mx-auto relative">
        <div class="corporate-card rounded-2xl p-7 sm:p-9 relative">
            <div class="mb-5 text-center">
                <div class="w-12 h-12 mx-auto rounded-xl bg-slate-900 border border-slate-700/80 p-2 shadow-sm flex items-center justify-center mb-3">
                    <i class="fa-solid fa-key text-slate-300 text-lg"></i>
                </div>
                <h1 class="text-xl font-bold tracking-tight text-white">Password Recovery</h1>
                <p class="text-xs text-slate-400 mt-1">
                    Enter your registered corporate email to receive a password reset link.
                </p>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
                @csrf

                <!-- Email Address -->
                <div>
                    <label for="email" class="block text-xs font-semibold text-slate-300 mb-1.5">
                        Corporate Email
                    </label>
                    <div class="relative group">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500 group-focus-within:text-slate-300 transition-colors text-xs">
                            <i class="fa-regular fa-envelope"></i>
                        </span>
                        <input id="email" 
                               type="email" 
                               name="email" 
                               value="{{ old('email') }}" 
                               required 
                               autofocus 
                               placeholder="your.name@zaujati.com"
                               class="w-full bg-slate-950/80 border border-slate-800 hover:border-slate-700 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500/40 rounded-xl pl-10 pr-4 py-2.5 text-xs text-white placeholder:text-slate-500 transition shadow-xs outline-none" />
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
                </div>

                <div class="pt-2">
                    <button type="submit"
                            class="w-full inline-flex items-center justify-center gap-2 py-2.5 px-4 rounded-xl text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-500 active:bg-indigo-700 shadow-md shadow-indigo-600/20 transition-all cursor-pointer">
                        <span>Email Password Reset Link</span>
                        <i class="fa-solid fa-paper-plane text-[11px]"></i>
                    </button>
                </div>

                <div class="text-center pt-3 border-t border-slate-800/80">
                    <a href="{{ route('login') }}" class="text-xs text-slate-400 hover:text-slate-200 transition font-medium inline-flex items-center gap-1.5">
                        <i class="fa-solid fa-arrow-left text-[10px]"></i>
                        <span>Return to Sign In</span>
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
