<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $unreadCount = Notification::where('user_id', Auth::id())->where('is_read', false)->count();

        return view('user.notifications', compact('notifications', 'unreadCount'));
    }

    public function markAsRead(Notification $notification)
    {
        if ($notification->user_id !== Auth::id()) abort(403);
        $notification->markAsRead();
        return response()->json(['success' => true]);
    }

    public function markAllAsRead()
    {
        Notification::where('user_id', Auth::id())->where('is_read', false)->update(['is_read' => true]);
        return back()->with('success', 'All notifications marked as read.');
    }

    public function destroy(Notification $notification)
    {
        if ($notification->user_id !== Auth::id()) abort(403);
        $notification->delete();
        return back()->with('success', 'Notification deleted.');
    }

    public function getUnreadCount()
    {
        $count = Notification::where('user_id', Auth::id())->where('is_read', false)->count();
        return response()->json(['count' => $count]);
    }

    public function getLatest()
    {
        $notifications = Notification::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($n) {
                return [
                    'id' => $n->id,
                    'title' => $n->title,
                    'message' => $n->message,
                    'type' => $n->type,
                    'type_color' => $n->type_color,
                    'type_icon' => $n->type_icon,
                    'is_read' => $n->is_read,
                    'time_ago' => $n->created_at->diffForHumans(),
                    'action_url' => $n->action_url,
                ];
            });

        $unreadCount = Notification::where('user_id', Auth::id())->where('is_read', false)->count();

        return response()->json(['notifications' => $notifications, 'unread_count' => $unreadCount]);
    }
}
