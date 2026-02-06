<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RackLocation extends Model
{
    protected $fillable = [
        'code',
        'zone',
        'aisle',
        'rack',
        'status',
    ];

    public function shipments()
    {
        return $this->hasMany(Shipment::class);
    }

    public function markAsOccupied()
    {
        $this->update(['status' => 'occupied']);
    }

    public function markAsAvailable()
    {
        $this->update(['status' => 'available']);
    }
}
