@extends('layouts.admin')
@section('title', $event->matchTitle() . ' — Stats')

@section('content')
<div class="flex flex-wrap items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl font-extrabold text-white">{{ $event->matchTitle() }}</h1>
        <p class="mt-1 text-sm text-gray-500">{{ $event->event_date->format('l, M j, Y · g:i A') }}</p>
    </div>
    <a href="{{ route('admin.dashboard') }}" class="text-sm text-gray-500 hover:text-white">← Dashboard</a>
</div>

{{-- KPIs --}}
<div class="mt-6 grid gap-5 sm:grid-cols-3">
    <div class="rounded-2xl bg-dark-100 p-5">
        <p class="text-xs font-bold uppercase tracking-wider text-gray-500">Revenue</p>
        <p class="mt-2 text-3xl font-extrabold text-white">KES {{ number_format($revenue) }}</p>
    </div>
    <div class="rounded-2xl bg-dark-100 p-5">
        <p class="text-xs font-bold uppercase tracking-wider text-gray-500">Tickets Sold</p>
        <p class="mt-2 text-3xl font-extrabold text-white">{{ number_format($ticketsSold) }}</p>
    </div>
    <div class="rounded-2xl bg-dark-100 p-5">
        <p class="text-xs font-bold uppercase tracking-wider text-gray-500">Capacity Used</p>
        <p class="mt-2 text-3xl font-extrabold text-white">{{ $event->max_capacity > 0 ? round(($ticketsSold / $event->max_capacity) * 100, 1) : 0 }}%</p>
    </div>
</div>

{{-- Section breakdown --}}
<div class="mt-8">
    <h2 class="mb-4 text-lg font-extrabold text-white">Tickets by Section</h2>
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        @foreach($sectionBreakdown as $section)
        <div class="rounded-2xl bg-dark-100 p-4">
            <div class="flex items-center gap-2">
                <span class="h-3 w-3 rounded-full" style="background:{{ $section->color }}"></span>
                <h3 class="text-sm font-bold text-white">{{ $section->name }}</h3>
            </div>
            <p class="mt-2 text-2xl font-extrabold text-white">{{ $section->tickets_count }}</p>
            <p class="text-xs text-gray-500">of {{ number_format($section->capacity) }} capacity</p>
        </div>
        @endforeach
    </div>
</div>

{{-- Gate breakdown --}}
<div class="mt-8 grid gap-8 lg:grid-cols-2">
    <div>
        <h2 class="mb-4 text-lg font-extrabold text-white">Gate Scans</h2>
        <div class="overflow-hidden rounded-2xl bg-dark-100">
            <table class="min-w-full text-sm">
                <thead class="border-b border-white/5 text-center text-xs font-bold uppercase tracking-wider text-gray-500">
                    <tr>
                        <th class="px-5 py-3">Gate</th>
                        <th class="px-5 py-3">Total Scans</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5 text-center">
                    @forelse($gateBreakdown as $gate)
                    <tr>
                        <td class="px-5 py-3 font-medium text-white">Gate {{ $gate->gate_number }}</td>
                        <td class="px-5 py-3 text-gray-400">{{ number_format($gate->total_scans) }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="2" class="px-5 py-6 text-gray-600">No scans recorded yet</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Hourly admissions --}}
    <div>
        <h2 class="mb-4 text-lg font-extrabold text-white">Hourly Admissions</h2>
        <div class="overflow-hidden rounded-2xl bg-dark-100 p-5">
            @if($hourlyAdmissions->count())
            <div class="flex items-end gap-2" style="height: 200px;">
                @php $maxCount = $hourlyAdmissions->max('count') ?: 1; @endphp
                @foreach($hourlyAdmissions as $h)
                <div class="flex flex-1 flex-col items-center gap-1">
                    <div class="w-full rounded-t bg-accent transition-all" style="height: {{ ($h->count / $maxCount) * 100 }}%"></div>
                    <span class="text-[10px] text-gray-500">{{ str_pad($h->hour, 2, '0', STR_PAD_LEFT) }}h</span>
                    <span class="text-[10px] font-bold text-gray-400">{{ $h->count }}</span>
                </div>
                @endforeach
            </div>
            @else
            <p class="py-10 text-center text-sm text-gray-600">No admission data yet</p>
            @endif
        </div>
    </div>
</div>
@endsection
