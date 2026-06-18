@extends('layouts.admin')

@section('title', 'Entry / Exit Logs')
@section('page-title', 'Entry / Exit Logs')
@section('page-subtitle', 'Track all parking entry and exit events')

@section('content')
<div>
    <div class="flex-between mb-20" style="flex-wrap:wrap;gap:12px;">
        <div></div>
        <a href="{{ route('admin.entry-exit.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Log New Event
        </a>
    </div>

    <div class="grid grid-3 mb-24">
        <div class="stat-card primary">
            <div class="stat-icon primary"><i class="fas fa-door-open"></i></div>
            <div class="stat-info">
                <div class="stat-value">{{ $stats['entries_today'] }}</div>
                <div class="stat-label">Entries Today</div>
            </div>
        </div>
        <div class="stat-card success">
            <div class="stat-icon success"><i class="fas fa-door-closed"></i></div>
            <div class="stat-info">
                <div class="stat-value">{{ $stats['exits_today'] }}</div>
                <div class="stat-label">Exits Today</div>
            </div>
        </div>
        <div class="stat-card secondary">
            <div class="stat-icon secondary"><i class="fas fa-clock"></i></div>
            <div class="stat-info">
                <div class="stat-value">{{ $stats['total_today'] }}</div>
                <div class="stat-label">Total Today</div>
            </div>
        </div>
    </div>

    <div class="table-container">
        <div class="table-header">
            <div>
                <div style="font-size:16px;font-weight:700;">All Entry/Exit Events</div>
                <div style="font-size:13px;color:var(--text-muted);">{{ $logs->total() }} records</div>
            </div>
            <form method="GET" style="display:flex;gap:10px;">
                <select name="event_type" class="form-select" style="width:140px;padding:8px 32px 8px 12px;" onchange="this.form.submit()">
                    <option value="">All Events</option>
                    <option value="entry" {{ request('event_type') === 'entry' ? 'selected' : '' }}>Entry Only</option>
                    <option value="exit" {{ request('event_type') === 'exit' ? 'selected' : '' }}>Exit Only</option>
                </select>
            </form>
        </div>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>User</th>
                        <th>Spot</th>
                        <th>Event</th>
                        <th>Time</th>
                        <th>Vehicle</th>
                        <th>Method</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                    <tr>
                        <td style="font-weight:700;color:var(--text-muted);">#{{ $log->id }}</td>
                        <td>
                            @if($log->user)
                            <div style="display:flex;align-items:center;gap:10px;">
                                <img src="{{ $log->user->avatar_url }}" style="width:34px;height:34px;border-radius:50%;object-fit:cover;border:2px solid var(--primary);">
                                <div>
                                    <div style="font-size:13px;font-weight:700;">{{ $log->user->name }}</div>
                                    <div style="font-size:11px;color:var(--text-muted);">{{ ucfirst($log->user->role) }}</div>
                                </div>
                            </div>
                            @else
                            <span style="color:var(--text-muted);">Unknown</span>
                            @endif
                        </td>
                        <td>
                            @if($log->spot)
                            <div style="font-size:16px;font-weight:900;background:var(--grad-primary);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">{{ $log->spot->zone->code }}-{{ $log->spot->spot_number }}</div>
                            @else — @endif
                        </td>
                        <td>
                            <span class="badge {{ $log->event_type === 'entry' ? 'badge-success' : 'badge-danger' }}">
                                <i class="fas fa-arrow-{{ $log->event_type === 'entry' ? 'right' : 'left' }}"></i>
                                {{ ucfirst($log->event_type) }}
                            </span>
                        </td>
                        <td style="font-size:13px;font-weight:600;">{{ $log->event_time->format('M d, Y H:i') }}</td>
                        <td style="font-size:13px;">{{ $log->vehicle_plate ?? '—' }}</td>
                        <td>
                            <span class="badge badge-secondary">{{ ucfirst($log->method ?? 'manual') }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" style="text-align:center;padding:50px;color:var(--text-muted);">No logs found</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($logs->hasPages())
        <div style="padding:20px 24px;border-top:1px solid var(--border-color);">
            <div class="pagination">
                @if($logs->onFirstPage())<span class="page-btn disabled"><i class="fas fa-chevron-left"></i></span>@else<a href="{{ $logs->previousPageUrl() }}" class="page-btn"><i class="fas fa-chevron-left"></i></a>@endif
                @foreach($logs->getUrlRange(1, $logs->lastPage()) as $page => $url)<a href="{{ $url }}" class="page-btn {{ $page == $logs->currentPage() ? 'active' : '' }}">{{ $page }}</a>@endforeach
                @if($logs->hasMorePages())<a href="{{ $logs->nextPageUrl() }}" class="page-btn"><i class="fas fa-chevron-right"></i></a>@else<span class="page-btn disabled"><i class="fas fa-chevron-right"></i></span>@endif
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
