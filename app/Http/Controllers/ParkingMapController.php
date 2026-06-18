<?php

namespace App\Http\Controllers;

use App\Models\ParkingSpot;
use App\Models\ParkingZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParkingMapController extends Controller
{
    public function index()
    {
        $zones = ParkingZone::where('is_active', true)->with(['spots' => function ($query) {
            $query->where('is_active', true)->orderBy('spot_number');
        }])->get();

        $googleMapsKey = config('services.google_maps.key');

        return view('user.parking-map', compact('zones', 'googleMapsKey'));
    }

    public function getSpots(Request $request)
    {
        $zoneId = $request->input('zone_id');

        $query = ParkingSpot::where('is_active', true)->with('zone');

        if ($zoneId) {
            $query->where('zone_id', $zoneId);
        }

        $spots = $query->get()->map(function ($spot) {
            return [
                'id' => $spot->id,
                'zone_id' => $spot->zone_id,
                'zone_code' => $spot->zone->code,
                'spot_number' => $spot->spot_number,
                'full_name' => $spot->zone->code . '-' . $spot->spot_number,
                'type' => $spot->type,
                'status' => $spot->status,
                'latitude' => $spot->latitude,
                'longitude' => $spot->longitude,
            ];
        });

        return response()->json(['spots' => $spots]);
    }

    public function getZones()
    {
        $zones = ParkingZone::where('is_active', true)->with('spots')->get()->map(function ($zone) {
            return [
                'id' => $zone->id,
                'name' => $zone->name,
                'code' => $zone->code,
                'latitude' => $zone->latitude,
                'longitude' => $zone->longitude,
                'total_spots' => $zone->spots->count(),
                'available' => $zone->spots->where('status', 'available')->count(),
                'reserved' => $zone->spots->where('status', 'reserved')->count(),
                'occupied' => $zone->spots->where('status', 'occupied')->count(),
                'occupancy_rate' => $zone->occupancy_rate,
            ];
        });

        return response()->json(['zones' => $zones]);
    }

    public function getSpotDetails(ParkingSpot $spot)
    {
        $activeReservation = $spot->activeReservation();

        return response()->json([
            'id' => $spot->id,
            'full_name' => $spot->zone->code . '-' . $spot->spot_number,
            'type' => $spot->type,
            'status' => $spot->status,
            'zone' => [
                'name' => $spot->zone->name,
                'code' => $spot->zone->code,
            ],
            'active_reservation' => $activeReservation ? [
                'id' => $activeReservation->id,
                'user_name' => $activeReservation->user->name,
                'start_time' => $activeReservation->start_time->format('Y-m-d H:i'),
                'end_time' => $activeReservation->end_time->format('Y-m-d H:i'),
            ] : null,
        ]);
    }
}
