@extends('layouts.admin')
@section('title', $event->matchTitle() . ' — Stats')

@section('content')
<div class="flex flex-wrap items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">{{ $event->matchTitle() }}</h1>
        <p class="mt-1 text-sm text-gray-500">{{ $event->event_date->format('l, M j, Y · g:i A') }}</p>
    </div>
    <a href="{{ route('admin.dashboard') }}" class="text-sm text-gray-400 hover:text-gray-600">← Dashboard</a>
</div>

{{-- KPIs --}}
<div class="mt-6 grid gap-5 sm:grid-cols-3">
    <div class="rounded-xl border bg-white p-5 shadow-sm">
        <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Revenue</p>
        <p class="mt-2 text-3xl font-bold text-gray-900">KES {{ number_format($revenue) }}</p>
    </div>
    <div class="rounded-xl border bg-white p-5 shadow-sm">
        <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Tickets Sold</p>
        <p class="mt-2 text-3xl font-bold text-gray-900">{{ number_format($ticketsSold) }}</p>
    </div>
    <div class="rounded-xl border bg-white p-5 shadow-sm">
        <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Capacity Used</p>
        <p class="mt-2 text-3xl font-bold text-gray-900">{{ $event->max_capacity > 0 ? round(($ticketsSold / $event->max_capacity) * 100, 1) : 0 }}%</p>
    </div>
</div>

{{-- Section breakdown --}}
<div class="mt-8">
    <h2 class="mb-4 text-lg font-bold text-gray-900">Tickets by Section</h2>
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        @foreach($sectionBreakdown as $section)
        <div class="rounded-xl border bg-white p-4 shadow-sm">
            <div class="flex items-center gap-2">
                <span class="h-3 w-3 rounded-full" style="background:{{ $section->color }}"></span>
                <h3 class="text-sm font-bold text-gray-800">{{ $section->name }}</h3>
            </div>
            <p class="mt-2 text-2xl font-bold text-gray-900">{{ $section->tickets_count }}</p>
            <p class="text-xs text-gray-400">of {{ number_format($section->capacity) }} capacity</p>
        </div>
        @endforeach
    </div>
</div>

{{-- Gate breakdown --}}
<div class="mt-8 grid gap-8 lg:grid-cols-2">
    <div>
        <h2 class="mb-4 text-lg font-bold text-gray-900">Gate Scans</h2>
        <div class="overflow-hidden rounded-xl border bg-white shadow-sm">
            <table class="min-w-full text-sm">
                <thead class="border-b bg-gray-50 text-center text-xs font-semibold uppercase tracking-wider text-gray-500">
                    <tr>
                        <th class="px-5 py-3">Gate</th>
                        <th class="px-5 py-3">Total Scans</th>
                    </tr>
                </thead>
                <tbody class="divide-y text-center">
                    @forelse($gateBreakdown as $gate)
                    <tr>
                        <td class="px-5 py-3 font-medium">Gate {{ $gate->gate_number }}</td>
                        <td class="px-5 py-3">{{ number_format($gate->total_scans) }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="2" class="px-5 py-6 text-gray-400">No scans recorded yet</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Hourly admissions --}}
    <div>
        <h2 class="mb-4 text-lg font-bold text-gray-900">Hourly Admissions</h2>
        <div class="overflow-hidden rounded-xl border bg-white p-5 shadow-sm">
            @if($hourlyAdmissions->count())
            <div class="flex items-end gap-2" style="height: 200px;">
                @php $maxCount = $hourlyAdmissions->max('count') ?: 1; @endphp
                @foreach($hourlyAdmissions as $h)
                <div class="flex flex-1 flex-col items-center gap-1">
                    <div class="w-full rounded-t bg-brand-500 transition-all" style="height: {{ ($h->count / $maxCount) * 100 }}%"></div>
                    <span class="text-[10px] text-gray-400">{{ str_pad($h->hour, 2, '0', STR_PAD_LEFT) }}h</span>
                    <span class="text-[10px] font-bold text-gray-600">{{ $h->count }}</span>
                </div>
                @endforeach
            </div>
            @else
            <p class="py-10 text-center text-sm text-gray-400">No admission data yet</p>
            @endif
        </div>
    </div>
</div>
@endsection
