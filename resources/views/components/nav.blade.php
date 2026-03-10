<header class="sticky top-0 z-50 border-b border-white/[0.06] bg-surface" x-data="{ open: false }">
    <div class="mx-auto flex h-14 max-w-7xl items-center justify-between px-4 sm:px-6">
        <a href="{{ route('home') }}" class="flex items-center gap-2">
            <span class="text-base font-semibold text-white">Stadi</span>
        </a>

        <nav class="hidden items-center gap-6 sm:flex">
            <a href="{{ route('home') }}" class="text-sm {{ request()->routeIs('home') ? 'text-white' : 'text-gray-500 hover:text-gray-300' }}">Events</a>
            <span class="text-xs text-gray-600">{{ config('stadium.name') }}</span>
        </nav>

        <button @click="open = !open" class="sm:hidden rounded p-1.5 text-gray-500 hover:text-white">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
    </div>

    <div x-show="open" x-cloak class="border-t border-white/[0.06] px-4 pb-3 pt-2 sm:hidden">
        <a href="{{ route('home') }}" class="block py-2 text-sm text-gray-400 hover:text-white">Events</a>
    </div>
</header>
