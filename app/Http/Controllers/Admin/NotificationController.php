<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function __construct(protected NotificationService $notificationService) {}

    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Notification::latest();

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('module', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        if ($status = $request->get('status')) {
            if ($status === 'unread') {
                $query->whereDoesntHave('readByUsers', fn($q) => $q->where('user_id', $user->id)->whereNotNull('read_at'));
            } elseif ($status === 'read') {
                $query->whereHas('readByUsers', fn($q) => $q->where('user_id', $user->id)->whereNotNull('read_at'));
            }
        }

        if ($dateFilter = $request->get('date_filter')) {
            match ($dateFilter) {
                'today'     => $query->whereDate('created_at', today()),
                'week'      => $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]),
                'month'     => $query->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year),
                default     => null,
            };
        }

        $notifications = $query->paginate(20)->withQueryString();

        $notifications->getCollection()->transform(function ($n) use ($user) {
            $n->is_read = $n->isReadBy($user);
            return $n;
        });

        $unreadCount = $this->notificationService->unreadCount($user);

        return view('admin.notifications.index', compact('notifications', 'unreadCount'));
    }

    public function markAsRead(Notification $notification)
    {
        $this->notificationService->markAsRead($notification, auth()->user());
        return back()->with('success', 'Notification marked as read.');
    }

    public function markAllAsRead()
    {
        $this->notificationService->markAllAsRead(auth()->user());
        return back()->with('success', 'All notifications marked as read.');
    }

    public function destroy(Notification $notification)
    {
        $this->notificationService->deleteNotification($notification);
        return back()->with('success', 'Notification deleted.');
    }

    public function clearAll()
    {
        $this->notificationService->clearAll(auth()->user());
        return back()->with('success', 'All notifications cleared.');
    }
}
