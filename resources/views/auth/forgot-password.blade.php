@extends('layouts.app')

@section('title', 'CyberShield — Reset Password Cipher')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center px-4 sm:px-6 lg:px-8 py-12">
    <div class="w-full max-w-md">
        <div class="bg-[#080d19]/90 border border-slate-800 rounded-2xl p-8 shadow-2xl shadow-cyan-950/20 backdrop-blur-md glow-border">
            <div class="text-center mb-8">
                <div class="inline-flex w-12 h-12 rounded-xl bg-gradient-to-tr from-cyan-600 to-sky-400 p-0.5 shadow-lg shadow-cyan-500/20 mb-4">
                    <div class="w-full h-full bg-[#080d19] rounded-[10px] flex items-center justify-center">
                        <svg class="w-6 h-6 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                        </svg>
                    </div>
                </div>
                <h1 class="text-2xl font-bold tracking-tight text-white">Reset Access Cipher</h1>
                <p class="text-xs text-slate-400 mt-1 font-mono">DISPATCH SECURE RECOVERY TOKEN</p>
            </div>

            <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="email" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">
                        Registered Account Identifier (Email)
                    </label>
                    <input id="email" 
                           type="email" 
                           name="email" 
                           value="{{ old('email') }}" 
                           required 
                           autofocus
                           placeholder="user@cybershield.security"
                           class="w-full px-4 py-2.5 bg-slate-900/90 border border-slate-700/80 rounded-xl text-sm text-white placeholder-slate-500 focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400 transition" />
                    @error('email')
                        <p class="mt-1 text-xs text-rose-400 font-mono">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" 
                        class="w-full py-3 px-4 rounded-xl text-xs font-bold uppercase tracking-wider text-black bg-gradient-to-r from-cyan-400 to-sky-400 hover:from-cyan-300 hover:to-sky-300 shadow-lg shadow-cyan-500/25 transition duration-200">
                    Dispatch Secure Reset Token
                </button>
            </form>

            <div class="mt-6 text-center text-xs text-slate-400 border-t border-slate-800/80 pt-4">
                Remember your credentials?
                <a href="{{ route('login') }}" class="text-cyan-400 hover:text-cyan-300 font-semibold underline underline-offset-4 ml-1">
                    Return to Login
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
