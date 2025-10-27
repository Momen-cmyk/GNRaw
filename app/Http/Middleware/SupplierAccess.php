<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\UserType;
use Symfony\Component\HttpFoundation\Response;

class SupplierAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated using supplier guard
        if (!Auth::guard('supplier')->check()) {
            return redirect()->route('supplier.login')->with('fail', 'Please login to access this area.');
        }

        // Check if user is supplier
        $user = Auth::guard('supplier')->user();
        if (!$user->is_active) {
            Auth::guard('supplier')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('supplier.login')->with('fail', 'Account is inactive.');
        }

        return $next($request);
    }
}
