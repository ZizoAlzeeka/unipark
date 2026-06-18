<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
    use HasFactory;

    protected $fillable = [
        'role',
        'permission',
        'module',
        'is_granted',
    ];

    protected $casts = [
        'is_granted' => 'boolean',
    ];

    public static function hasPermission(string $role, string $permission): bool
    {
        $record = static::where('role', $role)->where('permission', $permission)->first();
        if (!$record) return false;
        return $record->is_granted;
    }

    public static function getPermissionsForRole(string $role): array
    {
        return static::where('role', $role)
            ->get()
            ->mapWithKeys(fn($p) => [$p->permission => $p->is_granted])
            ->toArray();
    }
}
