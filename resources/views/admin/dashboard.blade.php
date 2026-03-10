@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')
<h1 class="text-2xl font-semibold text-white">Dashboard</h1>
<p class="mt-1 text-sm text-gray-500">Overview of {{ config('stadium.name') }}</p>

{{-- KPI cards --}}
<div class="mt-6 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
    <div class="rounded-lg bg-surface-100 p-5">
        <p class="text-xs font-bold uppercase tracking-wider text-gray-500">Total Revenue</p>
        <p class="mt-2 text-3xl font-semibold text-white">KES {{ number_format($totalRevenue) }}</p>
    </div>
    <div class="rounded-lg bg-surface-100 p-5">
        <p class="text-xs font-bold uppercase tracking-wider text-gray-500">Tickets Sold</p>
        <p class="mt-2 text-3xl font-semibold text-white">{{ number_format($totalTickets) }}</p>
    </div>
    <div class="rounded-lg bg-surface-100 p-5">
        <p class="text-xs font-bold uppercase tracking-wider text-gray-500">Total Events</p>
        <p class="mt-2 text-3xl font-semibold text-white">{{ $events->total() }}</p>
    </div>
    <div class="rounded-lg bg-surface-100 p-5">
        <p class="text-xs font-bold uppercase tracking-wider text-gray-500">Stadium Sections</p>
        <p class="mt-2 text-3xl font-semibold text-white">{{ $sections->count() }}</p>
    </div>
</div>

{{-- Section occupancy --}}
<div class="mt-8">
    <h2 class="mb-4 text-lg font-semibold text-white">Section Overview</h2>
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        @foreach($sections as $section)
        <div class="rounded-lg bg-surface-100 p-4">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-bold text-white">{{ $section->name }}</h3>
                <span class="h-3 w-3 rounded-full" style="background:{{ $section->color }}"></span>
            </div>
            <p class="mt-1 text-xs text-gray-500">{{ $section->tierLabel() }} &middot; Gate {{ $section->gate_number }}</p>
            <div class="mt-3 flex items-end justify-between">
                <span class="text-lg font-semibold text-white">{{ $section->occupancyPercent() }}%</span>
                <span class="text-xs text-gray-500">{{ number_format($section->current_occupancy) }}/{{ number_format($section->capacity) }}</span>
            </div>
            <div class="mt-1.5 h-1.5 w-full overflow-hidden rounded-full bg-white/5">
                <div class="h-full rounded-full" style="width:{{ $section->occupancyPercent() }}%; background:{{ $section->color }}"></div>
            </div>
        </div>
        @endforeach
    </div>
</div>

{{-- Recent events --}}
<div class="mt-8">
    <h2 class="mb-4 text-lg font-semibold text-white">Recent Events</h2>
    <div class="overflow-hidden rounded-lg bg-surface-100">
        <table class="min-w-full text-sm">
            <thead class="border-b border-white/5 text-left text-xs font-bold uppercase tracking-wider text-gray-500">
                <tr>
                    <th class="px-5 py-3">Event</th>
                    <th class="px-5 py-3">Date</th>
                    <th class="px-5 py-3">Status</th>
                    <th class="px-5 py-3">Attendance</th>
                    <th class="px-5 py-3">Sales</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @foreach($events as $event)
                <tr class="hover:bg-white/5 transition">
                    <td class="px-5 py-3 font-medium text-white">{{ $event->matchTitle() }}</td>
                    <td class="px-5 py-3 text-gray-500">{{ $event->event_date->format('M j, Y') }}</td>
                    <td class="px-5 py-3">
                        <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-bold
                            {{ $event->status === 'upcoming' ? 'bg-accent/10 text-accent-light' : ($event->status === 'ongoing' ? 'bg-green-500/10 text-green-400' : 'bg-white/5 text-gray-500') }}">
                            {{ ucfirst($event->status) }}
                        </span>
                    </td>
                    <td class="px-5 py-3 text-gray-400">{{ number_format($event->current_attendance) }} / {{ number_format($event->max_capacity) }}</td>
                    <td class="px-5 py-3">
                        @if($event->ticket_sales_open)
                            <span class="inline-flex items-center gap-1 text-green-400"><span class="h-2 w-2 rounded-full bg-green-400"></span> Open</span>
                        @else
                            <span class="inline-flex items-center gap-1 text-gray-600"><span class="h-2 w-2 rounded-full bg-gray-600"></span> Closed</span>
                        @endif
                    </td>
                    <td class="px-5 py-3"><a href="{{ route('admin.event.show', $event) }}" class="text-accent-light hover:underline">View Stats →</a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $events->links() }}</div>
</div>
@endsection
