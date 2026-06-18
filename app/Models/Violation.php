<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Violation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'spot_id',
        'reservation_id',
        'type',
        'description',
        'vehicle_plate',
        'status',
        'fine_amount',
        'fine_paid',
        'violation_time',
        'resolution_notes',
    ];

    protected $casts = [
        'fine_amount' => 'decimal:2',
        'fine_paid' => 'boolean',
        'violation_time' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function spot()
    {
        return $this->belongsTo(ParkingSpot::class, 'spot_id');
    }

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'resolved' => 'success',
            'dismissed' => 'secondary',
            'appealed' => 'warning',
            default => 'danger',
        };
    }

    public function getTypeIconAttribute(): string
    {
        return match($this->type) {
            'unauthorized' => 'ban',
            'overstay' => 'clock',
            'wrong_spot' => 'map-marker-alt',
            'no_reservation' => 'times',
            'accessible_misuse' => 'wheelchair',
            default => 'exclamation',
        };
    }
}
