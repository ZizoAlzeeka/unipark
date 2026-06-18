@extends('layouts.admin')

@section('title', 'Issue Violation')
@section('page-title', 'Issue Parking Violation')

@section('content')
<div class="animate-fade-up" style="max-width:720px;">
    <div class="mb-16">
        <a href="{{ route('admin.violations.index') }}" class="btn btn-ghost btn-sm"><i class="fas fa-arrow-left"></i> Back to Violations</a>
    </div>
    <div class="card">
        <div class="card-header">
            <div class="card-title">Issue New Violation</div>
            <div style="width:44px;height:44px;background:var(--grad-danger);border-radius:var(--radius-md);display:flex;align-items:center;justify-content:center;color:#fff;font-size:18px;"><i class="fas fa-exclamation-triangle"></i></div>
        </div>

        <form action="{{ route('admin.violations.store') }}" method="POST">
            @csrf
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
                <div class="form-group">
                    <label class="form-label">User (Optional)</label>
                    <select name="user_id" class="form-select">
                        <option value="">Unknown User</option>
                        @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }} ({{ ucfirst($user->role) }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Vehicle Plate <span class="required">*</span></label>
                    <div class="input-group">
                        <span class="input-icon"><i class="fas fa-car"></i></span>
                        <input type="text" name="vehicle_plate" class="form-control" placeholder="ABC 123" style="text-transform:uppercase;" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Violation Type <span class="required">*</span></label>
                    <select name="type" class="form-select" required>
                        <option value="unauthorized">Unauthorized Parking</option>
                        <option value="overstay">Overstay</option>
                        <option value="wrong_spot">Wrong Spot</option>
                        <option value="no_reservation">No Reservation</option>
                        <option value="accessible_misuse">Accessible Spot Misuse</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Fine Amount (SAR) <span class="required">*</span></label>
                    <div class="input-group">
                        <span class="input-icon"><i class="fas fa-money-bill"></i></span>
                        <input type="number" name="fine_amount" class="form-control" value="{{ old('fine_amount', 100) }}" min="0" step="0.01" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Related Spot (Optional)</label>
                    <select name="spot_id" class="form-select">
                        <option value="">None</option>
                        @foreach($spots as $spot)
                        <option value="{{ $spot->id }}">{{ $spot->zone->code }}-{{ $spot->spot_number }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Violation Date/Time <span class="required">*</span></label>
                    <div class="input-group">
                        <span class="input-icon"><i class="fas fa-clock"></i></span>
                        <input type="datetime-local" name="violation_time" class="form-control" value="{{ now()->format('Y-m-d\TH:i') }}" required>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="3" placeholder="Describe the violation..."></textarea>
            </div>
            <div style="display:flex;gap:12px;margin-top:24px;padding-top:24px;border-top:1px solid var(--border-color);">
                <button type="submit" class="btn btn-danger"><i class="fas fa-exclamation-triangle"></i> Issue Violation</button>
                <a href="{{ route('admin.violations.index') }}" class="btn btn-outline">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
