@extends('layouts.admin')

@section('title', 'All Reservations')
@section('page-title', 'Reservations')
@section('page-subtitle', 'Manage all parking reservations across the campus')

@section('content')
<div>

    <div class="grid grid-4 mb-24">
        @foreach([['label'=>'Total','value'=>$stats['total'],'icon'=>'fa-calendar-check','class'=>'primary'],['label'=>'Active','value'=>$stats['active'],'icon'=>'fa-check-circle','class'=>'success'],['label'=>'Completed','value'=>$stats['completed'],'icon'=>'fa-flag-checkered','class'=>'secondary'],['label'=>'Cancelled','value'=>$stats['cancelled'],'icon'=>'fa-times-circle','class'=>'danger']] as $s)
        <div class="stat-card {{ $s['class'] }}">
            <div class="stat-icon {{ $s['class'] }}"><i class="fas {{ $s['icon'] }}"></i></div>
            <div class="stat-info">
                <div class="stat-value">{{ $s['value'] }}</div>
                <div class="stat-label">{{ $s['label'] }}</div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="table-container">
        <div class="table-header">
            <div>
                <div style="font-size:16px;font-weight:700;">All Reservations</div>
                <div style="font-size:13px;color:var(--text-muted);">{{ $reservations->total() }} records</div>
            </div>
            <form method="GET" style="display:flex;gap:10px;flex-wrap:wrap;">
                <div class="input-group" style="width:220px;">
                    <span class="input-icon"><i class="fas fa-search"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Search..." value="{{ request('search') }}" style="padding:8px 8px 8px 40px;border-radius:var(--radius-full);">
                </div>
                <select name="status" class="form-select" style="width:140px;padding:8px 32px 8px 12px;" onchange="this.form.submit()">
                    <option value="">All Status</option>
                    @foreach(['active','completed','cancelled'] as $s)
                    <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                    @endforeach
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
                        <th>Start</th>
                        <th>End</th>
                        <th>Duration</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reservations as $res)
                    <tr>
                        <td style="font-weight:700;color:var(--text-muted);">#{{ $res->id }}</td>
                        <td>
                            <div style="display:flex;align-items:center;gap:10px;">
                                <img src="{{ $res->user->avatar_url }}" style="width:36px;height:36px;border-radius:50%;object-fit:cover;border:2px solid var(--primary);">
                                <div>
                                    <div style="font-size:13px;font-weight:700;">{{ $res->user->name }}</div>
                                    <div style="font-size:11px;color:var(--text-muted);">{{ ucfirst($res->user->role) }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div style="font-size:16px;font-weight:900;background:var(--grad-primary);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">{{ $res->spot->zone->code }}-{{ $res->spot->spot_number }}</div>
                        </td>
                        <td style="font-size:13px;font-weight:600;">{{ $res->start_time->format('M d, H:i') }}</td>
                        <td style="font-size:13px;font-weight:600;">{{ $res->end_time->format('M d, H:i') }}</td>
                        <td style="font-size:13px;font-weight:600;color:var(--primary);">{{ $res->duration }}</td>
                        <td>
                            <span class="badge badge-{{ $res->status_color }}">{{ ucfirst($res->status) }}</span>
                        </td>
                        <td data-label="Actions">
                            <div class="table-actions">
                                <a href="{{ route('admin.reservations.show', $res->id) }}" class="btn-action-view" title="View"><i class="fas fa-eye"></i></a>
                                @if($res->status === 'active')
                                <form action="{{ route('admin.reservations.complete', $res->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn-action-success" title="Mark Complete"><i class="fas fa-check"></i></button>
                                </form>
                                <form action="{{ route('admin.reservations.cancel', $res->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn-action-delete" title="Cancel"><i class="fas fa-times"></i></button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" style="text-align:center;padding:50px;color:var(--text-muted);">No reservations found</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($reservations->hasPages())
        <div style="padding:20px 24px;border-top:1px solid var(--border-color);">
            <div class="pagination">
                @if($reservations->onFirstPage())<span class="page-btn disabled"><i class="fas fa-chevron-left"></i></span>@else<a href="{{ $reservations->previousPageUrl() }}" class="page-btn"><i class="fas fa-chevron-left"></i></a>@endif
                @foreach($reservations->getUrlRange(1, $reservations->lastPage()) as $page => $url)<a href="{{ $url }}" class="page-btn {{ $page == $reservations->currentPage() ? 'active' : '' }}">{{ $page }}</a>@endforeach
                @if($reservations->hasMorePages())<a href="{{ $reservations->nextPageUrl() }}" class="page-btn"><i class="fas fa-chevron-right"></i></a>@else<span class="page-btn disabled"><i class="fas fa-chevron-right"></i></span>@endif
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
