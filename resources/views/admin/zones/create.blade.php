@extends('layouts.admin')

@section('title', 'Add Zone')
@section('page-title', 'Add Parking Zone')
@section('page-subtitle', 'Create a new campus parking zone')

@section('content')
<div class="animate-fade-up" style="max-width:720px;">
    <div class="mb-16">
        <a href="{{ route('admin.zones.index') }}" class="btn btn-ghost btn-sm">
            <i class="fas fa-arrow-left"></i> Back to Zones
        </a>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="card-title">New Parking Zone</div>
            <div style="width:44px;height:44px;background:var(--grad-primary);border-radius:var(--radius-md);display:flex;align-items:center;justify-content:center;color:#fff;font-size:18px;"><i class="fas fa-map"></i></div>
        </div>

        <form action="{{ route('admin.zones.store') }}" method="POST">
            @csrf

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
                <div class="form-group">
                    <label class="form-label">Zone Name <span class="required">*</span></label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="e.g. Main Campus Zone" required>
                    @error('name')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Zone Code <span class="required">*</span></label>
                    <input type="text" name="code" class="form-control" value="{{ old('code') }}" placeholder="e.g. ZA, ZB, ZC" maxlength="10" style="text-transform:uppercase;" required>
                    <p class="form-hint">Unique short code for the zone</p>
                </div>

                <div class="form-group">
                    <label class="form-label">Location / Building</label>
                    <div class="input-group">
                        <span class="input-icon"><i class="fas fa-map-marker-alt"></i></span>
                        <input type="text" name="location" class="form-control" value="{{ old('location') }}" placeholder="e.g. Near Admin Building">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Total Capacity</label>
                    <div class="input-group">
                        <span class="input-icon"><i class="fas fa-car"></i></span>
                        <input type="number" name="capacity" class="form-control" value="{{ old('capacity', 30) }}" min="1">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Latitude (for Google Maps)</label>
                    <input type="text" name="latitude" class="form-control" value="{{ old('latitude', '27.5114') }}" placeholder="27.5114">
                </div>

                <div class="form-group">
                    <label class="form-label">Longitude (for Google Maps)</label>
                    <input type="text" name="longitude" class="form-control" value="{{ old('longitude', '41.7208') }}" placeholder="41.7208">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="3" placeholder="Brief description of this zone...">{{ old('description') }}</textarea>
            </div>

            <div class="form-group">
                <label class="form-label">Allowed Roles (who can park here)</label>
                <div style="display:flex;gap:20px;flex-wrap:wrap;">
                    @foreach(['student','staff','admin'] as $role)
                    <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:14px;">
                        <input type="checkbox" name="allowed_roles[]" value="{{ $role }}" {{ in_array($role, old('allowed_roles', ['student','staff','admin'])) ? 'checked' : '' }} style="accent-color:var(--primary);width:16px;height:16px;">
                        {{ ucfirst($role) }}
                    </label>
                    @endforeach
                </div>
            </div>

            <div class="form-group">
                <label style="display:flex;align-items:center;gap:10px;cursor:pointer;font-size:14px;font-weight:600;">
                    <input type="checkbox" name="is_active" value="1" checked style="accent-color:var(--primary);width:18px;height:18px;">
                    Zone Active (users can make reservations in this zone)
                </label>
            </div>

            <div style="display:flex;gap:12px;margin-top:24px;padding-top:24px;border-top:1px solid var(--border-color);">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Create Zone</button>
                <a href="{{ route('admin.zones.index') }}" class="btn btn-outline">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
