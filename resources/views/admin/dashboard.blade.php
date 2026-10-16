@extends('layouts.dashboard')

@section('title', 'CyberShield — SOC Administration & Access Control')

@section('content')
<div class="space-y-8">
    <!-- Admin Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-amber-400 animate-pulse"></span>
                <span class="text-xs font-mono text-amber-400 uppercase tracking-widest font-bold">SOC Administration Authority</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-white mt-1">Personnel & Access Control Console</h1>
            <p class="text-xs text-slate-400 font-mono">ENFORCING ZERO-TRUST RBAC & ACCOUNT STATUS POLICIES</p>
        </div>

        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.security-monitoring') }}" class="px-4 py-2 rounded-xl text-xs font-bold uppercase tracking-wider text-black bg-gradient-to-r from-rose-500 to-amber-400 hover:from-rose-400 hover:to-amber-300 shadow-lg shadow-rose-950/40 transition flex items-center gap-2">
                <svg class="w-4 h-4 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                Launch Threat Monitoring
            </a>
        </div>
    </div>

    <!-- Metrics Cards Grid -->
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
        <div class="p-4 rounded-xl bg-[#090e1a] border border-slate-800 glow-border">
            <span class="text-[10px] font-mono uppercase text-slate-400 block">Total Identities</span>
            <span class="text-2xl font-extrabold text-white font-mono mt-1 block">{{ $totalUsers }}</span>
            <span class="text-[10px] text-cyan-400 font-mono">Enrolled credentials</span>
        </div>
        <div class="p-4 rounded-xl bg-[#090e1a] border border-slate-800 glow-border">
            <span class="text-[10px] font-mono uppercase text-slate-400 block">Active Accounts</span>
            <span class="text-2xl font-extrabold text-emerald-400 font-mono mt-1 block">{{ $activeUsers }}</span>
            <span class="text-[10px] text-emerald-400 font-mono">Operational status</span>
        </div>
        <div class="p-4 rounded-xl bg-[#090e1a] border border-slate-800 glow-border">
            <span class="text-[10px] font-mono uppercase text-slate-400 block">Deactivated</span>
            <span class="text-2xl font-extrabold text-rose-400 font-mono mt-1 block">{{ $deactivatedUsers }}</span>
            <span class="text-[10px] text-rose-400 font-mono">Sessions restricted</span>
        </div>
        <div class="p-4 rounded-xl bg-[#090e1a] border border-slate-800 glow-border">
            <span class="text-[10px] font-mono uppercase text-slate-400 block">Failed Logins (Today)</span>
            <span class="text-2xl font-extrabold text-amber-400 font-mono mt-1 block">{{ $failedLoginsToday }}</span>
            <span class="text-[10px] text-amber-400 font-mono">Recorded attempts</span>
        </div>
        <div class="p-4 rounded-xl bg-[#090e1a] border border-slate-800 glow-border col-span-2 lg:col-span-1">
            <span class="text-[10px] font-mono uppercase text-slate-400 block">Threat Events</span>
            <span class="text-2xl font-extrabold text-rose-500 font-mono mt-1 block">{{ $criticalEvents }}</span>
            <span class="text-[10px] text-rose-400 font-mono">High / Critical alerts</span>
        </div>
    </div>

    <!-- User Account Directory & Management Table -->
    <div class="p-6 rounded-2xl bg-[#080d19] border border-slate-800 shadow-xl glow-border">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <div>
                <h2 class="text-base font-bold text-white uppercase tracking-wider flex items-center gap-2">
                    <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    Identity Management Directory
                </h2>
                <p class="text-xs text-slate-400">Search, verify privilege, and activate/deactivate accounts</p>
            </div>

            <!-- Filter and Search Form -->
            <form method="GET" action="{{ route('admin.dashboard') }}" class="flex flex-wrap items-center gap-2">
                <input type="text" 
                       name="search" 
                       value="{{ $search }}" 
                       placeholder="Filter name or email..." 
                       class="px-3 py-1.5 bg-slate-900 border border-slate-700 rounded-lg text-xs text-white placeholder-slate-500 focus:outline-none focus:border-cyan-400" />
                
                <select name="role" class="px-2.5 py-1.5 bg-slate-900 border border-slate-700 rounded-lg text-xs text-slate-300 focus:outline-none focus:border-cyan-400">
                    <option value="">All Roles</option>
                    <option value="admin" {{ $roleFilter === 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="user" {{ $roleFilter === 'user' ? 'selected' : '' }}>User</option>
                </select>

                <select name="status" class="px-2.5 py-1.5 bg-slate-900 border border-slate-700 rounded-lg text-xs text-slate-300 focus:outline-none focus:border-cyan-400">
                    <option value="">All Statuses</option>
                    <option value="active" {{ $statusFilter === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ $statusFilter === 'inactive' ? 'selected' : '' }}>Deactivated</option>
                </select>

                <button type="submit" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 border border-slate-600 rounded-lg text-xs font-semibold text-white transition">
                    Filter
                </button>

                @if($search || $roleFilter || $statusFilter)
                    <a href="{{ route('admin.dashboard') }}" class="px-2 py-1.5 text-xs text-rose-400 hover:text-rose-300">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-slate-800 text-slate-400 font-mono uppercase text-[10px] bg-slate-900/50">
                        <th class="py-3 px-3">Identity / Operator</th>
                        <th class="py-3 px-3">Email Address</th>
                        <th class="py-3 px-3">Role</th>
                        <th class="py-3 px-3">Account Status</th>
                        <th class="py-3 px-3">Last Active IP</th>
                        <th class="py-3 px-3 text-right">Administrative Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60 font-mono">
                    @forelse($users as $account)
                        <tr class="hover:bg-slate-900/40 transition">
                            <td class="py-3 px-3 font-semibold text-white flex items-center gap-2">
                                <div class="w-6 h-6 rounded-full bg-slate-800 border border-slate-700 flex items-center justify-center text-[10px] font-bold text-cyan-400">
                                    {{ strtoupper(substr($account->name, 0, 1)) }}
                                </div>
                                {{ $account->name }}
                            </td>
                            <td class="py-3 px-3 text-slate-300">{{ $account->email }}</td>
                            <td class="py-3 px-3">
                                <span class="px-2 py-0.5 rounded text-[10px] uppercase font-bold {{ $account->isAdmin() ? 'bg-amber-500/20 text-amber-300 border border-amber-500/40' : 'bg-cyan-500/20 text-cyan-300 border border-cyan-500/40' }}">
                                    {{ $account->role }}
                                </span>
                            </td>
                            <td class="py-3 px-3">
                                <span class="px-2 py-0.5 rounded text-[10px] uppercase font-bold {{ $account->is_active ? 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30' : 'bg-rose-500/20 text-rose-400 border border-rose-500/30' }}">
                                    {{ $account->is_active ? 'Active' : 'Deactivated' }}
                                </span>
                            </td>
                            <td class="py-3 px-3 text-slate-400 text-[11px]">
                                {{ $account->last_login_ip ?? 'No record' }}
                            </td>
                            <td class="py-3 px-3 text-right">
                                @if(Auth::id() === $account->id)
                                    <span class="text-[10px] text-slate-500 font-sans italic">Current Session (Self)</span>
                                @else
                                    <form method="POST" action="{{ route('admin.users.toggle-status', $account->id) }}" class="inline" onsubmit="return confirm('Authorize account status toggle for {{ $account->email }}?')">
                                        @csrf
                                        <button type="submit" class="px-3 py-1 rounded text-[11px] font-semibold uppercase transition {{ $account->is_active ? 'bg-rose-950/60 hover:bg-rose-900 text-rose-300 border border-rose-700/60' : 'bg-emerald-950/60 hover:bg-emerald-900 text-emerald-300 border border-emerald-700/60' }}">
                                            {{ $account->is_active ? 'Deactivate' : 'Activate' }}
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-6 text-center text-slate-500">No identities found matching query.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </div>

    <!-- Recent System-Wide Login & Threat Events Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Recent Logins Feed -->
        <div class="p-6 rounded-2xl bg-[#080d19] border border-slate-800 shadow-xl glow-border">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-bold text-white uppercase tracking-wider flex items-center gap-2">
                    <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14" />
                    </svg>
                    Recent System Authentication Feed
                </h3>
                <a href="{{ route('admin.security-monitoring') }}" class="text-[11px] text-cyan-400 hover:text-cyan-300 font-mono">View All &rarr;</a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs font-mono">
                    <thead>
                        <tr class="border-b border-slate-800 text-slate-400 text-[10px] uppercase">
                            <th class="py-2">Status</th>
                            <th class="py-2">Account Target</th>
                            <th class="py-2">IP Address</th>
                            <th class="py-2">Time</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/60">
                        @foreach($recentLogins as $item)
                            <tr class="hover:bg-slate-900/30">
                                <td class="py-2">
                                    <span class="px-1.5 py-0.5 rounded text-[9px] uppercase font-bold {{ $item->status === 'success' ? 'bg-emerald-500/20 text-emerald-400' : 'bg-rose-500/20 text-rose-400' }}">
                                        {{ $item->status }}
                                    </span>
                                </td>
                                <td class="py-2 text-slate-300 truncate max-w-[140px]">{{ $item->email }}</td>
                                <td class="py-2 text-slate-400 text-[11px]">{{ $item->ip_address }}</td>
                                <td class="py-2 text-slate-500 text-[10px]">{{ $item->attempted_at->diffForHumans() }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Audit Events Feed -->
        <div class="p-6 rounded-2xl bg-[#080d19] border border-slate-800 shadow-xl glow-border">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-bold text-white uppercase tracking-wider flex items-center gap-2">
                    <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    Live Audit Ledger Stream
                </h3>
                <a href="{{ route('admin.security-monitoring') }}" class="text-[11px] text-amber-400 hover:text-amber-300 font-mono">Inspect Logs &rarr;</a>
            </div>

            <div class="space-y-2.5">
                @foreach($recentEvents as $event)
                    <div class="p-2.5 rounded-lg bg-slate-900/50 border border-slate-800 flex items-start justify-between gap-3 text-xs">
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <span class="px-1.5 py-0.5 rounded text-[9px] font-mono uppercase font-bold {{ $event->severity === 'critical' || $event->severity === 'high' ? 'bg-rose-500/20 text-rose-300' : 'bg-cyan-500/20 text-cyan-300' }}">
                                    {{ $event->severity }}
                                </span>
                                <span class="font-mono text-slate-300 font-semibold">{{ $event->event_type }}</span>
                            </div>
                            <p class="text-slate-400 text-[11px]">{{ $event->description }}</p>
                        </div>
                        <span class="text-[10px] font-mono text-slate-500 whitespace-nowrap">{{ $event->occurred_at->diffForHumans() }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
