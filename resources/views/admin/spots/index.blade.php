@extends('layouts.admin')

@section('title', 'Parking Spots')
@section('page-title', 'Parking Spots')
@section('page-subtitle', 'Manage individual parking spots')

@section('content')
<div>

    <div class="grid grid-4 mb-24">
        @foreach([['label'=>'Total Spots','value'=>$stats['total'],'icon'=>'fa-parking','class'=>'primary'],['label'=>'Available','value'=>$stats['available'],'icon'=>'fa-check-circle','class'=>'success'],['label'=>'Reserved','value'=>$stats['reserved'],'icon'=>'fa-clock','class'=>'warning'],['label'=>'Occupied','value'=>$stats['occupied'],'icon'=>'fa-times-circle','class'=>'danger']] as $s)
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
                <div style="font-size:16px;font-weight:700;">All Spots</div>
                <div style="font-size:13px;color:var(--text-muted);margin-top:2px;">{{ $spots->total() }} total spots</div>
            </div>
            <div style="display:flex;gap:10px;flex-wrap:wrap;">
                <form method="GET" style="display:flex;gap:10px;flex-wrap:wrap;">
                    <select name="zone_id" class="form-select" style="width:160px;padding:8px 32px 8px 12px;" onchange="this.form.submit()">
                        <option value="">All Zones</option>
                        @foreach($zones as $zone)
                        <option value="{{ $zone->id }}" {{ request('zone_id') == $zone->id ? 'selected' : '' }}>{{ $zone->name }}</option>
                        @endforeach
                    </select>
                    <select name="status" class="form-select" style="width:140px;padding:8px 32px 8px 12px;" onchange="this.form.submit()">
                        <option value="">All Statuses</option>
                        @foreach(['available','reserved','occupied','maintenance'] as $s)
                        <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </form>
                <a href="{{ route('admin.spots.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Add Spot</a>
            </div>
        </div>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Spot</th>
                        <th>Zone</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Active</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($spots as $spot)
                    <tr>
                        <td>
                            <div style="font-size:18px;font-weight:900;background:var(--grad-primary);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">{{ $spot->zone->code }}-{{ $spot->spot_number }}</div>
                        </td>
                        <td style="color:var(--text-secondary);font-weight:600;">{{ $spot->zone->name }}</td>
                        <td>
                            <span style="display:flex;align-items:center;gap:6px;font-size:13px;font-weight:600;">
                                <i class="fas {{ ['standard'=>'fa-car','staff'=>'fa-briefcase','disabled'=>'fa-wheelchair','vip'=>'fa-star'][$spot->type] ?? 'fa-car' }}" style="color:var(--primary);"></i>
                                {{ ucfirst($spot->type) }}
                            </span>
                        </td>
                        <td>
                            <span class="badge badge-{{ ['available'=>'success','reserved'=>'warning','occupied'=>'danger','maintenance'=>'secondary'][$spot->status] ?? 'secondary' }}">
                                {{ ucfirst($spot->status) }}
                            </span>
                        </td>
                        <td>
                            <span class="badge {{ $spot->is_active ? 'badge-success' : 'badge-secondary' }}">{{ $spot->is_active ? 'Yes' : 'No' }}</span>
                        </td>
                        <td data-label="Actions">
                            <div class="table-actions">
                                <a href="{{ route('admin.spots.show', $spot->id) }}" class="btn-action-view" title="View"><i class="fas fa-eye"></i></a>
                                <a href="{{ route('admin.spots.edit', $spot->id) }}" class="btn-action-edit" title="Edit"><i class="fas fa-edit"></i></a>
                                <form action="{{ route('admin.spots.destroy', $spot->id) }}" method="POST" onsubmit="return confirm('Delete this spot?')" style="display:inline;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-action-delete" title="Delete"><i class="fas fa-trash-alt"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" style="text-align:center;padding:50px;color:var(--text-muted);">No spots found</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($spots->hasPages())
        <div style="padding:20px 24px;border-top:1px solid var(--border-color);">
            <div class="pagination">
                @if($spots->onFirstPage())<span class="page-btn disabled"><i class="fas fa-chevron-left"></i></span>@else<a href="{{ $spots->previousPageUrl() }}" class="page-btn"><i class="fas fa-chevron-left"></i></a>@endif
                @foreach($spots->getUrlRange(1, $spots->lastPage()) as $page => $url)<a href="{{ $url }}" class="page-btn {{ $page == $spots->currentPage() ? 'active' : '' }}">{{ $page }}</a>@endforeach
                @if($spots->hasMorePages())<a href="{{ $spots->nextPageUrl() }}" class="page-btn"><i class="fas fa-chevron-right"></i></a>@else<span class="page-btn disabled"><i class="fas fa-chevron-right"></i></span>@endif
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
