@extends('layouts.app')
@section('title', $event->matchTitle() . ' — Select Section')

@section('content')
{{-- Event header --}}
<section class="tm-gradient py-10 relative overflow-hidden">
    <div class="absolute inset-0 opacity-[0.03]" style="background-image: radial-gradient(circle at 1px 1px, white 1px, transparent 0); background-size: 40px 40px;"></div>
    <div class="relative mx-auto max-w-5xl px-4">
        <a href="{{ route('home') }}" class="mb-4 inline-flex items-center gap-1.5 text-sm text-gray-400 hover:text-white transition">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            All Events
        </a>
        <div class="text-center">
            @if($event->competition)
                <span class="mb-2 inline-block rounded-full bg-accent/10 px-3 py-1 text-xs font-bold uppercase tracking-wider text-accent-light">{{ $event->competition }}</span>
            @endif
            <h1 class="text-3xl font-black text-white sm:text-4xl">{{ $event->matchTitle() }}</h1>
            <div class="mt-3 flex items-center justify-center gap-4 text-sm text-gray-400">
                <span class="flex items-center gap-1.5">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    {{ $event->event_date->format('D, M j, Y') }}
                </span>
                <span class="flex items-center gap-1.5">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ $event->event_date->format('g:i A') }}
                </span>
            </div>
        </div>
    </div>
</section>

<section class="mx-auto max-w-6xl px-4 py-10 sm:px-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-extrabold text-white">Choose Your Section</h2>
            <p class="mt-1 text-sm text-gray-500">Tap a section to proceed to checkout.</p>
        </div>
        <span class="rounded-full bg-white/5 px-3 py-1 text-xs font-semibold text-gray-400">{{ $sections->count() }} sections</span>
    </div>

    {{-- Stadium SVG map --}}
    <div class="mb-10 flex justify-center rounded-2xl bg-dark-100 p-6">
        <svg viewBox="0 0 600 400" class="w-full max-w-xl" xmlns="http://www.w3.org/2000/svg">
            {{-- Pitch --}}
            <ellipse cx="300" cy="200" rx="280" ry="180" fill="#1a1a2e" stroke="#2a2a4a" stroke-width="2"/>
            <ellipse cx="300" cy="200" rx="200" ry="110" fill="#0f3a1f"/>
            <rect x="170" y="150" width="260" height="100" rx="4" fill="none" stroke="white" stroke-width="1.5" opacity=".3"/>
            <circle cx="300" cy="200" r="30" fill="none" stroke="white" stroke-width="1.5" opacity=".3"/>

            {{-- Section arcs --}}
            @foreach($sections as $i => $section)
                @php
                    $angle = ($i / count($sections)) * 360;
                    $midAngle = deg2rad($angle + (360 / count($sections)) / 2);
                    $labelX = 300 + cos($midAngle) * 240;
                    $labelY = 200 + sin($midAngle) * 160;
                @endphp
                <a href="{{ $section->isFull() ? '#' : route('ticket.buy', [$event, $section]) }}">
                    <circle
                        cx="{{ $labelX }}" cy="{{ $labelY }}" r="28"
                        fill="{{ $section->color ?? '#026cdf' }}"
                        class="cursor-pointer transition hover:opacity-80 {{ $section->isFull() ? 'opacity-30 cursor-not-allowed' : '' }}"
                    />
                    <text x="{{ $labelX }}" y="{{ $labelY - 4 }}" text-anchor="middle" fill="white" font-size="9" font-weight="bold">{{ $section->code }}</text>
                    <text x="{{ $labelX }}" y="{{ $labelY + 8 }}" text-anchor="middle" fill="white" font-size="7" opacity=".8">KES {{ number_format($section->computed_price) }}</text>
                </a>
            @endforeach
        </svg>
    </div>

    {{-- Section cards list --}}
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        @foreach($sections as $section)
        <div class="group relative overflow-hidden rounded-2xl bg-dark-100 p-5 card-hover {{ $section->isFull() ? 'opacity-50' : '' }}">
            {{-- Tier stripe --}}
            <div class="absolute left-0 top-0 h-full w-1" style="background:{{ $section->color ?? '#026cdf' }}"></div>

            <div class="pl-3">
                <div class="flex items-center justify-between">
                    <h3 class="font-bold text-white">{{ $section->name }}</h3>
                    <span class="rounded-full px-2 py-0.5 text-xs font-bold" style="background:{{ $section->color ?? '#026cdf' }}20; color:{{ $section->color ?? '#026cdf' }}">
                        {{ $section->tierLabel() }}
                    </span>
                </div>

                <p class="mt-2 text-2xl font-extrabold text-white">KES {{ number_format($section->computed_price) }}</p>

                <div class="mt-2 flex items-center justify-between text-xs text-gray-500">
                    <span>{{ number_format($section->remainingSeats()) }} seats left</span>
                    <span>Gate {{ $section->gate_number }}</span>
                </div>

                {{-- Occupancy bar --}}
                <div class="mt-3 h-1.5 w-full overflow-hidden rounded-full bg-white/5">
                    <div class="h-full rounded-full transition-all" style="width:{{ $section->occupancyPercent() }}%; background:{{ $section->color ?? '#026cdf' }}"></div>
                </div>

                @if($section->isFull())
                    <span class="mt-3 inline-block rounded-full bg-red-500/10 px-3 py-1 text-xs font-bold text-red-400">FULL</span>
                @else
                    <a href="{{ route('ticket.buy', [$event, $section]) }}"
                       class="mt-4 inline-flex w-full items-center justify-center rounded-xl bg-accent px-4 py-2.5 text-sm font-bold text-white transition hover:bg-accent-light">
                        Get Tickets
                    </a>
                @endif
            </div>
        </div>
        @endforeach
    </div>
</section>
@endsection
