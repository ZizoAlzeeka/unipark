<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EntryExitLog;
use App\Models\ParkingSpot;
use App\Models\ParkingZone;
use App\Models\Reservation;
use App\Models\User;
use App\Models\Violation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        $dateFrom = request('date_from', now()->startOfMonth()->toDateString());
        $dateTo = request('date_to', now()->toDateString());

        $reservationStats = Reservation::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as total'),
            DB::raw("SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) as active"),
            DB::raw("SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed"),
            DB::raw("SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) as cancelled")
        )
            ->whereBetween('created_at', [$dateFrom, $dateTo . ' 23:59:59'])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $topUsers = User::withCount(['reservations' => function ($q) use ($dateFrom, $dateTo) {
            $q->whereBetween('created_at', [$dateFrom, $dateTo . ' 23:59:59']);
        }])
            ->orderBy('reservations_count', 'desc')
            ->take(10)
            ->get();

        $zoneUsage = ParkingZone::with(['spots.reservations' => function ($q) use ($dateFrom, $dateTo) {
            $q->whereBetween('created_at', [$dateFrom, $dateTo . ' 23:59:59']);
        }])->get()->map(function ($zone) {
            return [
                'name' => $zone->name,
                'code' => $zone->code,
                'total_reservations' => $zone->spots->sum(fn($s) => $s->reservations->count()),
                'total_spots' => $zone->spots->count(),
                'available_spots' => $zone->spots->where('status', 'available')->count(),
            ];
        });

        $peakHours = Reservation::select(
            DB::raw('HOUR(start_time) as hour'),
            DB::raw('COUNT(*) as count')
        )
            ->whereBetween('created_at', [$dateFrom, $dateTo . ' 23:59:59'])
            ->groupBy(DB::raw('HOUR(start_time)'))
            ->orderBy('hour')
            ->get();

        $violationStats = Violation::select(
            'type',
            DB::raw('COUNT(*) as count'),
            DB::raw('SUM(fine_amount) as total_fines')
        )
            ->whereBetween('created_at', [$dateFrom, $dateTo . ' 23:59:59'])
            ->groupBy('type')
            ->get();

        $summary = [
            'total_reservations' => Reservation::whereBetween('created_at', [$dateFrom, $dateTo . ' 23:59:59'])->count(),
            'total_users' => User::count(),
            'total_violations' => Violation::whereBetween('created_at', [$dateFrom, $dateTo . ' 23:59:59'])->count(),
            'total_fines' => Violation::whereBetween('created_at', [$dateFrom, $dateTo . ' 23:59:59'])->sum('fine_amount'),
            'avg_daily_reservations' => round(
                Reservation::whereBetween('created_at', [$dateFrom, $dateTo . ' 23:59:59'])->count() /
                max(1, now()->parse($dateFrom)->diffInDays($dateTo) + 1),
                1
            ),
        ];

        return view('admin.reports.index', compact(
            'reservationStats',
            'topUsers',
            'zoneUsage',
            'peakHours',
            'violationStats',
            'summary',
            'dateFrom',
            'dateTo'
        ));
    }

    public function exportCsv(Request $request)
    {
        $dateFrom = $request->input('date_from', now()->startOfMonth()->toDateString());
        $dateTo = $request->input('date_to', now()->toDateString());
        $type = $request->input('type', 'reservations');

        $filename = "{$type}_report_{$dateFrom}_to_{$dateTo}.csv";

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];

        $callback = function () use ($type, $dateFrom, $dateTo) {
            $file = fopen('php://output', 'w');

            if ($type === 'reservations') {
                fputcsv($file, ['ID', 'User', 'Email', 'Spot', 'Zone', 'Start Time', 'End Time', 'Status', 'Vehicle Plate', 'Created At']);
                Reservation::with(['user', 'spot.zone'])
                    ->whereBetween('created_at', [$dateFrom, $dateTo . ' 23:59:59'])
                    ->orderBy('created_at', 'desc')
                    ->chunk(100, function ($reservations) use ($file) {
                        foreach ($reservations as $r) {
                            fputcsv($file, [
                                $r->id,
                                $r->user->name ?? 'N/A',
                                $r->user->email ?? 'N/A',
                                $r->spot->spot_number ?? 'N/A',
                                $r->spot->zone->code ?? 'N/A',
                                $r->start_time->format('Y-m-d H:i'),
                                $r->end_time->format('Y-m-d H:i'),
                                $r->status,
                                $r->vehicle_plate,
                                $r->created_at->format('Y-m-d H:i'),
                            ]);
                        }
                    });
            } elseif ($type === 'violations') {
                fputcsv($file, ['ID', 'User', 'Vehicle Plate', 'Type', 'Spot', 'Status', 'Fine Amount', 'Fine Paid', 'Violation Time']);
                Violation::with(['user', 'spot.zone'])
                    ->whereBetween('created_at', [$dateFrom, $dateTo . ' 23:59:59'])
                    ->chunk(100, function ($violations) use ($file) {
                        foreach ($violations as $v) {
                            fputcsv($file, [
                                $v->id,
                                $v->user->name ?? 'Unknown',
                                $v->vehicle_plate,
                                $v->type,
                                $v->spot ? $v->spot->zone->code . '-' . $v->spot->spot_number : 'N/A',
                                $v->status,
                                $v->fine_amount,
                                $v->fine_paid ? 'Yes' : 'No',
                                $v->violation_time?->format('Y-m-d H:i'),
                            ]);
                        }
                    });
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
