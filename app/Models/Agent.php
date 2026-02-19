<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Agent extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'warehouse',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'warehouse' => 'integer',
        ];
    }
    
    // Warehouse constants
    const WAREHOUSE_NEW_YORK = 1;
    const WAREHOUSE_LONG_ISLAND = 2;
    const WAREHOUSE_CALIFORNIA = 3;
    
    // Get warehouse name
    public function getWarehouseNameAttribute(): string
    {
        return match($this->warehouse) {
            self::WAREHOUSE_NEW_YORK => 'New York',
            self::WAREHOUSE_LONG_ISLAND => 'Long Island',
            self::WAREHOUSE_CALIFORNIA => 'California',
            default => 'Unknown',
        };
    }
    
    // Get all warehouses for dropdown
    public static function getWarehouses(): array
    {
        return [
            self::WAREHOUSE_NEW_YORK => 'New York',
            self::WAREHOUSE_LONG_ISLAND => 'Long Island',
            self::WAREHOUSE_CALIFORNIA => 'California',
        ];
    }
}
