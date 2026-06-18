@extends('layouts.admin')

@section('title', 'Add Spot')
@section('page-title', 'Add Parking Spot')

@section('content')
<div class="animate-fade-up" style="max-width:720px;">
    <div class="mb-16">
        <a href="{{ route('admin.spots.index') }}" class="btn btn-ghost btn-sm">
            <i class="fas fa-arrow-left"></i> Back to Spots
        </a>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="card-title">New Parking Spot</div>
        </div>

        <form action="{{ route('admin.spots.store') }}" method="POST">
            @csrf
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
                <div class="form-group">
                    <label class="form-label">Zone <span class="required">*</span></label>
                    <select name="zone_id" class="form-select" required>
                        <option value="">Select Zone</option>
                        @foreach($zones as $zone)
                        <option value="{{ $zone->id }}" {{ (old('zone_id', request('zone')) == $zone->id) ? 'selected' : '' }}>{{ $zone->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Spot Number <span class="required">*</span></label>
                    <input type="text" name="spot_number" class="form-control" value="{{ old('spot_number') }}" placeholder="e.g. 001" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Spot Type <span class="required">*</span></label>
                    <select name="type" class="form-select" required>
                        @foreach(['standard','staff','disabled','vip'] as $type)
                        <option value="{{ $type }}" {{ old('type') === $type ? 'selected' : '' }}>{{ ucfirst($type) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        @foreach(['available','reserved','occupied','maintenance'] as $s)
                        <option value="{{ $s }}" {{ old('status','available') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Floor</label>
                    <input type="text" name="floor" class="form-control" value="{{ old('floor', 'G') }}" placeholder="G, 1, 2...">
                </div>
                <div class="form-group">
                    <label class="form-label">Section</label>
                    <input type="text" name="section" class="form-control" value="{{ old('section') }}" placeholder="A, B, C...">
                </div>
            </div>

            <div class="form-group">
                <label style="display:flex;align-items:center;gap:10px;cursor:pointer;font-size:14px;font-weight:600;">
                    <input type="checkbox" name="is_active" value="1" checked style="accent-color:var(--primary);width:18px;height:18px;">
                    Spot Active
                </label>
            </div>

            <div style="display:flex;gap:12px;margin-top:24px;padding-top:24px;border-top:1px solid var(--border-color);">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Create Spot</button>
                <a href="{{ route('admin.spots.index') }}" class="btn btn-outline">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
