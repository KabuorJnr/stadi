@extends('layouts.app')
@section('title', 'Upcoming Events')

@section('content')
{{-- Hero --}}
<section class="relative overflow-hidden tm-gradient">
    <div class="absolute inset-0 opacity-[0.03]" style="background-image: radial-gradient(circle at 1px 1px, white 1px, transparent 0); background-size: 40px 40px;"></div>
    <div class="relative mx-auto max-w-7xl px-4 py-16 sm:px-6 sm:py-24">
        <div class="max-w-2xl">
            <div class="mb-4 inline-flex items-center gap-2 rounded-full bg-accent/10 px-4 py-1.5 text-xs font-semibold text-accent-light">
                <span class="h-1.5 w-1.5 rounded-full bg-accent-light animate-pulse"></span>
                LIVE TICKET SALES
            </div>
            <h1 class="text-4xl font-black tracking-tight text-white sm:text-6xl">
                Get your<br><span class="text-gradient">match-day tickets</span>
            </h1>
            <p class="mt-4 text-base text-gray-400 sm:text-lg">{{ config('stadium.name') }} — Instant M-PESA payment, QR code entry, or dial <span class="font-mono font-bold text-accent-light">*384*123#</span> on any phone.</p>
        </div>
    </div>
</section>

{{-- Featured Event --}}
@if($events->count())
@php $featured = $events->first(); @endphp
<section class="mx-auto max-w-7xl px-4 sm:px-6 -mt-8 relative z-10">
    <a href="{{ route('event.sections', $featured) }}" class="group block overflow-hidden rounded-2xl card-hover">
        <div class="relative flex flex-col bg-dark-100 sm:flex-row">
            {{-- Left: gradient poster --}}
            <div class="relative flex h-56 w-full flex-shrink-0 items-center justify-center sm:h-auto sm:w-80"
                 style="background: linear-gradient(135deg, {{ $featured->home_team ? '#026cdf' : '#1a1a2e' }} 0%, #0f1b2d 100%);">
                @if($featured->poster_url)
                    <img src="{{ $featured->poster_url }}" alt="" class="h-full w-full object-cover opacity-80">
                @else
                    <div class="text-center">
                        <span class="text-6xl">⚽</span>
                        <p class="mt-2 text-xs font-semibold uppercase tracking-widest text-white/40">Featured Match</p>
                    </div>
                @endif
                @if($featured->isSoldOut())
                    <span class="absolute top-4 right-4 rounded-full bg-red-500 px-3 py-1 text-xs font-bold uppercase text-white">Sold Out</span>
                @endif
            </div>

            {{-- Right: info --}}
            <div class="flex flex-1 flex-col justify-between p-6 sm:p-8">
                <div>
                    <div class="flex flex-wrap items-center gap-3">
                        @if($featured->competition)
                            <span class="rounded-full bg-accent/10 px-3 py-1 text-xs font-bold text-accent-light">{{ $featured->competition }}</span>
                        @endif
                        <span class="rounded-full bg-white/5 px-3 py-1 text-xs font-semibold text-gray-400">FEATURED</span>
                    </div>
                    <h2 class="mt-3 text-2xl font-extrabold text-white group-hover:text-accent-light transition sm:text-3xl">{{ $featured->matchTitle() }}</h2>
                    @if($featured->description)
                        <p class="mt-2 text-sm text-gray-500 line-clamp-2">{{ $featured->description }}</p>
                    @endif
                </div>

                <div class="mt-6 flex flex-wrap items-center gap-6">
                    {{-- Date badge --}}
                    <div class="flex items-center gap-3">
                        <div class="flex h-14 w-14 flex-col items-center justify-center rounded-xl bg-accent text-white">
                            <span class="text-[10px] font-bold uppercase leading-none">{{ $featured->event_date->format('M') }}</span>
                            <span class="text-xl font-black leading-none">{{ $featured->event_date->format('j') }}</span>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-white">{{ $featured->event_date->format('l') }}</p>
                            <p class="text-xs text-gray-500">{{ $featured->event_date->format('g:i A') }}</p>
                        </div>
                    </div>

                    <div class="hidden h-10 w-px bg-white/10 sm:block"></div>

                    {{-- Price --}}
                    <div>
                        <p class="text-xs text-gray-500">From</p>
                        <p class="text-lg font-extrabold text-white">KES {{ number_format($featured->base_ticket_price) }}</p>
                    </div>

                    <div class="hidden h-10 w-px bg-white/10 sm:block"></div>

                    {{-- Remaining --}}
                    <div>
                        <p class="text-xs text-gray-500">Available</p>
                        <p class="text-lg font-extrabold text-white">{{ number_format($featured->remainingCapacity()) }} <span class="text-xs font-normal text-gray-500">seats</span></p>
                    </div>

                    {{-- CTA --}}
                    <div class="ml-auto">
                        <span class="inline-flex items-center gap-2 rounded-xl bg-accent px-6 py-3 text-sm font-bold text-white transition group-hover:bg-accent-light">
                            Get Tickets
                            <svg class="h-4 w-4 transition group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </a>
