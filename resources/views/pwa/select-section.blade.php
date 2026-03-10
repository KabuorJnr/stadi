@extends('layouts.app')
@section('title', $event->matchTitle() . ' — Select Section')

@section('content')
{{-- Event header --}}
<div class="border-b border-white/[0.06] bg-surface-100">
    <div class="mx-auto max-w-5xl px-4 py-8 sm:px-6">
        <a href="{{ route('home') }}" class="mb-3 inline-flex items-center gap-1 text-sm text-gray-500 hover:text-white transition-colors">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Events
        </a>
        <h1 class="text-xl font-semibold text-white">{{ $event->matchTitle() }}</h1>
        <div class="mt-1 flex items-center gap-3 text-sm text-gray-500">
            @if($event->competition)
                <span>{{ $event->competition }}</span>
                <span>&middot;</span>
            @endif
            <span>{{ $event->event_date->format('D, M j, Y') }}</span>
            <span>&middot;</span>
            <span>{{ $event->event_date->format('g:i A') }}</span>
        </div>
    </div>
</div>

<section class="mx-auto max-w-5xl px-4 py-8 sm:px-6">
    <h2 class="text-sm font-medium text-gray-400">Choose a section</h2>

    {{-- Stadium SVG map --}}
    <div class="mt-4 flex justify-center rounded-lg border border-white/[0.06] bg-surface-100 p-6">
        <svg viewBox="0 0 600 400" class="w-full max-w-xl" xmlns="http://www.w3.org/2000/svg">
            <ellipse cx="300" cy="200" rx="280" ry="180" fill="#161616" stroke="#2a2a2a" stroke-width="1.5"/>
            <ellipse cx="300" cy="200" rx="200" ry="110" fill="#0f3a1f"/>
            <rect x="170" y="150" width="260" height="100" rx="4" fill="none" stroke="white" stroke-width="1" opacity=".2"/>
            <circle cx="300" cy="200" r="30" fill="none" stroke="white" stroke-width="1" opacity=".2"/>

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
                        fill="{{ $section->color ?? '#2563eb' }}"
                        class="cursor-pointer transition hover:opacity-80 {{ $section->isFull() ? 'opacity-30 cursor-not-allowed' : '' }}"
                    />
                    <text x="{{ $labelX }}" y="{{ $labelY - 4 }}" text-anchor="middle" fill="white" font-size="9" font-weight="600">{{ $section->code }}</text>
                    <text x="{{ $labelX }}" y="{{ $labelY + 8 }}" text-anchor="middle" fill="white" font-size="7" opacity=".7">KES {{ number_format($section->computed_price) }}</text>
                </a>
            @endforeach
        </svg>
    </div>

    {{-- Section cards --}}
    <div class="mt-6 grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
        @foreach($sections as $section)
        <div class="relative rounded-lg border border-white/[0.06] bg-surface-100 p-4 {{ $section->isFull() ? 'opacity-50' : '' }}">
            <div class="absolute left-0 top-0 h-full w-0.5 rounded-full" style="background:{{ $section->color ?? '#2563eb' }}"></div>

            <div class="pl-3">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-medium text-white">{{ $section->name }}</h3>
                    <span class="text-xs text-gray-500">{{ $section->tierLabel() }}</span>
                </div>

                <p class="mt-1.5 text-lg font-semibold text-white">KES {{ number_format($section->computed_price) }}</p>

                <div class="mt-1.5 flex items-center justify-between text-xs text-gray-500">
                    <span>{{ number_format($section->remainingSeats()) }} seats left</span>
                    <span>Gate {{ $section->gate_number }}</span>
                </div>

                <div class="mt-2.5 h-1 w-full overflow-hidden rounded-full bg-white/[0.06]">
                    <div class="h-full rounded-full" style="width:{{ $section->occupancyPercent() }}%; background:{{ $section->color ?? '#2563eb' }}"></div>
                </div>

                @if($section->isFull())
                    <span class="mt-2.5 inline-block text-xs text-red-400">Full</span>
                @else
                    <a href="{{ route('ticket.buy', [$event, $section]) }}"
                       class="mt-3 inline-flex w-full items-center justify-center rounded-md bg-accent px-3 py-2 text-sm font-medium text-white transition hover:bg-accent-light">
                        Select
                    </a>
                @endif
            </div>
        </div>
        @endforeach
    </div>
</section>
@endsection
