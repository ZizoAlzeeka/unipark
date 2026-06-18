@extends('layouts.admin')

@section('title', 'Admin Dashboard')
@section('page-title', 'Admin Dashboard')
@section('page-subtitle', 'System overview and real-time statistics')

@section('content')
<div>

    <!-- Top Stats -->
    <div class="grid grid-4 mb-24">
        <div class="kpi-card-new bg-kpi-1 animate-fade-up stagger-1">
            <div class="kpi-icon-new"><i class="fas fa-list-ul"></i></div>
            <div class="kpi-info-new">
                <div class="kpi-value-new">{{ number_format($stats['total_users']) }}</div>
                <div class="kpi-label-new">Total Users</div>
                <div style="font-size:12px;opacity:0.8;margin-top:4px;">System registered</div>
            </div>
        </div>
        <div class="kpi-card-new bg-kpi-2 animate-fade-up stagger-2">
            <div class="kpi-icon-new"><i class="fas fa-user-graduate"></i></div>
            <div class="kpi-info-new">
                <div class="kpi-value-new">{{ number_format($stats['active_reservations']) }}</div>
                <div class="kpi-label-new">Active Reservations</div>
                <div style="font-size:12px;opacity:0.8;margin-top:4px;"><i class="fas fa-clock"></i> {{ $stats['total_reservations_today'] }} today</div>
            </div>
        </div>
        <div class="kpi-card-new bg-kpi-3 animate-fade-up stagger-3">
            <div class="kpi-icon-new"><i class="fas fa-clipboard-check"></i></div>
            <div class="kpi-info-new">
                <div class="kpi-value-new">{{ $stats['available_spots'] }}</div>
                <div class="kpi-label-new">Available Spots</div>
                <div style="font-size:12px;opacity:0.8;margin-top:4px;">of {{ $stats['total_spots'] }} total</div>
            </div>
        </div>
        <div class="kpi-card-new bg-kpi-4 animate-fade-up stagger-4">
            <div class="kpi-icon-new"><i class="far fa-calendar-alt"></i></div>
            <div class="kpi-info-new">
                <div class="kpi-value-new">{{ $stats['pending_violations'] }}</div>
                <div class="kpi-label-new">Pending Violations</div>
                <div style="font-size:12px;opacity:0.8;margin-top:4px;"><i class="fas fa-exclamation"></i> Needs attention</div>
            </div>
        </div>
    </div>

    <!-- Second Row Stats -->
    <div class="grid grid-4 mb-24">
        <div class="kpi-card-new bg-kpi-4">
            <div class="kpi-icon-new"><i class="fas fa-user-graduate"></i></div>
            <div class="kpi-info-new">
                <div class="kpi-value-new">{{ $stats['total_students'] }}</div>
                <div class="kpi-label-new">Students</div>
            </div>
        </div>
        <div class="kpi-card-new bg-kpi-3">
            <div class="kpi-icon-new"><i class="fas fa-chalkboard-teacher"></i></div>
            <div class="kpi-info-new">
                <div class="kpi-value-new">{{ $stats['total_staff'] }}</div>
                <div class="kpi-label-new">Staff Members</div>
            </div>
        </div>
        <div class="kpi-card-new bg-kpi-2">
            <div class="kpi-icon-new"><i class="fas fa-check-circle"></i></div>
            <div class="kpi-info-new">
                <div class="kpi-value-new">{{ $stats['reserved_spots'] }}</div>
                <div class="kpi-label-new">Reserved Spots</div>
            </div>
        </div>
        <div class="kpi-card-new bg-kpi-1">
            <div class="kpi-icon-new"><i class="fas fa-map"></i></div>
            <div class="kpi-info-new">
                <div class="kpi-value-new">{{ $stats['total_zones'] }}</div>
                <div class="kpi-label-new">Parking Zones</div>
            </div>
        </div>
    </div>

    <!-- Zones Overview & Recent Activity -->
    <div class="grid grid-2 mb-24">
        <!-- Zone Status -->
        <div class="card">
            <div class="card-header">
                <div>
                    <div class="card-title">Parking Zones Status</div>
                    <p style="font-size:13px;color:var(--text-muted);margin-top:2px;">Real-time occupancy by zone</p>
                </div>
                <a href="{{ route('admin.zones.index') }}" class="btn btn-ghost btn-sm">Manage</a>
            </div>

            @foreach($zones as $zone)
            @php
                $total = $zone->spots->count();
                $available = $zone->spots->where('status','available')->count();
                $occupied = $zone->spots->where('status','occupied')->count();
                $reserved = $zone->spots->where('status','reserved')->count();
                $rate = $total > 0 ? round((($occupied + $reserved) / $total) * 100) : 0;
            @endphp
            <div style="padding:16px;border:1px solid var(--border-color);border-radius:var(--radius-md);margin-bottom:12px;">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:10px;">
                    <div style="display:flex;align-items:center;gap:10px;">
                        <div style="width:36px;height:36px;background:var(--grad-primary);border-radius:var(--radius-sm);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:800;font-size:12px;">{{ $zone->code }}</div>
                        <div>
                            <div style="font-weight:700;font-size:14px;">{{ $zone->name }}</div>
                            <div style="font-size:11px;color:var(--text-muted);">{{ $total }} total spots</div>
                        </div>
                    </div>
                    <div style="display:flex;gap:10px;font-size:12px;">
                        <span style="color:var(--success);font-weight:700;">{{ $available }} Free</span>
                        <span style="color:var(--warning);font-weight:700;">{{ $reserved }} Rsv</span>
                        <span style="color:var(--danger);font-weight:700;">{{ $occupied }} Occ</span>
                    </div>
                </div>
                <div class="progress">
                    <div class="progress-bar {{ $rate < 50 ? 'success' : ($rate < 80 ? 'warning' : 'danger') }}" style="width:{{ $rate }}%;"></div>
                </div>
                <div style="font-size:11px;color:var(--text-muted);margin-top:4px;">{{ $rate }}% occupied</div>
            </div>
            @endforeach
        </div>

        <!-- Recent Reservations -->
        <div class="card">
            <div class="card-header">
                <div>
                    <div class="card-title">Recent Reservations</div>
                    <p style="font-size:13px;color:var(--text-muted);margin-top:2px;">Latest bookings in the system</p>
                </div>
                <a href="{{ route('admin.reservations.index') }}" class="btn btn-ghost btn-sm">View All</a>
            </div>

            <div style="display:flex;flex-direction:column;gap:10px;">
                @forelse($recentReservations as $res)
                <a href="{{ route('admin.reservations.show', $res->id) }}" style="display:flex;align-items:center;gap:12px;padding:12px;border:1px solid var(--border-color);border-radius:var(--radius-md);text-decoration:none;transition:var(--transition);" onmouseover="this.style.background='rgba(108,99,255,0.04)'" onmouseout="this.style.background='transparent'">
                    <img src="{{ $res->user->avatar_url }}" style="width:36px;height:36px;border-radius:50%;object-fit:cover;border:2px solid var(--primary);">
                    <div style="flex:1;min-width:0;">
                        <div style="font-size:13px;font-weight:700;color:var(--text-primary);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $res->user->name }}</div>
                        <div style="font-size:12px;color:var(--text-muted);">{{ $res->spot->zone->code }}-{{ $res->spot->spot_number }} • {{ $res->start_time->format('M d H:i') }}</div>
                    </div>
                    <span class="badge badge-{{ $res->status_color }}">{{ ucfirst($res->status) }}</span>
                </a>
                @empty
                <div style="text-align:center;padding:30px;color:var(--text-muted);">No reservations yet</div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Occupancy rate display -->
    <div class="card mb-24">
        <div class="card-header">
            <div class="card-title">Campus-Wide Occupancy</div>
        </div>
        <div style="display:flex;align-items:center;gap:24px;">
            <div style="width:120px;height:120px;position:relative;flex-shrink:0;">
                <svg viewBox="0 0 36 36" style="width:120px;height:120px;transform:rotate(-90deg);">
                    <circle cx="18" cy="18" r="15.91549430918954" fill="none" stroke="var(--border-color)" stroke-width="3.5"/>
                    <circle cx="18" cy="18" r="15.91549430918954" fill="none"
                            stroke="{{ $occupancyRate < 50 ? 'var(--success)' : ($occupancyRate < 80 ? 'var(--warning)' : 'var(--danger)') }}"
                            stroke-width="3.5"
                            stroke-dasharray="{{ $occupancyRate }} {{ 100 - $occupancyRate }}"
                            stroke-linecap="round"/>
                </svg>
                <div style="position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center;transform:rotate(0deg);">
                    <div style="font-size:22px;font-weight:900;color:var(--text-primary);">{{ $occupancyRate }}%</div>
                    <div style="font-size:10px;color:var(--text-muted);font-weight:600;">Occupied</div>
                </div>
            </div>
            <div style="flex:1;">
                <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px;">
                    @foreach([['Available','available_spots','var(--success)'],['Reserved','reserved_spots','var(--warning)'],['Occupied','occupied_spots','var(--danger)']] as [$label,$key,$color])
                    <div style="text-align:center;padding:16px;border:1px solid var(--border-color);border-radius:var(--radius-md);">
                        <div style="font-size:28px;font-weight:900;color:{{ $color }};">{{ $stats[$key] }}</div>
                        <div style="font-size:12px;color:var(--text-muted);font-weight:600;">{{ $label }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Admin Links -->
    <div class="card">
        <div class="card-header">
            <div class="card-title">Quick Management</div>
        </div>
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(160px,1fr));gap:16px;">
            @foreach([
                ['route' => 'admin.users.index', 'icon' => 'fa-users', 'label' => 'Users', 'grad' => 'var(--grad-primary)', 'shadow' => 'rgba(108,99,255,0.4)'],
                ['route' => 'admin.zones.index', 'icon' => 'fa-map', 'label' => 'Zones', 'grad' => 'var(--grad-secondary)', 'shadow' => 'rgba(0,180,216,0.4)'],
                ['route' => 'admin.spots.index', 'icon' => 'fa-parking', 'label' => 'Spots', 'grad' => 'var(--grad-success)', 'shadow' => 'rgba(6,214,160,0.4)'],
                ['route' => 'admin.violations.index', 'icon' => 'fa-exclamation-triangle', 'label' => 'Violations', 'grad' => 'var(--grad-danger)', 'shadow' => 'rgba(239,71,111,0.4)'],
                ['route' => 'admin.reports.index', 'icon' => 'fa-chart-bar', 'label' => 'Reports', 'grad' => 'var(--grad-warning)', 'shadow' => 'rgba(255,183,3,0.4)'],
                ['route' => 'admin.permissions.index', 'icon' => 'fa-shield-alt', 'label' => 'Permissions', 'grad' => 'var(--grad-accent)', 'shadow' => 'rgba(240,147,251,0.4)'],
            ] as $link)
            <a href="{{ route($link['route']) }}" style="display:flex;flex-direction:column;align-items:center;gap:10px;padding:20px;border:2px solid transparent;border-radius:var(--radius-lg);text-decoration:none;transition:var(--transition);background:rgba(108,99,255,0.02);" onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='var(--shadow-md)';this.style.borderColor='rgba(108,99,255,0.12)'" onmouseout="this.style.transform='none';this.style.boxShadow='none';this.style.borderColor='transparent'">
                <div style="width:50px;height:50px;background:{{ $link['grad'] }};border-radius:var(--radius-md);display:flex;align-items:center;justify-content:center;font-size:20px;color:#fff;box-shadow:0 4px 15px {{ $link['shadow'] }};">
                    <i class="fas {{ $link['icon'] }}"></i>
                </div>
                <span style="font-size:13px;font-weight:700;color:var(--text-primary);">{{ $link['label'] }}</span>
            </a>
            @endforeach
        </div>
    </div>

</div>
@endsection
