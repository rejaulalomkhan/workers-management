<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>{{ $title ?? 'FHTS System' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @php $branding = \App\Models\Setting::first(); @endphp

    {{-- ── PWA Meta ── --}}
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#0f255a">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="{{ $branding->company_name ?? 'FHTS' }}">
    <meta name="application-name" content="{{ $branding->company_name ?? 'FHTS' }}">
    @if($branding && $branding->logo_path)
        <link rel="icon" href="{{ asset($branding->logo_path) }}">
        <link rel="apple-touch-icon" href="{{ asset($branding->logo_path) }}">
    @endif

    @livewireStyles

    <style>
        /* ── Progress bar for wire:navigate ── */
        [wire\:loading-bar] { display: none; }

        /* Sidebar item active/hover */
        .nav-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 14px;
            border-radius: 8px;
            font-size: 13.5px;
            font-weight: 500;
            color: #abc2e6ff;
            transition: background 150ms, color 150ms;
            cursor: pointer;
            text-decoration: none;
        }
        .nav-item:hover { background: rgba(255,255,255,0.07); color: #e2e8f0; }
        .nav-item.active { background: rgba(99,179,237,0.15); color: #93c5fd; border: 1px solid rgba(99,179,237,0.2); }
        .nav-item svg { flex-shrink: 0; opacity: .85; }

        .nav-section {
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 1.2px;
            text-transform: uppercase;
            color: #93b122;
            padding: 0 14px;
            margin: 18px 0 6px;
        }

        /* Mobile bottom nav active */
        .bnav-item { display: flex; flex-direction: column; align-items: center; padding: 6px 4px; color: #64748b; font-size: 10px; gap: 2px; flex: 1; text-decoration: none; transition: color 150ms; }
        .bnav-item.active { color: #2563eb; }
        .bnav-item svg { width: 22px; height: 22px; }

        /* Livewire navigate loading bar */
        #nprogress .bar { background: #60a5fa; height: 2px; }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 font-sans antialiased pb-16 md:pb-0 md:pl-60"
      x-data="{ sidebarOpen: false }">

    {{-- ══ MOBILE TOP BAR ══ --}}
    <header class="md:hidden fixed top-0 w-full z-30 bg-white border-b border-slate-200 shadow-sm">
        <div class="px-4 h-14 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <button @click="sidebarOpen = !sidebarOpen"
                        class="p-1.5 rounded-lg text-slate-500 hover:bg-slate-100 transition">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <a href="/reports/profit-loss" wire:navigate class="flex items-center gap-2">
                    @if(isset($branding) && $branding->logo_path)
                        <img src="{{ asset($branding->logo_path) }}" alt="Logo" class="h-7 w-auto object-contain">
                    @else
                        <span class="font-bold text-slate-800 text-sm tracking-tight">FHTS</span>
                    @endif
                </a>
            </div>
            <form method="POST" action="/logout">
                @csrf
                <button type="submit"
                        class="text-xs font-semibold flex items-center gap-1.5 text-slate-500 hover:text-red-600 px-3 py-1.5 rounded-lg hover:bg-red-50 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Logout
                </button>
            </form>
        </div>
    </header>

    {{-- ══ SIDEBAR OVERLAY (mobile) ══ --}}
    <div x-show="sidebarOpen" @click="sidebarOpen = false"
         x-transition:enter="transition-opacity ease-out duration-200"
         x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-in duration-150"
         x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-slate-900/50 z-40 md:hidden" style="display:none;"></div>

    {{-- ══ SIDEBAR ══ --}}
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
           class="flex flex-col w-60 fixed h-full top-0 left-0 z-50
                  transform transition-transform duration-300 ease-in-out
                  -translate-x-full md:translate-x-0
                  bg-[#0f255a] border-r border-white/5"
           style="box-shadow: 1px 0 20px rgba(0,0,0,.3);">

        {{-- Logo --}}
        <div class="px-5 h-16 flex items-center justify-between border-b border-white/5">
            <a href="/reports/profit-loss" wire:navigate class="flex items-center gap-2.5">
                @if(isset($branding) && $branding->logo_path)
                    <img src="{{ asset($branding->logo_path) }}" alt="Logo"
                         class="h-8 w-auto object-contain bg-white rounded p-0.5">
                @endif
                <span class="font-bold text-white text-sm tracking-tight leading-tight">
                    FHTS<br><span class="text-slate-400 font-normal text-[10px] tracking-widest uppercase">Management</span>
                </span>
            </a>
            <button @click="sidebarOpen = false" class="md:hidden text-slate-400 hover:text-white p-1 rounded transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-0.5">

            <div class="nav-section">Main</div>

            <a href="/projects" wire:navigate @click="sidebarOpen = false"
               class="nav-item {{ request()->is('projects*') ? 'active' : '' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                Projects
            </a>

            <a href="/workers" wire:navigate @click="sidebarOpen = false"
               class="nav-item {{ request()->is('workers*') ? 'active' : '' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                Workers
            </a>

            <a href="/attendance" wire:navigate @click="sidebarOpen = false"
               class="nav-item {{ request()->is('attendance*') ? 'active' : '' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Attendance
            </a>

            <a href="/salary" wire:navigate @click="sidebarOpen = false"
               class="nav-item {{ request()->is('salary*') ? 'active' : '' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Salary
            </a>

            <a href="/invoices" wire:navigate @click="sidebarOpen = false"
               class="nav-item {{ request()->is('invoices*') ? 'active' : '' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Invoices
            </a>

            <div class="nav-section">Reports</div>

            <a href="/reports/monthly-attendance" wire:navigate @click="sidebarOpen = false"
               class="nav-item {{ request()->is('reports/monthly-attendance*') ? 'active' : '' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Monthly Attendance
            </a>

            <a href="/reports/profit-loss" wire:navigate @click="sidebarOpen = false"
               class="nav-item {{ request()->is('reports/profit-loss*') ? 'active' : '' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                </svg>
                Profit & Loss
            </a>

            <div class="nav-section">System</div>

            <a href="/settings" wire:navigate @click="sidebarOpen = false"
               class="nav-item {{ request()->is('settings*') ? 'active' : '' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                Settings
            </a>
        </nav>

        {{-- User / Logout --}}
        <div class="px-3 py-4 border-t border-white/5">
            <form method="POST" action="/logout">
                @csrf
                <button type="submit"
                        class="nav-item w-full text-left hover:text-red-400 hover:bg-red-500/10 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Logout
                </button>
            </form>

            {{-- Developer Credit --}}
            <div class="mt-3 pt-3 border-t border-white/5 text-center">
                <p class="text-[10px] text-slate-500 mb-1">Software Production <span class="text-red-400">♥</span> by</p>
                <a href="https://wa.me/+8801916628339" target="_blank"
                   class="inline-flex items-center gap-1.5 text-[11px] font-semibold text-slate-400 hover:text-green-400 transition-colors group">
                    <svg class="w-3 h-3 text-green-500 flex-shrink-0" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                    </svg>
                    Arman Azij
                </a>
            </div>
        </div>
    </aside>

    {{-- ══ MAIN CONTENT ══ --}}
    <main class="pt-14 md:pt-3 px-4 md:px-7 py-6 min-h-screen">
        {{ $slot }}
    </main>

    {{-- ══ MOBILE BOTTOM NAV ══ --}}
    <nav class="md:hidden fixed bottom-0 w-full z-20
                bg-white/90 backdrop-blur border-t border-slate-200
                flex items-stretch px-1 safe-pb"
         style="box-shadow: 0 -1px 10px rgba(0,0,0,.07);">

        <a href="/projects" wire:navigate
           class="bnav-item {{ request()->is('projects*') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
            <span>Projects</span>
        </a>

        <a href="/workers" wire:navigate
           class="bnav-item {{ request()->is('workers*') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <span>Workers</span>
        </a>

        <a href="/attendance" wire:navigate
           class="bnav-item {{ request()->is('attendance*') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <span>Attend</span>
        </a>

        <a href="/reports/monthly-attendance" wire:navigate
           class="bnav-item {{ request()->is('reports/monthly-attendance*') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <span>Report</span>
        </a>

        <a href="/invoices" wire:navigate
           class="bnav-item {{ request()->is('invoices*') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <span>Invoice</span>
        </a>
    </nav>

    @livewireScripts

    {{-- ── PWA: wire:navigate page transition loader ── --}}
    <div id="pwa-loader"
         style="display:none; position:fixed; inset:0; z-index:9999;
                background:#0f255a; flex-direction:column;
                align-items:center; justify-content:center; gap:16px;">
        @if($branding && $branding->logo_path)
        <img src="{{ asset($branding->logo_path) }}"
             style="width:72px; height:72px; object-fit:contain;
                    border-radius:16px; background:#fff; padding:8px;
                    animation: pulse-logo 1.2s ease-in-out infinite;">
        @endif
        <div style="width:36px; height:3px; background:rgba(255,255,255,.15); border-radius:2px; overflow:hidden;">
            <div id="pwa-bar" style="height:100%; width:0%; background:#60a5fa; border-radius:2px;
                           transition: width .8s ease; animation: load-bar 1.2s ease-in-out infinite;"></div>
        </div>
    </div>

    <style>
        @keyframes pulse-logo { 0%,100%{ opacity:1; transform:scale(1); } 50%{ opacity:.7; transform:scale(.93); } }
        @keyframes load-bar   { 0%{ width:0% } 60%{ width:85% } 100%{ width:100% } }
    </style>

    <script>
        // ── wire:navigate loader ──────────────────────────────────────────────
        const loader = document.getElementById('pwa-loader');

        document.addEventListener('livewire:navigating', () => {
            if (loader) loader.style.display = 'flex';
        });
        document.addEventListener('livewire:navigated', () => {
            if (loader) {
                setTimeout(() => { loader.style.display = 'none'; }, 150);
            }
        });

        // ── Service Worker registration ───────────────────────────────────────
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js', { scope: '/' })
                    .then(reg => console.log('[PWA] SW registered:', reg.scope))
                    .catch(err => console.warn('[PWA] SW error:', err));
            });
        }
    </script>
</body>
</html>
