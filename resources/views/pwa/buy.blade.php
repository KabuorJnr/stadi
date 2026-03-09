@extends('layouts.app')
@section('title', 'Buy Ticket — ' . $event->matchTitle())

@section('content')
<section class="bg-pitch py-10 text-white">
    <div class="mx-auto max-w-3xl px-4 text-center">
        <h1 class="text-3xl font-bold">Confirm Your Ticket</h1>
        <p class="mt-1 text-white/60">{{ $event->matchTitle() }}</p>
    </div>
</section>

<section class="mx-auto max-w-lg px-4 py-10">
    <div class="overflow-hidden rounded-2xl border bg-white shadow-lg">
        {{-- Match banner --}}
        <div class="bg-gradient-to-r from-pitch to-brand-600 p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold">{{ $event->matchTitle() }}</h2>
                    <p class="mt-1 text-sm text-white/70">{{ $event->event_date->format('D, M j, Y · g:i A') }}</p>
                </div>
                <span class="text-3xl">⚽</span>
            </div>
        </div>

        {{-- Details --}}
        <div class="divide-y p-6">
            <div class="flex items-center justify-between py-3">
                <span class="text-sm text-gray-500">Section</span>
                <span class="font-semibold text-gray-900">{{ $section->name }}</span>
            </div>
            <div class="flex items-center justify-between py-3">
                <span class="text-sm text-gray-500">Tier</span>
                <span class="inline-flex items-center gap-1.5">
                    <span class="h-2.5 w-2.5 rounded-full" style="background:{{ $section->color }}"></span>
                    {{ $section->tierLabel() }}
                </span>
            </div>
            <div class="flex items-center justify-between py-3">
                <span class="text-sm text-gray-500">Gate</span>
                <span class="font-medium">Gate {{ $section->gate_number }}</span>
            </div>
            <div class="flex items-center justify-between py-3">
                <span class="text-sm text-gray-500">Seats remaining</span>
                <span>{{ number_format($section->remainingSeats()) }}</span>
            </div>
            <div class="flex items-center justify-between py-4">
                <span class="text-base font-bold text-gray-900">Total</span>
                <span class="text-2xl font-bold text-brand-500">KES {{ number_format($price) }}</span>
            </div>
        </div>

        {{-- Payment --}}
        <div class="border-t bg-gray-50 p-6" x-data="{ phone: '', loading: false }">
            <h3 class="mb-3 text-sm font-semibold text-gray-700">Pay with M-PESA</h3>
            <p class="mb-4 text-xs text-gray-400">Enter your Safaricom phone number. You'll receive an STK push prompt on your phone.</p>

            <div class="flex gap-3">
                <div class="relative flex-1">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-gray-400">+254</span>
                    <input
                        type="tel"
                        x-model="phone"
                        placeholder="7XX XXX XXX"
                        maxlength="9"
                        class="w-full rounded-lg border border-gray-300 py-2.5 pl-14 pr-4 text-sm focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20"
                    >
                </div>
                <button
                    @click="loading = true"
                    :disabled="phone.length < 9 || loading"
                    class="flex-shrink-0 rounded-lg bg-brand-500 px-6 py-2.5 text-sm font-bold text-white transition hover:bg-brand-600 disabled:opacity-40"
                >
                    <span x-show="!loading">Pay Now</span>
                    <span x-show="loading" x-cloak class="flex items-center gap-2">
                        <svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                        Sending…
                    </span>
                </button>
            </div>

            <p class="mt-4 text-center text-xs text-gray-400">
                Powered by Safaricom M-PESA &middot; Lipa Na M-PESA
            </p>
        </div>
    </div>

    <a href="{{ route('event.sections', $event) }}" class="mt-6 flex items-center justify-center gap-1 text-sm text-gray-400 hover:text-gray-600 transition">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Back to sections
    </a>
</section>
@endsection