</section>
@endif

{{-- Event Grid --}}
<section class="mx-auto max-w-7xl px-4 py-12 sm:px-6">
    <div class="flex items-center justify-between">
        <h2 class="text-xl font-extrabold text-white sm:text-2xl">All Events</h2>
        <span class="rounded-full bg-white/5 px-3 py-1 text-xs font-semibold text-gray-400">{{ $events->count() }} events</span>
    </div>

    <div class="mt-6 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
        @forelse($events as $event)
        <a href="{{ route('event.sections', $event) }}"
           class="group overflow-hidden rounded-2xl bg-dark-100 card-hover">

            {{-- Poster --}}
            <div class="relative h-44 w-full"
                 style="background: linear-gradient(135deg, #16213e 0%, #0f1b2d 100%);">
                @if($event->poster_url)
                    <img src="{{ $event->poster_url }}" alt="" class="h-full w-full object-cover">
                @else
                    <div class="flex h-full items-center justify-center">
                        <span class="text-5xl opacity-20">⚽</span>
                    </div>
                @endif

                {{-- Date badge overlay --}}
                <div class="absolute top-3 left-3 flex h-14 w-14 flex-col items-center justify-center rounded-xl bg-accent text-white shadow-lg">
                    <span class="text-[10px] font-bold uppercase leading-none">{{ $event->event_date->format('M') }}</span>
                    <span class="text-xl font-black leading-none">{{ $event->event_date->format('j') }}</span>
                </div>

                @if($event->isSoldOut())
                    <div class="absolute inset-0 flex items-center justify-center bg-black/60">
                        <span class="rounded-full bg-red-500 px-4 py-1.5 text-xs font-bold uppercase tracking-wider text-white">Sold Out</span>
                    </div>
                @endif
            </div>

            {{-- Info --}}
            <div class="p-5">
                @if($event->competition)
                    <span class="text-[11px] font-bold uppercase tracking-wider text-accent-light">{{ $event->competition }}</span>
                @endif
                <h3 class="mt-1 text-lg font-extrabold text-white group-hover:text-accent-light transition">{{ $event->matchTitle() }}</h3>

                <div class="mt-3 flex items-center gap-4 text-xs text-gray-500">
                    <span class="flex items-center gap-1.5">
                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ $event->event_date->format('g:i A') }}
                    </span>
                    <span class="flex items-center gap-1.5">
                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/></svg>
                        {{ number_format($event->remainingCapacity()) }} left
                    </span>
                </div>

                <div class="mt-4 flex items-center justify-between border-t border-white/5 pt-4">
                    <div>
                        <span class="text-xs text-gray-500">From</span>
                        <p class="text-lg font-extrabold text-white">KES {{ number_format($event->base_ticket_price) }}</p>
                    </div>
                    <span class="rounded-lg bg-accent/10 px-4 py-2 text-xs font-bold text-accent-light transition group-hover:bg-accent group-hover:text-white">
                        Get Tickets →
                    </span>
                </div>
            </div>
        </a>
        @empty
        <div class="col-span-full rounded-2xl border border-white/5 bg-dark-100 py-20 text-center">
            <span class="text-6xl opacity-20">🏟️</span>
            <h3 class="mt-4 text-lg font-bold text-gray-400">No upcoming events</h3>
            <p class="mt-1 text-sm text-gray-600">Check back soon for new matches!</p>
        </div>
        @endforelse
    </div>
</section>

{{-- USSD Banner --}}
<section class="mx-auto max-w-7xl px-4 pb-12 sm:px-6">
    <div class="relative overflow-hidden rounded-2xl bg-dark-100 p-8 sm:p-10">
        <div class="absolute -right-10 -top-10 h-40 w-40 rounded-full bg-accent/5"></div>
        <div class="absolute -right-5 -bottom-5 h-24 w-24 rounded-full bg-accent/5"></div>
        <div class="relative flex flex-col items-start gap-6 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h3 class="text-xl font-extrabold text-white">No smartphone? No problem.</h3>
                <p class="mt-1 text-sm text-gray-400">Buy tickets from any phone using USSD. Works with M-PESA, no internet needed.</p>
                <p class="mt-1 text-xs text-gray-600">Available in English, Kiswahili &amp; Dholuo</p>
            </div>
            <div class="flex-shrink-0 rounded-2xl bg-dark px-8 py-4 text-center">
                <p class="text-xs font-semibold uppercase tracking-widest text-gray-500">Dial</p>
                <p class="mt-1 font-mono text-3xl font-black text-accent-light">*384*123#</p>
            </div>
        </div>
    </div>
</section>
@endsection
