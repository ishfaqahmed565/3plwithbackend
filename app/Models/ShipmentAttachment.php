<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShipmentAttachment extends Model
{
    protected $fillable = [
        'shipment_id',
        'uploaded_by',
        'context',
        'file_path',
        'original_name',
        'mime_type',
    ];

    public function shipment(): BelongsTo
    {
        return $this->belongsTo(Shipment::class);
    }
}
