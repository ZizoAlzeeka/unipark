@extends('layouts.admin')

@section('title', 'User: ' . $user->name)
@section('page-title', 'User Profile')
@section('page-subtitle', $user->name . ' — ' . ucfirst($user->role))

@section('content')
<div>
    <div class="mb-16">
        <a href="{{ route('admin.users.index') }}" class="btn btn-ghost btn-sm">
            <i class="fas fa-arrow-left"></i> Back to Users
        </a>
    </div>

    <div class="grid grid-2" style="gap:24px;align-items:start;">
        <div>
            <div class="card mb-20">
                <div style="text-align:center;padding:20px 0 8px;">
                    <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}"
                         style="width:90px;height:90px;border-radius:50%;border:4px solid var(--primary);object-fit:cover;margin-bottom:16px;">
                    <h2 style="font-size:20px;font-weight:800;">{{ $user->name }}</h2>
                    <p style="color:var(--text-muted);font-size:13px;">{{ $user->email }}</p>
                    <div style="display:flex;gap:8px;justify-content:center;margin-top:10px;">
                        <span class="badge {{ $user->role === 'admin' ? 'badge-danger' : ($user->role === 'staff' ? 'badge-info' : 'badge-primary') }}">{{ ucfirst($user->role) }}</span>
                        <span class="badge {{ $user->is_active ? 'badge-success' : 'badge-secondary' }}">{{ $user->is_active ? 'Active' : 'Inactive' }}</span>
                    </div>
                </div>

                <div style="margin-top:24px;border-top:1px solid var(--border-color);padding-top:20px;display:flex;flex-direction:column;gap:12px;">
                    @foreach([
                        ['label' => 'University ID', 'value' => $user->university_id ?? '—', 'icon' => 'fa-id-badge'],
                        ['label' => 'Phone', 'value' => $user->phone ?? '—', 'icon' => 'fa-phone'],
                        ['label' => 'Vehicle Plate', 'value' => $user->vehicle_plate ?? '—', 'icon' => 'fa-car'],
                        ['label' => 'Vehicle Model', 'value' => $user->vehicle_model ?? '—', 'icon' => 'fa-car-side'],
                        ['label' => 'Joined', 'value' => $user->created_at->format('M d, Y'), 'icon' => 'fa-calendar'],
                    ] as $item)
                    <div style="display:flex;align-items:center;justify-content:space-between;padding:12px;border:1px solid var(--border-color);border-radius:var(--radius-md);">
                        <span style="display:flex;align-items:center;gap:8px;font-size:13px;color:var(--text-muted);">
                            <i class="fas {{ $item['icon'] }}"></i> {{ $item['label'] }}
                        </span>
                        <span style="font-size:13px;font-weight:700;">{{ $item['value'] }}</span>
                    </div>
                    @endforeach
                </div>

                <div style="display:flex;gap:10px;margin-top:20px;">
                    <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-primary" style="flex:1;">
                        <i class="fas fa-edit"></i> Edit User
                    </a>
                    <form action="{{ route('admin.users.toggle-status', $user->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn {{ $user->is_active ? 'btn-danger' : 'btn-success' }}">
                            <i class="fas fa-{{ $user->is_active ? 'user-slash' : 'user-check' }}"></i>
                            {{ $user->is_active ? 'Deactivate' : 'Activate' }}
                        </button>
                    </form>
                </div>
            </div>

            <!-- Stats -->
            <div class="card">
                <div class="card-title" style="margin-bottom:16px;">Activity Statistics</div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                    @foreach([
                        ['value' => $user->reservations->count(), 'label' => 'Total Bookings', 'color' => 'var(--primary)'],
                        ['value' => $user->reservations->where('status','completed')->count(), 'label' => 'Completed', 'color' => 'var(--success)'],
                        ['value' => $user->reservations->where('status','cancelled')->count(), 'label' => 'Cancelled', 'color' => 'var(--danger)'],
                        ['value' => $user->violations->count(), 'label' => 'Violations', 'color' => 'var(--warning)'],
                    ] as $stat)
                    <div style="padding:16px;background:rgba(108,99,255,0.03);border:1px solid rgba(108,99,255,0.08);border-radius:var(--radius-md);text-align:center;">
                        <div style="font-size:26px;font-weight:800;color:{{ $stat['color'] }};">{{ $stat['value'] }}</div>
                        <div style="font-size:12px;color:var(--text-muted);font-weight:600;">{{ $stat['label'] }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Recent Reservations -->
        <div class="card">
            <div class="card-header">
                <div class="card-title">Recent Reservations</div>
            </div>
            @forelse($user->reservations()->with('spot.zone')->latest()->take(10)->get() as $res)
            <div style="display:flex;align-items:center;gap:12px;padding:12px;border:1px solid var(--border-color);border-radius:var(--radius-md);margin-bottom:8px;">
                <div style="font-size:18px;font-weight:900;background:var(--grad-primary);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;min-width:60px;">
                    {{ $res->spot->zone->code }}-{{ $res->spot->spot_number }}
                </div>
                <div style="flex:1;">
                    <div style="font-size:13px;font-weight:600;">{{ $res->start_time->format('M d, H:i') }} — {{ $res->end_time->format('H:i') }}</div>
                    <div style="font-size:12px;color:var(--text-muted);">{{ $res->duration }}</div>
                </div>
                <span class="badge badge-{{ $res->status_color }}">{{ ucfirst($res->status) }}</span>
            </div>
            @empty
            <div style="text-align:center;padding:30px;color:var(--text-muted);">No reservations yet</div>
            @endforelse
        </div>
    </div>
</div>
@endsection
