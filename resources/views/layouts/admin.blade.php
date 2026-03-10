<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') — Stadi</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        surface: { DEFAULT:'#111', 100:'#191919', 200:'#222' },
                        accent:  { DEFAULT:'#2563eb', light:'#60a5fa' },
                    }
                }
            }
        }
    </script>
    <style>[x-cloak]{display:none!important;}</style>
    @stack('styles')
</head>
<body class="min-h-screen bg-surface font-sans text-gray-200 antialiased" x-data="{ sidebarOpen: false }">

    {{-- Top bar --}}
    <header class="fixed top-0 left-0 right-0 z-30 flex h-14 items-center justify-between border-b border-white/[0.06] bg-surface-100 px-4 lg:pl-56">
        <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden rounded p-1 text-gray-500 hover:text-white">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
        <h1 class="text-sm font-semibold text-white">Stadi Admin</h1>
        <span class="text-xs text-gray-500">{{ config('stadium.name') }}</span>
    </header>

    {{-- Sidebar --}}
    <aside
        x-cloak
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        class="fixed inset-y-0 left-0 z-40 w-56 transform border-r border-white/[0.06] bg-surface-200 text-white transition-transform lg:translate-x-0"
    >
        <div class="flex h-14 items-center px-4 border-b border-white/[0.06]">
            <span class="text-sm font-semibold">Stadi</span>
        </div>
        <nav class="mt-2 space-y-0.5 px-2">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5 rounded-md px-3 py-2 text-sm transition {{ request()->routeIs('admin.dashboard') ? 'bg-white/[0.06] text-white' : 'text-gray-500 hover:text-gray-300 hover:bg-white/[0.03]' }}">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0a1 1 0 01-1-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 01-1 1"/></svg>
                Dashboard
            </a>
            <a href="{{ route('admin.events.index') }}" class="flex items-center gap-2.5 rounded-md px-3 py-2 text-sm transition {{ request()->routeIs('admin.events.*') ? 'bg-white/[0.06] text-white' : 'text-gray-500 hover:text-gray-300 hover:bg-white/[0.03]' }}">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                Events
            </a>
            <a href="{{ route('admin.revenue') }}" class="flex items-center gap-2.5 rounded-md px-3 py-2 text-sm transition {{ request()->routeIs('admin.revenue') ? 'bg-white/[0.06] text-white' : 'text-gray-500 hover:text-gray-300 hover:bg-white/[0.03]' }}">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/></svg>
                Revenue
            </a>
            <hr class="my-2 border-white/[0.06]">
            <a href="{{ route('home') }}" class="flex items-center gap-2.5 rounded-md px-3 py-2 text-sm text-gray-600 transition hover:text-gray-400 hover:bg-white/[0.03]">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                View Site
            </a>
        </nav>
    </aside>

    {{-- Overlay --}}
    <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 z-30 bg-black/60 lg:hidden"></div>

    {{-- Content --}}
    <div class="pt-14 lg:pl-56">
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 rounded-md border border-green-500/20 bg-green-500/10 p-3 text-sm text-green-400">
                    {{ session('success') }}
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @stack('scripts')
</body>
</html>
