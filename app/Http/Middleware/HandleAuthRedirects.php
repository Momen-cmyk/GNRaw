<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class HandleAuthRedirects
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Handle root URL redirects based on authentication status
        if ($request->is('/')) {
            // Check if any user is authenticated and redirect accordingly
            if (Auth::guard('admin')->check()) {
                return redirect()->route('admin.dashboard');
            } elseif (Auth::guard('supplier')->check()) {
                return redirect()->route('supplier.dashboard');
            } elseif (Auth::guard('web')->check()) {
                return redirect()->route('user.dashboard');
            }
            // If no one is authenticated, show the home page
            return $next($request);
        }

        // Handle admin redirects
        if ($request->is('admin') || $request->is('admin/')) {
            if (Auth::guard('admin')->check()) {
                return redirect()->route('admin.dashboard');
            }
            return redirect()->route('admin.login');
        }

        // Handle supplier redirects
        if ($request->is('supplier') || $request->is('supplier/')) {
            if (Auth::guard('supplier')->check()) {
                return redirect()->route('supplier.dashboard');
            }
            return redirect()->route('supplier.login');
        }

        return $next($request);
    }
}
