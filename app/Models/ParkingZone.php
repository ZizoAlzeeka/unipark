<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParkingZone extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
        'total_spots',
        'floor',
        'location',
        'latitude',
        'longitude',
        'is_active',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'is_active' => 'boolean',
        'total_spots' => 'integer',
    ];

    public function spots()
    {
        return $this->hasMany(ParkingSpot::class, 'zone_id');
    }

    public function getAvailableSpotsCountAttribute(): int
    {
        return $this->spots()->where('status', 'available')->where('is_active', true)->count();
    }

    public function getOccupiedSpotsCountAttribute(): int
    {
        return $this->spots()->where('status', 'occupied')->count();
    }

    public function getReservedSpotsCountAttribute(): int
    {
        return $this->spots()->where('status', 'reserved')->count();
    }

    public function getOccupancyRateAttribute(): float
    {
        $total = $this->spots()->where('is_active', true)->count();
        if ($total === 0) return 0;
        $occupied = $this->spots()->whereIn('status', ['occupied', 'reserved'])->count();
        return round(($occupied / $total) * 100, 1);
    }
}
