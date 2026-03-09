<header class="sticky top-0 z-30 bg-pitch text-white shadow-lg" x-data="{ open: false }">
    <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6">
        {{-- Logo --}}
        <a href="{{ route('home') }}" class="flex items-center gap-2 text-xl font-bold tracking-wide">
            <span class="text-2xl">⚽</span> Stadi
        </a>

        {{-- Desktop links --}}
        <nav class="hidden items-center gap-6 text-sm font-medium sm:flex">
            <a href="{{ route('home') }}" class="hover:text-gold-400 transition {{ request()->routeIs('home') ? 'text-gold-400' : '' }}">Events</a>
            <span class="text-xs opacity-40">{{ config('stadium.name') }}</span>
        </nav>

        {{-- Mobile toggle --}}
        <button @click="open = !open" class="sm:hidden rounded p-1.5 hover:bg-white/10">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
    </div>

    {{-- Mobile menu --}}
    <div x-show="open" x-cloak class="border-t border-white/10 px-4 pb-4 pt-2 sm:hidden">
        <a href="{{ route('home') }}" class="block rounded py-2 px-3 text-sm hover:bg-white/10">Events</a>
    </div>
</header>
