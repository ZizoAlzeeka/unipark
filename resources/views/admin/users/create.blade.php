@extends('layouts.admin')

@section('title', 'Add New User')
@section('page-title', 'Add New User')
@section('page-subtitle', 'Create a new user account')

@section('content')
<div class="animate-fade-up" style="max-width:720px;">
    <div class="mb-16">
        <a href="{{ route('admin.users.index') }}" class="btn btn-ghost btn-sm">
            <i class="fas fa-arrow-left"></i> Back to Users
        </a>
    </div>

    <div class="card">
        <div class="card-header">
            <div>
                <div class="card-title">Create New User</div>
                <p style="font-size:13px;color:var(--text-muted);margin-top:2px;">Fill in all required information</p>
            </div>
            <div style="width:46px;height:46px;background:var(--grad-primary);border-radius:var(--radius-md);display:flex;align-items:center;justify-content:center;color:#fff;font-size:18px;">
                <i class="fas fa-user-plus"></i>
            </div>
        </div>

        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
                <div class="form-group">
                    <label class="form-label">Full Name <span class="required">*</span></label>
                    <div class="input-group">
                        <span class="input-icon"><i class="fas fa-user"></i></span>
                        <input type="text" name="name" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" value="{{ old('name') }}" required>
                    </div>
                    @error('name')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Role <span class="required">*</span></label>
                    <select name="role" class="form-select {{ $errors->has('role') ? 'is-invalid' : '' }}" required>
                        <option value="student" {{ old('role') === 'student' ? 'selected' : '' }}>Student</option>
                        <option value="staff" {{ old('role') === 'staff' ? 'selected' : '' }}>Staff</option>
                        <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                    @error('role')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">University Email <span class="required">*</span></label>
                    <div class="input-group">
                        <span class="input-icon"><i class="fas fa-envelope"></i></span>
                        <input type="email" name="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" value="{{ old('email') }}" placeholder="user@uoh.edu.sa" required>
                    </div>
                    @error('email')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">University ID <span class="required">*</span></label>
                    <div class="input-group">
                        <span class="input-icon"><i class="fas fa-id-badge"></i></span>
                        <input type="text" name="university_id" class="form-control" value="{{ old('university_id') }}" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Password <span class="required">*</span></label>
                    <div class="input-group">
                        <span class="input-icon"><i class="fas fa-lock"></i></span>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Confirm Password <span class="required">*</span></label>
                    <div class="input-group">
                        <span class="input-icon"><i class="fas fa-lock"></i></span>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Phone</label>
                    <div class="input-group">
                        <span class="input-icon"><i class="fas fa-phone"></i></span>
                        <input type="tel" name="phone" class="form-control" value="{{ old('phone') }}">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Vehicle Plate</label>
                    <div class="input-group">
                        <span class="input-icon"><i class="fas fa-car"></i></span>
                        <input type="text" name="vehicle_plate" class="form-control" value="{{ old('vehicle_plate') }}" placeholder="ABC 123" style="text-transform:uppercase;">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Vehicle Model</label>
                    <div class="input-group">
                        <span class="input-icon"><i class="fas fa-car-side"></i></span>
                        <input type="text" name="vehicle_model" class="form-control" value="{{ old('vehicle_model') }}">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Vehicle Color</label>
                    <div class="input-group">
                        <span class="input-icon"><i class="fas fa-palette"></i></span>
                        <input type="text" name="vehicle_color" class="form-control" value="{{ old('vehicle_color') }}">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label style="display:flex;align-items:center;gap:10px;cursor:pointer;font-size:14px;font-weight:600;">
                    <input type="checkbox" name="is_active" value="1" checked style="accent-color:var(--primary);width:18px;height:18px;">
                    Account Active
                </label>
            </div>

            <div style="display:flex;gap:12px;margin-top:24px;padding-top:24px;border-top:1px solid var(--border-color);">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Create User
                </button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
