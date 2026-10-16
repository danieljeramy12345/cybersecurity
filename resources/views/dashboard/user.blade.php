@extends('layouts.dashboard')

@section('title', 'CyberShield — Operator Security Dashboard')

@section('content')
<div class="space-y-8">
    <!-- Top Greeting and Security Posture Banner -->
    <div class="p-6 rounded-2xl bg-gradient-to-r from-[#091224] via-[#09152b] to-[#0a1b24] border border-cyan-500/30 shadow-xl glow-border">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex items-center space-x-4">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-cyan-600 via-sky-500 to-emerald-400 p-0.5 shadow-lg shadow-cyan-500/30 flex-shrink-0">
                    <div class="w-full h-full bg-[#080d19] rounded-[14px] flex items-center justify-center text-cyan-400 font-bold text-xl">
                        {{ strtoupper(substr($user->name, 0, 2)) }}
                    </div>
                </div>
                <div>
                    <div class="flex items-center gap-2.5">
                        <h1 class="text-xl sm:text-2xl font-bold text-white">{{ $user->name }}</h1>
                        <span class="px-2.5 py-0.5 rounded text-[10px] font-mono uppercase font-bold {{ $user->isAdmin() ? 'bg-amber-500/20 text-amber-300 border border-amber-500/40' : 'bg-cyan-500/20 text-cyan-300 border border-cyan-500/40' }}">
                            {{ $user->role }}
                        </span>
                        <span class="px-2 py-0.5 rounded text-[10px] font-mono uppercase font-bold bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span> ACTIVE
                        </span>
                    </div>
                    <p class="text-xs text-slate-400 font-mono mt-0.5">{{ $user->email }} • Last Authentication: {{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Current Session' }} (IP: {{ $user->last_login_ip ?? request()->ip() }})</p>
                </div>
            </div>

            <!-- Security Health Metric Badge -->
            <div class="flex items-center space-x-4 bg-slate-900/80 px-5 py-3 rounded-xl border border-slate-800">
                <div class="text-right">
                    <span class="text-[11px] font-mono uppercase text-slate-400 block">Security Posture</span>
                    <span class="text-lg font-bold text-emerald-400 font-mono">{{ $securityScore }}% SECURE</span>
                </div>
                <div class="w-10 h-10 rounded-full border-2 border-emerald-400 flex items-center justify-center text-emerald-400 text-xs font-bold font-mono">
                    A+
                </div>
            </div>
        </div>
    </div>

    <!-- Security Telemetry Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Account Status Card -->
        <div class="p-5 rounded-xl bg-[#090e1a] border border-slate-800/90 glow-border">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-mono uppercase text-slate-400">Account Hardening</span>
                <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
            </div>
            <div class="space-y-2 text-xs">
                <div class="flex justify-between py-1 border-b border-slate-800/60">
                    <span class="text-slate-400">Hashing:</span>
                    <span class="font-mono text-emerald-400">Bcrypt (cost 12)</span>
                </div>
                <div class="flex justify-between py-1 border-b border-slate-800/60">
                    <span class="text-slate-400">Session Status:</span>
                    <span class="font-mono text-cyan-400">Regenerated & Bound</span>
                </div>
                <div class="flex justify-between py-1">
                    <span class="text-slate-400">Role Privilege:</span>
                    <span class="font-mono text-white capitalize">{{ $user->role }}</span>
                </div>
            </div>
        </div>

        <!-- Protection Shield Status -->
        <div class="p-5 rounded-xl bg-[#090e1a] border border-slate-800/90 glow-border">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-mono uppercase text-slate-400">Active Perimeter</span>
                <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
            </div>
            <div class="space-y-2 text-xs">
                <div class="flex justify-between py-1 border-b border-slate-800/60">
                    <span class="text-slate-400">Rate Limiter:</span>
                    <span class="font-mono text-emerald-400">5 req / 60 sec IP-throttled</span>
                </div>
                <div class="flex justify-between py-1 border-b border-slate-800/60">
                    <span class="text-slate-400">CSRF Guard:</span>
                    <span class="font-mono text-emerald-400">Encrypted Form Tokens</span>
                </div>
                <div class="flex justify-between py-1">
                    <span class="text-slate-400">SQL Injection Defense:</span>
                    <span class="font-mono text-emerald-400">Eloquent PDO Parameters</span>
                </div>
            </div>
        </div>

        <!-- Session Telemetry -->
        <div class="p-5 rounded-xl bg-[#090e1a] border border-slate-800/90 glow-border">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-mono uppercase text-slate-400">Client Telemetry</span>
                <svg class="w-4 h-4 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
            </div>
            <div class="space-y-2 text-xs">
                <div class="flex justify-between py-1 border-b border-slate-800/60">
                    <span class="text-slate-400">Current Client IP:</span>
                    <span class="font-mono text-white">{{ request()->ip() }}</span>
                </div>
                <div class="flex justify-between py-1 border-b border-slate-800/60">
                    <span class="text-slate-400">Database Engine:</span>
                    <span class="font-mono text-cyan-400">MySQL 8.4 via WAMP</span>
                </div>
                <div class="flex justify-between py-1">
                    <span class="text-slate-400">Defense Audit:</span>
                    <span class="font-mono text-emerald-400">Live Recording</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Management Forms Grid (Profile & Password Change) -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Update Profile Form -->
        <div class="p-6 rounded-2xl bg-[#080d19] border border-slate-800 shadow-xl glow-border">
            <div class="flex items-center gap-3 mb-5">
                <div class="w-8 h-8 rounded-lg bg-cyan-950 border border-cyan-500/40 flex items-center justify-center text-cyan-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-base font-bold text-white">Personnel Profile Information</h2>
                    <p class="text-xs text-slate-400">Update operator handle (audited on change)</p>
                </div>
            </div>

            <form method="POST" action="{{ route('profile.update') }}" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label for="prof_name" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">
                        Display Name / Handle
                    </label>
                    <input id="prof_name" 
                           type="text" 
                           name="name" 
                           value="{{ old('name', $user->name) }}" 
                           required 
                           class="w-full px-3.5 py-2.5 bg-slate-900/90 border border-slate-700 rounded-xl text-sm text-white focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400 transition" />
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">
                        Registered Identity Email (Locked)
                    </label>
                    <input type="email" 
                           disabled 
                           value="{{ $user->email }}" 
                           class="w-full px-3.5 py-2.5 bg-slate-950/80 border border-slate-800 rounded-xl text-sm text-slate-500 font-mono cursor-not-allowed" />
                    <span class="text-[10px] text-slate-500 mt-1 block">Role and primary email are protected against client-side tampering.</span>
                </div>

                <button type="submit" 
                        class="py-2.5 px-5 rounded-xl text-xs font-bold uppercase tracking-wider text-black bg-cyan-400 hover:bg-cyan-300 shadow-md shadow-cyan-500/20 transition duration-200">
                    Save Profile Changes
                </button>
            </form>
        </div>

        <!-- Update Password Form -->
        <div class="p-6 rounded-2xl bg-[#080d19] border border-slate-800 shadow-xl glow-border">
            <div class="flex items-center gap-3 mb-5">
                <div class="w-8 h-8 rounded-lg bg-emerald-950 border border-emerald-500/40 flex items-center justify-center text-emerald-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-base font-bold text-white">Re-Key Account Password</h2>
                    <p class="text-xs text-slate-400">Requires verification of existing credentials</p>
                </div>
            </div>

            <form method="POST" action="{{ route('password.change') }}" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label for="curr_password" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">
                        Current Cipher (Password)
                    </label>
                    <input id="curr_password" 
                           type="password" 
                           name="current_password" 
                           required 
                           placeholder="••••••••••••"
                           class="w-full px-3.5 py-2.5 bg-slate-900/90 border border-slate-700 rounded-xl text-sm text-white focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400 transition" />
                </div>

                <div>
                    <label for="new_password" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">
                        New Security Cipher
                    </label>
                    <input id="new_password" 
                           type="password" 
                           name="password" 
                           required 
                           placeholder="Min 8 chars, uppercase, lowercase, numbers, symbols"
                           class="w-full px-3.5 py-2.5 bg-slate-900/90 border border-slate-700 rounded-xl text-sm text-white focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400 transition" />
                </div>

                <div>
                    <label for="new_password_confirmation" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">
                        Confirm New Security Cipher
                    </label>
                    <input id="new_password_confirmation" 
                           type="password" 
                           name="password_confirmation" 
                           required 
                           placeholder="Confirm matching password"
                           class="w-full px-3.5 py-2.5 bg-slate-900/90 border border-slate-700 rounded-xl text-sm text-white focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400 transition" />
                </div>

                <button type="submit" 
                        class="py-2.5 px-5 rounded-xl text-xs font-bold uppercase tracking-wider text-black bg-emerald-400 hover:bg-emerald-300 shadow-md shadow-emerald-500/20 transition duration-200">
                    Apply New Cipher
                </button>
            </form>
        </div>
    </div>

    <!-- Telemetry Activity Tables Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Recent Personal Login Activity -->
        <div class="p-6 rounded-2xl bg-[#080d19] border border-slate-800 shadow-xl glow-border">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h3 class="text-sm font-bold text-white uppercase tracking-wider">Recent Login Activity</h3>
                </div>
                <span class="text-[10px] font-mono text-slate-400">Personal Audit</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-slate-800 text-slate-400 font-mono uppercase text-[10px]">
                            <th class="py-2.5 px-2">Status</th>
                            <th class="py-2.5 px-2">Client IP</th>
                            <th class="py-2.5 px-2">Timestamp</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/60 font-mono">
                        @forelse($recentLogins as $login)
                            <tr class="hover:bg-slate-900/40 transition">
                                <td class="py-2.5 px-2">
                                    <span class="px-2 py-0.5 rounded text-[10px] uppercase font-bold {{ $login->status === 'success' ? 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30' : 'bg-rose-500/20 text-rose-400 border border-rose-500/30' }}">
                                        {{ $login->status }}
                                    </span>
                                </td>
                                <td class="py-2.5 px-2 text-slate-300">{{ $login->ip_address }}</td>
                                <td class="py-2.5 px-2 text-slate-400 text-[11px]">{{ $login->attempted_at->format('M d, Y H:i:s') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="py-4 text-center text-slate-500">No login telemetry recorded.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Personal Security Event Notifications -->
        <div class="p-6 rounded-2xl bg-[#080d19] border border-slate-800 shadow-xl glow-border">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    <h3 class="text-sm font-bold text-white uppercase tracking-wider">Security Notifications</h3>
                </div>
                <span class="text-[10px] font-mono text-slate-400">Audit Events</span>
            </div>

            <div class="space-y-3">
                @forelse($securityEvents as $event)
                    <div class="p-3 rounded-lg bg-slate-900/60 border border-slate-800/80 flex items-start justify-between gap-3 text-xs">
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <span class="px-1.5 py-0.5 rounded text-[9px] font-mono uppercase font-bold {{ $event->severity === 'critical' || $event->severity === 'high' ? 'bg-rose-500/20 text-rose-300' : 'bg-cyan-500/20 text-cyan-300' }}">
                                    {{ $event->severity }}
                                </span>
                                <span class="font-mono text-slate-300 text-[11px] font-bold">{{ $event->event_type }}</span>
                            </div>
                            <p class="text-slate-400 text-xs">{{ $event->description }}</p>
                        </div>
                        <span class="text-[10px] font-mono text-slate-500 whitespace-nowrap">{{ $event->occurred_at->diffForHumans() }}</span>
                    </div>
                @empty
                    <p class="py-4 text-center text-xs text-slate-500">No account incidents or events detected.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
