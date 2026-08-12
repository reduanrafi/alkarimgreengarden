<?php

namespace App\Providers;

use App\Services\CatalogService;
use App\Services\NotificationService;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Dynamically override app.name from settings
        try {
            if (Schema::hasTable('settings')) {
                $siteName = setting('website_name');
                if ($siteName) {
                    config(['app.name' => $siteName]);
                }
            }
        } catch (\Exception $e) {
            // Avoid failing if migration has not run yet or db is not connected
        }

        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

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

        View::composer('components.navbar', function ($view) {
            $view->with('navbarCategories', CatalogService::categories());
        });
    }
}
