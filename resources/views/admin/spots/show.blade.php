@extends('layouts.admin')

@section('title', 'Spot Details')
@section('page-title', 'Spot Details')

@section('content')
<div class="animate-fade-up" style="max-width:720px;">
    <div class="mb-16">
        <a href="{{ route('admin.spots.index') }}" class="btn btn-ghost btn-sm"><i class="fas fa-arrow-left"></i> Back to Spots</a>
    </div>
    <div class="card">
        <div style="display:flex;align-items:center;gap:20px;margin-bottom:24px;padding-bottom:24px;border-bottom:1px solid var(--border-color);">
            <div class="parking-spot {{ $spot->status }}" style="width:80px;height:80px;border-radius:var(--radius-lg);cursor:default;flex-shrink:0;">
                <i class="fas {{ ['standard'=>'fa-car','staff'=>'fa-briefcase','disabled'=>'fa-wheelchair','vip'=>'fa-star'][$spot->type] ?? 'fa-car' }}" style="font-size:24px;"></i>
                <span style="font-size:12px;font-weight:700;">{{ $spot->zone->code }}-{{ $spot->spot_number }}</span>
            </div>
            <div>
                <div style="font-size:28px;font-weight:900;background:var(--grad-primary);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">{{ $spot->zone->code }}-{{ $spot->spot_number }}</div>
                <div style="display:flex;gap:8px;margin-top:8px;">
                    <span class="badge badge-{{ ['available'=>'success','reserved'=>'warning','occupied'=>'danger','maintenance'=>'secondary'][$spot->status] ?? 'secondary' }}">{{ ucfirst($spot->status) }}</span>
                    <span class="badge badge-info">{{ ucfirst($spot->type) }}</span>
                    <span class="badge {{ $spot->is_active ? 'badge-success' : 'badge-secondary' }}">{{ $spot->is_active ? 'Active' : 'Inactive' }}</span>
                </div>
            </div>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:24px;">
            @foreach([['Zone', $spot->zone->name],['Floor', $spot->floor ?? '—'],['Section', $spot->section ?? '—'],['Created', $spot->created_at->format('M d, Y')]] as [$label, $value])
            <div style="padding:14px;background:rgba(108,99,255,0.03);border:1px solid rgba(108,99,255,0.08);border-radius:var(--radius-md);">
                <div style="font-size:11px;text-transform:uppercase;letter-spacing:0.5px;color:var(--text-muted);font-weight:600;margin-bottom:4px;">{{ $label }}</div>
                <div style="font-size:14px;font-weight:700;">{{ $value }}</div>
            </div>
            @endforeach
        </div>

        <!-- Active Reservation -->
        @php $activeRes = $spot->activeReservation(); @endphp
        @if($activeRes)
        <div style="padding:16px;background:rgba(255,183,3,0.06);border:1px solid rgba(255,183,3,0.2);border-radius:var(--radius-md);margin-bottom:20px;">
            <div style="font-size:13px;font-weight:700;color:var(--warning);margin-bottom:8px;"><i class="fas fa-clock"></i> Currently Reserved</div>
            <div style="font-size:13px;color:var(--text-secondary);">
                By: <strong>{{ $activeRes->user->name ?? 'Unknown' }}</strong><br>
                Until: <strong>{{ $activeRes->end_time->format('M d, H:i') }}</strong>
            </div>
        </div>
        @endif

        <div style="display:flex;gap:10px;">
            <a href="{{ route('admin.spots.edit', $spot->id) }}" class="btn btn-primary"><i class="fas fa-edit"></i> Edit Spot</a>
        </div>
    </div>
</div>
@endsection
