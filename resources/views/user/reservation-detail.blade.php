@extends('layouts.app')

@section('title', 'Reservation #' . $reservation->id)
@section('page-title', 'Reservation Details')
@section('page-subtitle', 'Spot ' . $reservation->spot->zone->code . '-' . $reservation->spot->spot_number)

@section('content')
<div>
    <div class="mb-20">
        <a href="{{ route('reservations.index') }}" class="btn btn-ghost btn-sm">
            <i class="fas fa-arrow-left"></i> Back to Reservations
        </a>
    </div>

    <div class="grid grid-2" style="gap:24px; align-items:start;">
        <!-- Main Details -->
        <div>
            <div class="card mb-20">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;">
                    <div style="display:flex;align-items:center;gap:16px;">
                        <div style="font-size:42px;font-weight:900;background:var(--grad-primary);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">
                            {{ $reservation->spot->zone->code }}-{{ $reservation->spot->spot_number }}
                        </div>
                        <div>
                            <span class="badge badge-{{ $reservation->status_color }}" style="font-size:13px;padding:6px 16px;">
                                {{ ucfirst($reservation->status) }}
                            </span>
                            @if($reservation->remaining_time && $reservation->status === 'active')
                            <div style="font-size:12px;font-weight:700;color:var(--success);margin-top:6px;">{{ $reservation->remaining_time }}</div>
                            @endif
                        </div>
                    </div>
                    <span style="font-size:13px;color:var(--text-muted);">Reservation #{{ $reservation->id }}</span>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                    <div style="padding:16px;background:rgba(108,99,255,0.04);border:1px solid rgba(108,99,255,0.1);border-radius:var(--radius-md);">
                        <div style="font-size:11px;text-transform:uppercase;letter-spacing:0.5px;color:var(--text-muted);font-weight:600;margin-bottom:6px;">Parking Zone</div>
                        <div style="font-size:15px;font-weight:700;">{{ $reservation->spot->zone->name }}</div>
                        <div style="font-size:12px;color:var(--text-muted);">{{ $reservation->spot->zone->location ?? '' }}</div>
                    </div>
                    <div style="padding:16px;background:rgba(108,99,255,0.04);border:1px solid rgba(108,99,255,0.1);border-radius:var(--radius-md);">
                        <div style="font-size:11px;text-transform:uppercase;letter-spacing:0.5px;color:var(--text-muted);font-weight:600;margin-bottom:6px;">Spot Type</div>
                        <div style="font-size:15px;font-weight:700;display:flex;align-items:center;gap:6px;">
                            <i class="fas {{ ['standard'=>'fa-car','staff'=>'fa-briefcase','disabled'=>'fa-wheelchair','vip'=>'fa-star'][$reservation->spot->type] ?? 'fa-car' }}" style="color:var(--primary);"></i>
                            {{ ucfirst($reservation->spot->type) }}
                        </div>
                    </div>
                    <div style="padding:16px;background:rgba(6,214,160,0.04);border:1px solid rgba(6,214,160,0.15);border-radius:var(--radius-md);">
                        <div style="font-size:11px;text-transform:uppercase;letter-spacing:0.5px;color:var(--text-muted);font-weight:600;margin-bottom:6px;">Start Time</div>
                        <div style="font-size:15px;font-weight:700;">{{ $reservation->start_time->format('M d, Y') }}</div>
                        <div style="font-size:16px;font-weight:800;color:var(--success);">{{ $reservation->start_time->format('H:i') }}</div>
                    </div>
                    <div style="padding:16px;background:rgba(239,71,111,0.04);border:1px solid rgba(239,71,111,0.1);border-radius:var(--radius-md);">
                        <div style="font-size:11px;text-transform:uppercase;letter-spacing:0.5px;color:var(--text-muted);font-weight:600;margin-bottom:6px;">End Time</div>
                        <div style="font-size:15px;font-weight:700;">{{ $reservation->end_time->format('M d, Y') }}</div>
                        <div style="font-size:16px;font-weight:800;color:var(--danger);">{{ $reservation->end_time->format('H:i') }}</div>
                    </div>
                </div>

                <div style="margin-top:16px;padding:16px;background:rgba(108,99,255,0.04);border:1px solid rgba(108,99,255,0.1);border-radius:var(--radius-md);display:flex;align-items:center;gap:12px;">
                    <i class="fas fa-clock" style="color:var(--primary);font-size:18px;"></i>
                    <div>
                        <div style="font-size:11px;text-transform:uppercase;letter-spacing:0.5px;color:var(--text-muted);font-weight:600;">Duration</div>
                        <div style="font-size:16px;font-weight:800;color:var(--primary);">{{ $reservation->duration }}</div>
                    </div>
                </div>

                @if($reservation->vehicle_plate)
                <div style="margin-top:12px;padding:16px;background:rgba(0,180,216,0.04);border:1px solid rgba(0,180,216,0.15);border-radius:var(--radius-md);display:flex;align-items:center;gap:12px;">
                    <i class="fas fa-car" style="color:var(--secondary);font-size:18px;"></i>
                    <div>
                        <div style="font-size:11px;text-transform:uppercase;letter-spacing:0.5px;color:var(--text-muted);font-weight:600;">Vehicle Plate</div>
                        <div style="font-size:16px;font-weight:800;">{{ $reservation->vehicle_plate }}</div>
                    </div>
                </div>
                @endif

                @if($reservation->notes)
                <div style="margin-top:12px;padding:16px;background:rgba(255,183,3,0.04);border:1px solid rgba(255,183,3,0.15);border-radius:var(--radius-md);">
                    <div style="font-size:11px;text-transform:uppercase;letter-spacing:0.5px;color:var(--text-muted);font-weight:600;margin-bottom:6px;">Notes</div>
                    <p style="font-size:14px;color:var(--text-secondary);">{{ $reservation->notes }}</p>
                </div>
                @endif

                @if($reservation->status === 'cancelled')
                <div style="margin-top:12px;padding:16px;background:rgba(239,71,111,0.06);border:1px solid rgba(239,71,111,0.2);border-radius:var(--radius-md);">
                    <div style="font-size:12px;font-weight:700;color:var(--danger);margin-bottom:4px;"><i class="fas fa-times-circle"></i> Cancellation Details</div>
                    <div style="font-size:13px;color:var(--text-secondary);">Cancelled on: {{ $reservation->cancelled_at?->format('M d, Y H:i') }}</div>
                    @if($reservation->cancellation_reason)
                    <div style="font-size:13px;color:var(--text-secondary);margin-top:4px;">Reason: {{ $reservation->cancellation_reason }}</div>
                    @endif
                </div>
                @endif

                <!-- Actions -->
                <div style="margin-top:20px;padding-top:20px;border-top:1px solid var(--border-color);display:flex;gap:12px;flex-wrap:wrap;">
                    @if($reservation->canBeCancelled())
                    <button onclick="document.getElementById('cancelModal').classList.add('show')" class="btn btn-danger">
                        <i class="fas fa-times"></i> Cancel Reservation
                    </button>
                    @endif
                    <a href="{{ route('parking-map') }}" class="btn btn-outline">
                        <i class="fas fa-map-marked-alt"></i> View on Map
                    </a>
                </div>
            </div>

            <!-- Entry/Exit Log -->
            @if($reservation->entryExitLogs->count() > 0)
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Entry / Exit Log</div>
                </div>
                @foreach($reservation->entryExitLogs as $log)
                <div style="display:flex;align-items:center;gap:14px;padding:14px;border-radius:var(--radius-md);border:1px solid var(--border-color);margin-bottom:10px;">
                    <div style="width:40px;height:40px;border-radius:var(--radius-full);display:flex;align-items:center;justify-content:center;{{ $log->event_type === 'entry' ? 'background:rgba(6,214,160,0.1);color:var(--success);' : 'background:rgba(239,71,111,0.1);color:var(--danger);' }}">
                        <i class="fas fa-arrow-{{ $log->event_type === 'entry' ? 'right' : 'left' }}-circle"></i>
                    </div>
                    <div style="flex:1;">
                        <div style="font-weight:700;font-size:14px;">{{ ucfirst($log->event_type) }}</div>
                        <div style="font-size:13px;color:var(--text-muted);">{{ $log->event_time->format('M d, Y H:i') }}</div>
                    </div>
                    <span class="badge badge-{{ $log->event_type === 'entry' ? 'success' : 'danger' }}">{{ ucfirst($log->event_type) }}</span>
                </div>
                @endforeach
            </div>
            @endif
        </div>

        <!-- Timeline -->
        <div class="card">
            <div class="card-title" style="margin-bottom:20px;">Reservation Timeline</div>
            <div style="position:relative;padding-left:24px;">
                <div style="position:absolute;left:10px;top:0;bottom:0;width:2px;background:linear-gradient(to bottom,var(--primary),var(--success));border-radius:2px;"></div>

                <div style="position:relative;margin-bottom:24px;">
                    <div style="position:absolute;left:-19px;width:16px;height:16px;border-radius:50%;background:var(--primary);border:3px solid #fff;box-shadow:0 0 0 3px rgba(108,99,255,0.2);"></div>
                    <div style="font-size:12px;color:var(--text-muted);">{{ $reservation->created_at->format('M d, Y H:i') }}</div>
                    <div style="font-size:14px;font-weight:700;color:var(--text-primary);margin-top:2px;">Reservation Created</div>
                    <div style="font-size:12px;color:var(--text-muted);">Your reservation was successfully booked</div>
                </div>

                <div style="position:relative;margin-bottom:24px;">
                    <div style="position:absolute;left:-19px;width:16px;height:16px;border-radius:50%;{{ $reservation->status !== 'cancelled' ? 'background:var(--warning)' : 'background:var(--danger)' }};border:3px solid #fff;box-shadow:0 0 0 3px rgba(255,183,3,0.2);"></div>
                    <div style="font-size:12px;color:var(--text-muted);">{{ $reservation->start_time->format('M d, Y H:i') }}</div>
                    <div style="font-size:14px;font-weight:700;color:var(--text-primary);margin-top:2px;">Reservation Start</div>
                    <div style="font-size:12px;color:var(--text-muted);">Your spot is reserved from this time</div>
                </div>

                <div style="position:relative;">
                    <div style="position:absolute;left:-19px;width:16px;height:16px;border-radius:50%;{{ $reservation->status === 'completed' ? 'background:var(--success)' : 'background:var(--border-color)' }};border:3px solid #fff;box-shadow:0 0 0 3px rgba(6,214,160,0.2);"></div>
                    <div style="font-size:12px;color:var(--text-muted);">{{ $reservation->end_time->format('M d, Y H:i') }}</div>
                    <div style="font-size:14px;font-weight:700;color:var(--text-primary);margin-top:2px;">Reservation End</div>
                    <div style="font-size:12px;color:var(--text-muted);">{{ $reservation->status === 'completed' ? 'Reservation completed successfully' : 'Reservation ends at this time' }}</div>
                </div>

                @if($reservation->status === 'cancelled')
                <div style="position:relative;margin-top:24px;">
                    <div style="position:absolute;left:-19px;width:16px;height:16px;border-radius:50%;background:var(--danger);border:3px solid #fff;box-shadow:0 0 0 3px rgba(239,71,111,0.2);"></div>
                    <div style="font-size:12px;color:var(--text-muted);">{{ $reservation->cancelled_at?->format('M d, Y H:i') }}</div>
                    <div style="font-size:14px;font-weight:700;color:var(--danger);margin-top:2px;">Reservation Cancelled</div>
                    <div style="font-size:12px;color:var(--text-muted);">{{ $reservation->cancellation_reason ?? '' }}</div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Cancel Modal -->
