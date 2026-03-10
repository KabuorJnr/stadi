@extends('layouts.admin')
@section('title', 'Revenue')

@section('content')
<h1 class="text-2xl font-semibold text-white">Revenue</h1>
<p class="mt-1 text-sm text-gray-500">Transaction history & revenue breakdown</p>

{{-- Filters --}}
<form method="GET" class="mt-6 flex flex-wrap items-end gap-4 rounded-lg bg-surface-100 p-4">
    <div>
        <label class="mb-1 block text-xs font-bold text-gray-500">From</label>
        <input type="date" name="from" value="{{ request('from') }}"
               class="rounded-md border border-white/10 bg-surface px-3 py-2 text-sm text-white focus:border-accent focus:ring-2 focus:ring-accent/20 focus:outline-none">
    </div>
    <div>
        <label class="mb-1 block text-xs font-bold text-gray-500">To</label>
        <input type="date" name="to" value="{{ request('to') }}"
               class="rounded-md border border-white/10 bg-surface px-3 py-2 text-sm text-white focus:border-accent focus:ring-2 focus:ring-accent/20 focus:outline-none">
    </div>
    <button type="submit" class="rounded-md bg-accent px-5 py-2 text-sm font-bold text-white transition hover:bg-accent-light">Filter</button>
    @if(request('from') || request('to'))
        <a href="{{ route('admin.revenue') }}" class="text-sm text-gray-500 hover:text-white">Clear</a>
    @endif
</form>

{{-- Summary cards --}}
<div class="mt-6 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
    <div class="rounded-lg bg-surface-100 p-5">
        <p class="text-xs font-bold uppercase tracking-wider text-gray-500">Total Revenue</p>
        <p class="mt-2 text-3xl font-semibold text-white">KES {{ number_format($summary->total) }}</p>
    </div>
    <div class="rounded-lg bg-surface-100 p-5">
        <p class="text-xs font-bold uppercase tracking-wider text-gray-500">Transactions</p>
        <p class="mt-2 text-3xl font-semibold text-white">{{ number_format($summary->count) }}</p>
    </div>
    <div class="rounded-lg bg-surface-100 p-5">
        <p class="text-xs font-bold uppercase tracking-wider text-gray-500">STK Push</p>
        <p class="mt-2 text-3xl font-semibold text-white">KES {{ number_format($summary->stk) }}</p>
    </div>
    <div class="rounded-lg bg-surface-100 p-5">
        <p class="text-xs font-bold uppercase tracking-wider text-gray-500">C2B / Paybill</p>
        <p class="mt-2 text-3xl font-semibold text-white">KES {{ number_format($summary->c2b) }}</p>
    </div>
</div>

{{-- Transactions table --}}
<div class="mt-8 overflow-hidden rounded-lg bg-surface-100">
    <table class="min-w-full text-sm">
        <thead class="border-b border-white/5 text-left text-xs font-bold uppercase tracking-wider text-gray-500">
            <tr>
                <th class="px-5 py-3">Ref</th>
                <th class="px-5 py-3">Phone</th>
                <th class="px-5 py-3">Channel</th>
                <th class="px-5 py-3">Amount</th>
                <th class="px-5 py-3">Status</th>
                <th class="px-5 py-3">Date</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-white/5">
            @forelse($transactions as $txn)
            <tr class="hover:bg-white/5 transition">
                <td class="px-5 py-3 font-mono text-xs text-gray-400">{{ $txn->mpesa_receipt ?? '—' }}</td>
                <td class="px-5 py-3 text-gray-300">{{ $txn->phone ?? '—' }}</td>
                <td class="px-5 py-3">
                    <span class="rounded-full px-2 py-0.5 text-xs font-bold
                        {{ $txn->channel === 'stk_push' ? 'bg-purple-500/10 text-purple-400' : 'bg-accent/10 text-accent-light' }}">
                        {{ $txn->channel === 'stk_push' ? 'STK Push' : 'C2B' }}
                    </span>
                </td>
                <td class="px-5 py-3 font-bold text-white">KES {{ number_format($txn->amount) }}</td>
                <td class="px-5 py-3">
                    <span class="inline-flex items-center gap-1 text-xs font-bold
                        {{ $txn->status === 'completed' ? 'text-green-400' : ($txn->status === 'pending' ? 'text-yellow-400' : 'text-red-400') }}">
                        <span class="h-1.5 w-1.5 rounded-full
                            {{ $txn->status === 'completed' ? 'bg-green-400' : ($txn->status === 'pending' ? 'bg-yellow-400' : 'bg-red-400') }}"></span>
                        {{ ucfirst($txn->status) }}
                    </span>
                </td>
                <td class="px-5 py-3 text-gray-500">{{ $txn->created_at->format('M j, Y H:i') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-5 py-12 text-center text-gray-600">No transactions found</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">{{ $transactions->withQueryString()->links() }}</div>
@endsection
