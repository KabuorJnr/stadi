<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\GateLog;
use App\Models\StadiumSection;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $events       = Event::orderByDesc('event_date')->paginate(10);
        $totalRevenue = Transaction::where('status', 'completed')->sum('amount');
        $totalTickets = Transaction::where('status', 'completed')->count();
        $sections     = StadiumSection::orderBy('sort_order')->get();

        return view('admin.dashboard', compact('events', 'totalRevenue', 'totalTickets', 'sections'));
    }

    public function event(Event $event): View
    {
        $revenue     = Transaction::where('event_id', $event->id)->where('status', 'completed')->sum('amount');
        $ticketsSold = $event->tickets()->count();

        $gateBreakdown = GateLog::whereHas('ticket', fn ($q) => $q->where('event_id', $event->id))
            ->selectRaw('gate_number, COUNT(*) as total_scans')
            ->groupBy('gate_number')->orderBy('gate_number')->get();

        $hourlyAdmissions = GateLog::whereHas('ticket', fn ($q) => $q->where('event_id', $event->id))
            ->selectRaw('HOUR(scanned_at) as hour, COUNT(*) as count')
            ->groupByRaw('HOUR(scanned_at)')->orderBy('hour')->get();

        $sectionBreakdown = StadiumSection::withCount([
            'tickets' => fn ($q) => $q->where('event_id', $event->id)
        ])->orderBy('sort_order')->get();

        return view('admin.event', compact(
            'event', 'revenue', 'ticketsSold', 'gateBreakdown', 'hourlyAdmissions', 'sectionBreakdown'
        ));
    }

    public function revenue(Request $request): View
    {
        $query = Transaction::where('status', 'completed');
        if ($request->filled('from')) $query->whereDate('created_at', '>=', $request->input('from'));
        if ($request->filled('to'))   $query->whereDate('created_at', '<=', $request->input('to'));

        $transactions = $query->orderByDesc('created_at')->paginate(25);

        $base = Transaction::where('status', 'completed');
        if ($request->filled('from')) $base->whereDate('created_at', '>=', $request->input('from'));
        if ($request->filled('to'))   $base->whereDate('created_at', '<=', $request->input('to'));

        $summary = (object) [
            'total' => (clone $base)->sum('amount'),
            'count' => (clone $base)->count(),
            'stk'   => (clone $base)->where('channel', 'stk_push')->sum('amount'),
            'c2b'   => (clone $base)->where('channel', 'c2b')->sum('amount'),
        ];

        return view('admin.revenue', compact('transactions', 'summary'));
    }
}
