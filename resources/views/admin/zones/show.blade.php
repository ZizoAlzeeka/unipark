@extends('layouts.admin')

@section('title', 'Zone: ' . $zone->name)
@section('page-title', 'Zone Details: ' . $zone->name)

@section('content')
<div>
    <div class="mb-16">
        <a href="{{ route('admin.zones.index') }}" class="btn btn-ghost btn-sm">
            <i class="fas fa-arrow-left"></i> Back to Zones
        </a>
    </div>

    <div class="grid grid-2" style="gap:24px;align-items:start;">
        <div>
            <div class="card mb-20">
                <div style="background:var(--grad-primary);border-radius:var(--radius-md);padding:24px;margin-bottom:20px;position:relative;overflow:hidden;">
                    <div style="font-size:64px;font-weight:900;color:rgba(255,255,255,0.12);position:absolute;right:16px;top:8px;line-height:1;">{{ $zone->code }}</div>
                    <div style="color:#fff;">
                        <div style="font-size:11px;font-weight:600;opacity:0.8;text-transform:uppercase;letter-spacing:0.8px;">Zone {{ $zone->code }}</div>
                        <div style="font-size:24px;font-weight:800;margin-top:4px;">{{ $zone->name }}</div>
                        @if($zone->location)
                        <div style="font-size:13px;opacity:0.8;margin-top:6px;"><i class="fas fa-map-marker-alt"></i> {{ $zone->location }}</div>
                        @endif
                    </div>
                </div>

                @php
                    $total = $zone->spots->count();
                    $available = $zone->spots->where('status','available')->count();
                    $occupied = $zone->spots->where('status','occupied')->count();
                    $reserved = $zone->spots->where('status','reserved')->count();
                    $rate = $total > 0 ? round((($occupied + $reserved) / $total) * 100) : 0;
                @endphp

                <div style="display:grid;grid-template-columns:1fr 1fr 1fr 1fr;gap:12px;margin-bottom:20px;">
                    @foreach([[$available,'Free','var(--success)'],[$reserved,'Reserved','var(--warning)'],[$occupied,'Occupied','var(--danger)'],[$total,'Total','var(--primary)']] as [$val,$label,$color])
                    <div style="text-align:center;padding:14px;background:rgba(108,99,255,0.03);border-radius:var(--radius-md);border:1px solid var(--border-color);">
                        <div style="font-size:22px;font-weight:800;color:{{ $color }};">{{ $val }}</div>
                        <div style="font-size:11px;color:var(--text-muted);font-weight:600;">{{ $label }}</div>
                    </div>
                    @endforeach
                </div>

                <div class="progress mb-8"><div class="progress-bar {{ $rate < 50 ? 'success' : ($rate < 80 ? 'warning' : 'danger') }}" style="width:{{ $rate }}%;"></div></div>
                <div style="font-size:12px;color:var(--text-muted);text-align:center;margin-bottom:20px;">{{ $rate }}% occupied</div>

                @if($zone->description)
                <p style="font-size:14px;color:var(--text-secondary);margin-bottom:16px;">{{ $zone->description }}</p>
                @endif

                <div style="display:flex;gap:8px;">
                    <a href="{{ route('admin.zones.edit', $zone->id) }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-edit"></i> Edit Zone
                    </a>
                    <a href="{{ route('admin.spots.index') }}?zone_id={{ $zone->id }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-parking"></i> Manage Spots
                    </a>
                </div>
            </div>
        </div>

        <!-- Spots Grid -->
        <div class="card">
            <div class="card-header">
                <div class="card-title">Parking Spots</div>
                <a href="{{ route('admin.spots.create') }}?zone={{ $zone->id }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Add Spot</a>
            </div>
            <div class="parking-grid" style="padding:0;grid-template-columns:repeat(auto-fill,minmax(65px,1fr));gap:8px;max-height:400px;overflow-y:auto;">
                @foreach($zone->spots->sortBy('spot_number') as $spot)
                <a href="{{ route('admin.spots.show', $spot->id) }}" class="parking-spot {{ $spot->status }}" title="{{ $zone->code }}-{{ $spot->spot_number }}">
                    <i class="fas {{ ['standard'=>'fa-car','staff'=>'fa-briefcase','disabled'=>'fa-wheelchair','vip'=>'fa-star'][$spot->type] ?? 'fa-car' }}" style="font-size:13px;"></i>
                    <span style="font-size:10px;font-weight:700;">{{ $zone->code }}-{{ $spot->spot_number }}</span>
                </a>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
