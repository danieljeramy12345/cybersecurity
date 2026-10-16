@extends('layouts.app')

@section('title', 'CyberShield — Provision Access Identity')

@section('content')
<div class="min-h-[85vh] flex items-center justify-center px-4 sm:px-6 lg:px-8 py-12">
    <div class="w-full max-w-lg">
        <!-- Register Card -->
        <div class="bg-[#080d19]/90 border border-slate-800 rounded-2xl p-8 shadow-2xl shadow-cyan-950/20 backdrop-blur-md glow-border">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="inline-flex w-12 h-12 rounded-xl bg-gradient-to-tr from-cyan-600 via-sky-500 to-emerald-400 p-0.5 shadow-lg shadow-cyan-500/20 mb-4">
                    <div class="w-full h-full bg-[#080d19] rounded-[10px] flex items-center justify-center">
                        <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                    </div>
                </div>
                <h1 class="text-2xl font-bold tracking-tight text-white">Provision Access Credentials</h1>
                <p class="text-xs text-slate-400 mt-1 font-mono">ZERO-TRUST SECURE IDENTITY ENROLLMENT</p>
            </div>

            <!-- Form -->
            <form method="POST" action="{{ route('register.post') }}" class="space-y-4" autocomplete="off">
                @csrf

                <!-- Full Name -->
                <div>
                    <label for="name" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">
                        Full Legal Name / Operator Handle
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <input id="name" 
                               type="text" 
                               name="name" 
                               value="{{ old('name') }}" 
                               required 
                               placeholder="e.g. Jeramy Sentinel"
                               class="w-full pl-10 pr-4 py-2.5 bg-slate-900/90 border border-slate-700/80 rounded-xl text-sm text-white placeholder-slate-500 focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400 transition" />
                    </div>
                    @error('name')
                        <p class="mt-1 text-xs text-rose-400 font-mono">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">
                        Official Contact Email (Unique ID)
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <input id="email" 
                               type="email" 
                               name="email" 
                               value="{{ old('email') }}" 
                               required 
                               placeholder="analyst@cybershield.security"
                               class="w-full pl-10 pr-4 py-2.5 bg-slate-900/90 border border-slate-700/80 rounded-xl text-sm text-white placeholder-slate-500 focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400 transition" />
                    </div>
                    @error('email')
                        <p class="mt-1 text-xs text-rose-400 font-mono">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="reg_password" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">
                        Security Cipher (Min 8 Chars, Upper, Lower, Number, Symbol)
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <input id="reg_password" 
                               type="password" 
                               name="password" 
                               required 
                               oninput="checkPasswordStrength(this.value)"
                               placeholder="StrongPass123!#"
                               class="w-full pl-10 pr-11 py-2.5 bg-slate-900/90 border border-slate-700/80 rounded-xl text-sm text-white placeholder-slate-500 focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400 transition" />
                        
                        <button type="button" 
                                onclick="toggleVisibility('reg_password', 'reg_icon1')" 
                                class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-white"
                                aria-label="Toggle password visibility">
                            <svg id="reg_icon1" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>

                    <!-- Password Strength Meter -->
                    <div class="mt-2 space-y-1">
                        <div class="flex items-center justify-between text-[11px] font-mono">
                            <span class="text-slate-400">Cipher Complexity:</span>
                            <span id="strengthText" class="font-bold text-slate-500">Not Evaluated</span>
                        </div>
                        <div class="w-full h-1.5 bg-slate-800 rounded-full overflow-hidden flex">
                            <div id="strengthBar" class="h-full w-0 transition-all duration-300 bg-slate-600"></div>
                        </div>
                    </div>

                    @error('password')
                        <p class="mt-1 text-xs text-rose-400 font-mono">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Confirmation -->
                <div>
                    <label for="password_confirmation" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">
                        Confirm Security Cipher
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <input id="password_confirmation" 
                               type="password" 
                               name="password_confirmation" 
                               required 
                               placeholder="Repeat your password"
                               class="w-full pl-10 pr-11 py-2.5 bg-slate-900/90 border border-slate-700/80 rounded-xl text-sm text-white placeholder-slate-500 focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400 transition" />
                        
                        <button type="button" 
                                onclick="toggleVisibility('password_confirmation', 'reg_icon2')" 
                                class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-white"
                                aria-label="Toggle password confirmation visibility">
                            <svg id="reg_icon2" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="pt-2">
                    <button type="submit" 
                            class="w-full py-3 px-4 rounded-xl text-xs font-bold uppercase tracking-wider text-black bg-gradient-to-r from-emerald-400 via-teal-400 to-cyan-400 hover:from-emerald-300 hover:to-cyan-300 shadow-lg shadow-emerald-500/25 transition duration-200 flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                        Provision & Hash Identity
                    </button>
                </div>
            </form>

            <div class="mt-6 text-center text-xs text-slate-400 border-t border-slate-800/80 pt-4">
                Already registered in the defense network?
                <a href="{{ route('login') }}" class="text-cyan-400 hover:text-cyan-300 font-semibold underline underline-offset-4 ml-1">
                    Authenticate Identity
                </a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function toggleVisibility(inputId, iconId) {
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

    function checkPasswordStrength(password) {
        let score = 0;
        if (!password) {
            document.getElementById('strengthBar').style.width = '0%';
            document.getElementById('strengthText').innerText = 'Not Evaluated';
            document.getElementById('strengthText').className = 'font-bold text-slate-500';
            return;
        }

        if (password.length >= 8) score++;
        if (password.length >= 12) score++;
        if (/[A-Z]/.test(password) && /[a-z]/.test(password)) score++;
        if (/[0-9]/.test(password)) score++;
        if (/[^A-Za-z0-9]/.test(password)) score++;

        const bar = document.getElementById('strengthBar');
        const text = document.getElementById('strengthText');

        if (score <= 2) {
            bar.style.width = '33%';
            bar.className = 'h-full transition-all duration-300 bg-rose-500';
            text.innerText = 'Weak (Needs symbols & numbers)';
            text.className = 'font-bold text-rose-400';
        } else if (score <= 4) {
            bar.style.width = '66%';
            bar.className = 'h-full transition-all duration-300 bg-amber-400';
            text.innerText = 'Moderate (Acceptable)';
            text.className = 'font-bold text-amber-400';
        } else {
            bar.style.width = '100%';
            bar.className = 'h-full transition-all duration-300 bg-emerald-400';
            text.innerText = 'Optimal Cyber Defense Strength';
            text.className = 'font-bold text-emerald-400';
        }
    }
</script>
@endpush
@endsection
