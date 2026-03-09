<?php

namespace App\Services;

use App\Models\Ticket;
use Illuminate\Support\Str;

class QrService
{
    /**
     * Generate a unique, unguessable cryptographic hash for a ticket.
     */
    public function generateHash(int $ticketId, int $userId, int $eventId): string
    {
        $salt   = Str::random(32);
        $secret = config('app.key') . config('stadium.scanner_api_key', '');
        $data   = implode('|', [$ticketId, $userId, $eventId, $salt, $secret]);

        return hash('sha256', $data);
    }

    public function validate(string $qrHash): ?Ticket
    {
        return Ticket::where('qr_hash', $qrHash)->where('status', 'active')->first();
    }

    public function ticketUrl(string $qrHash): string
    {
        return url("/ticket/{$qrHash}");
    }
}
