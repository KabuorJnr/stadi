@extends('layouts.admin')
@section('title', 'Edit — ' . $event->matchTitle())

@section('content')
<div class="flex items-center gap-3">
    <a href="{{ route('admin.events.index') }}" class="text-gray-400 hover:text-gray-600">
        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
    </a>
    <h1 class="text-2xl font-bold text-gray-900">Edit Event</h1>
</div>

<form method="POST" action="{{ route('admin.events.update', $event) }}" class="mt-6 max-w-2xl">
    @csrf
    @method('PUT')
    @include('admin.events._form')

    <div class="mt-6 rounded-lg border bg-gray-50 p-4">
        <label class="flex items-center gap-3">
            <input type="hidden" name="ticket_sales_open" value="0">
            <input type="checkbox" name="ticket_sales_open" value="1" {{ old('ticket_sales_open', $event->ticket_sales_open) ? 'checked' : '' }}
                   class="h-4 w-4 rounded border-gray-300 text-brand-500 focus:ring-brand-500">
            <span class="text-sm font-medium text-gray-700">Ticket sales open</span>
        </label>
    </div>

    <div class="mt-8 flex items-center gap-4">
        <button type="submit" class="rounded-lg bg-brand-500 px-6 py-2.5 text-sm font-bold text-white transition hover:bg-brand-600">Update Event</button>
        <a href="{{ route('admin.events.index') }}" class="text-sm text-gray-400 hover:text-gray-600">Cancel</a>
    </div>
</form>
@endsection
