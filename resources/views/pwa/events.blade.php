@extends('layouts.app')
@section('title', 'Upcoming Events')

@section('content')
{{-- Page header --}}
<div class="mx-auto max-w-5xl px-4 pt-10 pb-6 sm:px-6">
    <h1 class="text-2xl font-semibold text-white">Events</h1>
    <p class="mt-1 text-sm text-gray-500">{{ config('stadium.name') }} &mdash; Buy with M-PESA or dial <span class="font-mono text-gray-400">*384*123#</span></p>
</div>

{{-- Event list --}}
<section class="mx-auto max-w-5xl px-4 pb-12 sm:px-6">
    <div class="space-y-3">
        @forelse($events as $event)
        <a href="{{ route('event.sections', $event) }}"
           class="group flex flex-col gap-4 rounded-lg border border-white/[0.06] bg-surface-100 p-4 transition hover:border-white/[0.1] sm:flex-row sm:items-center">

            {{-- Date --}}
            <div class="flex items-center gap-4 sm:w-36 sm:flex-shrink-0">
                <div class="text-center leading-tight">
                    <p class="text-xs text-gray-500">{{ $event->event_date->format('M') }}</p>
                    <p class="text-xl font-bold text-white">{{ $event->event_date->format('j') }}</p>
                    <p class="text-xs text-gray-600">{{ $event->event_date->format('D') }}</p>
                </div>
                <div class="h-10 w-px bg-white/[0.06] sm:hidden"></div>
                <div class="sm:hidden">
                    <p class="text-sm font-medium text-white">{{ $event->matchTitle() }}</p>
                    @if($event->competition)
                        <p class="text-xs text-gray-500">{{ $event->competition }}</p>
                    @endif
                </div>
            </div>

            {{-- Info --}}
            <div class="hidden flex-1 sm:block">
                <p class="text-sm font-medium text-white group-hover:text-accent-light transition-colors">{{ $event->matchTitle() }}</p>
                <div class="mt-0.5 flex items-center gap-3 text-xs text-gray-500">
                    @if($event->competition)
                        <span>{{ $event->competition }}</span>
                        <span>&middot;</span>
                    @endif
                    <span>{{ $event->event_date->format('g:i A') }}</span>
                    <span>&middot;</span>
                    <span>{{ number_format($event->remainingCapacity()) }} seats left</span>
                </div>
            </div>

            {{-- Mobile meta --}}
            <div class="flex items-center justify-between text-xs text-gray-500 sm:hidden">
                <span>{{ $event->event_date->format('g:i A') }} &middot; {{ number_format($event->remainingCapacity()) }} seats left</span>
            </div>

            {{-- Price & action --}}
            <div class="flex items-center justify-between sm:flex-shrink-0 sm:flex-col sm:items-end sm:gap-1">
                <p class="text-sm font-semibold text-white">KES {{ number_format($event->base_ticket_price) }}</p>
                @if($event->isSoldOut())
                    <span class="text-xs text-red-400">Sold out</span>
                @else
                    <span class="text-xs text-gray-500 group-hover:text-accent-light transition-colors">Buy tickets &rarr;</span>
                @endif
            </div>
        </a>
        @empty
        <div class="rounded-lg border border-white/[0.06] bg-surface-100 py-16 text-center">
            <h3 class="text-sm font-medium text-gray-400">No upcoming events</h3>
            <p class="mt-1 text-xs text-gray-600">Check back soon for new matches.</p>
        </div>
        @endforelse
    </div>
</section>

{{-- USSD --}}
<section class="mx-auto max-w-5xl px-4 pb-12 sm:px-6">
    <div class="flex flex-col gap-4 rounded-lg border border-white/[0.06] bg-surface-100 p-5 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm font-medium text-white">No smartphone? Buy via USSD</p>
            <p class="mt-0.5 text-xs text-gray-500">Works on any phone with M-PESA. No internet needed.</p>
        </div>
        <code class="text-lg font-semibold text-accent-light">*384*123#</code>
    </div>
</section>
@endsection
