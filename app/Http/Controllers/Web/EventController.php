<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EventController extends Controller
{
    public function index(): View
    {
        return view('admin.events.index', [
            'events' => Event::orderByDesc('event_date')->paginate(15),
        ]);
    }

    public function create(): View
    {
        return view('admin.events.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $v = $request->validate([
            'name'              => 'required|string|max:255',
            'description'       => 'nullable|string',
            'event_date'        => 'required|date|after:now',
            'base_ticket_price' => 'required|integer|min:0',
            'max_capacity'      => 'required|integer|min:1|max:' . config('stadium.max_capacity'),
            'home_team'         => 'nullable|string|max:100',
            'away_team'         => 'nullable|string|max:100',
            'competition'       => 'nullable|string|max:100',
        ]);

        Event::create($v);
        return redirect()->route('admin.events.index')->with('success', __('events.created'));
    }

    public function edit(Event $event): View
    {
        return view('admin.events.edit', compact('event'));
    }

    public function update(Request $request, Event $event): RedirectResponse
    {
        $v = $request->validate([
            'name'              => 'required|string|max:255',
            'description'       => 'nullable|string',
            'event_date'        => 'required|date',
            'base_ticket_price' => 'required|integer|min:0',
            'max_capacity'      => 'required|integer|min:1|max:' . config('stadium.max_capacity'),
            'home_team'         => 'nullable|string|max:100',
            'away_team'         => 'nullable|string|max:100',
            'competition'       => 'nullable|string|max:100',
            'ticket_sales_open' => 'boolean',
        ]);

        $event->update($v);
        return redirect()->route('admin.events.index')->with('success', __('events.updated'));
    }

    public function toggleSales(Event $event): RedirectResponse
    {
        $event->update(['ticket_sales_open' => !$event->ticket_sales_open]);
        return back()->with('success', $event->ticket_sales_open ? __('events.sales_opened') : __('events.sales_closed'));
    }
}
