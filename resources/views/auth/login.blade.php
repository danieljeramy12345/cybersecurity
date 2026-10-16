@extends('layouts.app')

@section('title', 'CyberShield — Secure Gateway Authentication')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center px-4 sm:px-6 lg:px-8 py-12">
    <div class="w-full max-w-md">
        <!-- Auth Card Container -->
        <div class="bg-[#080d19]/90 border border-slate-800 rounded-2xl p-8 shadow-2xl shadow-cyan-950/20 backdrop-blur-md glow-border">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="inline-flex w-12 h-12 rounded-xl bg-gradient-to-tr from-cyan-600 via-sky-500 to-emerald-400 p-0.5 shadow-lg shadow-cyan-500/20 mb-4">
                    <div class="w-full h-full bg-[#080d19] rounded-[10px] flex items-center justify-center">
                        <svg class="w-6 h-6 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                </div>
                <h1 class="text-2xl font-bold tracking-tight text-white">Identity Verification</h1>
                <p class="text-xs text-slate-400 mt-1 font-mono">ENCRYPTED GATEWAY • RATE-LIMIT PROTECTED</p>
            </div>

            <!-- Login Form -->
            <form method="POST" action="{{ route('login.post') }}" class="space-y-5" autocomplete="off">
                @csrf

                <!-- Email Input -->
                <div>
                    <label for="email" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">
                        Account Identifier (Email)
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                            </svg>
                        </div>
                        <input id="email" 
                               type="email" 
                               name="email" 
                               value="{{ old('email') }}" 
                               required 
                               autofocus
                               placeholder="user@cybershield.security"
                               class="w-full pl-10 pr-4 py-2.5 bg-slate-900/90 border border-slate-700/80 rounded-xl text-sm text-white placeholder-slate-500 focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400 transition" />
                    </div>
                    @error('email')
                        <p class="mt-1.5 text-xs text-rose-400 flex items-center gap-1 font-mono">
                            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Password Input with Show/Hide Toggle -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label for="password" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider">
                            Access Cipher (Password)
                        </label>
                        <a href="{{ route('password.request') }}" class="text-xs text-cyan-400 hover:text-cyan-300 transition">
                            Forgot Password?
                        </a>
                    </div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <input id="password" 
                               type="password" 
                               name="password" 
                               required 
                               placeholder="••••••••••••"
                               class="w-full pl-10 pr-11 py-2.5 bg-slate-900/90 border border-slate-700/80 rounded-xl text-sm text-white placeholder-slate-500 focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400 transition" />
                        
                        <!-- Show / Hide Password Button -->
                        <button type="button" 
                                id="togglePasswordBtn"
                                onclick="togglePasswordVisibility('password', 'togglePasswordIcon')" 
                                class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-white transition"
                                title="Toggle cipher visibility"
                                aria-label="Toggle password visibility">
                            <svg id="togglePasswordIcon" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="mt-1.5 text-xs text-rose-400 font-mono">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember Me & Security Policy -->
                <div class="flex items-center justify-between text-xs">
                    <label class="flex items-center space-x-2 text-slate-400 cursor-pointer">
                        <input type="checkbox" 
                               name="remember" 
                               class="w-4 h-4 rounded bg-slate-900 border-slate-700 text-cyan-500 focus:ring-cyan-400 focus:ring-offset-0">
                        <span class="hover:text-slate-300">Remember Trusted Device</span>
                    </label>
                    <span class="text-[11px] font-mono text-emerald-400 flex items-center gap-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span> 2FA Ready
                    </span>
                </div>

                <!-- Submit Button -->
                <button type="submit" 
                        class="w-full py-3 px-4 rounded-xl text-xs font-bold uppercase tracking-wider text-black bg-gradient-to-r from-cyan-400 via-sky-400 to-emerald-400 hover:from-cyan-300 hover:to-emerald-300 shadow-lg shadow-cyan-500/25 transition duration-200 flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                    </svg>
                    Authorize Session
                </button>
            </form>

            <!-- Quick Demo Credentials Drawer for Easy Verification -->
            <div class="mt-6 pt-6 border-t border-slate-800/80">
                <span class="text-[11px] font-mono uppercase tracking-wider text-slate-500 block mb-3 text-center">
                    Quick Verification Presets (Demo Database)
                </span>
                <div class="grid grid-cols-2 gap-2 text-xs">
                    <button type="button" 
                            onclick="fillCredentials('admin@cybershield.security', 'AdminPassword123!#')"
                            class="p-2 rounded-lg bg-slate-900/90 border border-amber-500/30 hover:border-amber-400 text-amber-300 text-left transition font-mono">
                        <span class="block font-bold">Admin Role</span>
                        <span class="text-[10px] text-slate-400">Full SOC Access</span>
                    </button>
                    <button type="button" 
                            onclick="fillCredentials('user@cybershield.security', 'UserPassword123!#')"
                            class="p-2 rounded-lg bg-slate-900/90 border border-cyan-500/30 hover:border-cyan-400 text-cyan-300 text-left transition font-mono">
                        <span class="block font-bold">User Role</span>
                        <span class="text-[10px] text-slate-400">Personnel Portal</span>
                    </button>
                </div>
            </div>

            <!-- Footer Link -->
            <div class="mt-6 text-center text-xs text-slate-400">
                Need to provision an access account?
                <a href="{{ route('register') }}" class="text-cyan-400 hover:text-cyan-300 font-semibold underline underline-offset-4 ml-1">
                    Register Identity
                </a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function togglePasswordVisibility(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);
        if (input.type === 'password') {
            input.type = 'text';
            icon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />`;
        } else {
            input.type = 'password';
            icon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />`;
        }
    }

    function fillCredentials(email, password) {
        document.getElementById('email').value = email;
        document.getElementById('password').value = password;
    }
</script>
@endpush
@endsection
