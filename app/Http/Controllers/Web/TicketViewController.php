<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\StadiumSection;
use App\Models\Ticket;
use Illuminate\View\View;

class TicketViewController extends Controller
{
    /** PWA landing — upcoming matches with Manchester-United-style cards. */
    public function events(): View
    {
        $events = Event::where('status', 'upcoming')
            ->where('ticket_sales_open', true)
            ->orderBy('event_date')
            ->get();

        return view('pwa.events', compact('events'));
    }

    /** PWA — interactive stadium map / section picker. */
    public function selectSection(Event $event): View
    {
        $sections = StadiumSection::orderBy('sort_order')
            ->get()
            ->map(function ($s) use ($event) {
                $s->computed_price = $s->priceForEvent($event);
                return $s;
            });

        return view('pwa.select-section', compact('event', 'sections'));
    }

    /** PWA — confirm & pay for a specific section. */
    public function buy(Event $event, StadiumSection $section): View
    {
        $price = $section->priceForEvent($event);
        return view('pwa.buy', compact('event', 'section', 'price'));
    }

    /** PWA — display fan's ticket with QR code. */
    public function show(string $qrHash): View
    {
        $ticket = Ticket::where('qr_hash', $qrHash)
            ->with(['event', 'user', 'section'])
            ->firstOrFail();

        return view('ticket.show', compact('ticket'));
    }
}
