<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $reservationStats = [
            'total' => $user->reservations()->count(),
            'active' => $user->reservations()->where('status', 'active')->count(),
            'completed' => $user->reservations()->where('status', 'completed')->count(),
            'cancelled' => $user->reservations()->where('status', 'cancelled')->count(),
        ];
        return view('user.profile', compact('user', 'reservationStats'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'vehicle_plate' => 'nullable|string|max:20',
            'vehicle_model' => 'nullable|string|max:100',
            'vehicle_color' => 'nullable|string|max:50',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = [
            'name' => $request->name,
            'phone' => $request->phone,
            'vehicle_plate' => strtoupper($request->vehicle_plate),
            'vehicle_model' => $request->vehicle_model,
            'vehicle_color' => $request->vehicle_color,
        ];

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update($data);

        return back()->with('success', 'Profile updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        if (!Hash::check($request->current_password, Auth::user()->password)) {
            return back()->withErrors(['current_password' => 'The current password is incorrect.']);
        }

        Auth::user()->update(['password' => Hash::make($request->password)]);

        Notification::create([
            'user_id' => Auth::id(),
            'title' => 'Password Changed',
            'message' => 'Your password has been changed successfully. If you did not make this change, please contact support immediately.',
            'type' => 'warning',
            'icon' => 'lock',
        ]);

        return back()->with('success', 'Password changed successfully.');
    }
}
