<?php

namespace App\Http\Controllers\Ussd;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\StadiumSection;
use App\Models\Transaction;
use App\Models\User;
use App\Services\MpesaService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UssdController extends Controller
{
    public function handle(Request $request): Response
    {
        $phone = $request->input('phoneNumber');
        $text  = trim($request->input('text', ''));
        $parts = $text === '' ? [] : explode('*', $text);
        $level = count($parts);

        $user = User::where('phone_number', $phone)->first();
        app()->setLocale($user?->preferred_locale ?? 'en');

        $out = match (true) {
            $level === 0 => $this->mainMenu(),

            $level === 1 && $parts[0] === '1' => $this->listEvents(),
            $level === 1 && $parts[0] === '2' => $this->checkTicket($phone),
            $level === 1 && $parts[0] === '3' => $this->changeLanguage(),

            $level === 2 && $parts[0] === '1' => $this->listSections($parts[1]),
            $level === 2 && $parts[0] === '3' => $this->setLanguage($parts[1], $phone),

            $level === 3 && $parts[0] === '1' => $this->confirmPurchase($parts[1], $parts[2], $phone),

            $level === 4 && $parts[0] === '1' && $parts[3] === '1'
                => $this->initPurchase($parts[1], $parts[2], $phone),

            default => $this->end(__('ussd.invalid_option')),
        };

        return response($out, 200)->header('Content-Type', 'text/plain');
    }

    private function mainMenu(): string
    {
        return $this->con(
            __('ussd.welcome') . "\n"
            . "1. " . __('ussd.buy_ticket') . "\n"
            . "2. " . __('ussd.check_ticket') . "\n"
            . "3. " . __('ussd.change_language')
        );
    }

    private function listEvents(): string
    {
        $events = Event::where('ticket_sales_open', true)->where('status', 'upcoming')
            ->orderBy('event_date')->take(5)->get();

        if ($events->isEmpty()) return $this->end(__('ussd.no_events'));

        $menu = __('ussd.select_event') . "\n";
        foreach ($events as $e) {
            $menu .= "{$e->id}. {$e->matchTitle()} ({$e->event_date->format('d/m')}) - KES {$e->base_ticket_price}\n";
        }
        return $this->con($menu);
    }

    private function listSections(string $eventId): string
    {
        $event = Event::find($eventId);
        if (!$event || !$event->canSellTickets()) return $this->end(__('ussd.event_unavailable'));

        $sections = StadiumSection::where('current_occupancy', '<', \DB::raw('capacity'))
            ->orderBy('sort_order')->get();

        if ($sections->isEmpty()) return $this->end(__('ussd.no_sections'));

        $menu = __('ussd.select_section') . "\n";
        foreach ($sections as $s) {
            $price = $s->priceForEvent($event);
            $menu .= "{$s->id}. {$s->name} - KES {$price} ({$s->remainingSeats()} left)\n";
        }
        return $this->con($menu);
    }

    private function confirmPurchase(string $eventId, string $sectionId, string $phone): string
    {
        $event   = Event::find($eventId);
        $section = StadiumSection::find($sectionId);
        if (!$event || !$section || !$event->canSellTickets()) return $this->end(__('ussd.event_unavailable'));

        $price = $section->priceForEvent($event);

        return $this->con(
            __('ussd.confirm_purchase', [
                'event'   => $event->matchTitle(),
                'section' => $section->name,
                'price'   => $price,
                'phone'   => $phone,
            ]) . "\n1. " . __('ussd.confirm') . "\n2. " . __('ussd.cancel')
        );
    }

    private function initPurchase(string $eventId, string $sectionId, string $phone): string
    {
        $event   = Event::find($eventId);
        $section = StadiumSection::find($sectionId);
        if (!$event || !$section || !$event->canSellTickets()) return $this->end(__('ussd.event_unavailable'));

        $user  = User::firstOrCreate(['phone_number' => $phone], ['name' => 'USSD Fan', 'role' => 'fan']);
        $price = $section->priceForEvent($event);
        $ref   = "STADI-{$event->id}-{$user->id}-{$section->code}";

        try {
            app(MpesaService::class)->stkPush($phone, $price, $ref, "Stadi: {$event->matchTitle()}");
            Transaction::create([
                'user_id' => $user->id, 'event_id' => $event->id, 'section_id' => $section->id,
                'amount' => $price, 'phone_number' => $phone, 'status' => 'pending', 'channel' => 'stk_push',
            ]);
        } catch (\Throwable) {
            return $this->end(__('ussd.payment_error'));
        }

        return $this->end(__('ussd.payment_sent'));
    }

    private function checkTicket(string $phone): string
    {
        $user = User::where('phone_number', $phone)->first();
        if (!$user) return $this->end(__('ussd.no_tickets'));

        $tickets = $user->tickets()->with(['event', 'section'])->where('status', 'active')->get();
        if ($tickets->isEmpty()) return $this->end(__('ussd.no_tickets'));

        $list = __('ussd.your_tickets') . "\n";
        foreach ($tickets as $t) {
            $list .= "- {$t->event->matchTitle()} [{$t->section?->code}]\n";
        }
        return $this->end($list);
    }

    private function changeLanguage(): string
    {
        return $this->con(__('ussd.select_language') . "\n1. English\n2. Kiswahili\n3. Dholuo");
    }

    private function setLanguage(string $ch, string $phone): string
    {
        $locale = match ($ch) { '2' => 'sw', '3' => 'dholuo', default => 'en' };
        User::where('phone_number', $phone)->update(['preferred_locale' => $locale]);
        app()->setLocale($locale);
        return $this->end(__('ussd.language_set'));
    }

    private function con(string $m): string { return "CON {$m}"; }
    private function end(string $m): string { return "END {$m}"; }
}
