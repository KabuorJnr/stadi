<footer class="border-t border-white/5 bg-dark-50">
    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6">
        <div class="flex flex-col items-center justify-between gap-6 sm:flex-row">
            <div class="flex items-center gap-2.5">
                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-accent text-sm font-black text-white">S</div>
                <span class="text-lg font-extrabold tracking-tight text-white">Stadi</span>
            </div>
            <div class="flex flex-wrap items-center gap-6 text-xs text-gray-500">
                <span>{{ config('stadium.name') }}</span>
                <span class="hidden sm:inline">·</span>
                <span>M-PESA Payments</span>
                <span class="hidden sm:inline">·</span>
                <span>USSD: *384*123#</span>
            </div>
        </div>
        <div class="mt-8 border-t border-white/5 pt-6 text-center text-xs text-gray-600">
            &copy; {{ date('Y') }} Stadi. All rights reserved. Designed for Kenyan stadiums.
        </div>
    </div>
</footer>
