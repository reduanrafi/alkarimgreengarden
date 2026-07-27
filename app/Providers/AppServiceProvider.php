<?php

namespace App\Providers;

use App\Services\NotificationService;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Blade::componentNamespace('App\\View\\Components', 'admin');
        Blade::anonymousComponentPath(resource_path('views/admin/components'), 'admin');

        View::composer('components.admin.topbar', function ($view) {
            $user = auth()->user();
            $notificationService = app(NotificationService::class);
            $view->with([
                'notifUnreadCount' => $user ? $notificationService->unreadCount($user) : 0,
                'notifRecent'      => $user ? $notificationService->recentUnread(5, $user) : collect(),
            ]);
        });
    }
}
