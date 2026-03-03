<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>{{ $title ?? 'FHTS System' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-gray-50 text-gray-900 font-sans antialiased pb-20 md:pb-0 md:pl-64" x-data="{ sidebarOpen: false }">
    <!-- Mobile Header -->
    <header class="bg-blue-700 text-white shadow-md md:hidden fixed top-0 w-full z-30">
        <div class="px-4 py-3 flex justify-between items-center">
            <div class="flex items-center">
                <button @click="sidebarOpen = !sidebarOpen" class="mr-3 p-1 rounded-md hover:bg-blue-600 focus:outline-none">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                <a href="/" class="text-lg font-bold tracking-tight">FHTS System</a>
            </div>
            <form method="POST" action="/logout" class="block">
                @csrf
                <button type="submit" class="text-sm font-semibold flex items-center bg-blue-800 px-3 py-1.5 rounded-lg active:bg-blue-900">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    Out
                </button>
            </form>
        </div>
    </header>

    <!-- Mobile Sidebar Overlay -->
    <div x-show="sidebarOpen" @click="sidebarOpen = false" x-transition.opacity class="fixed inset-0 bg-gray-900 bg-opacity-50 z-40 md:hidden" style="display: none;"></div>

    <!-- Sidebar -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="flex flex-col w-64 bg-blue-800 text-white fixed h-full top-0 left-0 shadow-[4px_0_24px_rgba(0,0,0,0.15)] z-50 transform transition-transform duration-300 ease-in-out -translate-x-full md:translate-x-0">
        <div class="px-6 py-5 border-b border-blue-700 flex justify-between items-center">
            <a href="/" class="font-bold text-2xl">FHTS System</a>
            <button @click="sidebarOpen = false" class="md:hidden text-white hover:text-gray-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <nav class="flex-1 px-4 py-6 space-y-2">
            <a href="/" class="block px-4 py-2 rounded-lg hover:bg-blue-700 {{ request()->is('/') ? 'bg-blue-700' : '' }}">Dashboard</a>
            <a href="/projects" class="block px-4 py-2 rounded-lg hover:bg-blue-700 {{ request()->is('projects*') ? 'bg-blue-700' : '' }}">Projects</a>
            <a href="/workers" class="block px-4 py-2 rounded-lg hover:bg-blue-700 {{ request()->is('workers*') ? 'bg-blue-700' : '' }}">Workers</a>
            <a href="/attendance" class="block px-4 py-2 rounded-lg hover:bg-blue-700 {{ request()->is('attendance*') ? 'bg-blue-700' : '' }}">Attendance</a>
            <a href="/salary" class="block px-4 py-2 rounded-lg hover:bg-blue-700 {{ request()->is('salary*') ? 'bg-blue-700' : '' }}">Salary</a>
            <a href="/invoices" class="block px-4 py-2 rounded-lg hover:bg-blue-700 {{ request()->is('invoices*') ? 'bg-blue-700' : '' }}">Invoices</a>
            <a href="/settings" class="block px-4 py-2 rounded-lg hover:bg-blue-700 {{ request()->is('settings*') ? 'bg-blue-700' : '' }}">Settings</a>
        </nav>
        <div class="px-4 py-4 border-t border-blue-700 mt-auto">
            <form method="POST" action="/logout">
                @csrf
                <button type="submit" class="w-full text-left px-4 py-2 rounded-lg hover:bg-blue-700 text-blue-200 hover:text-white transition flex items-center">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    Logout
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="pt-16 md:pt-6 px-4 md:px-8 pb-6 mx-auto w-full max-w-7xl">
        {{ $slot }}
    </main>

    <!-- Mobile Bottom Navigation -->
    <nav class="md:hidden fixed bottom-0 w-full bg-white border-t border-gray-200 shadow-[0_-2px_10px_rgba(0,0,0,0.05)] flex justify-between px-2 text-[10px] text-gray-500 pb-safe z-20">
        <a href="/" class="flex flex-col items-center py-2 px-1 w-1/5 {{ request()->is('/') ? 'text-blue-600' : '' }}">
            <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
            Home
        </a>
        <a href="/projects" class="flex flex-col items-center py-2 px-1 w-1/5 {{ request()->is('projects*') ? 'text-blue-600' : '' }}">
            <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            Projects
        </a>
        <a href="/workers" class="flex flex-col items-center py-2 px-1 w-1/5 {{ request()->is('workers*') ? 'text-blue-600' : '' }}">
            <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            Workers
        </a>
        <a href="/attendance" class="flex flex-col items-center py-2 px-1 w-1/5 {{ request()->is('attendance*') ? 'text-blue-600' : '' }}">
            <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            Attend
        </a>
        <a href="/invoices" class="flex flex-col items-center py-2 px-1 w-1/5 {{ request()->is('invoices*') ? 'text-blue-600' : '' }}">
            <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            Invoice
        </a>
    </nav>
    <a href="/settings" class="md:hidden fixed bottom-20 right-4 bg-gray-800 text-white p-3 rounded-full shadow-lg z-20 {{ request()->is('settings*') ? 'bg-blue-600' : '' }}">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
    </a>

    @livewireScripts
</body>
</html>
