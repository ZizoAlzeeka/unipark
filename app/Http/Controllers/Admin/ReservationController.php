<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Reservation;
use App\Models\User;
use App\Models\ParkingSpot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReservationController extends Controller
{
    public function index(Request $request)
    {
        $query = Reservation::with(['user', 'spot.zone']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            })->orWhere('vehicle_plate', 'like', "%{$search}%");
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->where('start_time', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('end_time', '<=', $request->date_to . ' 23:59:59');
        }

        $reservations = $query->orderBy('created_at', 'desc')->paginate(15);

        $stats = [
            'total' => Reservation::count(),
            'active' => Reservation::where('status', 'active')->count(),
            'completed' => Reservation::where('status', 'completed')->count(),
            'cancelled' => Reservation::where('status', 'cancelled')->count(),
            'today' => Reservation::whereDate('created_at', today())->count(),
        ];

        return view('admin.reservations.index', compact('reservations', 'stats'));
    }

    public function show(Reservation $reservation)
    {
        $reservation->load(['user', 'spot.zone', 'entryExitLogs.user', 'violations']);
        return view('admin.reservations.show', compact('reservation'));
    }

    public function cancel(Request $request, Reservation $reservation)
    {
        if (!in_array($reservation->status, ['active'])) {
            return back()->with('error', 'Only active reservations can be cancelled.');
        }

        $request->validate(['cancellation_reason' => 'nullable|string|max:500']);

        DB::transaction(function () use ($reservation, $request) {
            $reservation->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
                'cancellation_reason' => $request->cancellation_reason ?? 'Cancelled by admin',
            ]);
            $reservation->spot->update(['status' => 'available']);

            Notification::create([
                'user_id' => $reservation->user_id,
                'title' => 'Reservation Cancelled by Admin',
                'message' => "Your reservation for spot {$reservation->spot->zone->code}-{$reservation->spot->spot_number} has been cancelled by the administrator.",
                'type' => 'warning',
                'icon' => 'calendar-times',
            ]);
        });

        return back()->with('success', 'Reservation cancelled successfully.');
    }

    public function complete(Reservation $reservation)
    {
        if ($reservation->status !== 'active') {
            return back()->with('error', 'Only active reservations can be marked as completed.');
        }

        DB::transaction(function () use ($reservation) {
            $reservation->update(['status' => 'completed']);
            $reservation->spot->update(['status' => 'available']);
        });

        return back()->with('success', 'Reservation marked as completed.');
    }
}
