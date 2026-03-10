{{-- Shared form partial for create/edit event --}}
@if($errors->any())
<div class="mb-6 rounded-md border border-red-500/20 bg-red-500/10 p-4">
    <ul class="list-inside list-disc text-sm text-red-400">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="space-y-5 rounded-lg bg-surface-100 p-6">
    <h2 class="text-base font-semibold text-white">Match Details</h2>

    <div class="grid gap-5 sm:grid-cols-2">
        <div class="sm:col-span-2">
            <label class="mb-1 block text-sm font-medium text-gray-400">Event Name</label>
            <input type="text" name="name" value="{{ old('name', $event->name ?? '') }}" required
                   class="w-full rounded-md border border-white/10 bg-surface px-3 py-2 text-sm text-white placeholder-gray-600 focus:border-accent focus:ring-2 focus:ring-accent/20 focus:outline-none"
                   placeholder="e.g. Gor Mahia vs AFC Leopards">
        </div>

        <div>
            <label class="mb-1 block text-sm font-medium text-gray-400">Home Team</label>
            <input type="text" name="home_team" value="{{ old('home_team', $event->home_team ?? '') }}"
                   class="w-full rounded-md border border-white/10 bg-surface px-3 py-2 text-sm text-white placeholder-gray-600 focus:border-accent focus:ring-2 focus:ring-accent/20 focus:outline-none"
                   placeholder="Gor Mahia">
        </div>

        <div>
            <label class="mb-1 block text-sm font-medium text-gray-400">Away Team</label>
            <input type="text" name="away_team" value="{{ old('away_team', $event->away_team ?? '') }}"
                   class="w-full rounded-md border border-white/10 bg-surface px-3 py-2 text-sm text-white placeholder-gray-600 focus:border-accent focus:ring-2 focus:ring-accent/20 focus:outline-none"
                   placeholder="AFC Leopards">
        </div>

        <div>
            <label class="mb-1 block text-sm font-medium text-gray-400">Competition</label>
            <input type="text" name="competition" value="{{ old('competition', $event->competition ?? '') }}"
                   class="w-full rounded-md border border-white/10 bg-surface px-3 py-2 text-sm text-white placeholder-gray-600 focus:border-accent focus:ring-2 focus:ring-accent/20 focus:outline-none"
                   placeholder="FKF Premier League">
        </div>

        <div>
            <label class="mb-1 block text-sm font-medium text-gray-400">Event Date & Time</label>
            <input type="datetime-local" name="event_date" value="{{ old('event_date', isset($event) ? $event->event_date->format('Y-m-d\TH:i') : '') }}" required
                   class="w-full rounded-md border border-white/10 bg-surface px-3 py-2 text-sm text-white focus:border-accent focus:ring-2 focus:ring-accent/20 focus:outline-none">
        </div>
    </div>

    <div>
        <label class="mb-1 block text-sm font-medium text-gray-400">Description</label>
        <textarea name="description" rows="3"
                  class="w-full rounded-md border border-white/10 bg-surface px-3 py-2 text-sm text-white placeholder-gray-600 focus:border-accent focus:ring-2 focus:ring-accent/20 focus:outline-none"
                  placeholder="Optional description…">{{ old('description', $event->description ?? '') }}</textarea>
    </div>

    <hr class="border-white/5">
    <h2 class="text-base font-semibold text-white">Pricing & Capacity</h2>

    <div class="grid gap-5 sm:grid-cols-2">
        <div>
            <label class="mb-1 block text-sm font-medium text-gray-400">Base Ticket Price (KES)</label>
            <input type="number" name="base_ticket_price" value="{{ old('base_ticket_price', $event->base_ticket_price ?? '') }}" required min="0"
                   class="w-full rounded-md border border-white/10 bg-surface px-3 py-2 text-sm text-white placeholder-gray-600 focus:border-accent focus:ring-2 focus:ring-accent/20 focus:outline-none"
                   placeholder="200">
            <p class="mt-1 text-xs text-gray-600">Economy tier uses this price. VIP = 5×, Premium = 3×, Regular = 1.5×</p>
        </div>

        <div>
            <label class="mb-1 block text-sm font-medium text-gray-400">Max Capacity</label>
            <input type="number" name="max_capacity" value="{{ old('max_capacity', $event->max_capacity ?? config('stadium.max_capacity')) }}" required min="1" max="{{ config('stadium.max_capacity') }}"
                   class="w-full rounded-md border border-white/10 bg-surface px-3 py-2 text-sm text-white placeholder-gray-600 focus:border-accent focus:ring-2 focus:ring-accent/20 focus:outline-none">
            <p class="mt-1 text-xs text-gray-600">Max: {{ number_format(config('stadium.max_capacity')) }} ({{ config('stadium.name') }})</p>
        </div>
    </div>
</div>
