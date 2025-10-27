<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\UserType;
use Symfony\Component\HttpFoundation\Response;

class AdminAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated using admin guard
        if (!Auth::guard('admin')->check()) {
            return redirect()->route('admin.login')->with('fail', 'Please login to access this area.');
        }

        // Check if user is admin or super admin
        $user = Auth::guard('admin')->user();
        if ($user->role !== 'admin' && $user->role !== 'super_admin') {
            Auth::guard('admin')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('admin.login')->with('fail', 'Access denied. Invalid admin role.');
        }

        return $next($request);
    }
}
