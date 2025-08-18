<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RedirectBasedOnRole
{
    public function handle(Request $request, Closure $next)
    {
        Log::info('RedirectBasedOnRole middleware executed for user: ' . Auth::id());

        if (!Auth::check()) {
            return redirect('/'); // Redirect to login if not authenticated
        }

        $user = Auth::user();

        if (!$user || !in_array($user->role, ['admin', 'designer', 'super_admin', 'customer'])) {
            abort(403, 'Unauthorized access.');
        }

        // Prevent redirect loop by checking the current route
        $currentRoute = $request->route()->getName();

        switch ($user->role) {
            case 'admin':
            case 'designer':
            case 'super_admin':
                if ($currentRoute !== 'dashboard') {
                    return redirect()->route('dashboard');
                }
                break;
            case 'customer':
                if ($currentRoute !== 'customers.landing') {
                    return redirect()->route('customers.landing');
                }
                break;
        }

        return $next($request);
    }
}