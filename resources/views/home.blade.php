@extends('layouts.app')

@section('title', 'CyberShield — Enterprise Defensive Security & Authentication')

@section('content')
<div class="relative overflow-hidden pt-12 pb-24">
    <!-- Hero Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
        <!-- Live Status Pill -->
        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-cyan-950/60 border border-cyan-500/30 text-cyan-300 text-xs font-mono uppercase tracking-widest mb-8 shadow-lg shadow-cyan-950/40">
            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
            DEFENSE LEVEL 5 • SHIELD OPERATIONAL
        </div>

        <h1 class="text-4xl sm:text-6xl font-extrabold tracking-tight text-white mb-6">
            Hardened Authentication & <br>
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 via-sky-400 to-emerald-400">
                Cyber Threat Monitoring System
            </span>
        </h1>

        <p class="max-w-3xl mx-auto text-base sm:text-lg text-slate-400 leading-relaxed mb-10">
            CyberShield delivers robust zero-trust defense mechanisms engineered to counter brute-force attacks, session hijacking, SQL injection, privilege escalation, and credential abuse with real-time audit event telemetry.
        </p>

        <!-- CTA Buttons -->
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
            @auth
                <a href="{{ route('dashboard') }}" class="w-full sm:w-auto px-8 py-3.5 rounded-xl font-bold text-sm uppercase tracking-wider text-black bg-gradient-to-r from-cyan-400 to-emerald-400 hover:from-cyan-300 hover:to-emerald-300 shadow-lg shadow-cyan-500/25 hover:shadow-cyan-400/40 transition duration-200 flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                    Access Protected Dashboard
                </a>
                @if(Auth::user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="w-full sm:w-auto px-8 py-3.5 rounded-xl font-bold text-sm uppercase tracking-wider text-amber-300 bg-amber-950/40 hover:bg-amber-900/60 border border-amber-500/40 shadow-lg shadow-amber-950/30 transition duration-200 flex items-center justify-center gap-2">
                        <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        Admin SOC Console
                    </a>
                @endif
            @else
                <a href="{{ route('login') }}" class="w-full sm:w-auto px-8 py-3.5 rounded-xl font-bold text-sm uppercase tracking-wider text-black bg-gradient-to-r from-cyan-400 to-emerald-400 hover:from-cyan-300 hover:to-emerald-300 shadow-lg shadow-cyan-500/25 hover:shadow-cyan-400/40 transition duration-200 flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                    </svg>
                    Authenticate Identity
                </a>
                <a href="{{ route('register') }}" class="w-full sm:w-auto px-8 py-3.5 rounded-xl font-bold text-sm uppercase tracking-wider text-slate-300 hover:text-white bg-slate-900/80 hover:bg-slate-800 border border-slate-700/80 shadow-lg transition duration-200 flex items-center justify-center gap-2">
                    <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                    Provision New Credentials
                </a>
            @endauth
        </div>

        <!-- Telemetry Highlights Grid -->
        <div class="mt-16 grid grid-cols-2 md:grid-cols-4 gap-4 max-w-4xl mx-auto text-left">
            <div class="p-4 rounded-xl bg-slate-900/60 border border-slate-800 glow-border">
                <span class="text-[11px] font-mono text-cyan-400 uppercase tracking-wider block mb-1">Defense Control</span>
                <span class="text-sm font-bold text-white block">Rate Limiting 5/min</span>
                <span class="text-[11px] text-slate-400">Brute-force lockout active</span>
            </div>
            <div class="p-4 rounded-xl bg-slate-900/60 border border-slate-800 glow-border">
                <span class="text-[11px] font-mono text-emerald-400 uppercase tracking-wider block mb-1">Crypto Standard</span>
                <span class="text-sm font-bold text-white block">Bcrypt Cost 12</span>
                <span class="text-[11px] text-slate-400">Adaptive salt hashing</span>
            </div>
            <div class="p-4 rounded-xl bg-slate-900/60 border border-slate-800 glow-border">
                <span class="text-[11px] font-mono text-sky-400 uppercase tracking-wider block mb-1">Access Model</span>
                <span class="text-sm font-bold text-white block">RBAC Zero Trust</span>
                <span class="text-[11px] text-slate-400">Granular admin isolation</span>
            </div>
            <div class="p-4 rounded-xl bg-slate-900/60 border border-slate-800 glow-border">
                <span class="text-[11px] font-mono text-amber-400 uppercase tracking-wider block mb-1">Audit Ledger</span>
                <span class="text-sm font-bold text-white block">Full Event Stream</span>
                <span class="text-[11px] text-slate-400">Redacted sensitive payloads</span>
            </div>
        </div>
    </div>

    <!-- Security Controls Feature Grid -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-24">
        <div class="text-center mb-16">
            <h2 class="text-xs font-mono uppercase tracking-widest text-cyan-400 mb-2">Hardened Defense Architecture</h2>
            <h3 class="text-2xl sm:text-3xl font-extrabold text-white">Multi-Layered Cybersecurity Countermeasures</h3>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Feature 1 -->
            <div class="p-6 rounded-2xl bg-[#090e1a] border border-slate-800/90 hover:border-cyan-500/50 transition duration-300 glow-border">
                <div class="w-12 h-12 rounded-xl bg-cyan-950 border border-cyan-500/40 flex items-center justify-center text-cyan-400 mb-5 shadow-lg shadow-cyan-950/40">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <h4 class="text-lg font-bold text-white mb-2">Brute-Force & Credential Attack Throttling</h4>
                <p class="text-sm text-slate-400 leading-relaxed mb-4">
                    Dynamic rate-limiters track IP addresses and target credentials. Consecutive failures automatically activate progressive cooldown timers and trigger high-severity incident logs.
                </p>
                <div class="font-mono text-xs text-emerald-400 flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span> Active Throttling Policy
                </div>
            </div>

            <!-- Feature 2 -->
            <div class="p-6 rounded-2xl bg-[#090e1a] border border-slate-800/90 hover:border-emerald-500/50 transition duration-300 glow-border">
                <div class="w-12 h-12 rounded-xl bg-emerald-950 border border-emerald-500/40 flex items-center justify-center text-emerald-400 mb-5 shadow-lg shadow-emerald-950/40">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                <h4 class="text-lg font-bold text-white mb-2">Session Regeneration & Anti-Fixation</h4>
                <p class="text-sm text-slate-400 leading-relaxed mb-4">
                    Upon successful credential authentication, PHP session IDs are regenerated immediately, terminating session fixation vulnerabilities and binding valid tokens exclusively to active cookies.
                </p>
                <div class="font-mono text-xs text-emerald-400 flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span> Invalidation On Logout
                </div>
            </div>

            <!-- Feature 3 -->
            <div class="p-6 rounded-2xl bg-[#090e1a] border border-slate-800/90 hover:border-sky-500/50 transition duration-300 glow-border">
                <div class="w-12 h-12 rounded-xl bg-sky-950 border border-sky-500/40 flex items-center justify-center text-sky-400 mb-5 shadow-lg shadow-sky-950/40">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <h4 class="text-lg font-bold text-white mb-2">Sanitized Audit Event Ledger</h4>
                <p class="text-sm text-slate-400 leading-relaxed mb-4">
                    Every security action is stamped with IP, timestamp, user agent, and severity. Strict redaction algorithms purge plaintext passwords and tokens prior to persistent database commit.
                </p>
                <div class="font-mono text-xs text-emerald-400 flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span> Redacted Telemetry
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
