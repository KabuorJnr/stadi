@extends('layouts.app')
@section('title', $event->matchTitle() . ' — Select Section')

@section('content')
{{-- Event header --}}
<section class="bg-pitch py-10 text-white">
    <div class="mx-auto max-w-5xl px-4 text-center">
        @if($event->competition)
            <span class="mb-2 inline-block rounded-full bg-white/10 px-3 py-0.5 text-xs font-semibold uppercase tracking-wider">{{ $event->competition }}</span>
        @endif
        <h1 class="text-3xl font-bold sm:text-4xl">{{ $event->matchTitle() }}</h1>
        <p class="mt-2 text-white/60">{{ $event->event_date->format('l, M j, Y · g:i A') }}</p>
    </div>
</section>

<section class="mx-auto max-w-6xl px-4 py-10 sm:px-6">
    <h2 class="mb-2 text-xl font-bold text-gray-900">Choose Your Section</h2>
    <p class="mb-8 text-sm text-gray-500">Tap a section to proceed to checkout.</p>

    {{-- Stadium SVG map --}}
    <div class="mb-10 flex justify-center">
        <svg viewBox="0 0 600 400" class="w-full max-w-xl" xmlns="http://www.w3.org/2000/svg">
            {{-- Pitch --}}
            <ellipse cx="300" cy="200" rx="280" ry="180" fill="#e8e8e8" stroke="#ccc" stroke-width="2"/>
            <ellipse cx="300" cy="200" rx="200" ry="110" fill="#2d6a3e"/>
            <rect x="170" y="150" width="260" height="100" rx="4" fill="none" stroke="white" stroke-width="1.5" opacity=".5"/>
            <circle cx="300" cy="200" r="30" fill="none" stroke="white" stroke-width="1.5" opacity=".5"/>

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
                        fill="{{ $section->color ?? '#666' }}"
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
        <div class="relative overflow-hidden rounded-xl border bg-white p-5 shadow-sm transition hover:shadow-md {{ $section->isFull() ? 'opacity-50' : '' }}">
            {{-- Tier stripe --}}
            <div class="absolute left-0 top-0 h-full w-1.5 rounded-l-xl" style="background:{{ $section->color ?? '#ccc' }}"></div>

            <div class="pl-3">
                <div class="flex items-center justify-between">
                    <h3 class="font-bold text-gray-900">{{ $section->name }}</h3>
                    <span class="rounded-full px-2 py-0.5 text-xs font-semibold" style="background:{{ $section->color ?? '#ccc' }}20; color:{{ $section->color ?? '#666' }}">
                        {{ $section->tierLabel() }}
                    </span>
                </div>

                <p class="mt-2 text-2xl font-bold text-gray-900">KES {{ number_format($section->computed_price) }}</p>

                <div class="mt-2 flex items-center justify-between text-xs text-gray-500">
                    <span>{{ number_format($section->remainingSeats()) }} seats left</span>
                    <span>Gate {{ $section->gate_number }}</span>
                </div>

                {{-- Occupancy bar --}}
                <div class="mt-2 h-1.5 w-full overflow-hidden rounded-full bg-gray-100">
                    <div class="h-full rounded-full transition-all" style="width:{{ $section->occupancyPercent() }}%; background:{{ $section->color ?? '#ccc' }}"></div>
                </div>

                @if($section->isFull())
                    <span class="mt-3 inline-block text-xs font-semibold text-red-500">FULL</span>
                @else
                    <a href="{{ route('ticket.buy', [$event, $section]) }}"
                       class="mt-3 inline-flex w-full items-center justify-center rounded-lg bg-brand-500 py-2 text-sm font-semibold text-white transition hover:bg-brand-600">
                        Buy Ticket
                    </a>
                @endif
            </div>
        </div>
        @endforeach
    </div>
</section>
@endsection
