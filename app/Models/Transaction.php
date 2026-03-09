<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id', 'user_id', 'event_id', 'section_id',
        'mpesa_receipt_number', 'merchant_request_id', 'checkout_request_id',
        'amount', 'phone_number', 'status', 'channel', 'raw_callback',
    ];

    protected function casts(): array
    {
        return ['amount' => 'integer', 'raw_callback' => 'array'];
    }

    public function ticket(): BelongsTo  { return $this->belongsTo(Ticket::class); }
    public function user(): BelongsTo    { return $this->belongsTo(User::class); }
    public function event(): BelongsTo   { return $this->belongsTo(Event::class); }
    public function section(): BelongsTo { return $this->belongsTo(StadiumSection::class, 'section_id'); }

    public function isCompleted(): bool { return $this->status === 'completed'; }

    public function markCompleted(string $receipt, array $raw): bool
    {
        return $this->update([
            'status' => 'completed',
            'mpesa_receipt_number' => $receipt,
            'raw_callback' => $raw,
        ]);
    }

    public function markFailed(array $raw): bool
    {
        return $this->update(['status' => 'failed', 'raw_callback' => $raw]);
    }
}
