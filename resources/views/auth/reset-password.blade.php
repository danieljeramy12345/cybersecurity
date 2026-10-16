@extends('layouts.app')

@section('title', 'CyberShield — Set New Password Cipher')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center px-4 sm:px-6 lg:px-8 py-12">
    <div class="w-full max-w-md">
        <div class="bg-[#080d19]/90 border border-slate-800 rounded-2xl p-8 shadow-2xl shadow-cyan-950/20 backdrop-blur-md glow-border">
            <div class="text-center mb-8">
                <div class="inline-flex w-12 h-12 rounded-xl bg-gradient-to-tr from-cyan-600 to-emerald-400 p-0.5 shadow-lg shadow-cyan-500/20 mb-4">
                    <div class="w-full h-full bg-[#080d19] rounded-[10px] flex items-center justify-center">
                        <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                </div>
                <h1 class="text-2xl font-bold tracking-tight text-white">Configure New Cipher</h1>
                <p class="text-xs text-slate-400 mt-1 font-mono">TOKEN VALIDATED • RE-KEYING ACCOUNT</p>
            </div>

            <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <div>
                    <label for="email" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">
                        Account Identifier (Email)
                    </label>
                    <input id="email" 
                           type="email" 
                           name="email" 
                           value="{{ old('email', $email) }}" 
                           required 
                           class="w-full px-4 py-2.5 bg-slate-900/90 border border-slate-700/80 rounded-xl text-sm text-white focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400 transition" />
                    @error('email')
                        <p class="mt-1 text-xs text-rose-400 font-mono">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">
                        New Security Cipher
                    </label>
                    <input id="password" 
                           type="password" 
                           name="password" 
                           required 
                           placeholder="••••••••••••"
                           class="w-full px-4 py-2.5 bg-slate-900/90 border border-slate-700/80 rounded-xl text-sm text-white focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400 transition" />
                    @error('password')
                        <p class="mt-1 text-xs text-rose-400 font-mono">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">
                        Confirm New Cipher
                    </label>
                    <input id="password_confirmation" 
                           type="password" 
                           name="password_confirmation" 
                           required 
                           placeholder="••••••••••••"
                           class="w-full px-4 py-2.5 bg-slate-900/90 border border-slate-700/80 rounded-xl text-sm text-white focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400 transition" />
                </div>

                <button type="submit" 
                        class="w-full py-3 px-4 rounded-xl text-xs font-bold uppercase tracking-wider text-black bg-gradient-to-r from-emerald-400 to-cyan-400 hover:from-emerald-300 hover:to-cyan-300 shadow-lg shadow-emerald-500/25 transition duration-200">
                    Apply Cipher & Update Account
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
