@extends('layouts.app')

@section('title', 'Notifications')
@section('page-title', 'Notifications')
@section('page-subtitle', 'Stay updated with your parking activity')

@section('content')
<div>
    <div class="flex-between mb-20" style="flex-wrap:wrap;gap:12px;">
        <div></div>
        @if($unreadCount > 0)
        <form action="{{ route('notifications.read-all') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-ghost btn-sm">
                <i class="fas fa-check-double"></i> Mark All as Read
            </button>
        </form>
        @endif
    </div>

    @if($unreadCount > 0)
    <div class="alert alert-info mb-20">
        <span class="alert-icon"><i class="fas fa-bell"></i></span>
        <div>You have <strong>{{ $unreadCount }}</strong> unread notification(s).</div>
    </div>
    @endif

    <div class="card" style="padding:0;">
        @forelse($notifications as $notif)
        <div style="display:flex;align-items:flex-start;gap:16px;padding:18px 24px;border-bottom:1px solid rgba(108,99,255,0.06);{{ !$notif->is_read ? 'background:rgba(108,99,255,0.03);' : '' }}transition:var(--transition);" onmouseover="this.style.background='rgba(108,99,255,0.03)'" onmouseout="this.style.background='{{ !$notif->is_read ? 'rgba(108,99,255,0.03)' : 'transparent' }}'">
            <div class="notif-item-icon {{ $notif->type }}" style="width:46px;height:46px;border-radius:var(--radius-md);flex-shrink:0;font-size:17px;">
                <i class="fas fa-{{ $notif->type_icon }}"></i>
            </div>

            <div style="flex:1;min-width:0;">
                <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:12px;">
                    <div>
                        <div style="font-size:15px;font-weight:{{ !$notif->is_read ? '700' : '600' }};color:var(--text-primary);">{{ $notif->title }}</div>
                        <div style="font-size:14px;color:var(--text-secondary);margin-top:4px;line-height:1.5;">{{ $notif->message }}</div>
                        <div style="font-size:12px;color:var(--text-muted);margin-top:6px;font-weight:500;">
                            <i class="fas fa-clock" style="margin-right:4px;"></i>{{ $notif->created_at->diffForHumans() }} &bull; {{ $notif->created_at->format('M d, Y H:i') }}
                        </div>
                    </div>
                    <div style="display:flex;align-items:center;gap:8px;flex-shrink:0;">
                        @if(!$notif->is_read)
                        <div style="width:10px;height:10px;border-radius:50%;background:var(--primary);"></div>
                        @endif
                        <form action="{{ route('notifications.destroy', $notif->id) }}" method="POST" onsubmit="return confirm('Delete this notification?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm" style="background:none;border:none;cursor:pointer;color:var(--text-muted);padding:6px;border-radius:var(--radius-sm);transition:var(--transition);" onmouseover="this.style.color='var(--danger)';this.style.background='rgba(239,71,111,0.08)'" onmouseout="this.style.color='var(--text-muted)';this.style.background='none'">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                    </div>
                </div>

                @if($notif->action_url)
                <div style="margin-top:10px;">
                    <a href="{{ $notif->action_url }}" class="btn btn-ghost btn-sm" style="font-size:12px;">
                        View Details <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
                @endif
            </div>
        </div>
        @empty
        <div style="padding:80px;text-align:center;color:var(--text-muted);">
            <i class="fas fa-bell-slash" style="font-size:52px;margin-bottom:16px;display:block;opacity:0.2;"></i>
            <div style="font-size:18px;font-weight:700;margin-bottom:8px;">No notifications</div>
            <p>You're all caught up! Notifications about your parking activity will appear here.</p>
        </div>
        @endforelse
    </div>

    @if($notifications->hasPages())
    <div class="mt-20">
        <div class="pagination">
            @if($notifications->onFirstPage())
                <span class="page-btn disabled"><i class="fas fa-chevron-left"></i></span>
            @else
                <a href="{{ $notifications->previousPageUrl() }}" class="page-btn"><i class="fas fa-chevron-left"></i></a>
            @endif
            @foreach($notifications->getUrlRange(1, $notifications->lastPage()) as $page => $url)
                <a href="{{ $url }}" class="page-btn {{ $page == $notifications->currentPage() ? 'active' : '' }}">{{ $page }}</a>
            @endforeach
            @if($notifications->hasMorePages())
                <a href="{{ $notifications->nextPageUrl() }}" class="page-btn"><i class="fas fa-chevron-right"></i></a>
            @else
                <span class="page-btn disabled"><i class="fas fa-chevron-right"></i></span>
            @endif
        </div>
    </div>
    @endif
</div>
@endsection
