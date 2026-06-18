<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParkingSpot extends Model
{
    use HasFactory;

    protected $fillable = [
        'zone_id',
        'spot_number',
        'type',
        'status',
        'latitude',
        'longitude',
        'is_active',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'is_active' => 'boolean',
    ];

    public function zone()
    {
        return $this->belongsTo(ParkingZone::class, 'zone_id');
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'spot_id');
    }

    public function activeReservation()
    {
        return $this->reservations()
            ->where('status', 'active')
            ->where('start_time', '<=', now())
            ->where('end_time', '>=', now())
            ->first();
    }

    public function violations()
    {
        return $this->hasMany(Violation::class, 'spot_id');
    }

    public function entryExitLogs()
    {
        return $this->hasMany(EntryExitLog::class, 'spot_id');
    }

    public function getFullNameAttribute(): string
    {
        return $this->zone->code . '-' . $this->spot_number;
    }

    public function isAvailable(): bool
    {
        return $this->status === 'available' && $this->is_active;
    }

    public function getTypeColorAttribute(): string
    {
        return match($this->type) {
            'staff' => '#4facfe',
            'disabled' => '#43e97b',
            'vip' => '#f5a623',
            default => '#667eea',
        };
    }
}
