@extends('layouts.admin')

@section('title', 'Log Entry/Exit Event')
@section('page-title', 'Log Entry/Exit Event')

@section('content')
<div class="animate-fade-up" style="max-width:720px;">
    <div class="mb-16">
        <a href="{{ route('admin.entry-exit.index') }}" class="btn btn-ghost btn-sm"><i class="fas fa-arrow-left"></i> Back to Logs</a>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="card-title">Log New Entry/Exit Event</div>
            <div style="width:44px;height:44px;background:var(--grad-secondary);border-radius:var(--radius-md);display:flex;align-items:center;justify-content:center;color:#fff;font-size:18px;"><i class="fas fa-exchange-alt"></i></div>
        </div>

        <form action="{{ route('admin.entry-exit.store') }}" method="POST">
            @csrf
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
                <div class="form-group">
                    <label class="form-label">User (Optional)</label>
                    <select name="user_id" class="form-select">
                        <option value="">Unknown / Not Registered</option>
                        @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }} ({{ ucfirst($user->role) }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Parking Spot (Optional)</label>
                    <select name="spot_id" class="form-select">
                        <option value="">Select Spot</option>
                        @foreach($spots as $spot)
                        <option value="{{ $spot->id }}">{{ $spot->zone->code }}-{{ $spot->spot_number }} ({{ ucfirst($spot->status) }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Event Type <span class="required">*</span></label>
                    <div style="display:flex;gap:16px;margin-top:6px;">
                        <label style="display:flex;align-items:center;gap:8px;cursor:pointer;padding:12px 20px;border:2px solid var(--success);background:rgba(6,214,160,0.06);border-radius:var(--radius-md);flex:1;justify-content:center;" id="entryLabel">
                            <input type="radio" name="event_type" value="entry" checked style="display:none;">
                            <i class="fas fa-arrow-right" style="color:var(--success);"></i>
                            <span style="font-weight:700;font-size:14px;">Entry</span>
                        </label>
                        <label style="display:flex;align-items:center;gap:8px;cursor:pointer;padding:12px 20px;border:2px solid var(--border-color);border-radius:var(--radius-md);flex:1;justify-content:center;transition:var(--transition);" id="exitLabel">
                            <input type="radio" name="event_type" value="exit" style="display:none;">
                            <i class="fas fa-arrow-left" style="color:var(--danger);"></i>
                            <span style="font-weight:700;font-size:14px;">Exit</span>
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Event Time <span class="required">*</span></label>
                    <div class="input-group">
                        <span class="input-icon"><i class="fas fa-clock"></i></span>
                        <input type="datetime-local" name="event_time" class="form-control" value="{{ now()->format('Y-m-d\TH:i') }}" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Vehicle Plate <span class="required">*</span></label>
                    <div class="input-group">
                        <span class="input-icon"><i class="fas fa-car"></i></span>
                        <input type="text" name="vehicle_plate" class="form-control" placeholder="ABC 123" style="text-transform:uppercase;" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Method</label>
                    <select name="method" class="form-select">
                        <option value="manual">Manual (Admin)</option>
                        <option value="scan">Scan / QR</option>
                        <option value="plate_recognition">Plate Recognition</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Notes</label>
                <textarea name="notes" class="form-control" rows="2" placeholder="Optional notes..."></textarea>
            </div>
            <div style="display:flex;gap:12px;margin-top:24px;padding-top:24px;border-top:1px solid var(--border-color);">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Log Event</button>
                <a href="{{ route('admin.entry-exit.index') }}" class="btn btn-outline">Cancel</a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.querySelectorAll('input[name="event_type"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const entryLabel = document.getElementById('entryLabel');
            const exitLabel = document.getElementById('exitLabel');
            if (this.value === 'entry') {
                entryLabel.style.borderColor = 'var(--success)';
                entryLabel.style.background = 'rgba(6,214,160,0.06)';
                exitLabel.style.borderColor = 'var(--border-color)';
                exitLabel.style.background = 'transparent';
            } else {
                exitLabel.style.borderColor = 'var(--danger)';
                exitLabel.style.background = 'rgba(239,71,111,0.06)';
                entryLabel.style.borderColor = 'var(--border-color)';
                entryLabel.style.background = 'transparent';
            }
        });
    });
</script>
@endpush
@endsection
