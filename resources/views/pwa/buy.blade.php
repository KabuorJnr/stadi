@extends('layouts.app')
@section('title', 'Buy Ticket — ' . $event->matchTitle())

@section('content')
<div class="border-b border-white/[0.06] bg-surface-100">
    <div class="mx-auto max-w-lg px-4 py-8">
        <a href="{{ route('event.sections', $event) }}" class="mb-3 inline-flex items-center gap-1 text-sm text-gray-500 hover:text-white transition-colors">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back to sections
        </a>
        <h1 class="text-xl font-semibold text-white">Checkout</h1>
        <p class="mt-0.5 text-sm text-gray-500">{{ $event->matchTitle() }}</p>
    </div>
</div>

<section class="mx-auto max-w-lg px-4 py-8">
    <div class="rounded-lg border border-white/[0.06] bg-surface-100">
        {{-- Order summary --}}
        <div class="p-5">
            <h2 class="text-xs font-medium uppercase tracking-wide text-gray-500">Order summary</h2>
            <div class="mt-3 space-y-2.5">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-400">Event</span>
                    <span class="text-white">{{ $event->matchTitle() }}</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-400">Date</span>
                    <span class="text-white">{{ $event->event_date->format('M j, Y · g:i A') }}</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-400">Section</span>
                    <span class="text-white">{{ $section->name }}</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-400">Tier</span>
                    <span class="inline-flex items-center gap-1.5">
                        <span class="h-2 w-2 rounded-full" style="background:{{ $section->color }}"></span>
                        <span class="text-white">{{ $section->tierLabel() }}</span>
                    </span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-400">Gate</span>
                    <span class="text-white">Gate {{ $section->gate_number }}</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-400">Available</span>
                    <span class="text-white">{{ number_format($section->remainingSeats()) }} seats</span>
                </div>
            </div>
            <div class="mt-4 flex items-center justify-between border-t border-white/[0.06] pt-4">
                <span class="text-sm font-medium text-white">Total</span>
                <span class="text-lg font-semibold text-white">KES {{ number_format($price) }}</span>
            </div>
        </div>

        {{-- Payment --}}
        <div class="border-t border-white/[0.06] p-5"
             x-data="{ phone: '', step: 'input' }"
        >
            {{-- Step 1: Phone input --}}
            <div x-show="step === 'input'">
                <h3 class="text-sm font-medium text-white">Pay with M-PESA</h3>
                <p class="mt-1 text-xs text-gray-500">Enter your Safaricom number to receive an STK push.</p>

                <div class="mt-3 flex gap-2">
                    <div class="relative flex-1">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-gray-500">+254</span>
                        <input
                            type="tel"
                            x-model="phone"
                            placeholder="7XX XXX XXX"
                            maxlength="9"
                            class="w-full rounded-md border border-white/[0.1] bg-surface py-2 pl-14 pr-3 text-sm text-white placeholder-gray-600 focus:border-accent focus:ring-1 focus:ring-accent focus:outline-none"
                        >
                    </div>
                    <button
                        @click="step = 'sending'"
                        :disabled="phone.length < 9"
                        class="flex-shrink-0 rounded-md bg-accent px-5 py-2 text-sm font-medium text-white transition hover:bg-accent-light disabled:opacity-40"
                    >
                        Pay
                    </button>
                </div>
            </div>

            {{-- Step 2: Simulated STK push --}}
            <div x-show="step === 'sending'" x-cloak x-init="$watch('step', val => { if (val === 'sending') setTimeout(() => step = 'confirming', 2000) })">
                <div class="py-6 text-center">
                    <svg class="mx-auto h-6 w-6 animate-spin text-accent" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                    <p class="mt-3 text-sm text-white">Sending STK push to +254<span x-text="phone"></span></p>
                    <p class="mt-1 text-xs text-gray-500">Check your phone for the M-PESA prompt</p>
                </div>
            </div>

            {{-- Step 3: Waiting for PIN --}}
            <div x-show="step === 'confirming'" x-cloak x-init="$watch('step', val => { if (val === 'confirming') setTimeout(() => step = 'success', 3000) })">
                <div class="py-6 text-center">
                    <svg class="mx-auto h-6 w-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    <p class="mt-3 text-sm text-white">Waiting for M-PESA PIN</p>
                    <p class="mt-1 text-xs text-gray-500">Enter your PIN on your phone to confirm</p>
                </div>
            </div>

            {{-- Step 4: Success → redirect to demo ticket --}}
            <div x-show="step === 'success'" x-cloak x-init="$watch('step', val => { if (val === 'success') setTimeout(() => window.location.href = '{{ route('ticket.show', '1da28811e65208188c8839e1897acabb9ad044fededd9545a3b85ffcc11b02ee') }}', 2000) })">
                <div class="py-6 text-center">
                    <svg class="mx-auto h-6 w-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    <p class="mt-3 text-sm font-medium text-green-400">Payment confirmed</p>
                    <p class="mt-1 text-xs text-gray-500">KES {{ number_format($price) }} received. Redirecting to your ticket…</p>
                    <p class="mt-1 font-mono text-xs text-gray-600">Ref: SHK7891DEMO</p>
                </div>
            </div>

            <p class="mt-4 text-center text-xs text-gray-600">
                Powered by Safaricom M-PESA
            </p>
        </div>
    </div>

    {{-- USSD alternative --}}
    <div class="mt-4 rounded-lg border border-white/[0.06] bg-surface-100 p-5">
        <div class="flex items-start gap-3">
            <svg class="mt-0.5 h-4 w-4 flex-shrink-0 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
            <div>
                <p class="text-sm text-white">No internet? Dial <code class="text-accent-light">*384*123#</code></p>
                <p class="mt-0.5 text-xs text-gray-500">Works on any phone with M-PESA. No smartphone needed.</p>
            </div>
        </div>
    </div>
</section>
@endsection
