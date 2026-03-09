{{-- Shared form partial for create/edit event --}}
@if($errors->any())
<div class="mb-6 rounded-lg border border-red-200 bg-red-50 p-4">
    <ul class="list-inside list-disc text-sm text-red-700">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="space-y-5 rounded-xl border bg-white p-6 shadow-sm">
    <h2 class="text-base font-bold text-gray-800">Match Details</h2>

    <div class="grid gap-5 sm:grid-cols-2">
        <div class="sm:col-span-2">
            <label class="mb-1 block text-sm font-medium text-gray-700">Event Name</label>
            <input type="text" name="name" value="{{ old('name', $event->name ?? '') }}" required
                   class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20"
                   placeholder="e.g. Gor Mahia vs AFC Leopards">
        </div>

        <div>
            <label class="mb-1 block text-sm font-medium text-gray-700">Home Team</label>
            <input type="text" name="home_team" value="{{ old('home_team', $event->home_team ?? '') }}"
                   class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20"
                   placeholder="Gor Mahia">
        </div>

        <div>
            <label class="mb-1 block text-sm font-medium text-gray-700">Away Team</label>
            <input type="text" name="away_team" value="{{ old('away_team', $event->away_team ?? '') }}"
                   class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20"
                   placeholder="AFC Leopards">
        </div>

        <div>
            <label class="mb-1 block text-sm font-medium text-gray-700">Competition</label>
            <input type="text" name="competition" value="{{ old('competition', $event->competition ?? '') }}"
                   class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20"
                   placeholder="FKF Premier League">
        </div>

        <div>
            <label class="mb-1 block text-sm font-medium text-gray-700">Event Date & Time</label>
            <input type="datetime-local" name="event_date" value="{{ old('event_date', isset($event) ? $event->event_date->format('Y-m-d\TH:i') : '') }}" required
                   class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20">
        </div>
    </div>

    <div>
        <label class="mb-1 block text-sm font-medium text-gray-700">Description</label>
        <textarea name="description" rows="3"
                  class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20"
                  placeholder="Optional description…">{{ old('description', $event->description ?? '') }}</textarea>
    </div>

    <hr class="border-gray-100">
    <h2 class="text-base font-bold text-gray-800">Pricing & Capacity</h2>

    <div class="grid gap-5 sm:grid-cols-2">
        <div>
            <label class="mb-1 block text-sm font-medium text-gray-700">Base Ticket Price (KES)</label>
            <input type="number" name="base_ticket_price" value="{{ old('base_ticket_price', $event->base_ticket_price ?? '') }}" required min="0"
                   class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20"
                   placeholder="200">
            <p class="mt-1 text-xs text-gray-400">Economy tier uses this price. VIP = 5×, Premium = 3×, Regular = 1.5×</p>
        </div>

        <div>
            <label class="mb-1 block text-sm font-medium text-gray-700">Max Capacity</label>
            <input type="number" name="max_capacity" value="{{ old('max_capacity', $event->max_capacity ?? config('stadium.max_capacity')) }}" required min="1" max="{{ config('stadium.max_capacity') }}"
                   class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20">
            <p class="mt-1 text-xs text-gray-400">Max: {{ number_format(config('stadium.max_capacity')) }} ({{ config('stadium.name') }})</p>
        </div>
    </div>
</div>
