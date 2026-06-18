<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntryExitLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'spot_id',
        'reservation_id',
        'event_type',
        'vehicle_plate',
        'vehicle_model',
        'event_time',
        'notes',
        'recorded_by',
    ];

    protected $casts = [
        'event_time' => 'datetime',
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

    public function getEventTypeIconAttribute(): string
    {
        return $this->event_type === 'entry' ? 'arrow-right-circle' : 'arrow-left-circle';
    }

    public function getEventTypeColorAttribute(): string
    {
        return $this->event_type === 'entry' ? 'success' : 'danger';
    }
}
