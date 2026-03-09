<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'event_date', 'base_ticket_price', 'max_capacity',
        'current_attendance', 'status', 'ticket_sales_open',
        'home_team', 'away_team', 'competition', 'poster_url',
    ];

    protected function casts(): array
    {
        return [
            'event_date' => 'datetime',
            'base_ticket_price' => 'integer',
            'max_capacity' => 'integer',
            'current_attendance' => 'integer',
            'ticket_sales_open' => 'boolean',
        ];
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function isSoldOut(): bool
    {
        return $this->current_attendance >= $this->max_capacity;
    }

    public function canSellTickets(): bool
    {
        return $this->ticket_sales_open && !$this->isSoldOut()
            && in_array($this->status, ['upcoming', 'ongoing']);
    }

    public function remainingCapacity(): int
    {
        return max(0, $this->max_capacity - $this->current_attendance);
    }

    /**
     * Calculate the price for a given tier using the base price + multiplier.
     */
    public function priceForTier(string $tier): int
    {
        $multiplier = config("stadium.price_tiers.{$tier}.multiplier", 1.0);
        return (int) round($this->base_ticket_price * $multiplier);
    }

    public function matchTitle(): string
    {
        if ($this->home_team && $this->away_team) {
            return "{$this->home_team} vs {$this->away_team}";
        }
        return $this->name;
    }
}
