<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    protected $fillable = [
        'order_code',
        'client_id',
        'shipment_id',
        'tracking_id',
        'quantity',
        'customer_name',
        'customer_phone',
        'customer_address',
        'label_file_path',
        'status',
        'scan_2_at',
        'scan_3_at',
    ];

    protected $casts = [
        'scan_2_at' => 'datetime',
        'scan_3_at' => 'datetime',
    ];

    // Relationships
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function shipment(): BelongsTo
    {
        return $this->belongsTo(Shipment::class);
    }

    public function settlement(): HasOne
    {
        return $this->hasOne(Settlement::class);
    }

    // Business Logic - Scan Operations
    public function performScan2(): void
    {
        $this->update([
            'status' => 'prepared_for_delivery',
            'scan_2_at' => now(),
        ]);
    }

    public function performScan3(): void
    {
        $this->update([
            'status' => 'handover_to_delivery_partner',
            'scan_3_at' => now(),
        ]);
    }

    public function canPerformScan2(): bool
    {
        return $this->status === 'pending_scan2';
    }

    public function canPerformScan3(): bool
    {
        return $this->status === 'prepared_for_delivery';
    }
}
