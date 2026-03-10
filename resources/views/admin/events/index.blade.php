@extends('layouts.admin')
@section('title', 'Manage Events')

@section('content')
<div class="flex items-center justify-between">
    <h1 class="text-2xl font-semibold text-white">Events</h1>
    <a href="{{ route('admin.events.create') }}" class="inline-flex items-center gap-2 rounded-md bg-accent px-4 py-2.5 text-sm font-bold text-white transition hover:bg-accent-light">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        New Event
    </a>
</div>

<div class="mt-6 overflow-hidden rounded-lg bg-surface-100">
    <table class="min-w-full text-sm">
        <thead class="border-b border-white/5 text-left text-xs font-bold uppercase tracking-wider text-gray-500">
            <tr>
                <th class="px-5 py-3">Event</th>
                <th class="px-5 py-3">Date</th>
                <th class="px-5 py-3">Base Price</th>
                <th class="px-5 py-3">Capacity</th>
                <th class="px-5 py-3">Sales</th>
                <th class="px-5 py-3 text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-white/5">
            @forelse($events as $event)
            <tr class="hover:bg-white/5 transition">
                <td class="px-5 py-3">
                    <div>
                        <p class="font-bold text-white">{{ $event->matchTitle() }}</p>
                        @if($event->competition)
                            <p class="text-xs text-gray-500">{{ $event->competition }}</p>
                        @endif
                    </div>
                </td>
                <td class="px-5 py-3 text-gray-400">{{ $event->event_date->format('M j, Y · g:i A') }}</td>
                <td class="px-5 py-3 text-white">KES {{ number_format($event->base_ticket_price) }}</td>
                <td class="px-5 py-3 text-gray-400">{{ number_format($event->current_attendance) }} / {{ number_format($event->max_capacity) }}</td>
                <td class="px-5 py-3">
                    <form method="POST" action="{{ route('admin.events.toggle-sales', $event) }}">
                        @csrf
                        <button type="submit" class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-bold transition
                            {{ $event->ticket_sales_open ? 'bg-green-500/10 text-green-400 hover:bg-green-500/20' : 'bg-white/5 text-gray-500 hover:bg-white/10' }}">
                            <span class="h-2 w-2 rounded-full {{ $event->ticket_sales_open ? 'bg-green-400' : 'bg-gray-600' }}"></span>
                            {{ $event->ticket_sales_open ? 'Open' : 'Closed' }}
                        </button>
                    </form>
                </td>
                <td class="px-5 py-3 text-right">
                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('admin.events.edit', $event) }}" class="rounded-lg border border-white/10 px-3 py-1.5 text-xs font-medium text-gray-400 hover:bg-white/5">Edit</a>
                        <a href="{{ route('admin.event.show', $event) }}" class="rounded-lg bg-accent/10 px-3 py-1.5 text-xs font-bold text-accent-light hover:bg-accent/20">Stats</a>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-5 py-12 text-center text-gray-600">No events yet. Create your first event!</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">{{ $events->links() }}</div>
@endsection
