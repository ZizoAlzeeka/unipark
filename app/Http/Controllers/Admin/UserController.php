<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('university_id', 'like', "%{$search}%")
                    ->orWhere('vehicle_plate', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $users = $query->withCount(['reservations', 'violations'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $stats = [
            'total' => User::count(),
            'students' => User::where('role', 'student')->count(),
            'staff' => User::where('role', 'staff')->count(),
            'admins' => User::where('role', 'admin')->count(),
            'active' => User::where('is_active', true)->count(),
        ];

        return view('admin.users.index', compact('users', 'stats'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => ['required', Rules\Password::defaults()],
            'role' => 'required|in:student,staff,admin',
            'university_id' => 'nullable|string|max:50|unique:users',
            'phone' => 'nullable|string|max:20',
            'vehicle_plate' => 'nullable|string|max:20',
            'vehicle_model' => 'nullable|string|max:100',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'university_id' => $request->university_id,
            'phone' => $request->phone,
            'vehicle_plate' => strtoupper($request->vehicle_plate),
            'vehicle_model' => $request->vehicle_model,
            'is_active' => true,
        ]);

        Notification::create([
            'user_id' => $user->id,
            'title' => 'Account Created by Admin',
            'message' => "Your UniPark account has been created by the system administrator. Please log in with your credentials.",
            'type' => 'info',
            'icon' => 'user-check',
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    public function show(User $user)
    {
        $user->load(['reservations.spot.zone', 'violations.spot.zone', 'notifications']);
        $reservationStats = [
            'total' => $user->reservations()->count(),
            'active' => $user->reservations()->where('status', 'active')->count(),
            'completed' => $user->reservations()->where('status', 'completed')->count(),
            'cancelled' => $user->reservations()->where('status', 'cancelled')->count(),
        ];
        return view('admin.users.show', compact('user', 'reservationStats'));
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => "required|email|unique:users,email,{$user->id}",
            'role' => 'required|in:student,staff,admin',
            'university_id' => "nullable|string|max:50|unique:users,university_id,{$user->id}",
            'phone' => 'nullable|string|max:20',
            'vehicle_plate' => 'nullable|string|max:20',
            'vehicle_model' => 'nullable|string|max:100',
            'is_active' => 'boolean',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'university_id' => $request->university_id,
            'phone' => $request->phone,
            'vehicle_plate' => strtoupper($request->vehicle_plate),
            'vehicle_model' => $request->vehicle_model,
            'is_active' => $request->boolean('is_active'),
        ]);

        if ($request->filled('password')) {
            $request->validate(['password' => Rules\Password::defaults()]);
            $user->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }

    public function toggleStatus(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot deactivate your own account.');
        }
        $user->update(['is_active' => !$user->is_active]);
        $status = $user->is_active ? 'activated' : 'deactivated';
        return back()->with('success', "User {$status} successfully.");
    }
}
