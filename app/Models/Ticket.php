<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'event_id', 'section_id', 'qr_hash', 'status',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function section(): BelongsTo
    {
        return $this->belongsTo(StadiumSection::class, 'section_id');
    }

    public function transaction(): HasOne
    {
        return $this->hasOne(Transaction::class);
    }

    public function gateLogs(): HasMany
    {
        return $this->hasMany(GateLog::class);
    }

    public function isActive(): bool  { return $this->status === 'active'; }
    public function isScanned(): bool { return $this->status === 'scanned'; }

    public function markScanned(): bool
    {
        return $this->update(['status' => 'scanned']);
    }

    public function cancel(): bool
    {
        return $this->update(['status' => 'cancelled']);
    }
}
