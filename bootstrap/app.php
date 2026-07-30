<?php

use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\CheckBlocked;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: [__DIR__.'/../routes/web.php', __DIR__.'/../routes/sitemap.php'],
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => AdminMiddleware::class,
            'seller' => \App\Http\Middleware\SellerMiddleware::class,
            'blocked' => CheckBlocked::class,
        ]);

        $middleware->web(append: [
            ThrottleRequests::class.':60,1',
        ]);

        $middleware->throttleApi();
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (NotFoundHttpException $e) {
            return response()->view('errors.404', [], 404);
        });

        $exceptions->render(function (AccessDeniedHttpException $e) {
            return response()->view('errors.403', [], 403);
        });

        $exceptions->render(function (HttpException $e) {
            if ($e->getStatusCode() === 403) {
                return response()->view('errors.403', [], 403);
            }
            if ($e->getStatusCode() === 429) {
                return response()->view('errors.429', [], 429);
            }
            if ($e->getStatusCode() === 419) {
                return response()->view('errors.419', [], 419);
            }
        });

        $exceptions->render(function (Throwable $e) {
            if (app()->isProduction()) {
                return response()->view('errors.500', [], 500);
            }
        });
    })->create();
