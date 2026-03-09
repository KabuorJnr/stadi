<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StadiumSection extends Model
{
    protected $fillable = [
        'name', 'code', 'capacity', 'current_occupancy', 'price_tier',
        'color', 'sort_order', 'svg_path_id', 'gate_number', 'description',
    ];

    protected function casts(): array
    {
        return [
            'capacity' => 'integer',
            'current_occupancy' => 'integer',
            'sort_order' => 'integer',
            'gate_number' => 'integer',
        ];
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class, 'section_id');
    }

    public function isFull(): bool
    {
        return $this->current_occupancy >= $this->capacity;
    }

    public function remainingSeats(): int
    {
        return max(0, $this->capacity - $this->current_occupancy);
    }

    public function occupancyPercent(): float
    {
        return $this->capacity > 0
            ? round(($this->current_occupancy / $this->capacity) * 100, 1)
            : 0;
    }

    /**
     * Get the ticket price for this section given an event's base price.
     */
    public function priceForEvent(Event $event): int
    {
        return $event->priceForTier($this->price_tier);
    }

    /**
     * Return the tier label (VIP, Premium, Regular, Economy).
     */
    public function tierLabel(): string
    {
        return config("stadium.price_tiers.{$this->price_tier}.label", ucfirst($this->price_tier));
    }
}
