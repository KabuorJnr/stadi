@extends('layouts.app')
@section('title', 'Upcoming Events')

@section('content')
{{-- Hero --}}
<section class="relative bg-pitch py-20 text-white">
    <div class="absolute inset-0 opacity-10" style="background-image:url('/images/stadium-pattern.svg');background-size:cover;"></div>
    <div class="relative mx-auto max-w-5xl px-4 text-center">
        <h1 class="text-4xl font-bold tracking-tight sm:text-5xl">{{ config('stadium.name') }}</h1>
        <p class="mt-3 text-lg text-white/70">Get your match-day tickets. Instant M-PESA payment, QR entry.</p>
    </div>
</section>

{{-- Events grid --}}
<section class="mx-auto max-w-7xl px-4 py-12 sm:px-6">
    <h2 class="mb-8 text-2xl font-bold text-gray-900">Upcoming Matches</h2>

    @forelse($events as $event)
    <a href="{{ route('event.sections', $event) }}"
       class="group mb-6 flex flex-col overflow-hidden rounded-2xl border bg-white shadow-sm transition hover:shadow-md sm:flex-row">

        {{-- Poster / placeholder --}}
        <div class="relative flex h-48 w-full flex-shrink-0 items-center justify-center bg-gradient-to-br from-pitch to-brand-600 sm:h-auto sm:w-56">
            @if($event->poster_url)
                <img src="{{ $event->poster_url }}" alt="" class="h-full w-full object-cover">
            @else
                <span class="text-5xl">⚽</span>
            @endif
            @if($event->isSoldOut())
                <span class="absolute top-3 right-3 rounded-full bg-red-600 px-3 py-0.5 text-xs font-bold uppercase tracking-wide text-white">Sold Out</span>
            @endif
        </div>

        {{-- Info --}}
        <div class="flex flex-1 flex-col justify-between p-5 sm:p-6">
            <div>
                @if($event->competition)
                    <span class="mb-1 inline-block rounded bg-brand-50 px-2 py-0.5 text-xs font-semibold text-brand-600">{{ $event->competition }}</span>
                @endif
                <h3 class="text-xl font-bold text-gray-900 group-hover:text-brand-500 transition">{{ $event->matchTitle() }}</h3>
                @if($event->description)
                    <p class="mt-1 text-sm text-gray-500 line-clamp-2">{{ $event->description }}</p>
                @endif
            </div>

            <div class="mt-4 flex flex-wrap items-center gap-4 text-sm text-gray-500">
                {{-- Date --}}
                <span class="flex items-center gap-1.5">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    {{ $event->event_date->format('D, M j · g:i A') }}
                </span>

                {{-- Price --}}
                <span class="flex items-center gap-1.5">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z"/></svg>
                    From KES {{ number_format($event->base_ticket_price) }}
                </span>

                {{-- Remaining --}}
                <span class="flex items-center gap-1.5">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    {{ number_format($event->remainingCapacity()) }} seats left
                </span>
            </div>

            <div class="mt-4">
                <span class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-5 py-2.5 text-sm font-semibold text-white transition group-hover:bg-brand-600">
                    Select Section
                    <svg class="h-4 w-4 transition group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </span>
            </div>
        </div>
    </a>
    @empty
    <div class="rounded-2xl border-2 border-dashed border-gray-200 py-16 text-center">
        <span class="text-5xl">🏟️</span>
        <h3 class="mt-4 text-lg font-semibold text-gray-600">No upcoming events</h3>
        <p class="mt-1 text-sm text-gray-400">Check back soon for new matches!</p>
    </div>
    @endforelse
</section>
@endsection
