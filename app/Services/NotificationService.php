<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;

class NotificationService
{
    public function create(string $type, string $module, string $title, ?string $description = null): Notification
    {
        $notification = Notification::create([
            'type'        => $type,
            'module'      => $module,
            'title'       => $title,
            'description' => $description,
        ]);

        return $notification;
    }

    public function markAsRead(Notification $notification, User $user): void
    {
        $notification->readByUsers()->syncWithoutDetaching([
            $user->id => ['read_at' => now()],
        ]);
    }

    public function markAllAsRead(User $user): void
    {
        $unread = $this->unreadQuery($user)->get();
        foreach ($unread as $notification) {
            $this->markAsRead($notification, $user);
        }
    }

    public function deleteNotification(Notification $notification): void
    {
        $notification->delete();
    }

    public function clearAll(User $user): void
    {
        Notification::whereHas('readByUsers', fn($q) => $q->where('user_id', $user->id))->delete();
        Notification::whereDoesntHave('readByUsers')->delete();
    }

    public function unreadQuery(?User $user = null)
    {
        if (!$user) {
            $user = auth()->user();
        }
        $userId = $user->id;

        return Notification::whereDoesntHave('readByUsers', function ($q) use ($userId) {
            $q->where('user_id', $userId)->whereNotNull('read_at');
        });
    }

    public function unreadCount(?User $user = null): int
    {
        return $this->unreadQuery($user)->count();
    }

    public function recentUnread(int $limit = 5, ?User $user = null)
    {
        return $this->unreadQuery($user)->latest()->limit($limit)->get();
    }

    public function recent(int $limit = 5, ?User $user = null)
    {
        if (!$user) {
            $user = auth()->user();
        }
        return Notification::latest()->limit($limit)->get()->map(function ($n) use ($user) {
            $n->is_read = $n->isReadBy($user);
            return $n;
        });
    }
}