<div class="modal-overlay" id="cancelModal">
    <div class="modal">
        <div class="modal-header">
            <h3 class="modal-title">Cancel Reservation</h3>
            <button class="modal-close" onclick="document.getElementById('cancelModal').classList.remove('show')"><i class="fas fa-times"></i></button>
        </div>
        <form action="{{ route('reservations.cancel', $reservation->id) }}" method="POST">
            @csrf
            <div class="modal-body">
                <div style="padding:16px;background:rgba(255,183,3,0.06);border:1px solid rgba(255,183,3,0.2);border-radius:var(--radius-md);margin-bottom:20px;">
                    <p style="font-size:14px;color:var(--text-secondary);"><i class="fas fa-exclamation-triangle" style="color:var(--warning);"></i> Are you sure you want to cancel this reservation? The spot will be released immediately.</p>
                </div>
                <div class="form-group">
                    <label class="form-label">Reason for Cancellation (Optional)</label>
                    <textarea name="cancellation_reason" class="form-control" rows="3" placeholder="Why are you cancelling?"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="document.getElementById('cancelModal').classList.remove('show')">Keep It</button>
                <button type="submit" class="btn btn-danger"><i class="fas fa-times"></i> Cancel Reservation</button>
            </div>
        </form>
    </div>
</div>
@endsection
