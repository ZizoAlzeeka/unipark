<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'message',
        'type',
        'icon',
        'is_read',
        'action_url',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getTypeColorAttribute(): string
    {
        return match($this->type) {
            'success', 'reservation' => 'success',
            'warning' => 'warning',
            'error', 'violation' => 'danger',
            default => 'primary',
        };
    }

    public function getTypeIconAttribute(): string
    {
        return match($this->type) {
            'success' => 'check-circle',
            'reservation' => 'calendar-check',
            'warning' => 'exclamation-triangle',
            'error' => 'times-circle',
            'violation' => 'exclamation-circle',
            default => 'bell',
        };
    }

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function markAsRead(): void
    {
        $this->update(['is_read' => true]);
    }
}
