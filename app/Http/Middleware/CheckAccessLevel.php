<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckAccessLevel
{
    public function handle($request, Closure $next, $level)
    {
        if (!Auth::check() || Auth::user()->access_level < $level) {
            abort(403, 'Unauthorized');
        }
        return $next($request);
    }
}
