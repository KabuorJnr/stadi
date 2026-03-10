@extends('layouts.app')
@section('title', 'Your Ticket')
@section('no-nav', true)

@section('content')
<div class="flex min-h-screen items-center justify-center px-4 py-10" style="background: linear-gradient(135deg, #0a1628 0%, #121212 50%, #0f1b2d 100%);">
    <div class="w-full max-w-sm">
        {{-- Ticket card --}}
        <div class="overflow-hidden rounded-3xl bg-dark-100 shadow-2xl shadow-accent/10">

            {{-- Top accent --}}
            <div class="relative px-6 py-6 text-center" style="background: linear-gradient(135deg, #026cdf 0%, #0150a8 100%);">
                <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(circle at 1px 1px, white 1px, transparent 0); background-size: 20px 20px;"></div>
                <div class="relative">
                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-xl bg-white/10 backdrop-blur">
                        <span class="text-2xl">⚽</span>
                    </div>
                    <h1 class="mt-2 text-lg font-black tracking-wide text-white">STADI</h1>
                    <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-white/40">Match Day Ticket</p>
                </div>
            </div>

            {{-- Match info --}}
            <div class="px-6 py-5 text-center">
                @if($ticket->event->competition)
                    <span class="mb-1 inline-block rounded-full bg-accent/10 px-3 py-1 text-xs font-bold text-accent-light">{{ $ticket->event->competition }}</span>
                @endif
                <h2 class="text-xl font-extrabold text-white">{{ $ticket->event->matchTitle() }}</h2>
                <p class="mt-1 text-sm text-gray-500">{{ $ticket->event->event_date->format('l, M j, Y') }}</p>
                <p class="text-sm text-gray-500">{{ $ticket->event->event_date->format('g:i A') }}</p>
            </div>

            {{-- Perforated divider --}}
            <div class="relative flex items-center px-4">
                <div class="absolute -left-4 h-8 w-8 rounded-full" style="background: linear-gradient(135deg, #0a1628, #121212);"></div>
                <div class="flex-1 border-t-2 border-dashed border-white/10"></div>
                <div class="absolute -right-4 h-8 w-8 rounded-full" style="background: linear-gradient(135deg, #121212, #0f1b2d);"></div>
            </div>

            {{-- Details grid --}}
            <div class="grid grid-cols-2 gap-4 px-6 py-5 text-center">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-wider text-gray-600">Section</p>
                    <p class="mt-0.5 text-sm font-bold text-white">{{ $ticket->section->name }}</p>
                </div>
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-wider text-gray-600">Tier</p>
                    <p class="mt-0.5 text-sm font-bold" style="color:{{ $ticket->section->color }}">{{ $ticket->section->tierLabel() }}</p>
                </div>
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-wider text-gray-600">Gate</p>
                    <p class="mt-0.5 text-sm font-bold text-white">Gate {{ $ticket->section->gate_number }}</p>
                </div>
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-wider text-gray-600">Status</p>
                    <p class="mt-0.5 text-sm font-bold {{ $ticket->isActive() ? 'text-green-400' : ($ticket->isScanned() ? 'text-accent-light' : 'text-red-400') }}">
                        {{ strtoupper($ticket->status) }}
                    </p>
                </div>
            </div>

            {{-- QR Code --}}
            <div class="flex flex-col items-center bg-dark-200 px-6 py-6">
                <div class="rounded-2xl bg-white p-4">
                    <div class="flex h-48 w-48 items-center justify-center">
                        <img
                            src="https://api.qrserver.com/v1/create-qr-code/?size=192x192&data={{ urlencode(route('ticket.show', $ticket->qr_hash)) }}"
                            alt="QR Code"
                            class="h-48 w-48"
                        >
                    </div>
                </div>
                <p class="mt-3 font-mono text-xs text-gray-500 tracking-wider">{{ strtoupper(substr($ticket->qr_hash, 0, 16)) }}</p>
                <p class="mt-1 text-[10px] text-gray-600">Present this QR at the gate for entry</p>
            </div>

            {{-- Fan info --}}
            <div class="border-t border-white/5 px-6 py-4 text-center text-xs text-gray-600">
                <p>{{ $ticket->user->phone ?? 'Fan' }} &middot; {{ config('stadium.name') }}</p>
            </div>
        </div>

        {{-- Home link --}}
        <div class="mt-6 text-center">
            <a href="{{ route('home') }}" class="text-sm text-gray-500 hover:text-accent-light transition">← Back to Events</a>
        </div>
    </div>
</div>
@endsection
