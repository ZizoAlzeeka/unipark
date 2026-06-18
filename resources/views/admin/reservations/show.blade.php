@extends('layouts.admin')

@section('title', 'Reservation #' . $reservation->id)
@section('page-title', 'Reservation Details')

@section('content')
<div class="animate-fade-up" style="max-width:800px;">
    <div class="mb-16">
        <a href="{{ route('admin.reservations.index') }}" class="btn btn-ghost btn-sm"><i class="fas fa-arrow-left"></i> Back to Reservations</a>
    </div>
    <div class="card">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;padding-bottom:20px;border-bottom:1px solid var(--border-color);">
            <div>
                <div style="font-size:36px;font-weight:900;background:var(--grad-primary);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">{{ $reservation->spot->zone->code }}-{{ $reservation->spot->spot_number }}</div>
                <div style="font-size:13px;color:var(--text-muted);">Reservation #{{ $reservation->id }}</div>
            </div>
            <span class="badge badge-{{ $reservation->status_color }}" style="font-size:14px;padding:8px 20px;">{{ ucfirst($reservation->status) }}</span>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:24px;">
            <!-- User Info -->
            <div style="padding:18px;background:rgba(108,99,255,0.04);border:1px solid rgba(108,99,255,0.1);border-radius:var(--radius-md);">
                <div style="font-size:12px;text-transform:uppercase;letter-spacing:0.5px;color:var(--text-muted);font-weight:700;margin-bottom:12px;">User Information</div>
                <div style="display:flex;align-items:center;gap:12px;">
                    <img src="{{ $reservation->user->avatar_url }}" style="width:44px;height:44px;border-radius:50%;border:2px solid var(--primary);object-fit:cover;">
                    <div>
                        <div style="font-weight:700;font-size:15px;">{{ $reservation->user->name }}</div>
                        <div style="font-size:12px;color:var(--text-muted);">{{ $reservation->user->email }}</div>
                        <span class="badge badge-primary" style="margin-top:4px;font-size:10px;">{{ ucfirst($reservation->user->role) }}</span>
                    </div>
                </div>
            </div>

            <!-- Spot Info -->
            <div style="padding:18px;background:rgba(0,180,216,0.04);border:1px solid rgba(0,180,216,0.12);border-radius:var(--radius-md);">
                <div style="font-size:12px;text-transform:uppercase;letter-spacing:0.5px;color:var(--text-muted);font-weight:700;margin-bottom:12px;">Spot Information</div>
                <div style="font-size:24px;font-weight:900;background:var(--grad-secondary);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">{{ $reservation->spot->zone->code }}-{{ $reservation->spot->spot_number }}</div>
                <div style="font-size:13px;color:var(--text-muted);">{{ $reservation->spot->zone->name }} — {{ ucfirst($reservation->spot->type) }}</div>
            </div>

            <!-- Time Info -->
            <div style="padding:18px;background:rgba(6,214,160,0.04);border:1px solid rgba(6,214,160,0.12);border-radius:var(--radius-md);">
                <div style="font-size:12px;text-transform:uppercase;letter-spacing:0.5px;color:var(--text-muted);font-weight:700;margin-bottom:12px;">Reservation Time</div>
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px;">
                    <span style="font-size:13px;color:var(--text-muted);">Start</span>
                    <span style="font-size:14px;font-weight:700;color:var(--success);">{{ $reservation->start_time->format('M d, Y H:i') }}</span>
                </div>
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px;">
                    <span style="font-size:13px;color:var(--text-muted);">End</span>
                    <span style="font-size:14px;font-weight:700;color:var(--danger);">{{ $reservation->end_time->format('M d, Y H:i') }}</span>
                </div>
                <div style="display:flex;align-items:center;justify-content:space-between;">
                    <span style="font-size:13px;color:var(--text-muted);">Duration</span>
                    <span style="font-size:14px;font-weight:700;color:var(--primary);">{{ $reservation->duration }}</span>
                </div>
            </div>

            <!-- Vehicle Info -->
            <div style="padding:18px;background:rgba(255,183,3,0.04);border:1px solid rgba(255,183,3,0.12);border-radius:var(--radius-md);">
                <div style="font-size:12px;text-transform:uppercase;letter-spacing:0.5px;color:var(--text-muted);font-weight:700;margin-bottom:12px;">Vehicle Information</div>
                <div style="font-size:18px;font-weight:800;">{{ $reservation->vehicle_plate ?? $reservation->user->vehicle_plate ?? '—' }}</div>
                <div style="font-size:13px;color:var(--text-muted);margin-top:4px;">{{ $reservation->user->vehicle_model ?? '' }} {{ $reservation->user->vehicle_color ?? '' }}</div>
            </div>
        </div>

        @if($reservation->notes)
        <div style="padding:16px;background:rgba(108,99,255,0.04);border:1px solid rgba(108,99,255,0.1);border-radius:var(--radius-md);margin-bottom:20px;">
            <div style="font-size:12px;text-transform:uppercase;letter-spacing:0.5px;color:var(--text-muted);font-weight:700;margin-bottom:6px;">Notes</div>
            <p style="font-size:14px;color:var(--text-secondary);">{{ $reservation->notes }}</p>
        </div>
        @endif

        <!-- Admin Actions -->
        @if($reservation->status === 'active')
        <div style="display:flex;gap:12px;padding-top:20px;border-top:1px solid var(--border-color);">
            <form action="{{ route('admin.reservations.complete', $reservation->id) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-success"><i class="fas fa-check"></i> Mark Complete</button>
            </form>
            <form action="{{ route('admin.reservations.cancel', $reservation->id) }}" method="POST" onsubmit="return confirm('Cancel this reservation?')">
                @csrf
                <button type="submit" class="btn btn-danger"><i class="fas fa-times"></i> Cancel Reservation</button>
            </form>
        </div>
        @endif
    </div>
</div>
@endsection
