@extends('layouts.app')
@section('title', 'Your Ticket')
@section('no-nav', true)

@section('content')
<div class="flex min-h-screen items-center justify-center px-4 py-10">
    <div class="w-full max-w-sm">
        {{-- Ticket card --}}
        <div class="overflow-hidden rounded-xl border border-white/[0.06] bg-surface-100">

            {{-- Header --}}
            <div class="bg-accent px-5 py-5 text-center">
                <p class="text-sm font-semibold tracking-wide text-white">STADI</p>
                <p class="text-[10px] uppercase tracking-widest text-white/50">Match Day Ticket</p>
            </div>

            {{-- Match info --}}
            <div class="px-5 py-4 text-center">
                @if($ticket->event->competition)
                    <p class="mb-1 text-xs text-gray-500">{{ $ticket->event->competition }}</p>
                @endif
                <h2 class="text-lg font-semibold text-white">{{ $ticket->event->matchTitle() }}</h2>
                <p class="mt-1 text-sm text-gray-500">{{ $ticket->event->event_date->format('l, M j, Y') }} &middot; {{ $ticket->event->event_date->format('g:i A') }}</p>
            </div>

            {{-- Perforated divider --}}
            <div class="relative flex items-center px-4">
                <div class="absolute -left-3 h-6 w-6 rounded-full bg-surface"></div>
                <div class="flex-1 border-t border-dashed border-white/[0.08]"></div>
                <div class="absolute -right-3 h-6 w-6 rounded-full bg-surface"></div>
            </div>

            {{-- Details grid --}}
            <div class="grid grid-cols-2 gap-3 px-5 py-4 text-center">
                <div>
                    <p class="text-[10px] uppercase tracking-wider text-gray-600">Section</p>
                    <p class="mt-0.5 text-sm font-medium text-white">{{ $ticket->section->name }}</p>
                </div>
                <div>
                    <p class="text-[10px] uppercase tracking-wider text-gray-600">Tier</p>
                    <p class="mt-0.5 text-sm font-medium" style="color:{{ $ticket->section->color }}">{{ $ticket->section->tierLabel() }}</p>
                </div>
                <div>
                    <p class="text-[10px] uppercase tracking-wider text-gray-600">Gate</p>
                    <p class="mt-0.5 text-sm font-medium text-white">Gate {{ $ticket->section->gate_number }}</p>
                </div>
                <div>
                    <p class="text-[10px] uppercase tracking-wider text-gray-600">Status</p>
                    <p class="mt-0.5 text-sm font-medium {{ $ticket->isActive() ? 'text-green-400' : ($ticket->isScanned() ? 'text-accent-light' : 'text-red-400') }}">
                        {{ strtoupper($ticket->status) }}
                    </p>
                </div>
            </div>

            {{-- QR Code --}}
            <div class="flex flex-col items-center bg-surface-200 px-5 py-5">
                <div class="rounded-lg bg-white p-3">
                    <img
                        src="https://api.qrserver.com/v1/create-qr-code/?size=192x192&data={{ urlencode(route('ticket.show', $ticket->qr_hash)) }}"
                        alt="QR Code"
                        class="h-48 w-48"
                    >
                </div>
                <p class="mt-2 font-mono text-xs text-gray-600 tracking-wider">{{ strtoupper(substr($ticket->qr_hash, 0, 16)) }}</p>
                <p class="mt-1 text-[10px] text-gray-600">Present this QR at the gate for entry</p>
            </div>

            {{-- Fan info --}}
            <div class="border-t border-white/[0.06] px-5 py-3 text-center text-xs text-gray-600">
                {{ $ticket->user->phone ?? 'Fan' }} &middot; {{ config('stadium.name') }}
            </div>
        </div>

        <div class="mt-5 text-center">
            <a href="{{ route('home') }}" class="text-sm text-gray-500 hover:text-white transition-colors">&larr; Back to Events</a>
        </div>
    </div>
</div>
@endsection
