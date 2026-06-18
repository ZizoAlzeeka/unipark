<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\ParkingSpot;
use App\Models\ParkingZone;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReservationCreatedUserMail;
use App\Mail\ReservationCreatedAdminMail;
use App\Models\User;

class ReservationController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $reservations = Reservation::where('user_id', $user->id)
            ->with(['spot.zone'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('user.reservations', compact('reservations'));
    }

    public function create(Request $request)
    {
        $zones = ParkingZone::where('is_active', true)
            ->with(['spots' => function ($q) {
                $q->where('is_active', true)->where('status', 'available')->orderBy('spot_number');
            }])
            ->get();

        $selectedSpot = null;
        if ($request->has('spot_id')) {
            $selectedSpot = ParkingSpot::with('zone')->find($request->spot_id);
        }

        $zonesData = $zones->map(function ($z) {
            return [
                'id' => $z->id,
                'name' => $z->name,
                'code' => $z->code,
                'spots' => $z->spots->map(function ($s) {
                    return [
                        'id' => $s->id,
                        'spot_number' => $s->spot_number,
                        'type' => $s->type,
                        'status' => $s->status,
                    ];
                })->values()->toArray(),
            ];
        })->values()->toArray();

        return view('user.reserve', compact('zones', 'selectedSpot', 'zonesData'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'spot_id' => 'required|exists:parking_spots,id',
            'start_time' => 'required|date|after:now',
            'end_time' => 'required|date|after:start_time',
            'vehicle_plate' => 'nullable|string|max:20',
            'notes' => 'nullable|string|max:500',
        ]);

        $spot = ParkingSpot::findOrFail($request->spot_id);

        if (!$spot->is_active || $spot->status !== 'available') {
            return back()->with('error', 'This parking spot is no longer available.')->withInput();
        }

        // Conflict detection
        $conflict = Reservation::where('spot_id', $spot->id)
            ->where('status', 'active')
            ->where(function ($q) use ($request) {
                $q->whereBetween('start_time', [$request->start_time, $request->end_time])
                    ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                    ->orWhere(function ($q2) use ($request) {
                        $q2->where('start_time', '<=', $request->start_time)
                            ->where('end_time', '>=', $request->end_time);
                    });
            })
            ->exists();

        if ($conflict) {
            return back()->with('error', 'This spot is already reserved for the selected time period.')->withInput();
        }

        DB::transaction(function () use ($request, $spot) {
            $reservation = Reservation::create([
                'user_id' => Auth::id(),
                'spot_id' => $spot->id,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'status' => 'active',
                'vehicle_plate' => strtoupper($request->vehicle_plate ?? Auth::user()->vehicle_plate),
                'notes' => $request->notes,
            ]);

            $spot->update(['status' => 'reserved']);

            Notification::create([
                'user_id' => Auth::id(),
                'title' => 'Reservation Confirmed!',
                'message' => "Your reservation for spot {$spot->zone->code}-{$spot->spot_number} has been confirmed from " .
                    date('M d, H:i', strtotime($request->start_time)) . " to " .
                    date('M d, H:i', strtotime($request->end_time)) . ".",
                'type' => 'reservation',
                'icon' => 'calendar-check',
                'action_url' => route('reservations.show', $reservation->id),
            ]);

            // Send Email to User
            Mail::to(Auth::user()->email)->send(new ReservationCreatedUserMail($reservation));

            // Send Email to Admins
            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                Mail::to($admin->email)->send(new ReservationCreatedAdminMail($reservation));
            }
        });

        return redirect()->route('reservations.index')->with('success', 'Reservation created successfully!');
    }

    public function show(Reservation $reservation)
    {
        if ($reservation->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403);
        }
        $reservation->load(['spot.zone', 'entryExitLogs']);
        return view('user.reservation-detail', compact('reservation'));
    }

    public function cancel(Request $request, Reservation $reservation)
    {
        if ($reservation->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403);
        }

        if (!$reservation->canBeCancelled() && !Auth::user()->isAdmin()) {
            return back()->with('error', 'This reservation cannot be cancelled as it has already started.');
        }

        $request->validate(['cancellation_reason' => 'nullable|string|max:500']);

        DB::transaction(function () use ($reservation, $request) {
            $reservation->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
                'cancellation_reason' => $request->cancellation_reason ?? 'Cancelled by user',
            ]);

            $reservation->spot->update(['status' => 'available']);

            Notification::create([
                'user_id' => $reservation->user_id,
                'title' => 'Reservation Cancelled',
                'message' => "Your reservation for spot {$reservation->spot->zone->code}-{$reservation->spot->spot_number} has been cancelled.",
                'type' => 'warning',
                'icon' => 'calendar-times',
            ]);
        });

        return redirect()->route('reservations.index')->with('success', 'Reservation cancelled successfully.');
    }

    public function history()
    {
        $user = Auth::user();
        $reservations = Reservation::where('user_id', $user->id)
            ->whereIn('status', ['completed', 'cancelled', 'expired'])
            ->with(['spot.zone'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('user.history', compact('reservations'));
    }
}
