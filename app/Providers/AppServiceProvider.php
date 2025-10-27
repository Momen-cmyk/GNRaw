<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Middleware\RedirectIfAuthenticated;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use App\Models\ProductCategory;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Share categories globally for header navigation (without binary data)
        View::composer('front.layout.app', function ($view) {
            $categories = ProductCategory::where('is_active', true)->get();
            $view->with('categories', $categories);
        });

        // Redirect authenticated users to appropriate dashboard based on guard
        RedirectIfAuthenticated::redirectUsing(function ($request) {
            // Check which guard is being used based on the route
            if ($request->is('admin/*')) {
                return route('admin.dashboard');
            } elseif ($request->is('supplier/*')) {
                return route('supplier.dashboard');
            } else {
                return route('user.dashboard');
            }
        });

        // Redirect unauthenticated users to appropriate login page based on route
        Authenticate::redirectUsing(function ($request) {
            Session::flash('fail', 'You must be logged in to access that page.');

            if ($request->is('admin/*')) {
                return route('admin.login');
            } elseif ($request->is('supplier/*')) {
                return route('supplier.login');
            } else {
                return route('login');
            }
        });
    }
}
