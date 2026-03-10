@extends('layouts.admin')
@section('title', 'Create Event')

@section('content')
<div class="flex items-center gap-3">
    <a href="{{ route('admin.events.index') }}" class="text-gray-500 hover:text-white">
        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
    </a>
    <h1 class="text-2xl font-extrabold text-white">Create Event</h1>
</div>

<form method="POST" action="{{ route('admin.events.store') }}" class="mt-6 max-w-2xl">
    @csrf
    @include('admin.events._form')

    <div class="mt-8 flex items-center gap-4">
        <button type="submit" class="rounded-xl bg-accent px-6 py-2.5 text-sm font-bold text-white transition hover:bg-accent-light">Create Event</button>
        <a href="{{ route('admin.events.index') }}" class="text-sm text-gray-500 hover:text-white">Cancel</a>
    </div>
</form>
@endsection
