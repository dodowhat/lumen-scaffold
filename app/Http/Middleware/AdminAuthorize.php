<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

class AdminAuthorize
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $currentUser = Auth::guard('admin_api')->user();
        $action = $request->route()[1]['uses'];
        Gate::forUser($currentUser)->authorize('admin-rbac', [$action]);

        return $next($request);
    }
}
