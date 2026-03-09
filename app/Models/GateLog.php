<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GateLog extends Model
{
    public $timestamps = false;

    protected $fillable = ['ticket_id', 'gate_number', 'scanned_at', 'scanner_device_id'];

    protected function casts(): array
    {
        return ['gate_number' => 'integer', 'scanned_at' => 'datetime'];
    }

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }
}
