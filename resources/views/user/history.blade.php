@extends('layouts.app')

@section('title', 'History')
@section('page-title', 'Parking History')
@section('page-subtitle', 'Your past and cancelled reservations')

@section('content')
<div>
    <div class="table-container">
        <div class="table-header">
            <div>
                <div style="font-size:16px;font-weight:700;">Reservation History</div>
                <div style="font-size:13px;color:var(--text-muted);margin-top:2px;">{{ $reservations->total() }} records found</div>
            </div>
            <a href="{{ route('reservations.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> New Reservation
            </a>
        </div>

        @if($reservations->count() > 0)
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Spot</th>
                        <th>Zone</th>
                        <th>Date</th>
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
                        <td>
                            <div style="font-size:13px;font-weight:600;">{{ $res->start_time->format('M d, Y') }}</div>
                            <div style="font-size:12px;color:var(--text-muted);">{{ $res->start_time->format('H:i') }} — {{ $res->end_time->format('H:i') }}</div>
                        </td>
                        <td><span style="font-size:13px;font-weight:600;color:var(--primary);">{{ $res->duration }}</span></td>
                        <td style="font-size:13px;">{{ $res->vehicle_plate ?? '—' }}</td>
                        <td>
                            <span class="badge badge-{{ $res->status_color }}">{{ ucfirst($res->status) }}</span>
                        </td>
                        <td>
                            <a href="{{ route('reservations.show', $res->id) }}" class="btn btn-ghost btn-sm">
                                <i class="fas fa-eye"></i> View
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($reservations->hasPages())
        <div style="padding:20px 24px;border-top:1px solid var(--border-color);">
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
            <i class="fas fa-history" style="font-size:52px;margin-bottom:16px;display:block;opacity:0.2;"></i>
            <div style="font-size:18px;font-weight:700;margin-bottom:8px;">No history yet</div>
            <p style="margin-bottom:20px;">Your completed and cancelled reservations will appear here.</p>
            <a href="{{ route('reservations.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Make a Reservation
            </a>
        </div>
        @endif
    </div>
</div>
@endsection
