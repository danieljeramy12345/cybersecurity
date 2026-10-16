<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'CyberShield — Enterprise Defensive Security')</title>

    <!-- Preconnect Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <!-- Vite Styles and Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#050811] text-slate-200 cyber-grid antialiased flex flex-col selection:bg-cyan-500 selection:text-black">

    <!-- Top Glow Overlay -->
    <div class="fixed top-0 left-1/2 -translate-x-1/2 w-full max-w-7xl h-96 cyber-radial pointer-events-none -z-10"></div>

    <!-- Main Navigation Bar -->
    <nav class="sticky top-0 z-40 backdrop-blur-md bg-[#070b14]/85 border-b border-slate-800/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Brand / Logo -->
                <div class="flex items-center space-x-3">
                    <a href="{{ route('home') }}" class="flex items-center space-x-2.5 group">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-cyan-600 via-sky-500 to-emerald-400 p-0.5 shadow-lg shadow-cyan-500/20 group-hover:shadow-cyan-400/40 transition duration-300">
                            <div class="w-full h-full bg-[#070b14] rounded-[10px] flex items-center justify-center">
                                <svg class="w-5 h-5 text-cyan-400 group-hover:text-emerald-400 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                            </div>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-lg font-bold tracking-wider text-white flex items-center gap-1.5">
                                CYBER<span class="text-cyan-400">SHIELD</span>
                            </span>
                            <span class="text-[10px] uppercase tracking-widest text-emerald-400 font-semibold -mt-1 flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span> DEFENSE NODE ACTIVE
                            </span>
                        </div>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden md:flex items-center space-x-6">
                    <a href="{{ route('home') }}" class="text-sm font-medium {{ request()->routeIs('home') ? 'text-cyan-400' : 'text-slate-400 hover:text-white' }} transition">
                        System Overview
                    </a>

                    @auth
                        @if(Auth::user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="text-sm font-medium {{ request()->routeIs('admin.dashboard') ? 'text-cyan-400' : 'text-slate-400 hover:text-white' }} transition flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-amber-400"></span> Admin Console
                            </a>
                            <a href="{{ route('admin.security-monitoring') }}" class="text-sm font-medium {{ request()->routeIs('admin.security-monitoring') ? 'text-cyan-400' : 'text-slate-400 hover:text-white' }} transition flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-red-400 animate-ping"></span> Threat SOC
                            </a>
                        @endif

                        <a href="{{ route('dashboard') }}" class="text-sm font-medium {{ request()->routeIs('dashboard') ? 'text-cyan-400' : 'text-slate-400 hover:text-white' }} transition">
                            User Dashboard
                        </a>
                    @endauth
                </div>

                <!-- Auth Controls -->
                <div class="flex items-center space-x-4">
                    @auth
                        <div class="hidden sm:flex flex-col text-right">
                            <span class="text-xs font-semibold text-white">{{ Auth::user()->name }}</span>
                            <span class="text-[10px] text-cyan-400 uppercase tracking-wider font-mono">{{ Auth::user()->role }}</span>
                        </div>

                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="px-3.5 py-1.5 text-xs font-medium text-slate-300 hover:text-white bg-slate-800/80 hover:bg-red-950/40 border border-slate-700 hover:border-red-500/50 rounded-lg transition duration-200 flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                Terminate Session
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-medium text-slate-300 hover:text-white transition px-3 py-1.5">
                            Authenticate
                        </a>
                        <a href="{{ route('register') }}" class="px-4 py-2 text-xs font-semibold uppercase tracking-wider text-black bg-gradient-to-r from-cyan-400 to-emerald-400 hover:from-cyan-300 hover:to-emerald-300 rounded-lg shadow-lg shadow-cyan-500/20 hover:shadow-cyan-400/30 transition duration-200">
                            Provision Access
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Global Flash Notification Alerts -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full mt-4">
        @if (session('success'))
            <div class="mb-4 p-4 rounded-xl bg-emerald-950/40 border border-emerald-500/40 text-emerald-300 text-sm flex items-center justify-between shadow-lg shadow-emerald-950/20">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-emerald-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-4 p-4 rounded-xl bg-rose-950/40 border border-rose-500/40 text-rose-300 text-sm flex items-center justify-between shadow-lg shadow-rose-950/20">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-rose-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
            </div>
        @endif

        @if (session('status'))
            <div class="mb-4 p-4 rounded-xl bg-cyan-950/40 border border-cyan-500/40 text-cyan-300 text-sm flex items-center gap-3 shadow-lg shadow-cyan-950/20">
                <svg class="w-5 h-5 text-cyan-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('status') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 p-4 rounded-xl bg-rose-950/30 border border-rose-600/40 text-rose-200 text-sm">
                <div class="flex items-center gap-2 font-semibold mb-1 text-rose-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Security Validation Exceptions:</span>
                </div>
                <ul class="list-disc list-inside space-y-1 text-xs text-rose-300 pl-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <!-- Main Content Slot -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Cybersecurity Telemetry Footer -->
    <footer class="mt-20 border-t border-slate-800/80 bg-[#060912]/90 backdrop-blur-sm py-8 text-xs text-slate-500">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                <div class="flex items-center space-x-4">
                    <span class="text-white font-bold tracking-wider">CYBER<span class="text-cyan-400">SHIELD</span></span>
                    <span class="text-slate-700">|</span>
                    <span class="text-slate-400">Zero-Trust Authentication Engine</span>
                    <span class="text-slate-700">|</span>
                    <span class="text-emerald-400 font-mono text-[11px] flex items-center gap-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span> CSRF & Rate-Limiter Active
                    </span>
                </div>
                <div class="flex items-center space-x-6 text-slate-400">
                    <span class="flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        AES-256-CBC Session Encryption
                    </span>
                    <span class="font-mono text-[10px] text-slate-500">WampServer MySQL 8.4 • PHP 8.3</span>
                </div>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
