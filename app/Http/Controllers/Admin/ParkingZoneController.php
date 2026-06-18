<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ParkingZone;
use Illuminate\Http\Request;

class ParkingZoneController extends Controller
{
    public function index(Request $request)
    {
        $query = ParkingZone::with('spots');

        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%")
                ->orWhere('code', 'like', "%{$request->search}%");
        }

        $zones = $query->orderBy('code')->paginate(10);

        return view('admin.zones.index', compact('zones'));
    }

    public function create()
    {
        return view('admin.zones.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:parking_zones',
            'description' => 'nullable|string|max:1000',
            'floor' => 'nullable|string|max:50',
            'location' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ]);

        $zone = ParkingZone::create([
            'name' => $request->name,
            'code' => strtoupper($request->code),
            'description' => $request->description,
            'floor' => $request->floor,
            'location' => $request->location,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'total_spots' => 0,
            'is_active' => true,
        ]);

        return redirect()->route('admin.zones.index')->with('success', "Zone {$zone->code} created successfully.");
    }

    public function show(ParkingZone $zone)
    {
        $zone->load(['spots' => function ($q) {
            $q->orderBy('spot_number');
        }]);
        return view('admin.zones.show', compact('zone'));
    }

    public function edit(ParkingZone $zone)
    {
        return view('admin.zones.edit', compact('zone'));
    }

    public function update(Request $request, ParkingZone $zone)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => "required|string|max:10|unique:parking_zones,code,{$zone->id}",
            'description' => 'nullable|string|max:1000',
            'floor' => 'nullable|string|max:50',
            'location' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'is_active' => 'boolean',
        ]);

        $zone->update([
            'name' => $request->name,
            'code' => strtoupper($request->code),
            'description' => $request->description,
            'floor' => $request->floor,
            'location' => $request->location,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.zones.index')->with('success', "Zone updated successfully.");
    }

    public function destroy(ParkingZone $zone)
    {
        if ($zone->spots()->whereIn('status', ['occupied', 'reserved'])->exists()) {
            return back()->with('error', 'Cannot delete zone with active or reserved spots.');
        }
        $zone->delete();
        return redirect()->route('admin.zones.index')->with('success', "Zone deleted successfully.");
    }
}
