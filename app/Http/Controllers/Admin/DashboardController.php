<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EntryExitLog;
use App\Models\ParkingSpot;
use App\Models\ParkingZone;
use App\Models\Reservation;
use App\Models\User;
use App\Models\Violation;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'total_students' => User::where('role', 'student')->count(),
            'total_staff' => User::where('role', 'staff')->count(),
            'total_zones' => ParkingZone::where('is_active', true)->count(),
            'total_spots' => ParkingSpot::where('is_active', true)->count(),
            'available_spots' => ParkingSpot::where('status', 'available')->where('is_active', true)->count(),
            'reserved_spots' => ParkingSpot::where('status', 'reserved')->count(),
            'occupied_spots' => ParkingSpot::where('status', 'occupied')->count(),
            'active_reservations' => Reservation::where('status', 'active')->count(),
            'total_reservations_today' => Reservation::whereDate('created_at', today())->count(),
            'pending_violations' => Violation::where('status', 'pending')->count(),
            'total_violations' => Violation::count(),
        ];

        $occupancyRate = $stats['total_spots'] > 0
            ? round((($stats['reserved_spots'] + $stats['occupied_spots']) / $stats['total_spots']) * 100, 1)
            : 0;

        $zones = ParkingZone::where('is_active', true)->with('spots')->get();

        $recentReservations = Reservation::with(['user', 'spot.zone'])
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get();

        $recentLogs = EntryExitLog::with(['user', 'spot.zone'])
            ->orderBy('event_time', 'desc')
            ->take(8)
            ->get();

        $recentViolations = Violation::with(['user', 'spot.zone'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $weeklyReservations = Reservation::select(
            DB::raw('DAYNAME(created_at) as day'),
            DB::raw('COUNT(*) as count'),
            DB::raw('DAYOFWEEK(created_at) as dow')
        )
            ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->groupBy(DB::raw('DAYNAME(created_at)'), DB::raw('DAYOFWEEK(created_at)'))
            ->orderBy('dow')
            ->get();

        $monthlyStats = Reservation::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as count')
        )
            ->whereYear('created_at', now()->year)
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->orderBy('month')
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'occupancyRate',
            'zones',
            'recentReservations',
            'recentLogs',
            'recentViolations',
            'weeklyReservations',
            'monthlyStats'
        ));
    }
}
