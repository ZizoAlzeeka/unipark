<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'university_id',
        'phone',
        'vehicle_plate',
        'vehicle_model',
        'vehicle_color',
        'avatar',
        'is_active',
        'verification_code',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
    ];

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function violations()
    {
        return $this->hasMany(Violation::class);
    }

    public function entryExitLogs()
    {
        return $this->hasMany(EntryExitLog::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isStaff(): bool
    {
        return $this->role === 'staff';
    }

    public function isStudent(): bool
    {
        return $this->role === 'student';
    }

    public function getUnreadNotificationsCountAttribute(): int
    {
        return $this->notifications()->where('is_read', false)->count();
    }

    public function activeReservation()
    {
        return $this->reservations()
            ->where('status', 'active')
            ->where('start_time', '<=', now())
            ->where('end_time', '>=', now())
            ->first();
    }

    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }
        $name = urlencode($this->name);
        return "https://ui-avatars.com/api/?name={$name}&background=6C63FF&color=fff&size=128&bold=true";
    }
}
