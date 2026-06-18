<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ParkingSpot;
use App\Models\ParkingZone;
use Illuminate\Http\Request;

class ParkingSpotController extends Controller
{
    public function index(Request $request)
    {
        $query = ParkingSpot::with('zone');

        if ($request->filled('zone_id')) {
            $query->where('zone_id', $request->zone_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $spots = $query->orderBy('zone_id')->orderBy('spot_number')->paginate(20);
        $zones = ParkingZone::where('is_active', true)->get();

        $stats = [
            'total' => ParkingSpot::count(),
            'available' => ParkingSpot::where('status', 'available')->count(),
            'reserved' => ParkingSpot::where('status', 'reserved')->count(),
            'occupied' => ParkingSpot::where('status', 'occupied')->count(),
            'maintenance' => ParkingSpot::where('status', 'maintenance')->count(),
        ];

        return view('admin.spots.index', compact('spots', 'zones', 'stats'));
    }

    public function create()
    {
        $zones = ParkingZone::where('is_active', true)->get();
        return view('admin.spots.create', compact('zones'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'zone_id' => 'required|exists:parking_zones,id',
            'spot_number' => 'required|string|max:20',
            'type' => 'required|in:standard,staff,disabled,vip',
            'status' => 'required|in:available,reserved,occupied,maintenance',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ]);

        $exists = ParkingSpot::where('zone_id', $request->zone_id)
            ->where('spot_number', $request->spot_number)
            ->exists();

        if ($exists) {
            return back()->with('error', 'A spot with this number already exists in the selected zone.')->withInput();
        }

        ParkingSpot::create($request->only(['zone_id', 'spot_number', 'type', 'status', 'latitude', 'longitude']) + ['is_active' => true]);

        $zone = ParkingZone::find($request->zone_id);
        $zone->update(['total_spots' => $zone->spots()->count()]);

        return redirect()->route('admin.spots.index')->with('success', 'Parking spot created successfully.');
    }

    public function show(ParkingSpot $spot)
    {
        $spot->load('zone');
        return view('admin.spots.show', compact('spot'));
    }

    public function edit(ParkingSpot $spot)
    {
        $zones = ParkingZone::where('is_active', true)->get();
        return view('admin.spots.edit', compact('spot', 'zones'));
    }

    public function update(Request $request, ParkingSpot $spot)
    {
        $request->validate([
            'zone_id' => 'required|exists:parking_zones,id',
            'spot_number' => 'required|string|max:20',
            'type' => 'required|in:standard,staff,disabled,vip',
            'status' => 'required|in:available,reserved,occupied,maintenance',
            'is_active' => 'boolean',
        ]);

        $exists = ParkingSpot::where('zone_id', $request->zone_id)
            ->where('spot_number', $request->spot_number)
            ->where('id', '!=', $spot->id)
            ->exists();

        if ($exists) {
            return back()->with('error', 'A spot with this number already exists in the selected zone.')->withInput();
        }

        $spot->update([
            'zone_id' => $request->zone_id,
            'spot_number' => $request->spot_number,
            'type' => $request->type,
            'status' => $request->status,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.spots.index')->with('success', 'Parking spot updated successfully.');
    }

    public function destroy(ParkingSpot $spot)
    {
        if (in_array($spot->status, ['occupied', 'reserved'])) {
            return back()->with('error', 'Cannot delete an occupied or reserved spot.');
        }
        $zoneId = $spot->zone_id;
        $spot->delete();

        $zone = ParkingZone::find($zoneId);
        if ($zone) $zone->update(['total_spots' => $zone->spots()->count()]);

        return redirect()->route('admin.spots.index')->with('success', 'Parking spot deleted successfully.');
    }

    public function bulkCreate(Request $request)
    {
        $request->validate([
            'zone_id' => 'required|exists:parking_zones,id',
            'count' => 'required|integer|min:1|max:100',
            'type' => 'required|in:standard,staff,disabled,vip',
            'start_number' => 'required|integer|min:1',
        ]);

        $zone = ParkingZone::findOrFail($request->zone_id);
        $created = 0;

        for ($i = $request->start_number; $i < $request->start_number + $request->count; $i++) {
            $spotNumber = str_pad($i, 2, '0', STR_PAD_LEFT);
            if (!ParkingSpot::where('zone_id', $zone->id)->where('spot_number', $spotNumber)->exists()) {
                ParkingSpot::create([
                    'zone_id' => $zone->id,
                    'spot_number' => $spotNumber,
                    'type' => $request->type,
                    'status' => 'available',
                    'is_active' => true,
                ]);
                $created++;
            }
        }

        $zone->update(['total_spots' => $zone->spots()->count()]);

        return redirect()->route('admin.spots.index')->with('success', "{$created} parking spots created successfully.");
    }
}
