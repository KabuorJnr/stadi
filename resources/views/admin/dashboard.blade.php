@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')
<h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
<p class="mt-1 text-sm text-gray-500">Overview of {{ config('stadium.name') }}</p>

{{-- KPI cards --}}
<div class="mt-6 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
    <div class="rounded-xl border bg-white p-5 shadow-sm">
        <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Total Revenue</p>
        <p class="mt-2 text-3xl font-bold text-gray-900">KES {{ number_format($totalRevenue) }}</p>
    </div>
    <div class="rounded-xl border bg-white p-5 shadow-sm">
        <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Tickets Sold</p>
        <p class="mt-2 text-3xl font-bold text-gray-900">{{ number_format($totalTickets) }}</p>
    </div>
    <div class="rounded-xl border bg-white p-5 shadow-sm">
        <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Total Events</p>
        <p class="mt-2 text-3xl font-bold text-gray-900">{{ $events->total() }}</p>
    </div>
    <div class="rounded-xl border bg-white p-5 shadow-sm">
        <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Stadium Sections</p>
        <p class="mt-2 text-3xl font-bold text-gray-900">{{ $sections->count() }}</p>
    </div>
</div>

{{-- Section occupancy --}}
<div class="mt-8">
    <h2 class="mb-4 text-lg font-bold text-gray-900">Section Overview</h2>
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        @foreach($sections as $section)
        <div class="rounded-xl border bg-white p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-bold text-gray-800">{{ $section->name }}</h3>
                <span class="h-3 w-3 rounded-full" style="background:{{ $section->color }}"></span>
            </div>
            <p class="mt-1 text-xs text-gray-400">{{ $section->tierLabel() }} &middot; Gate {{ $section->gate_number }}</p>
            <div class="mt-3 flex items-end justify-between">
                <span class="text-lg font-bold text-gray-900">{{ $section->occupancyPercent() }}%</span>
                <span class="text-xs text-gray-400">{{ number_format($section->current_occupancy) }}/{{ number_format($section->capacity) }}</span>
            </div>
            <div class="mt-1.5 h-1.5 w-full overflow-hidden rounded-full bg-gray-100">
                <div class="h-full rounded-full" style="width:{{ $section->occupancyPercent() }}%; background:{{ $section->color }}"></div>
            </div>
        </div>
        @endforeach
    </div>
</div>

{{-- Recent events --}}
<div class="mt-8">
    <h2 class="mb-4 text-lg font-bold text-gray-900">Recent Events</h2>
    <div class="overflow-hidden rounded-xl border bg-white shadow-sm">
        <table class="min-w-full text-sm">
            <thead class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                <tr>
                    <th class="px-5 py-3">Event</th>
                    <th class="px-5 py-3">Date</th>
                    <th class="px-5 py-3">Status</th>
                    <th class="px-5 py-3">Attendance</th>
                    <th class="px-5 py-3">Sales</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach($events as $event)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-5 py-3 font-medium text-gray-900">{{ $event->matchTitle() }}</td>
                    <td class="px-5 py-3 text-gray-500">{{ $event->event_date->format('M j, Y') }}</td>
                    <td class="px-5 py-3">
                        <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-semibold
                            {{ $event->status === 'upcoming' ? 'bg-blue-50 text-blue-700' : ($event->status === 'ongoing' ? 'bg-green-50 text-green-700' : 'bg-gray-100 text-gray-500') }}">
                            {{ ucfirst($event->status) }}
                        </span>
                    </td>
                    <td class="px-5 py-3 text-gray-500">{{ number_format($event->current_attendance) }} / {{ number_format($event->max_capacity) }}</td>
                    <td class="px-5 py-3">
                        @if($event->ticket_sales_open)
                            <span class="inline-flex items-center gap-1 text-green-600"><span class="h-2 w-2 rounded-full bg-green-500"></span> Open</span>
                        @else
                            <span class="inline-flex items-center gap-1 text-gray-400"><span class="h-2 w-2 rounded-full bg-gray-300"></span> Closed</span>
                        @endif
                    </td>
                    <td class="px-5 py-3"><a href="{{ route('admin.event.show', $event) }}" class="text-brand-500 hover:underline">View Stats →</a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $events->links() }}</div>
</div>
@endsection
