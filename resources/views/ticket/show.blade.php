@extends('layouts.app')
@section('title', 'Your Ticket')
@section('no-nav', true)

@section('content')
<div class="flex min-h-screen items-center justify-center bg-gradient-to-br from-pitch to-brand-600 px-4 py-10">
    <div class="w-full max-w-sm">
        {{-- Ticket card --}}
        <div class="overflow-hidden rounded-3xl bg-white shadow-2xl">

            {{-- Top accent --}}
            <div class="bg-pitch px-6 py-5 text-center text-white">
                <span class="text-4xl">⚽</span>
                <h1 class="mt-1 text-lg font-bold tracking-wide">STADI</h1>
                <p class="text-xs text-white/50 uppercase tracking-widest">Match Day Ticket</p>
            </div>

            {{-- Match info --}}
            <div class="px-6 py-5 text-center">
                @if($ticket->event->competition)
                    <span class="mb-1 inline-block rounded-full bg-brand-50 px-3 py-0.5 text-xs font-semibold text-brand-600">{{ $ticket->event->competition }}</span>
                @endif
                <h2 class="text-xl font-bold text-gray-900">{{ $ticket->event->matchTitle() }}</h2>
                <p class="mt-1 text-sm text-gray-500">{{ $ticket->event->event_date->format('l, M j, Y') }}</p>
                <p class="text-sm text-gray-500">{{ $ticket->event->event_date->format('g:i A') }}</p>
            </div>

            {{-- Perforated divider --}}
            <div class="relative flex items-center px-4">
                <div class="absolute -left-4 h-8 w-8 rounded-full bg-gradient-to-br from-pitch to-brand-600"></div>
                <div class="flex-1 border-t-2 border-dashed border-gray-200"></div>
                <div class="absolute -right-4 h-8 w-8 rounded-full bg-gradient-to-br from-pitch to-brand-600"></div>
            </div>

            {{-- Details grid --}}
            <div class="grid grid-cols-2 gap-4 px-6 py-5 text-center">
                <div>
                    <p class="text-[10px] font-semibold uppercase tracking-wider text-gray-400">Section</p>
                    <p class="mt-0.5 text-sm font-bold text-gray-900">{{ $ticket->section->name }}</p>
                </div>
                <div>
                    <p class="text-[10px] font-semibold uppercase tracking-wider text-gray-400">Tier</p>
                    <p class="mt-0.5 text-sm font-bold" style="color:{{ $ticket->section->color }}">{{ $ticket->section->tierLabel() }}</p>
                </div>
                <div>
                    <p class="text-[10px] font-semibold uppercase tracking-wider text-gray-400">Gate</p>
                    <p class="mt-0.5 text-sm font-bold text-gray-900">Gate {{ $ticket->section->gate_number }}</p>
                </div>
                <div>
                    <p class="text-[10px] font-semibold uppercase tracking-wider text-gray-400">Status</p>
                    <p class="mt-0.5 text-sm font-bold {{ $ticket->isActive() ? 'text-green-600' : ($ticket->isScanned() ? 'text-blue-600' : 'text-red-500') }}">
                        {{ strtoupper($ticket->status) }}
                    </p>
                </div>
            </div>

            {{-- QR Code --}}
            <div class="flex flex-col items-center bg-gray-50 px-6 py-6">
                <div class="rounded-2xl border-2 border-dashed border-gray-200 bg-white p-4">
                    {{-- QR placeholder using an inline SVG pattern --}}
                    <div class="flex h-48 w-48 items-center justify-center">
                        <img
                            src="https://api.qrserver.com/v1/create-qr-code/?size=192x192&data={{ urlencode(route('ticket.show', $ticket->qr_hash)) }}"
                            alt="QR Code"
                            class="h-48 w-48"
                        >
                    </div>
                </div>
                <p class="mt-3 font-mono text-xs text-gray-400 tracking-wider">{{ strtoupper(substr($ticket->qr_hash, 0, 16)) }}</p>
                <p class="mt-1 text-[10px] text-gray-400">Present this QR at the gate for entry</p>
            </div>

            {{-- Fan info --}}
            <div class="border-t px-6 py-4 text-center text-xs text-gray-400">
                <p>{{ $ticket->user->phone ?? 'Fan' }} &middot; {{ config('stadium.name') }}</p>
            </div>
        </div>

        {{-- Home link --}}
        <div class="mt-6 text-center">
            <a href="{{ route('home') }}" class="text-sm text-white/70 hover:text-white transition">← Back to Events</a>
        </div>
    </div>
</div>
@endsection
