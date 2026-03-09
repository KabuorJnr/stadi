@extends('layouts.admin')
@section('title', 'Revenue')

@section('content')
<h1 class="text-2xl font-bold text-gray-900">Revenue</h1>
<p class="mt-1 text-sm text-gray-500">Transaction history & revenue breakdown</p>

{{-- Filters --}}
<form method="GET" class="mt-6 flex flex-wrap items-end gap-4 rounded-xl border bg-white p-4 shadow-sm">
    <div>
        <label class="mb-1 block text-xs font-medium text-gray-500">From</label>
        <input type="date" name="from" value="{{ request('from') }}"
               class="rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20">
    </div>
    <div>
        <label class="mb-1 block text-xs font-medium text-gray-500">To</label>
        <input type="date" name="to" value="{{ request('to') }}"
               class="rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20">
    </div>
    <button type="submit" class="rounded-lg bg-brand-500 px-5 py-2 text-sm font-semibold text-white transition hover:bg-brand-600">Filter</button>
    @if(request('from') || request('to'))
        <a href="{{ route('admin.revenue') }}" class="text-sm text-gray-400 hover:text-gray-600">Clear</a>
    @endif
</form>

{{-- Summary cards --}}
<div class="mt-6 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
    <div class="rounded-xl border bg-white p-5 shadow-sm">
        <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Total Revenue</p>
        <p class="mt-2 text-3xl font-bold text-gray-900">KES {{ number_format($summary->total) }}</p>
    </div>
    <div class="rounded-xl border bg-white p-5 shadow-sm">
        <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Transactions</p>
        <p class="mt-2 text-3xl font-bold text-gray-900">{{ number_format($summary->count) }}</p>
    </div>
    <div class="rounded-xl border bg-white p-5 shadow-sm">
        <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">STK Push</p>
        <p class="mt-2 text-3xl font-bold text-gray-900">KES {{ number_format($summary->stk) }}</p>
    </div>
    <div class="rounded-xl border bg-white p-5 shadow-sm">
        <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">C2B / Paybill</p>
        <p class="mt-2 text-3xl font-bold text-gray-900">KES {{ number_format($summary->c2b) }}</p>
    </div>
</div>

{{-- Transactions table --}}
<div class="mt-8 overflow-hidden rounded-xl border bg-white shadow-sm">
    <table class="min-w-full text-sm">
        <thead class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
            <tr>
                <th class="px-5 py-3">Ref</th>
                <th class="px-5 py-3">Phone</th>
                <th class="px-5 py-3">Channel</th>
                <th class="px-5 py-3">Amount</th>
                <th class="px-5 py-3">Status</th>
                <th class="px-5 py-3">Date</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse($transactions as $txn)
            <tr class="hover:bg-gray-50 transition">
                <td class="px-5 py-3 font-mono text-xs text-gray-600">{{ $txn->mpesa_receipt ?? '—' }}</td>
                <td class="px-5 py-3 text-gray-700">{{ $txn->phone ?? '—' }}</td>
                <td class="px-5 py-3">
                    <span class="rounded-full px-2 py-0.5 text-xs font-semibold
                        {{ $txn->channel === 'stk_push' ? 'bg-purple-50 text-purple-700' : 'bg-blue-50 text-blue-700' }}">
                        {{ $txn->channel === 'stk_push' ? 'STK Push' : 'C2B' }}
                    </span>
                </td>
                <td class="px-5 py-3 font-medium text-gray-900">KES {{ number_format($txn->amount) }}</td>
                <td class="px-5 py-3">
                    <span class="inline-flex items-center gap-1 text-xs font-semibold
                        {{ $txn->status === 'completed' ? 'text-green-600' : ($txn->status === 'pending' ? 'text-yellow-600' : 'text-red-500') }}">
                        <span class="h-1.5 w-1.5 rounded-full
                            {{ $txn->status === 'completed' ? 'bg-green-500' : ($txn->status === 'pending' ? 'bg-yellow-500' : 'bg-red-500') }}"></span>
                        {{ ucfirst($txn->status) }}
                    </span>
                </td>
                <td class="px-5 py-3 text-gray-500">{{ $txn->created_at->format('M j, Y H:i') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-5 py-12 text-center text-gray-400">No transactions found</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">{{ $transactions->withQueryString()->links() }}</div>
@endsection
