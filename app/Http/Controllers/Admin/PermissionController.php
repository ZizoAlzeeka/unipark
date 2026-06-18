<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RolePermission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function index()
    {
        $roles = ['student', 'staff', 'admin'];
        $modules = [
            'Parking' => ['view_parking_map'],
            'Reservation' => ['create_reservation', 'view_own_reservations', 'cancel_reservation', 'view_all_reservations'],
            'Profile' => ['edit_own_profile'],
            'Notification' => ['view_notifications'],
            'History' => ['view_own_history'],
            'Admin' => ['access_admin_panel', 'manage_users', 'manage_zones'],
            'Report' => ['generate_reports'],
        ];

        $permissions = [];
        foreach ($roles as $role) {
            $permissions[$role] = RolePermission::where('role', $role)
                ->get()
                ->mapWithKeys(fn($p) => [$p->permission => $p->is_granted])
                ->toArray();
        }

        $permissionLabels = [
            'view_parking_map' => 'View Parking Map',
            'create_reservation' => 'Create Reservation',
            'view_own_reservations' => 'View Own Reservations',
            'cancel_reservation' => 'Cancel Reservation',
            'view_all_reservations' => 'View All Reservations',
            'edit_own_profile' => 'Edit Own Profile',
            'view_notifications' => 'View Notifications',
            'view_own_history' => 'View Own History',
            'access_admin_panel' => 'Access Admin Panel',
            'manage_users' => 'Manage Users',
            'manage_zones' => 'Manage Parking Zones',
            'generate_reports' => 'Generate Reports',
        ];

        return view('admin.permissions.index', compact('roles', 'modules', 'permissions', 'permissionLabels'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'permissions' => 'array',
            'permissions.*.role' => 'required|in:student,staff,admin',
            'permissions.*.permission' => 'required|string',
        ]);

        $allPermissions = [
            'view_parking_map', 'create_reservation', 'view_own_reservations',
            'cancel_reservation', 'view_all_reservations', 'edit_own_profile',
            'view_notifications', 'view_own_history', 'access_admin_panel',
            'manage_users', 'manage_zones', 'generate_reports',
        ];
        $roles = ['student', 'staff', 'admin'];

        $submitted = collect($request->input('permissions', []));

        foreach ($roles as $role) {
            foreach ($allPermissions as $permission) {
                $isGranted = $submitted->contains(function ($item) use ($role, $permission) {
                    return $item['role'] === $role && $item['permission'] === $permission;
                });

                RolePermission::where('role', $role)
                    ->where('permission', $permission)
                    ->update(['is_granted' => $isGranted]);
            }
        }

        return back()->with('success', 'Permissions updated successfully.');
    }

    public function toggle(Request $request)
    {
        $request->validate([
            'role' => 'required|in:student,staff,admin',
            'permission' => 'required|string',
            'is_granted' => 'required|boolean',
        ]);

        RolePermission::where('role', $request->role)
            ->where('permission', $request->permission)
            ->update(['is_granted' => $request->is_granted]);

        return response()->json([
            'success' => true,
            'is_granted' => $request->is_granted,
            'message' => 'Permission ' . ($request->is_granted ? 'granted' : 'revoked') . ' successfully.',
        ]);
    }
}
