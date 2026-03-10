@extends('layouts.app')
@section('title', 'Buy Ticket — ' . $event->matchTitle())

@section('content')
<section class="tm-gradient py-10 relative overflow-hidden">
    <div class="absolute inset-0 opacity-[0.03]" style="background-image: radial-gradient(circle at 1px 1px, white 1px, transparent 0); background-size: 40px 40px;"></div>
    <div class="relative mx-auto max-w-3xl px-4">
        <a href="{{ route('event.sections', $event) }}" class="mb-4 inline-flex items-center gap-1.5 text-sm text-gray-400 hover:text-white transition">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back to sections
        </a>
        <div class="text-center">
            <h1 class="text-3xl font-black text-white">Confirm Your Ticket</h1>
            <p class="mt-1 text-gray-400">{{ $event->matchTitle() }}</p>
        </div>
    </div>
</section>

<section class="mx-auto max-w-lg px-4 py-10 -mt-4 relative z-10">
    <div class="overflow-hidden rounded-2xl bg-dark-100">
        {{-- Match banner --}}
        <div class="relative p-6" style="background: linear-gradient(135deg, #026cdf 0%, #0f1b2d 100%);">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-extrabold text-white">{{ $event->matchTitle() }}</h2>
                    <p class="mt-1 text-sm text-white/50">{{ $event->event_date->format('D, M j, Y · g:i A') }}</p>
                </div>
                <span class="text-3xl">⚽</span>
            </div>
        </div>

        {{-- Details --}}
        <div class="divide-y divide-white/5 p-6">
            <div class="flex items-center justify-between py-3">
                <span class="text-sm text-gray-500">Section</span>
                <span class="font-bold text-white">{{ $section->name }}</span>
            </div>
            <div class="flex items-center justify-between py-3">
                <span class="text-sm text-gray-500">Tier</span>
                <span class="inline-flex items-center gap-1.5">
                    <span class="h-2.5 w-2.5 rounded-full" style="background:{{ $section->color }}"></span>
                    <span class="text-white">{{ $section->tierLabel() }}</span>
                </span>
            </div>
            <div class="flex items-center justify-between py-3">
                <span class="text-sm text-gray-500">Gate</span>
                <span class="font-medium text-white">Gate {{ $section->gate_number }}</span>
            </div>
            <div class="flex items-center justify-between py-3">
                <span class="text-sm text-gray-500">Seats remaining</span>
                <span class="text-white">{{ number_format($section->remainingSeats()) }}</span>
            </div>
            <div class="flex items-center justify-between py-4">
                <span class="text-base font-extrabold text-white">Total</span>
                <span class="text-2xl font-black text-accent-light">KES {{ number_format($price) }}</span>
            </div>
        </div>

        {{-- Payment --}}
        <div class="border-t border-white/5 bg-dark-200 p-6"
             x-data="{ phone: '', step: 'input' }"
        >
            {{-- Step 1: Phone input --}}
            <div x-show="step === 'input'">
                <h3 class="mb-3 text-sm font-bold text-white">Pay with M-PESA</h3>
                <p class="mb-4 text-xs text-gray-500">Enter your Safaricom phone number. You'll receive an STK push prompt on your phone.</p>

                <div class="flex gap-3">
                    <div class="relative flex-1">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-gray-500">+254</span>
                        <input
                            type="tel"
                            x-model="phone"
                            placeholder="7XX XXX XXX"
                            maxlength="9"
                            class="w-full rounded-xl border border-white/10 bg-dark py-2.5 pl-14 pr-4 text-sm text-white placeholder-gray-600 focus:border-accent focus:ring-2 focus:ring-accent/20 focus:outline-none"
                        >
                    </div>
                    <button
                        @click="step = 'sending'"
                        :disabled="phone.length < 9"
                        class="flex-shrink-0 rounded-xl bg-accent px-6 py-2.5 text-sm font-bold text-white transition hover:bg-accent-light disabled:opacity-40"
                    >
                        Pay Now
                    </button>
                </div>
            </div>

            {{-- Step 2: Simulated STK push --}}
            <div x-show="step === 'sending'" x-cloak x-init="$watch('step', val => { if (val === 'sending') setTimeout(() => step = 'confirming', 2000) })">
                <div class="py-4 text-center">
                    <svg class="mx-auto h-8 w-8 animate-spin text-accent" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                    <p class="mt-3 text-sm font-bold text-white">Sending STK push to +254<span x-text="phone"></span>…</p>
                    <p class="mt-1 text-xs text-gray-500">Check your phone for the M-PESA prompt</p>
                </div>
            </div>

            {{-- Step 3: Simulated PIN entry --}}
            <div x-show="step === 'confirming'" x-cloak x-init="$watch('step', val => { if (val === 'confirming') setTimeout(() => step = 'success', 3000) })">
                <div class="py-4 text-center">
                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-yellow-500/10">
                        <svg class="h-7 w-7 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    </div>
                    <p class="mt-3 text-sm font-bold text-white">Waiting for M-PESA PIN…</p>
                    <p class="mt-1 text-xs text-gray-500">Enter your M-PESA PIN on your phone to confirm payment</p>
                    <div class="mt-3 flex justify-center gap-2">
                        <span class="h-2 w-2 animate-bounce rounded-full bg-accent" style="animation-delay: 0s"></span>
                        <span class="h-2 w-2 animate-bounce rounded-full bg-accent" style="animation-delay: 0.15s"></span>
                        <span class="h-2 w-2 animate-bounce rounded-full bg-accent" style="animation-delay: 0.3s"></span>
                    </div>
                </div>
            </div>

            {{-- Step 4: Success → redirect to demo ticket --}}
            <div x-show="step === 'success'" x-cloak x-init="$watch('step', val => { if (val === 'success') setTimeout(() => window.location.href = '{{ route('ticket.show', '1da28811e65208188c8839e1897acabb9ad044fededd9545a3b85ffcc11b02ee') }}', 2000) })">
                <div class="py-4 text-center">
                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-green-500/10">
                        <svg class="h-8 w-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <p class="mt-3 text-sm font-bold text-green-400">Payment Confirmed!</p>
                    <p class="mt-1 text-xs text-gray-500">KES {{ number_format($price) }} received. Redirecting to your ticket…</p>
                    <p class="mt-2 font-mono text-xs text-gray-600">Ref: SHK7891DEMO</p>
                </div>
            </div>

            <p class="mt-4 text-center text-xs text-gray-600">
                Powered by Safaricom M-PESA &middot; Lipa Na M-PESA
            </p>
        </div>
    </div>

    {{-- USSD Alternative --}}
    <div class="mt-6 overflow-hidden rounded-2xl bg-dark-100">
        <div class="border-b border-white/5 px-6 py-4">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-accent/10">
                    <svg class="h-5 w-5 text-accent-light" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-white">No internet? Buy via USSD</h3>
                    <p class="text-xs text-gray-500">Works on any phone — no smartphone or data needed</p>
                </div>
            </div>
        </div>
        <div class="px-6 py-5">
            <p class="mb-3 text-sm text-gray-400">Dial from your Safaricom line:</p>
            <div class="flex items-center justify-center rounded-xl bg-dark px-6 py-4">
                <span class="font-mono text-2xl font-black tracking-wider text-accent-light">*384*123#</span>
            </div>
            <div class="mt-4 space-y-2 text-xs text-gray-500">
                <p class="flex items-start gap-2">
                    <span class="mt-0.5 flex h-4 w-4 flex-shrink-0 items-center justify-center rounded-full bg-accent/10 text-[10px] font-bold text-accent-light">1</span>
                    Select <strong class="text-gray-300">Buy Ticket</strong>
                </p>
                <p class="flex items-start gap-2">
                    <span class="mt-0.5 flex h-4 w-4 flex-shrink-0 items-center justify-center rounded-full bg-accent/10 text-[10px] font-bold text-accent-light">2</span>
                    Choose your match &amp; section
                </p>
                <p class="flex items-start gap-2">
                    <span class="mt-0.5 flex h-4 w-4 flex-shrink-0 items-center justify-center rounded-full bg-accent/10 text-[10px] font-bold text-accent-light">3</span>
                    Confirm M-PESA payment
                </p>
                <p class="flex items-start gap-2">
                    <span class="mt-0.5 flex h-4 w-4 flex-shrink-0 items-center justify-center rounded-full bg-accent/10 text-[10px] font-bold text-accent-light">4</span>
                    Receive ticket SMS with QR link
                </p>
            </div>
            <p class="mt-4 text-xs text-gray-600">Available in English, Kiswahili, and Dholuo. Standard USSD rates apply.</p>
        </div>
    </div>
</section>
@endsection
