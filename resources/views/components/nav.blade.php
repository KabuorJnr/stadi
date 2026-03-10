<header class="sticky top-0 z-50 border-b border-white/5 bg-dark/95 backdrop-blur-xl" x-data="{ open: false }">
    <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6">
        {{-- Logo --}}
        <a href="{{ route('home') }}" class="flex items-center gap-2.5">
            <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-accent text-lg font-black text-white">S</div>
            <span class="text-xl font-extrabold tracking-tight text-white">Stadi</span>
        </a>

        {{-- Desktop nav --}}
        <nav class="hidden items-center gap-1 sm:flex">
            <a href="{{ route('home') }}" class="rounded-lg px-4 py-2 text-sm font-semibold transition {{ request()->routeIs('home') ? 'bg-white/10 text-white' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">Events</a>
            <span class="mx-3 text-xs text-gray-600">|</span>
            <span class="text-xs font-medium text-gray-500">{{ config('stadium.name') }}</span>
        </nav>

        {{-- Mobile toggle --}}
        <button @click="open = !open" class="sm:hidden rounded-lg p-2 hover:bg-white/10 text-gray-400">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
    </div>

    {{-- Mobile menu --}}
    <div x-show="open" x-cloak class="border-t border-white/5 px-4 pb-4 pt-2 sm:hidden">
        <a href="{{ route('home') }}" class="block rounded-lg py-2.5 px-3 text-sm font-medium text-gray-300 hover:bg-white/5 hover:text-white">Events</a>
    </div>
</header>
