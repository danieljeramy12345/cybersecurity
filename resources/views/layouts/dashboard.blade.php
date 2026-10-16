<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'CyberShield — Command Console')</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#050811] text-slate-200 antialiased flex selection:bg-cyan-500 selection:text-black">

    <!-- Mobile Sidebar Toggle Overlay -->
    <div id="sidebarBackdrop" onclick="toggleSidebar()" class="fixed inset-0 bg-black/70 backdrop-blur-sm z-30 hidden lg:hidden"></div>

    <!-- Sidebar Navigation -->
    <aside id="mainSidebar" class="fixed top-0 bottom-0 left-0 w-64 bg-[#070c18] border-r border-slate-800/80 z-40 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 flex flex-col justify-between">
        <div>
            <!-- Sidebar Header / Logo -->
            <div class="h-16 px-6 flex items-center justify-between border-b border-slate-800/80 bg-[#060a14]">
                <a href="{{ route('home') }}" class="flex items-center space-x-2.5">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-tr from-cyan-500 to-emerald-400 p-0.5 shadow-md shadow-cyan-500/30">
                        <div class="w-full h-full bg-[#070c18] rounded-[6px] flex items-center justify-center">
                            <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                    </div>
                    <span class="font-bold tracking-wider text-white text-base">
                        CYBER<span class="text-cyan-400">SHIELD</span>
                    </span>
                </a>
                <button onclick="toggleSidebar()" class="lg:hidden text-slate-400 hover:text-white">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- User Status Card in Sidebar -->
            <div class="p-4 mx-4 my-4 rounded-xl bg-slate-900/60 border border-slate-800/90 glow-border">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-slate-700 to-slate-800 border border-cyan-500/40 flex items-center justify-center text-cyan-400 font-bold text-sm">
                        {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-semibold text-white truncate">{{ Auth::user()->name }}</p>
                        <p class="text-[11px] text-slate-400 truncate">{{ Auth::user()->email }}</p>
                    </div>
                </div>
                <div class="mt-3 pt-2.5 border-t border-slate-800/80 flex items-center justify-between text-[11px]">
                    <span class="text-slate-400">Privilege:</span>
                    <span class="px-2 py-0.5 rounded text-[10px] font-mono uppercase font-semibold {{ Auth::user()->isAdmin() ? 'bg-amber-500/20 text-amber-300 border border-amber-500/40' : 'bg-cyan-500/20 text-cyan-300 border border-cyan-500/40' }}">
                        {{ Auth::user()->role }}
                    </span>
                </div>
            </div>

            <!-- Navigation Links -->
            <nav class="px-4 space-y-1">
                <div class="px-2 py-1.5 text-[10px] font-semibold uppercase tracking-wider text-slate-500">
                    Personnel Access
                </div>

                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2 text-xs font-medium rounded-lg transition {{ request()->routeIs('dashboard') ? 'bg-cyan-500/15 text-cyan-400 border border-cyan-500/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/50' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    My Security Center
                </a>

                @if(Auth::user()->isAdmin())
                    <div class="pt-4 px-2 py-1.5 text-[10px] font-semibold uppercase tracking-wider text-amber-400/90 flex items-center justify-between">
                        <span>SOC Administration</span>
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-pulse"></span>
                    </div>

                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2 text-xs font-medium rounded-lg transition {{ request()->routeIs('admin.dashboard') ? 'bg-amber-500/15 text-amber-300 border border-amber-500/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/50' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        User & Access Control
                    </a>

                    <a href="{{ route('admin.security-monitoring') }}" class="flex items-center gap-3 px-3 py-2 text-xs font-medium rounded-lg transition {{ request()->routeIs('admin.security-monitoring') ? 'bg-rose-500/15 text-rose-300 border border-rose-500/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/50' }}">
                        <svg class="w-4 h-4 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        Security Monitoring Console
                    </a>
                @endif

                <div class="pt-4 px-2 py-1.5 text-[10px] font-semibold uppercase tracking-wider text-slate-500">
                    Defensive Systems
                </div>

                <a href="{{ route('home') }}" class="flex items-center gap-3 px-3 py-2 text-xs font-medium text-slate-400 hover:text-white hover:bg-slate-800/50 rounded-lg transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                    </svg>
                    Public Portal
                </a>
            </nav>
        </div>

        <!-- Sidebar Footer Session Termination -->
        <div class="p-4 border-t border-slate-800/80 bg-[#060a14]">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full py-2 px-3 text-xs font-semibold text-rose-300 hover:text-white bg-rose-950/40 hover:bg-rose-900/60 border border-rose-800/50 rounded-lg transition flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Terminate Session
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 lg:pl-64 flex flex-col min-h-screen">
        <!-- Top App Bar -->
        <header class="sticky top-0 z-20 backdrop-blur-md bg-[#070c18]/90 border-b border-slate-800/80 px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <button onclick="toggleSidebar()" class="lg:hidden p-2 text-slate-400 hover:text-white rounded-lg border border-slate-800">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span class="text-xs font-mono uppercase tracking-wider text-emerald-400 font-semibold hidden sm:inline">
                        DEFENSE STATUS: ACTIVE • RATE-LIMITING 5/MIN • CSRF SECURE
                    </span>
                </div>
            </div>

            <div class="flex items-center space-x-4">
                <div class="hidden sm:flex items-center gap-2 text-xs font-mono text-slate-400 bg-slate-900/80 px-3 py-1 rounded-md border border-slate-800">
                    <svg class="w-3.5 h-3.5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>IP: {{ request()->ip() }}</span>
                </div>

                <div class="flex items-center space-x-2">
                    <span class="text-xs text-slate-300 font-medium">{{ Auth::user()->name }}</span>
                    <div class="w-7 h-7 rounded-full bg-cyan-950 border border-cyan-500/50 flex items-center justify-center text-[11px] font-bold text-cyan-400">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                </div>
            </div>
        </header>

        <!-- Flash messages in dashboard -->
        <div class="px-4 sm:px-6 lg:px-8 mt-4">
            @if (session('success'))
                <div class="p-3.5 rounded-xl bg-emerald-950/40 border border-emerald-500/40 text-emerald-300 text-xs flex items-center justify-between shadow-lg">
                    <div class="flex items-center gap-2.5">
                        <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="p-3.5 rounded-xl bg-rose-950/40 border border-rose-500/40 text-rose-300 text-xs flex items-center justify-between shadow-lg">
                    <div class="flex items-center gap-2.5">
                        <svg class="w-4 h-4 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <span>{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            @if ($errors->any())
                <div class="p-3.5 rounded-xl bg-rose-950/30 border border-rose-600/40 text-rose-200 text-xs">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        <!-- Dashboard Body Slot -->
        <main class="flex-1 p-4 sm:p-6 lg:p-8">
            @yield('content')
        </main>
    </div>

    <!-- Client-side script for sidebar toggle -->
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('mainSidebar');
            const backdrop = document.getElementById('sidebarBackdrop');
            if (sidebar.classList.contains('-translate-x-full')) {
                sidebar.classList.remove('-translate-x-full');
                backdrop.classList.remove('hidden');
            } else {
                sidebar.classList.add('-translate-x-full');
                backdrop.classList.add('hidden');
            }
        }
    </script>
    @stack('scripts')
</body>
</html>
