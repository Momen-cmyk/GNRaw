<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Add global middleware
        $middleware->append(App\Http\Middleware\SetLocale::class);

        $middleware->alias([
            'preventBackHistory' => App\Http\Middleware\PreventBackHistory::class,
            'adminAccess' => App\Http\Middleware\AdminAccess::class,
            'supplierAccess' => App\Http\Middleware\SupplierAccess::class,
            'setUserLanguage' => App\Http\Middleware\SetUserLanguage::class,
            'locale' => App\Http\Middleware\SetLocale::class,
            'handleAuthRedirects' => App\Http\Middleware\HandleAuthRedirects::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
