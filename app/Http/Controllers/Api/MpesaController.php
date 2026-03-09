<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\StadiumSection;
use App\Models\Ticket;
use App\Models\Transaction;
use App\Models\User;
use App\Services\MpesaService;
use App\Services\QrService;
use App\Services\SmsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MpesaController extends Controller
{
    public function __construct(
        private MpesaService $mpesa,
        private QrService $qr,
        private SmsService $sms,
    ) {}

    /**
     * Initiate STK Push — called from the PWA buy flow after the user
     * picks a stadium section from the interactive map.
     */
    public function stkPush(Request $request): JsonResponse
    {
        $request->validate([
            'phone_number' => 'required|string',
            'event_id'     => 'required|exists:events,id',
            'section_id'   => 'required|exists:stadium_sections,id',
        ]);

        $event   = Event::findOrFail($request->input('event_id'));
        $section = StadiumSection::findOrFail($request->input('section_id'));

        if (!$event->canSellTickets()) {
            return response()->json(['error' => __('tickets.sales_closed')], 422);
        }
        if ($section->isFull()) {
            return response()->json(['error' => __('tickets.section_full')], 422);
        }

        $price = $section->priceForEvent($event);
        $user  = User::firstOrCreate(
            ['phone_number' => $request->input('phone_number')],
            ['name' => 'Fan', 'role' => 'fan']
        );

        $ref = "STADI-{$event->id}-{$user->id}-{$section->code}";
        $stk = $this->mpesa->stkPush($user->phone_number, $price, $ref, "Stadi: {$event->matchTitle()}");

        Transaction::create([
            'user_id'              => $user->id,
            'event_id'             => $event->id,
            'section_id'           => $section->id,
            'merchant_request_id'  => $stk['MerchantRequestID'] ?? null,
            'checkout_request_id'  => $stk['CheckoutRequestID'] ?? null,
            'amount'               => $price,
            'phone_number'         => $user->phone_number,
            'status'               => 'pending',
            'channel'              => 'stk_push',
        ]);

        return response()->json([
            'message'             => __('tickets.payment_initiated'),
            'checkout_request_id' => $stk['CheckoutRequestID'] ?? null,
        ]);
    }

    /** Safaricom STK callback. */
    public function stkCallback(Request $request): JsonResponse
    {
        $payload = $request->all();
        Log::info('M-PESA STK Callback', $payload);

        $body        = $payload['Body']['stkCallback'] ?? [];
        $checkoutId  = $body['CheckoutRequestID'] ?? null;
        $resultCode  = $body['ResultCode'] ?? -1;

        $txn = Transaction::where('checkout_request_id', $checkoutId)->first();
        if (!$txn) {
            return response()->json(['ResultCode' => 0, 'ResultDesc' => 'Accepted']);
        }

        if ($resultCode !== 0) {
            $txn->markFailed($body);
            return response()->json(['ResultCode' => 0, 'ResultDesc' => 'Accepted']);
        }

        $receipt = $this->extractMeta($body, 'MpesaReceiptNumber');

        DB::transaction(function () use ($txn, $receipt, $body) {
            $txn->markCompleted($receipt, $body);
            $this->issueTicket($txn);
        });

        return response()->json(['ResultCode' => 0, 'ResultDesc' => 'Accepted']);
    }

    /** C2B confirmation — paybill / USSD payments. */
    public function c2bConfirm(Request $request): JsonResponse
    {
        $p = $request->all();
        Log::info('M-PESA C2B Confirm', $p);

        $parts   = explode('-', $p['BillRefNumber'] ?? '');
        $eventId = $parts[1] ?? null;
        $userId  = $parts[2] ?? null;
        $secCode = $parts[3] ?? null;

        $user    = $userId ? User::find($userId) : null;
        $event   = $eventId ? Event::find($eventId) : null;
        $section = $secCode ? StadiumSection::where('code', $secCode)->first() : null;

        if (!$user || !$event || !$event->canSellTickets()) {
            return response()->json(['ResultCode' => 0, 'ResultDesc' => 'Accepted']);
        }

        $amount = (int) ($p['TransAmount'] ?? 0);
        $txn = Transaction::create([
            'user_id'              => $user->id,
            'event_id'             => $event->id,
            'section_id'           => $section?->id,
            'mpesa_receipt_number' => $p['TransID'] ?? null,
            'amount'               => $amount,
            'phone_number'         => $p['MSISDN'] ?? '',
            'status'               => 'completed',
            'channel'              => 'c2b',
            'raw_callback'         => $p,
        ]);

        $this->issueTicket($txn);

        return response()->json(['ResultCode' => 0, 'ResultDesc' => 'Accepted']);
    }

    public function c2bValidate(Request $request): JsonResponse
    {
        Log::info('M-PESA C2B Validate', $request->all());
        return response()->json(['ResultCode' => 0, 'ResultDesc' => 'Accepted']);
    }

    /** Issue ticket after successful payment. */
    private function issueTicket(Transaction $txn): void
    {
        $ticket = Ticket::create([
            'user_id'    => $txn->user_id,
            'event_id'   => $txn->event_id,
            'section_id' => $txn->section_id,
            'qr_hash'    => $this->qr->generateHash($txn->id, $txn->user_id, $txn->event_id),
            'status'     => 'active',
        ]);

        $txn->update(['ticket_id' => $ticket->id]);

        // Increment section occupancy
        if ($txn->section_id) {
            StadiumSection::where('id', $txn->section_id)->increment('current_occupancy');
        }

        $event     = Event::find($txn->event_id);
        $section   = $txn->section_id ? StadiumSection::find($txn->section_id) : null;
        $ticketUrl = $this->qr->ticketUrl($ticket->qr_hash);

        $this->sms->sendTicketConfirmation(
            $txn->phone_number,
            $event->matchTitle(),
            $ticketUrl,
            $section?->name ?? '',
        );
    }

    private function extractMeta(array $body, string $key): ?string
    {
        foreach ($body['CallbackMetadata']['Item'] ?? [] as $item) {
            if (($item['Name'] ?? '') === $key) return (string) ($item['Value'] ?? '');
        }
        return null;
    }
}
