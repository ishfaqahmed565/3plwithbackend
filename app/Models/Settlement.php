<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Settlement extends Model
{
    protected $fillable = [
        'order_id',
        'client_id',
        'amount',
        'settlement_rule_id',
        'status',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    // Relationships
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    // Business Logic
    public function approve(): void
    {
        $this->update(['status' => 'approved']);
    }

    public function markAsPaid(): void
    {
        $this->update(['status' => 'paid']);
    }
}
