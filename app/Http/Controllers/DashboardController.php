<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\ParkingSpot;
use App\Models\ParkingZone;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $activeReservations = Reservation::where('user_id', $user->id)
            ->where('status', 'active')
            ->with(['spot.zone'])
            ->orderBy('start_time')
            ->get();

        $upcomingReservations = Reservation::where('user_id', $user->id)
            ->where('status', 'active')
            ->where('start_time', '>', now())
            ->with(['spot.zone'])
            ->orderBy('start_time')
            ->take(3)
            ->get();

        $totalReservations = Reservation::where('user_id', $user->id)->count();
        $completedReservations = Reservation::where('user_id', $user->id)->where('status', 'completed')->count();

        $availableSpots = ParkingSpot::where('status', 'available')->where('is_active', true)->count();
        $totalSpots = ParkingSpot::where('is_active', true)->count();

        $zones = ParkingZone::where('is_active', true)->with('spots')->get();

        $recentNotifications = Notification::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $unreadCount = Notification::where('user_id', $user->id)->where('is_read', false)->count();

        return view('user.dashboard', compact(
            'user',
            'activeReservations',
            'upcomingReservations',
            'totalReservations',
            'completedReservations',
            'availableSpots',
            'totalSpots',
            'zones',
            'recentNotifications',
            'unreadCount'
        ));
    }
}
