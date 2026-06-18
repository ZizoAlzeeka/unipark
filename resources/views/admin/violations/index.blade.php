@extends('layouts.admin')

@section('title', 'Violations')
@section('page-title', 'Parking Violations')
@section('page-subtitle', 'Manage and track parking violations and fines')

@section('content')
<div>
    <div class="flex-between mb-24" style="flex-wrap:wrap;gap:12px;">
        <div></div>
        <a href="{{ route('admin.violations.create') }}" class="btn btn-danger">
            <i class="fas fa-plus"></i> Issue Violation
        </a>
    </div>

    <div class="grid grid-4 mb-24">
        @foreach([['label'=>'Total','value'=>$stats['total'],'icon'=>'fa-file-alt','class'=>'primary'],['label'=>'Pending','value'=>$stats['pending'],'icon'=>'fa-clock','class'=>'warning'],['label'=>'Resolved','value'=>$stats['resolved'],'icon'=>'fa-check-circle','class'=>'success'],['label'=>'Total Fines (SAR)','value'=>number_format($stats['total_fines'],0),'icon'=>'fa-money-bill-wave','class'=>'danger']] as $s)
        <div class="stat-card {{ $s['class'] }}">
            <div class="stat-icon {{ $s['class'] }}"><i class="fas {{ $s['icon'] }}"></i></div>
            <div class="stat-info">
                <div class="stat-value" style="font-size:22px;">{{ $s['value'] }}</div>
                <div class="stat-label">{{ $s['label'] }}</div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="table-container">
        <div class="table-header">
            <div>
                <div style="font-size:16px;font-weight:700;">All Violations</div>
                <div style="font-size:13px;color:var(--text-muted);">{{ $violations->total() }} records</div>
            </div>
            <form method="GET" style="display:flex;gap:10px;flex-wrap:wrap;">
                <div class="input-group" style="width:220px;">
                    <span class="input-icon"><i class="fas fa-search"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Search plate, user..." value="{{ request('search') }}" style="padding:8px 8px 8px 40px;border-radius:var(--radius-full);">
                </div>
                <select name="status" class="form-select" style="width:140px;padding:8px 32px 8px 12px;" onchange="this.form.submit()">
                    <option value="">All Status</option>
                    @foreach(['pending','resolved','dismissed'] as $s)
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
                        <th>User / Vehicle</th>
                        <th>Type</th>
                        <th>Spot</th>
                        <th>Fine (SAR)</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($violations as $v)
                    <tr>
                        <td style="font-weight:700;color:var(--text-muted);">#{{ $v->id }}</td>
                        <td>
                            @if($v->user)
                            <div style="font-size:13px;font-weight:700;">{{ $v->user->name }}</div>
                            @endif
                            <div style="font-size:13px;font-weight:700;color:var(--primary);">{{ $v->vehicle_plate }}</div>
                        </td>
                        <td>
                            <span class="badge badge-warning">{{ str_replace('_', ' ', ucfirst($v->type)) }}</span>
                        </td>
                        <td>
                            @if($v->spot)
                            <div style="font-weight:700;font-size:14px;">{{ $v->spot->zone->code }}-{{ $v->spot->spot_number }}</div>
                            @else — @endif
                        </td>
                        <td>
                            <div style="font-weight:800;font-size:16px;color:var(--danger);">{{ number_format($v->fine_amount, 0) }}</div>
                            @if($v->fine_paid)<div style="font-size:11px;color:var(--success);font-weight:700;">Paid ✓</div>@endif
                        </td>
                        <td>
                            <span class="badge badge-{{ ['pending'=>'warning','resolved'=>'success','dismissed'=>'secondary'][$v->status] ?? 'secondary' }}">{{ ucfirst($v->status) }}</span>
                        </td>
                        <td style="font-size:13px;color:var(--text-muted);">{{ $v->violation_time?->format('M d, Y') ?? $v->created_at->format('M d, Y') }}</td>
                        <td data-label="Actions">
                            <div class="table-actions">
                                <a href="{{ route('admin.violations.show', $v->id) }}" class="btn-action-view" title="View"><i class="fas fa-eye"></i></a>
                                @if($v->status === 'pending')
                                <form action="{{ route('admin.violations.resolve', $v->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Mark as resolved?')">
                                    @csrf
                                    <button type="submit" class="btn-action-success" title="Resolve"><i class="fas fa-check"></i></button>
                                </form>
                                @endif
                                @if(!$v->fine_paid && $v->status !== 'dismissed')
                                <form action="{{ route('admin.violations.fine-paid', $v->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn-action-warning" title="Mark Fine Paid"><i class="fas fa-money-bill"></i></button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" style="text-align:center;padding:50px;color:var(--text-muted);">No violations found</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($violations->hasPages())
        <div style="padding:20px 24px;border-top:1px solid var(--border-color);">
            <div class="pagination">
                @if($violations->onFirstPage())<span class="page-btn disabled"><i class="fas fa-chevron-left"></i></span>@else<a href="{{ $violations->previousPageUrl() }}" class="page-btn"><i class="fas fa-chevron-left"></i></a>@endif
                @foreach($violations->getUrlRange(1, $violations->lastPage()) as $page => $url)<a href="{{ $url }}" class="page-btn {{ $page == $violations->currentPage() ? 'active' : '' }}">{{ $page }}</a>@endforeach
                @if($violations->hasMorePages())<a href="{{ $violations->nextPageUrl() }}" class="page-btn"><i class="fas fa-chevron-right"></i></a>@else<span class="page-btn disabled"><i class="fas fa-chevron-right"></i></span>@endif
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
