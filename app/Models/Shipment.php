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
        'created_by_agent_id',
        'created_by_admin_id',
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
        'rack_location',
        'received_quantity',
        'product_condition',
        'scan1_notes',
        'scan1_image_path',
        'received_by_agent_id',
        'received_in_warehouse',
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

    public function products(): HasMany
    {
        return $this->hasMany(ShipmentProduct::class);
    }

    public function receivedByAgent(): BelongsTo
    {
        return $this->belongsTo(Agent::class, 'received_by_agent_id');
    }

    public function createdByAgent(): BelongsTo
    {
        return $this->belongsTo(Agent::class, 'created_by_agent_id');
    }

    public function createdByAdmin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'created_by_admin_id');
    }

    public function getWarehouseNameAttribute(): ?string
    {
        if (!$this->received_in_warehouse) {
            return null;
        }
        return match($this->received_in_warehouse) {
            1 => 'New York',
            2 => 'Long Island',
            3 => 'California',
            default => 'Unknown',
        };
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
