@extends('layouts.admin')

@section('title', 'Permissions Management')
@section('page-title', 'Permissions Management')
@section('page-subtitle', 'Control access and grant or deny permissions for each role')

@section('content')
<div>

    <div class="alert alert-info mb-24">
        <span class="alert-icon"><i class="fas fa-shield-alt"></i></span>
        <div>
            Use the toggles below to grant or deny specific permissions for each user role. Click any toggle to instantly update it.
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div>
                <div class="card-title">Role Permission Matrix</div>
                <p style="font-size:13px;color:var(--text-muted);margin-top:2px;">Toggle permissions per role — green = granted, grey = denied</p>
            </div>
            <div style="display:flex;gap:8px;">
                <span class="badge badge-success" style="font-size:12px;padding:6px 14px;"><i class="fas fa-check"></i> Granted</span>
                <span class="badge badge-secondary" style="font-size:12px;padding:6px 14px;"><i class="fas fa-times"></i> Denied</span>
            </div>
        </div>

        <div style="overflow-x:auto;">
            <table style="width:100%;border-collapse:separate;border-spacing:0;">
                <thead>
                    <tr>
                        <th style="padding:14px 20px;background:rgba(108,99,255,0.04);border-bottom:2px solid var(--border-color);text-align:left;font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;color:var(--text-muted);">Permission</th>
                        @foreach([
                            'student' => ['icon' => 'fa-user-graduate', 'color' => 'var(--primary)'],
                            'staff' => ['icon' => 'fa-chalkboard-teacher', 'color' => 'var(--secondary)'],
                            'admin' => ['icon' => 'fa-shield-alt', 'color' => 'var(--danger)'],
                        ] as $role => $info)
                        <th style="padding:14px;background:rgba(108,99,255,0.04);border-bottom:2px solid var(--border-color);border-left:1px solid var(--border-color);text-align:center;">
                            <div style="display:flex;align-items:center;justify-content:center;gap:6px;">
                                <i class="fas {{ $info['icon'] }}" style="color:{{ $info['color'] }};font-size:15px;"></i>
                                <span style="font-size:14px;font-weight:700;color:{{ $info['color'] }};">{{ ucfirst($role) }}</span>
                            </div>
                        </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($modules as $group => $permList)
                    <tr>
                        <td colspan="4" style="padding:10px 20px;background:rgba(108,99,255,0.06);border-bottom:1px solid rgba(108,99,255,0.1);border-top:1px solid rgba(108,99,255,0.1);">
                            <span style="font-size:12px;font-weight:800;text-transform:uppercase;letter-spacing:0.8px;color:var(--primary);">
                                <i class="fas fa-folder" style="margin-right:6px;"></i>{{ $group }}
                            </span>
                        </td>
                    </tr>
                    @foreach($permList as $permKey)
                    <tr style="border-bottom:1px solid rgba(108,99,255,0.04);">
                        <td style="padding:14px 20px;">
                            <div style="font-size:14px;font-weight:600;color:var(--text-primary);">{{ $permissionLabels[$permKey] ?? ucwords(str_replace('_',' ',$permKey)) }}</div>
                            <div style="font-size:12px;color:var(--text-muted);font-family:monospace;margin-top:2px;">{{ $permKey }}</div>
                        </td>
                        @foreach(['student', 'staff', 'admin'] as $role)
                        @php
                            $granted = $permissions[$role][$permKey] ?? false;
                        @endphp
                        <td style="border-left:1px solid var(--border-color);text-align:center;padding:14px;">
                            <div class="perm-toggle {{ $granted ? 'active' : '' }}"
                                 onclick="togglePermission(this, '{{ $role }}', '{{ $permKey }}')"
                                 title="{{ $granted ? 'Click to deny' : 'Click to grant' }}">
                                <div class="perm-toggle-thumb"></div>
                            </div>
                        </td>
                        @endforeach
                    </tr>
                    @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>

<div id="permToast" style="position:fixed;bottom:24px;right:24px;padding:14px 22px;border-radius:var(--radius-lg);font-size:14px;font-weight:700;color:#fff;opacity:0;transition:opacity 0.3s;z-index:9999;box-shadow:var(--shadow-lg);display:flex;align-items:center;gap:8px;">
    <i class="fas fa-check-circle"></i> <span id="permToastMsg">Permission updated</span>
</div>

@push('scripts')
<script>
function togglePermission(el, role, permission) {
    const isActive = el.classList.contains('active');
    const newState = !isActive;

    if (newState) {
        el.classList.add('active');
        el.title = 'Click to deny';
    } else {
        el.classList.remove('active');
        el.title = 'Click to grant';
    }

    fetch('{{ route('admin.permissions.toggle') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify({ role: role, permission: permission, is_granted: newState }),
    })
    .then(res => res.json())
    .then(data => {
        showToast(data.message || (newState ? 'Permission granted' : 'Permission denied'), newState ? 'var(--success)' : 'var(--danger)');
    })
    .catch(() => {
        // Revert on error
        if (newState) el.classList.remove('active'); else el.classList.add('active');
        showToast('Failed to update permission', 'var(--danger)');
    });
}

function showToast(msg, color) {
    const toast = document.getElementById('permToast');
    document.getElementById('permToastMsg').textContent = msg;
    toast.style.background = color;
    toast.style.opacity = '1';
    setTimeout(() => { toast.style.opacity = '0'; }, 2500);
}
</script>

<style>
.perm-toggle {
    width: 48px; height: 26px;
    background: var(--border-color);
    border-radius: 13px;
    position: relative;
    cursor: pointer;
    transition: background 0.25s;
    margin: 0 auto;
    flex-shrink: 0;
}
.perm-toggle.active { background: var(--success); }
.perm-toggle-thumb {
    position: absolute; top: 3px; left: 3px;
    width: 20px; height: 20px;
    border-radius: 50%;
    background: #fff;
    box-shadow: 0 2px 6px rgba(0,0,0,0.2);
    transition: left 0.25s;
}
.perm-toggle.active .perm-toggle-thumb { left: 25px; }
</style>
@endpush
@endsection
