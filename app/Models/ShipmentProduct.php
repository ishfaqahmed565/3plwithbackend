<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShipmentProduct extends Model
{
    protected $fillable = [
        'shipment_id',
        'name',
        'description',
        'quantity_expected',
        'quantity_available',
        'received_quantity',
        'product_condition',
        'rack_location',
        'remarks',
        'notes',
    ];

    public function shipment(): BelongsTo
    {
        return $this->belongsTo(Shipment::class);
    }

    public function canCreateOrder(int $quantity): bool
    {
        return $this->quantity_available >= $quantity && $this->shipment->status === 'received_in_warehouse';
    }

    public function decreaseInventory(int $quantity): void
    {
        $this->decrement('quantity_available', $quantity);
    }
}
