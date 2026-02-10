<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\ShipmentAttachment;
use App\Models\ShipmentLineItem;

class Shipment extends Model
{
    protected $fillable = [
        'shipment_code',
        'client_id',
        'tracking_id',
        'source',
        'delivery_partner',
        'product_name',
        'product_description',
        'category',
        'product_image_path',
        'quantity_total',
        'quantity_available',
        'status',
        'scan1_at',
        'rack_location_id',
        'received_quantity',
        'product_condition',
        'scan1_notes',
        'scan1_image_path',
    ];

    protected $casts = [
        'scan1_at' => 'datetime',
    ];

    // Relationships
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function rackLocation(): BelongsTo
    {
        return $this->belongsTo(RackLocation::class);
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(ShipmentAttachment::class);
    }

    public function lineItems(): HasMany
    {
        return $this->hasMany(ShipmentLineItem::class);
    }

    // Business Logic
    public function canCreateOrder(int $quantity): bool
    {
        return $this->quantity_available >= $quantity && $this->status === 'received_in_warehouse';
    }

    public function decreaseInventory(int $quantity): void
    {
        $this->decrement('quantity_available', $quantity);
    }

    public function markAsReceived(): void
    {
        $this->update([
            'status' => 'received_in_warehouse',
            'received_at' => now(),
        ]);
    }
}
