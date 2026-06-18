@extends('layouts.admin')

@section('title', 'Reports & Analytics')
@section('page-title', 'Reports & Analytics')
@section('page-subtitle', 'Comprehensive parking system insights and statistics')

@section('content')
<div>

    <!-- Date Range Filter -->
    <div class="card mb-24">
        <form method="GET" style="display:flex;align-items:flex-end;gap:16px;flex-wrap:wrap;">
            <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">From Date</label>
                <input type="date" name="date_from" class="form-control" value="{{ $dateFrom }}" style="width:160px;">
            </div>
            <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">To Date</label>
                <input type="date" name="date_to" class="form-control" value="{{ $dateTo }}" style="width:160px;">
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i> Filter</button>
            <a href="{{ route('admin.reports.export') }}?date_from={{ $dateFrom }}&date_to={{ $dateTo }}&type=reservations" class="btn btn-ghost">
                <i class="fas fa-download"></i> Export Reservations CSV
            </a>
            <a href="{{ route('admin.reports.export') }}?date_from={{ $dateFrom }}&date_to={{ $dateTo }}&type=violations" class="btn btn-ghost">
                <i class="fas fa-download"></i> Export Violations CSV
            </a>
        </form>
    </div>

    <!-- Summary Stats -->
    <div class="grid grid-4 mb-24">
        @foreach([
            ['label' => 'Total Reservations', 'value' => number_format($summary['total_reservations']), 'icon' => 'fa-calendar-check', 'class' => 'primary'],
            ['label' => 'Total Users', 'value' => number_format($summary['total_users']), 'icon' => 'fa-users', 'class' => 'success'],
            ['label' => 'Total Fines (SAR)', 'value' => number_format($summary['total_fines'], 0), 'icon' => 'fa-money-bill-wave', 'class' => 'warning'],
            ['label' => 'Violations', 'value' => number_format($summary['total_violations']), 'icon' => 'fa-exclamation-triangle', 'class' => 'danger'],
        ] as $s)
        <div class="stat-card {{ $s['class'] }}">
            <div class="stat-icon {{ $s['class'] }}"><i class="fas {{ $s['icon'] }}"></i></div>
            <div class="stat-info">
                <div class="stat-value" style="font-size:22px;">{{ $s['value'] }}</div>
                <div class="stat-label">{{ $s['label'] }}</div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="grid grid-2 mb-24">
        <!-- Zone Usage -->
        <div class="card">
            <div class="card-header">
                <div class="card-title">Zone Usage</div>
                <i class="fas fa-map" style="color:var(--secondary);font-size:20px;"></i>
            </div>
            @foreach($zoneUsage as $zone)
            @php
                $total = max($zone['total_spots'], 1);
                $rate = $total > 0 ? round($zone['total_reservations'] / max($zone['total_reservations'], 1) * 100) : 0;
                // Simple ratio: available/total
                $occupancyRate = $total > 0 ? round((($total - $zone['available_spots']) / $total) * 100) : 0;
            @endphp
            <div style="margin-bottom:16px;">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:6px;">
                    <span style="font-size:14px;font-weight:700;">{{ $zone['name'] }} <small style="color:var(--text-muted);font-weight:500;">({{ $zone['code'] }})</small></span>
                    <div style="display:flex;gap:12px;font-size:12px;color:var(--text-muted);">
                        <span>{{ $zone['total_reservations'] }} reservations</span>
                        <span style="font-weight:800;color:{{ $occupancyRate < 50 ? 'var(--success)' : ($occupancyRate < 80 ? 'var(--warning)' : 'var(--danger)') }};">{{ $occupancyRate }}%</span>
                    </div>
                </div>
                <div class="progress">
                    <div class="progress-bar {{ $occupancyRate < 50 ? 'success' : ($occupancyRate < 80 ? 'warning' : 'danger') }}" style="width:{{ $occupancyRate }}%;"></div>
                </div>
                <div style="font-size:11px;color:var(--text-muted);margin-top:3px;">{{ $zone['available_spots'] }} / {{ $zone['total_spots'] }} spots available</div>
            </div>
            @endforeach
        </div>

        <!-- Violation Types -->
        <div class="card">
            <div class="card-header">
                <div class="card-title">Violations by Type</div>
                <i class="fas fa-chart-pie" style="color:var(--danger);font-size:20px;"></i>
            </div>
            @if($violationStats->count() > 0)
            @foreach($violationStats as $vStat)
            <div style="display:flex;align-items:center;justify-content:space-between;padding:12px;border:1px solid var(--border-color);border-radius:var(--radius-md);margin-bottom:8px;">
                <div>
                    <div style="font-size:14px;font-weight:700;">{{ str_replace('_',' ', ucfirst($vStat->type)) }}</div>
                    <div style="font-size:12px;color:var(--text-muted);">{{ $vStat->count }} violation(s)</div>
                </div>
                <div style="text-align:right;">
                    <div style="font-size:14px;font-weight:800;color:var(--danger);">SAR {{ number_format($vStat->total_fines, 0) }}</div>
                    <span class="badge badge-warning" style="font-size:10px;">{{ $vStat->count }}</span>
                </div>
            </div>
            @endforeach
            @else
            <div style="text-align:center;padding:30px;color:var(--text-muted);">No violations in this period</div>
            @endif
        </div>
    </div>

    <div class="grid grid-2 mb-24">
        <!-- Peak Hours -->
        <div class="card">
            <div class="card-header">
                <div class="card-title">Peak Parking Hours</div>
                <i class="fas fa-clock" style="color:var(--warning);font-size:20px;"></i>
            </div>
            @php $maxCount = $peakHours->max('count') ?: 1; @endphp
            @if($peakHours->count() > 0)
            @foreach($peakHours as $hour)
            @php $pct = round($hour->count / $maxCount * 100); @endphp
            <div style="display:flex;align-items:center;gap:12px;margin-bottom:10px;">
                <div style="min-width:50px;font-size:13px;font-weight:700;color:var(--text-secondary);">{{ str_pad($hour->hour, 2, '0', STR_PAD_LEFT) }}:00</div>
                <div style="flex:1;">
                    <div class="progress" style="height:10px;">
                        <div class="progress-bar {{ $pct >= 80 ? 'danger' : ($pct >= 60 ? 'warning' : 'success') }}" style="width:{{ $pct }}%;"></div>
                    </div>
                </div>
                <div style="min-width:36px;font-size:13px;font-weight:700;color:var(--text-muted);text-align:right;">{{ $hour->count }}</div>
            </div>
            @endforeach
            @else
            <div style="text-align:center;padding:30px;color:var(--text-muted);">No data for peak hours</div>
            @endif
        </div>

        <!-- Top Users -->
        <div class="card">
            <div class="card-header">
                <div class="card-title">Most Active Users</div>
                <i class="fas fa-trophy" style="color:var(--warning);font-size:20px;"></i>
            </div>
            @foreach($topUsers->take(8) as $i => $user)
            <div style="display:flex;align-items:center;gap:12px;padding:10px;border-radius:var(--radius-md);{{ $i < 3 ? 'background:rgba(255,183,3,0.04);border:1px solid rgba(255,183,3,0.1);' : '' }}margin-bottom:6px;">
                <div style="width:28px;height:28px;border-radius:50%;{{ $i === 0 ? 'background:linear-gradient(135deg,#FFD700,#FFA500);' : ($i === 1 ? 'background:linear-gradient(135deg,#C0C0C0,#808080);' : ($i === 2 ? 'background:linear-gradient(135deg,#CD7F32,#8B4513);' : 'background:rgba(108,99,255,0.1);')) }}display:flex;align-items:center;justify-content:center;color:{{ $i < 3 ? '#fff' : 'var(--primary)' }};font-weight:800;font-size:12px;flex-shrink:0;">
                    {{ $i + 1 }}
                </div>
                <img src="{{ $user->avatar_url }}" style="width:34px;height:34px;border-radius:50%;border:2px solid var(--primary);object-fit:cover;">
                <div style="flex:1;min-width:0;">
                    <div style="font-size:13px;font-weight:700;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $user->name }}</div>
                    <div style="font-size:11px;color:var(--text-muted);">{{ ucfirst($user->role) }}</div>
                </div>
                <span style="font-size:14px;font-weight:800;color:var(--primary);">{{ $user->reservations_count }}</span>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Daily Reservations table -->
    @if($reservationStats->count() > 0)
    <div class="card">
        <div class="card-header">
            <div class="card-title">Daily Reservations Breakdown</div>
            <div style="font-size:13px;color:var(--text-muted);">{{ $dateFrom }} to {{ $dateTo }}</div>
        </div>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Total</th>
                        <th>Active</th>
                        <th>Completed</th>
                        <th>Cancelled</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reservationStats as $day)
                    <tr>
                        <td style="font-weight:600;">{{ \Carbon\Carbon::parse($day->date)->format('M d, Y (D)') }}</td>
                        <td><span style="font-weight:800;color:var(--primary);">{{ $day->total }}</span></td>
                        <td><span style="font-weight:700;color:var(--success);">{{ $day->active }}</span></td>
                        <td><span style="font-weight:700;color:var(--secondary);">{{ $day->completed }}</span></td>
                        <td><span style="font-weight:700;color:var(--danger);">{{ $day->cancelled }}</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

</div>
@endsection
