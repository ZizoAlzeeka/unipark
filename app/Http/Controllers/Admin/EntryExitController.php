<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EntryExitLog;
use App\Models\ParkingSpot;
use App\Models\ParkingZone;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Http\Request;

class EntryExitController extends Controller
{
    public function index(Request $request)
    {
        $query = EntryExitLog::with(['user', 'spot.zone', 'reservation']);

        if ($request->filled('event_type')) {
            $query->where('event_type', $request->event_type);
        }

        if ($request->filled('vehicle_plate')) {
            $query->where('vehicle_plate', 'like', "%{$request->vehicle_plate}%");
        }

        if ($request->filled('date')) {
            $query->whereDate('event_time', $request->date);
        }

        $logs = $query->orderBy('event_time', 'desc')->paginate(20);

        $stats = [
            'total_today' => EntryExitLog::whereDate('event_time', today())->count(),
            'entries_today' => EntryExitLog::where('event_type', 'entry')->whereDate('event_time', today())->count(),
            'exits_today' => EntryExitLog::where('event_type', 'exit')->whereDate('event_time', today())->count(),
            'total_all' => EntryExitLog::count(),
        ];

        return view('admin.entry-exit.index', compact('logs', 'stats'));
    }

    public function create()
    {
        $users = User::whereIn('role', ['student', 'staff'])->where('is_active', true)->get();
        $spots = ParkingSpot::with('zone')->where('is_active', true)->orderBy('zone_id')->orderBy('spot_number')->get();
        return view('admin.entry-exit.create', compact('users', 'spots'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'event_type' => 'required|in:entry,exit',
            'vehicle_plate' => 'required|string|max:20',
            'spot_id' => 'nullable|exists:parking_spots,id',
            'user_id' => 'nullable|exists:users,id',
            'reservation_id' => 'nullable|exists:reservations,id',
            'event_time' => 'required|date',
            'notes' => 'nullable|string|max:500',
        ]);

        EntryExitLog::create([
            'user_id' => $request->user_id,
            'spot_id' => $request->spot_id,
            'reservation_id' => $request->reservation_id,
            'event_type' => $request->event_type,
            'vehicle_plate' => strtoupper($request->vehicle_plate),
            'event_time' => $request->event_time,
            'notes' => $request->notes,
            'recorded_by' => auth()->user()->name,
        ]);

        if ($request->spot_id && $request->event_type === 'entry') {
            ParkingSpot::find($request->spot_id)->update(['status' => 'occupied']);
        } elseif ($request->spot_id && $request->event_type === 'exit') {
            ParkingSpot::find($request->spot_id)->update(['status' => 'available']);
        }

        return redirect()->route('admin.entry-exit.index')->with('success', 'Entry/Exit log recorded successfully.');
    }
}
