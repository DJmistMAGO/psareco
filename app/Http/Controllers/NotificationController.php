<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{

    public function index(Request $request)
    {
        $filter = $request->get('filter');
        $query = $request->user()->notifications()->latest();
        if ($filter === 'unread') {
            $query->whereNull('read_at');
        } elseif ($filter === 'read') {
            $query->whereNotNull('read_at');
        }
        $notifications = $query->paginate(10)->withQueryString();
        $unreadCount = $request->user()->unreadNotifications()->count();
        $totalCount = $request->user()->notifications()->count();
        $readCount = $totalCount - $unreadCount;
        return view('admin.notifications', compact('notifications', 'unreadCount', 'readCount', 'totalCount'));
    }


    public function redirect(Request $request, string $notification)
    {
        $userNotification = $request->user()
            ->notifications()
            ->findOrFail($notification);

        $userNotification->markAsRead();

        return redirect()->route('sales.index');
    }

    public function markAllAsRead(Request $request)
    {
        $request->user()->unreadNotifications->markAsRead();

        return back()->with('success', 'All notifications marked as read.');
    }
}
