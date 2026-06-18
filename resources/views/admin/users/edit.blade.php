@extends('layouts.admin')

@section('title', 'Edit User')
@section('page-title', 'Edit User')
@section('page-subtitle', 'Update user information for ' . $user->name)

@section('content')
<div class="animate-fade-up" style="max-width:720px;">
    <div class="mb-16">
        <a href="{{ route('admin.users.index') }}" class="btn btn-ghost btn-sm">
            <i class="fas fa-arrow-left"></i> Back to Users
        </a>
    </div>

    <div class="card">
        <div class="card-header">
            <div style="display:flex;align-items:center;gap:14px;">
                <img src="{{ $user->avatar_url }}" style="width:52px;height:52px;border-radius:50%;border:3px solid var(--primary);object-fit:cover;">
                <div>
                    <div class="card-title">{{ $user->name }}</div>
                    <p style="font-size:13px;color:var(--text-muted);">{{ $user->email }}</p>
                </div>
            </div>
            <span class="badge {{ $user->role === 'admin' ? 'badge-danger' : ($user->role === 'staff' ? 'badge-info' : 'badge-primary') }}">{{ ucfirst($user->role) }}</span>
        </div>

        <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
            @csrf @method('PUT')

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
                <div class="form-group">
                    <label class="form-label">Full Name <span class="required">*</span></label>
                    <div class="input-group">
                        <span class="input-icon"><i class="fas fa-user"></i></span>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Role <span class="required">*</span></label>
                    <select name="role" class="form-select">
                        <option value="student" {{ $user->role === 'student' ? 'selected' : '' }}>Student</option>
                        <option value="staff" {{ $user->role === 'staff' ? 'selected' : '' }}>Staff</option>
                        <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Email (read-only)</label>
                    <div class="input-group">
                        <span class="input-icon"><i class="fas fa-envelope"></i></span>
                        <input type="email" class="form-control" value="{{ $user->email }}" readonly style="background:rgba(108,99,255,0.03);">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">University ID</label>
                    <div class="input-group">
                        <span class="input-icon"><i class="fas fa-id-badge"></i></span>
                        <input type="text" name="university_id" class="form-control" value="{{ old('university_id', $user->university_id) }}">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Phone</label>
                    <div class="input-group">
                        <span class="input-icon"><i class="fas fa-phone"></i></span>
                        <input type="tel" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Vehicle Plate</label>
                    <div class="input-group">
                        <span class="input-icon"><i class="fas fa-car"></i></span>
                        <input type="text" name="vehicle_plate" class="form-control" value="{{ old('vehicle_plate', $user->vehicle_plate) }}" style="text-transform:uppercase;">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">New Password <small style="color:var(--text-muted);">(leave blank to keep)</small></label>
                    <div class="input-group">
                        <span class="input-icon"><i class="fas fa-lock"></i></span>
                        <input type="password" name="password" class="form-control" placeholder="Leave blank to keep current">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Confirm New Password</label>
                    <div class="input-group">
                        <span class="input-icon"><i class="fas fa-lock"></i></span>
                        <input type="password" name="password_confirmation" class="form-control">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label style="display:flex;align-items:center;gap:10px;cursor:pointer;font-size:14px;font-weight:600;">
                    <input type="checkbox" name="is_active" value="1" {{ $user->is_active ? 'checked' : '' }} style="accent-color:var(--primary);width:18px;height:18px;">
                    Account Active
                </label>
            </div>

            <div style="display:flex;gap:12px;margin-top:24px;padding-top:24px;border-top:1px solid var(--border-color);">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Changes</button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
