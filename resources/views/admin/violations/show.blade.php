@extends('layouts.admin')

@section('title', 'Violation #' . $violation->id)
@section('page-title', 'Violation Details')

@section('content')
<div class="animate-fade-up" style="max-width:800px;">
    <div class="mb-16">
        <a href="{{ route('admin.violations.index') }}" class="btn btn-ghost btn-sm"><i class="fas fa-arrow-left"></i> Back to Violations</a>
    </div>
    <div class="card">
        <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:24px;padding-bottom:20px;border-bottom:1px solid var(--border-color);">
            <div style="display:flex;align-items:center;gap:16px;">
                <div style="width:60px;height:60px;background:var(--grad-danger);border-radius:var(--radius-lg);display:flex;align-items:center;justify-content:center;color:#fff;font-size:24px;box-shadow:0 4px 15px rgba(239,71,111,0.4);">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div>
                    <div style="font-size:14px;font-weight:600;color:var(--text-muted);">Violation #{{ $violation->id }}</div>
                    <div style="font-size:22px;font-weight:800;">{{ str_replace('_',' ',ucfirst($violation->type)) }}</div>
                    <div style="font-size:13px;color:var(--text-muted);">{{ $violation->violation_time?->format('M d, Y H:i') ?? $violation->created_at->format('M d, Y H:i') }}</div>
                </div>
            </div>
            <div style="text-align:right;">
                <span class="badge badge-{{ ['pending'=>'warning','resolved'=>'success','dismissed'=>'secondary'][$violation->status] ?? 'secondary' }}" style="font-size:14px;padding:8px 20px;">{{ ucfirst($violation->status) }}</span>
                @if($violation->fine_paid)<div style="font-size:12px;font-weight:700;color:var(--success);margin-top:8px;"><i class="fas fa-check-circle"></i> Fine Paid</div>@endif
            </div>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:24px;">
            <div style="padding:16px;background:rgba(239,71,111,0.04);border:1px solid rgba(239,71,111,0.12);border-radius:var(--radius-md);">
                <div style="font-size:11px;text-transform:uppercase;letter-spacing:0.5px;color:var(--text-muted);font-weight:600;margin-bottom:6px;">Fine Amount</div>
                <div style="font-size:32px;font-weight:900;color:var(--danger);">SAR {{ number_format($violation->fine_amount, 0) }}</div>
                <div style="font-size:12px;color:var(--text-muted);margin-top:4px;">{{ $violation->fine_paid ? 'Paid ✓' : 'Unpaid' }}</div>
            </div>

            @if($violation->user)
            <div style="padding:16px;background:rgba(108,99,255,0.04);border:1px solid rgba(108,99,255,0.1);border-radius:var(--radius-md);">
                <div style="font-size:11px;text-transform:uppercase;letter-spacing:0.5px;color:var(--text-muted);font-weight:600;margin-bottom:8px;">Violator</div>
                <div style="display:flex;align-items:center;gap:10px;">
                    <img src="{{ $violation->user->avatar_url }}" style="width:38px;height:38px;border-radius:50%;border:2px solid var(--primary);object-fit:cover;">
                    <div>
                        <div style="font-weight:700;font-size:14px;">{{ $violation->user->name }}</div>
                        <div style="font-size:12px;color:var(--text-muted);">{{ $violation->user->email }}</div>
                    </div>
                </div>
            </div>
            @endif

            <div style="padding:16px;background:rgba(255,183,3,0.04);border:1px solid rgba(255,183,3,0.12);border-radius:var(--radius-md);">
                <div style="font-size:11px;text-transform:uppercase;letter-spacing:0.5px;color:var(--text-muted);font-weight:600;margin-bottom:6px;">Vehicle Plate</div>
                <div style="font-size:24px;font-weight:900;color:var(--text-primary);">{{ $violation->vehicle_plate }}</div>
            </div>

            @if($violation->spot)
            <div style="padding:16px;background:rgba(0,180,216,0.04);border:1px solid rgba(0,180,216,0.12);border-radius:var(--radius-md);">
                <div style="font-size:11px;text-transform:uppercase;letter-spacing:0.5px;color:var(--text-muted);font-weight:600;margin-bottom:6px;">Spot</div>
                <div style="font-size:20px;font-weight:900;background:var(--grad-secondary);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">{{ $violation->spot->zone->code }}-{{ $violation->spot->spot_number }}</div>
            </div>
            @endif
        </div>

        @if($violation->description)
        <div style="padding:16px;background:rgba(108,99,255,0.04);border:1px solid rgba(108,99,255,0.1);border-radius:var(--radius-md);margin-bottom:20px;">
            <div style="font-size:12px;text-transform:uppercase;letter-spacing:0.5px;color:var(--text-muted);font-weight:600;margin-bottom:6px;">Description</div>
            <p style="font-size:14px;color:var(--text-secondary);">{{ $violation->description }}</p>
        </div>
        @endif

        @if($violation->resolution_notes)
        <div style="padding:16px;background:rgba(6,214,160,0.06);border:1px solid rgba(6,214,160,0.2);border-radius:var(--radius-md);margin-bottom:20px;">
            <div style="font-size:12px;text-transform:uppercase;letter-spacing:0.5px;color:var(--success);font-weight:600;margin-bottom:6px;">Resolution Notes</div>
            <p style="font-size:14px;color:var(--text-secondary);">{{ $violation->resolution_notes }}</p>
        </div>
        @endif

        <!-- Actions -->
        <div style="display:flex;gap:12px;flex-wrap:wrap;padding-top:20px;border-top:1px solid var(--border-color);">
            @if($violation->status === 'pending')
            <form action="{{ route('admin.violations.resolve', $violation->id) }}" method="POST">
                @csrf
                <input type="hidden" name="resolution_notes" value="">
                <button type="submit" class="btn btn-success"><i class="fas fa-check"></i> Resolve</button>
            </form>
            <form action="{{ route('admin.violations.dismiss', $violation->id) }}" method="POST" onsubmit="return confirm('Dismiss this violation?')">
                @csrf
                <button type="submit" class="btn btn-outline" style="color:var(--text-muted);">Dismiss</button>
            </form>
            @endif
            @if(!$violation->fine_paid && $violation->status !== 'dismissed')
            <form action="{{ route('admin.violations.fine-paid', $violation->id) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-warning"><i class="fas fa-money-bill"></i> Mark Fine as Paid</button>
            </form>
            @endif
        </div>
    </div>
</div>
@endsection
