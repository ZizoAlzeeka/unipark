<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'spot_id',
        'start_time',
        'end_time',
        'status',
        'vehicle_plate',
        'notes',
        'cancelled_at',
        'cancellation_reason',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function spot()
    {
        return $this->belongsTo(ParkingSpot::class, 'spot_id');
    }

    public function entryExitLogs()
    {
        return $this->hasMany(EntryExitLog::class);
    }

    public function violations()
    {
        return $this->hasMany(Violation::class);
    }

    public function getDurationAttribute(): string
    {
        $diff = $this->start_time->diff($this->end_time);
        if ($diff->h > 0) {
            return $diff->h . 'h ' . $diff->i . 'm';
        }
        return $diff->i . ' minutes';
    }

    public function getRemainingTimeAttribute(): ?string
    {
        if ($this->status !== 'active') return null;
        if (now()->gt($this->end_time)) return 'Expired';
        $diff = now()->diff($this->end_time);
        if ($diff->h > 0) {
            return $diff->h . 'h ' . $diff->i . 'm remaining';
        }
        return $diff->i . 'm remaining';
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'active' => 'success',
            'completed' => 'primary',
            'cancelled' => 'danger',
            'expired' => 'warning',
            default => 'secondary',
        };
    }

    public function canBeCancelled(): bool
    {
        return $this->status === 'active' && now()->lt($this->start_time);
    }
}
