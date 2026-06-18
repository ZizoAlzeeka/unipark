@extends('layouts.app')

@section('title', 'My Reservations')
@section('page-title', 'My Reservations')
@section('page-subtitle', 'Manage all your parking reservations')

@section('content')
<div>

    <div class="flex-between mb-24" style="flex-wrap:wrap;gap:12px;">
        <div></div>
        <a href="{{ route('reservations.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> New Reservation
        </a>
    </div>

    <div class="table-container">
        <div class="table-header">
            <div>
                <div style="font-size:16px;font-weight:700;">All Reservations</div>
                <div style="font-size:13px;color:var(--text-muted);margin-top:2px;">{{ $reservations->total() }} total reservations</div>
            </div>
        </div>

        @if($reservations->count() > 0)
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Spot</th>
                        <th>Zone</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Duration</th>
                        <th>Vehicle</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reservations as $res)
                    <tr>
                        <td style="font-weight:700;color:var(--text-muted);">#{{ $res->id }}</td>
                        <td>
                            <div style="font-size:16px;font-weight:800;background:var(--grad-primary);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">{{ $res->spot->zone->code }}-{{ $res->spot->spot_number }}</div>
                        </td>
                        <td style="color:var(--text-secondary);">{{ $res->spot->zone->name }}</td>
                        <td style="font-weight:600;">{{ $res->start_time->format('M d, Y') }}<br><span style="font-size:12px;color:var(--text-muted);">{{ $res->start_time->format('H:i') }}</span></td>
                        <td style="font-weight:600;">{{ $res->end_time->format('M d, Y') }}<br><span style="font-size:12px;color:var(--text-muted);">{{ $res->end_time->format('H:i') }}</span></td>
                        <td>
                            <span style="font-size:13px;font-weight:600;color:var(--primary);">{{ $res->duration }}</span>
                        </td>
                        <td>
                            <span style="font-size:13px;font-weight:600;">{{ $res->vehicle_plate ?? '—' }}</span>
                        </td>
                        <td>
                            <span class="badge badge-{{ $res->status_color }}">
                                {{ ucfirst($res->status) }}
                            </span>
                            @if($res->status === 'active' && $res->remaining_time)
                            <div style="font-size:11px;font-weight:600;color:var(--success);margin-top:4px;">{{ $res->remaining_time }}</div>
                            @endif
                        </td>
                        <td>
                            <div style="display:flex;gap:6px;flex-wrap:wrap;">
                                <a href="{{ route('reservations.show', $res->id) }}" class="btn btn-ghost btn-sm">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if($res->canBeCancelled())
                                <button onclick="cancelReservation({{ $res->id }})" class="btn btn-sm" style="background:rgba(239,71,111,0.08);color:var(--danger);border-radius:var(--radius-full);padding:6px 12px;border:none;cursor:pointer;font-size:13px;font-weight:600;transition:var(--transition);" onmouseover="this.style.background='rgba(239,71,111,0.15)'" onmouseout="this.style.background='rgba(239,71,111,0.08)'">
                                    <i class="fas fa-times"></i> Cancel
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($reservations->hasPages())
        <div style="padding:20px 24px; border-top:1px solid var(--border-color);">
            <div class="pagination">
                @if($reservations->onFirstPage())
                    <span class="page-btn disabled"><i class="fas fa-chevron-left"></i></span>
                @else
                    <a href="{{ $reservations->previousPageUrl() }}" class="page-btn"><i class="fas fa-chevron-left"></i></a>
                @endif

                @foreach($reservations->getUrlRange(1, $reservations->lastPage()) as $page => $url)
                    <a href="{{ $url }}" class="page-btn {{ $page == $reservations->currentPage() ? 'active' : '' }}">{{ $page }}</a>
                @endforeach

                @if($reservations->hasMorePages())
                    <a href="{{ $reservations->nextPageUrl() }}" class="page-btn"><i class="fas fa-chevron-right"></i></a>
                @else
                    <span class="page-btn disabled"><i class="fas fa-chevron-right"></i></span>
                @endif
            </div>
        </div>
        @endif

        @else
        <div style="padding:60px;text-align:center;color:var(--text-muted);">
            <i class="fas fa-calendar-times" style="font-size:52px;margin-bottom:16px;display:block;opacity:0.2;"></i>
            <div style="font-size:18px;font-weight:700;margin-bottom:8px;">No reservations yet</div>
            <p style="margin-bottom:20px;">You haven't made any reservations. Start by booking a parking spot.</p>
            <a href="{{ route('reservations.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Book Your First Spot
            </a>
        </div>
        @endif
    </div>
</div>

<!-- Cancel Modal -->
<div class="modal-overlay" id="cancelModal">
    <div class="modal">
        <div class="modal-header">
            <h3 class="modal-title">Cancel Reservation</h3>
            <button class="modal-close" onclick="document.getElementById('cancelModal').classList.remove('show')"><i class="fas fa-times"></i></button>
        </div>
        <form id="cancelForm" method="POST">
            @csrf
            <div class="modal-body">
                <div style="padding:16px;background:rgba(255,183,3,0.06);border:1px solid rgba(255,183,3,0.2);border-radius:var(--radius-md);margin-bottom:20px;display:flex;align-items:flex-start;gap:12px;">
                    <i class="fas fa-exclamation-triangle" style="color:var(--warning);font-size:18px;flex-shrink:0;margin-top:2px;"></i>
                    <p style="font-size:14px;color:var(--text-secondary);">Are you sure you want to cancel this reservation? This action cannot be undone.</p>
                </div>
                <div class="form-group">
                    <label class="form-label">Cancellation Reason (Optional)</label>
                    <textarea name="cancellation_reason" class="form-control" rows="3" placeholder="Provide a reason for cancellation..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="document.getElementById('cancelModal').classList.remove('show')">Keep Reservation</button>
                <button type="submit" class="btn btn-danger"><i class="fas fa-times"></i> Cancel Reservation</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function cancelReservation(id) {
        document.getElementById('cancelForm').action = `/reservations/${id}/cancel`;
        document.getElementById('cancelModal').classList.add('show');
    }

    document.getElementById('cancelModal').addEventListener('click', function(e) {
        if (e.target === this) this.classList.remove('show');
    });
</script>
@endpush
@endsection
