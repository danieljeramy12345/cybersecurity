@extends('layouts.dashboard')

@section('title', 'CyberShield — Threat & Security Monitoring SOC')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-rose-500 animate-ping"></span>
                <span class="text-xs font-mono text-rose-400 uppercase tracking-widest font-bold">Threat Defense & Telemetry Console</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-white mt-1">Real-Time Security Event Monitoring</h1>
            <p class="text-xs text-slate-400 font-mono">AUTOMATED BRUTE-FORCE CORRELATION & AUDIT LEDGER INSPECTION</p>
        </div>

        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 rounded-xl text-xs font-bold uppercase tracking-wider text-slate-300 bg-slate-900 hover:bg-slate-800 border border-slate-700 transition flex items-center gap-2">
                &larr; Return to Admin Overview
            </a>
        </div>
    </div>

    <!-- Active Threat Detection Alert Banners (Repeated Failed Logins) -->
    @if($suspiciousIPs->isNotEmpty() || $suspiciousEmails->isNotEmpty())
        <div class="p-5 rounded-2xl bg-rose-950/40 border border-rose-500/60 shadow-xl glow-red">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-8 h-8 rounded-lg bg-rose-900 border border-rose-400 flex items-center justify-center text-rose-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-base font-bold text-rose-200 uppercase tracking-wide">High-Risk Attack Pattern Detected</h2>
                    <p class="text-xs text-rose-300/80 font-mono">Repeated failed authentication attempts detected within the past 24-hour observation window.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4 font-mono text-xs">
                @if($suspiciousIPs->isNotEmpty())
                    <div class="p-3 rounded-xl bg-slate-950/70 border border-rose-800/60">
                        <span class="text-rose-400 font-bold block mb-2 text-[11px] uppercase tracking-wider">Targeted Source IP Watchlist (>=3 Failures):</span>
                        <div class="space-y-1.5">
                            @foreach($suspiciousIPs as $ipItem)
                                <div class="flex items-center justify-between text-slate-300 bg-slate-900/60 px-2 py-1 rounded">
                                    <span>IP: <strong class="text-white">{{ $ipItem->ip_address }}</strong></span>
                                    <span class="px-2 py-0.5 rounded bg-rose-500/20 text-rose-400 font-bold text-[10px]">{{ $ipItem->failure_count }} Failed Attempts</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if($suspiciousEmails->isNotEmpty())
                    <div class="p-3 rounded-xl bg-slate-950/70 border border-rose-800/60">
                        <span class="text-rose-400 font-bold block mb-2 text-[11px] uppercase tracking-wider">Targeted Account Watchlist (>=3 Failures):</span>
                        <div class="space-y-1.5">
                            @foreach($suspiciousEmails as $emailItem)
                                <div class="flex items-center justify-between text-slate-300 bg-slate-900/60 px-2 py-1 rounded">
                                    <span class="truncate max-w-[200px]">Target: <strong class="text-white">{{ $emailItem->email }}</strong></span>
                                    <span class="px-2 py-0.5 rounded bg-rose-500/20 text-rose-400 font-bold text-[10px]">{{ $emailItem->failure_count }} Failed Attempts</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @else
        <div class="p-4 rounded-xl bg-emerald-950/30 border border-emerald-500/40 text-emerald-300 text-xs flex items-center justify-between shadow-lg">
            <div class="flex items-center gap-2.5">
                <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
                <span class="font-mono">NO ACTIVE BRUTE-FORCE SPIKES DETECTED • PERIMETER DEFENSE IN OPTIMAL EQUILIBRIUM</span>
            </div>
        </div>
    @endif

    <!-- Telemetry Stats Summary -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="p-4 rounded-xl bg-[#090e1a] border border-slate-800 glow-border font-mono">
            <span class="text-[10px] uppercase text-slate-400 block">Total Inbound (24h)</span>
            <span class="text-2xl font-bold text-white mt-1 block">{{ $stats['total_logins_24h'] }}</span>
            <span class="text-[10px] text-slate-500">Authentication requests</span>
        </div>
        <div class="p-4 rounded-xl bg-[#090e1a] border border-slate-800 glow-border font-mono">
            <span class="text-[10px] uppercase text-slate-400 block">Verified Success (24h)</span>
            <span class="text-2xl font-bold text-emerald-400 mt-1 block">{{ $stats['successful_24h'] }}</span>
            <span class="text-[10px] text-emerald-400">Authenticated sessions</span>
        </div>
        <div class="p-4 rounded-xl bg-[#090e1a] border border-slate-800 glow-border font-mono">
            <span class="text-[10px] uppercase text-slate-400 block">Failed Attempts (24h)</span>
            <span class="text-2xl font-bold text-rose-400 mt-1 block">{{ $stats['failed_24h'] }}</span>
            <span class="text-[10px] text-rose-400">Invalid credential events</span>
        </div>
        <div class="p-4 rounded-xl bg-[#090e1a] border border-slate-800 glow-border font-mono">
            <span class="text-[10px] uppercase text-slate-400 block">Blocked By Throttler</span>
            <span class="text-2xl font-bold text-amber-400 mt-1 block">{{ $stats['blocked_24h'] }}</span>
            <span class="text-[10px] text-amber-400">Rate-limited drops</span>
        </div>
    </div>

    <!-- Section 1: Detailed Login Activity Feed -->
    <div class="p-6 rounded-2xl bg-[#080d19] border border-slate-800 shadow-xl glow-border">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <div>
                <h2 class="text-base font-bold text-white uppercase tracking-wider flex items-center gap-2">
                    <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                    </svg>
                    Comprehensive Login Activity Ledger
                </h2>
                <p class="text-xs text-slate-400">Every authentication attempt recorded with IP and status (Passwords and tokens omitted by policy)</p>
            </div>

            <!-- Filter -->
            <form method="GET" action="{{ route('admin.security-monitoring') }}" class="flex items-center gap-2">
                <select name="status" class="px-2.5 py-1.5 bg-slate-900 border border-slate-700 rounded-lg text-xs text-slate-300 focus:outline-none focus:border-cyan-400">
                    <option value="">All Statuses</option>
                    <option value="success" {{ $statusFilter === 'success' ? 'selected' : '' }}>Success</option>
                    <option value="failed" {{ $statusFilter === 'failed' ? 'selected' : '' }}>Failed</option>
                    <option value="blocked" {{ $statusFilter === 'blocked' ? 'selected' : '' }}>Blocked</option>
                </select>
                <button type="submit" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 border border-slate-600 rounded-lg text-xs font-semibold text-white transition">
                    Filter
                </button>
                @if($statusFilter)
                    <a href="{{ route('admin.security-monitoring') }}" class="text-xs text-rose-400 hover:text-rose-300">Reset</a>
                @endif
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs font-mono">
                <thead>
                    <tr class="border-b border-slate-800 text-slate-400 uppercase text-[10px] bg-slate-900/50">
                        <th class="py-3 px-3">Outcome</th>
                        <th class="py-3 px-3">Target Email</th>
                        <th class="py-3 px-3">Source IP</th>
                        <th class="py-3 px-3">Diagnostic Failure Reason</th>
                        <th class="py-3 px-3">User Agent</th>
                        <th class="py-3 px-3 text-right">Attempt Timestamp</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60">
                    @forelse($loginActivities as $activity)
                        <tr class="hover:bg-slate-900/40 transition">
                            <td class="py-3 px-3">
                                <span class="px-2 py-0.5 rounded text-[10px] uppercase font-bold {{ $activity->status === 'success' ? 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30' : ($activity->status === 'blocked' ? 'bg-amber-500/20 text-amber-400 border border-amber-500/30' : 'bg-rose-500/20 text-rose-400 border border-rose-500/30') }}">
                                    {{ $activity->status }}
                                </span>
                            </td>
                            <td class="py-3 px-3 text-slate-200 font-semibold truncate max-w-[160px]">{{ $activity->email }}</td>
                            <td class="py-3 px-3 text-cyan-400">{{ $activity->ip_address }}</td>
                            <td class="py-3 px-3 text-slate-400">
                                {{ $activity->failure_reason ?? 'None (Authorized)' }}
                            </td>
                            <td class="py-3 px-3 text-slate-500 truncate max-w-[200px]" title="{{ $activity->user_agent }}">
                                {{ $activity->user_agent ? substr($activity->user_agent, 0, 35) . '...' : 'Unknown' }}
                            </td>
                            <td class="py-3 px-3 text-right text-slate-400 text-[11px]">
                                {{ $activity->attempted_at->format('Y-m-d H:i:s') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-6 text-center text-slate-500">No login telemetry recorded.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $loginActivities->links() }}
        </div>
    </div>

    <!-- Section 2: Security Events Audit Ledger -->
    <div class="p-6 rounded-2xl bg-[#080d19] border border-slate-800 shadow-xl glow-border">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <div>
                <h2 class="text-base font-bold text-white uppercase tracking-wider flex items-center gap-2">
                    <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Master Security Audit Trail
                </h2>
                <p class="text-xs text-slate-400">Persistent immutable event ledger tracking authorization, administration, and threat triggers</p>
            </div>

            <!-- Severity Filter -->
            <form method="GET" action="{{ route('admin.security-monitoring') }}" class="flex items-center gap-2">
                <select name="severity" class="px-2.5 py-1.5 bg-slate-900 border border-slate-700 rounded-lg text-xs text-slate-300 focus:outline-none focus:border-cyan-400">
                    <option value="">All Severities</option>
                    <option value="low" {{ $severityFilter === 'low' ? 'selected' : '' }}>Low</option>
                    <option value="medium" {{ $severityFilter === 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="high" {{ $severityFilter === 'high' ? 'selected' : '' }}>High</option>
                    <option value="critical" {{ $severityFilter === 'critical' ? 'selected' : '' }}>Critical</option>
                </select>
                <button type="submit" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 border border-slate-600 rounded-lg text-xs font-semibold text-white transition">
                    Filter
                </button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs font-mono">
                <thead>
                    <tr class="border-b border-slate-800 text-slate-400 uppercase text-[10px] bg-slate-900/50">
                        <th class="py-3 px-3">Severity</th>
                        <th class="py-3 px-3">Event Type</th>
                        <th class="py-3 px-3">Description</th>
                        <th class="py-3 px-3">Origin IP</th>
                        <th class="py-3 px-3">Sanitized Metadata</th>
                        <th class="py-3 px-3 text-right">Occurred At</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60">
                    @forelse($securityEvents as $event)
                        <tr class="hover:bg-slate-900/40 transition">
                            <td class="py-3 px-3">
                                <span class="px-2 py-0.5 rounded text-[9px] uppercase font-bold {{ $event->severity === 'critical' ? 'bg-rose-600 text-white shadow-sm' : ($event->severity === 'high' ? 'bg-rose-500/20 text-rose-300 border border-rose-500/30' : ($event->severity === 'medium' ? 'bg-amber-500/20 text-amber-300 border border-amber-500/30' : 'bg-cyan-500/20 text-cyan-300 border border-cyan-500/30')) }}">
                                    {{ $event->severity }}
                                </span>
                            </td>
                            <td class="py-3 px-3 text-white font-semibold">{{ $event->event_type }}</td>
                            <td class="py-3 px-3 text-slate-300 max-w-sm">{{ $event->description }}</td>
                            <td class="py-3 px-3 text-slate-400">{{ $event->ip_address ?? 'system' }}</td>
                            <td class="py-3 px-3 text-slate-400">
                                @if($event->details)
                                    <span class="text-[10px] bg-slate-950 px-2 py-1 rounded border border-slate-800 text-slate-300 block max-w-xs truncate" title="{{ json_encode($event->details) }}">
                                        {{ json_encode($event->details) }}
                                    </span>
                                @else
                                    <span class="text-slate-600 italic">None</span>
                                @endif
                            </td>
                            <td class="py-3 px-3 text-right text-slate-400 text-[11px]">
                                {{ $event->occurred_at->format('Y-m-d H:i:s') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-6 text-center text-slate-500">No security audit records logged.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $securityEvents->links() }}
        </div>
    </div>
</div>
@endsection
