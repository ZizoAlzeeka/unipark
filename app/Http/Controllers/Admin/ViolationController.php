<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\ParkingSpot;
use App\Models\Reservation;
use App\Models\User;
use App\Models\Violation;
use Illuminate\Http\Request;

class ViolationController extends Controller
{
    public function index(Request $request)
    {
        $query = Violation::with(['user', 'spot.zone']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('vehicle_plate', 'like', "%{$search}%")
                    ->orWhereHas('user', fn($u) => $u->where('name', 'like', "%{$search}%"));
            });
        }

        $violations = $query->orderBy('created_at', 'desc')->paginate(15);

        $stats = [
            'total' => Violation::count(),
            'pending' => Violation::where('status', 'pending')->count(),
            'resolved' => Violation::where('status', 'resolved')->count(),
            'total_fines' => Violation::where('status', 'pending')->sum('fine_amount'),
            'collected_fines' => Violation::where('fine_paid', true)->sum('fine_amount'),
        ];

        return view('admin.violations.index', compact('violations', 'stats'));
    }

    public function create()
    {
        $users = User::whereIn('role', ['student', 'staff'])->where('is_active', true)->get();
        $spots = ParkingSpot::with('zone')->where('is_active', true)->get();
        $reservations = Reservation::with(['user', 'spot.zone'])->where('status', 'active')->get();
        return view('admin.violations.create', compact('users', 'spots', 'reservations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:unauthorized,overstay,wrong_spot,no_reservation,accessible_misuse',
            'description' => 'nullable|string|max:1000',
            'vehicle_plate' => 'required|string|max:20',
            'fine_amount' => 'required|numeric|min:0',
            'user_id' => 'nullable|exists:users,id',
            'spot_id' => 'nullable|exists:parking_spots,id',
            'reservation_id' => 'nullable|exists:reservations,id',
            'violation_time' => 'required|date',
        ]);

        $violation = Violation::create([
            'user_id' => $request->user_id,
            'spot_id' => $request->spot_id,
            'reservation_id' => $request->reservation_id,
            'type' => $request->type,
            'description' => $request->description,
            'vehicle_plate' => strtoupper($request->vehicle_plate),
            'status' => 'pending',
            'fine_amount' => $request->fine_amount,
            'violation_time' => $request->violation_time,
        ]);

        if ($request->user_id) {
            Notification::create([
                'user_id' => $request->user_id,
                'title' => 'Parking Violation Issued',
                'message' => "A parking violation ({$request->type}) has been issued for vehicle {$request->vehicle_plate}. Fine amount: SAR {$request->fine_amount}.",
                'type' => 'violation',
                'icon' => 'exclamation-circle',
            ]);
        }

        return redirect()->route('admin.violations.index')->with('success', 'Violation recorded successfully.');
    }

    public function show(Violation $violation)
    {
        $violation->load(['user', 'spot.zone', 'reservation']);
        return view('admin.violations.show', compact('violation'));
    }

    public function resolve(Request $request, Violation $violation)
    {
        $request->validate(['resolution_notes' => 'nullable|string|max:500']);

        $violation->update([
            'status' => 'resolved',
            'resolution_notes' => $request->resolution_notes,
        ]);

        if ($violation->user_id) {
            Notification::create([
                'user_id' => $violation->user_id,
                'title' => 'Violation Resolved',
                'message' => "Your parking violation has been resolved. " . ($request->resolution_notes ?? ''),
                'type' => 'success',
                'icon' => 'check-circle',
            ]);
        }

        return back()->with('success', 'Violation resolved successfully.');
    }

    public function dismiss(Request $request, Violation $violation)
    {
        $request->validate(['resolution_notes' => 'nullable|string|max:500']);
        $violation->update(['status' => 'dismissed', 'resolution_notes' => $request->resolution_notes]);
        return back()->with('success', 'Violation dismissed.');
    }

    public function markFinePaid(Violation $violation)
    {
        $violation->update(['fine_paid' => true]);
        return back()->with('success', 'Fine marked as paid.');
    }
}
