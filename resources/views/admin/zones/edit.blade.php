@extends('layouts.admin')

@section('title', 'Edit Zone')
@section('page-title', 'Edit Zone: ' . $zone->name)

@section('content')
<div class="animate-fade-up" style="max-width:720px;">
    <div class="mb-16">
        <a href="{{ route('admin.zones.index') }}" class="btn btn-ghost btn-sm">
            <i class="fas fa-arrow-left"></i> Back to Zones
        </a>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="card-title">Edit Zone: {{ $zone->name }}</div>
        </div>

        <form action="{{ route('admin.zones.update', $zone->id) }}" method="POST">
            @csrf @method('PUT')

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
                <div class="form-group">
                    <label class="form-label">Zone Name <span class="required">*</span></label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $zone->name) }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Zone Code <span class="required">*</span></label>
                    <input type="text" name="code" class="form-control" value="{{ old('code', $zone->code) }}" style="text-transform:uppercase;" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Location</label>
                    <div class="input-group"><span class="input-icon"><i class="fas fa-map-marker-alt"></i></span>
                        <input type="text" name="location" class="form-control" value="{{ old('location', $zone->location) }}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Capacity</label>
                    <input type="number" name="capacity" class="form-control" value="{{ old('capacity', $zone->capacity) }}" min="1">
                </div>
                <div class="form-group">
                    <label class="form-label">Latitude</label>
                    <input type="text" name="latitude" class="form-control" value="{{ old('latitude', $zone->latitude) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Longitude</label>
                    <input type="text" name="longitude" class="form-control" value="{{ old('longitude', $zone->longitude) }}">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="3">{{ old('description', $zone->description) }}</textarea>
            </div>

            <div class="form-group">
                <label class="form-label">Allowed Roles</label>
                <div style="display:flex;gap:20px;">
                    @foreach(['student','staff','admin'] as $role)
                    <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:14px;">
                        <input type="checkbox" name="allowed_roles[]" value="{{ $role }}" {{ in_array($role, old('allowed_roles', $zone->allowed_roles ?? ['student','staff','admin'])) ? 'checked' : '' }} style="accent-color:var(--primary);width:16px;height:16px;">
                        {{ ucfirst($role) }}
                    </label>
                    @endforeach
                </div>
            </div>

            <div class="form-group">
                <label style="display:flex;align-items:center;gap:10px;cursor:pointer;font-size:14px;font-weight:600;">
                    <input type="checkbox" name="is_active" value="1" {{ $zone->is_active ? 'checked' : '' }} style="accent-color:var(--primary);width:18px;height:18px;">
                    Zone Active
                </label>
            </div>

            <div style="display:flex;gap:12px;margin-top:24px;padding-top:24px;border-top:1px solid var(--border-color);">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Changes</button>
                <a href="{{ route('admin.zones.index') }}" class="btn btn-outline">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
