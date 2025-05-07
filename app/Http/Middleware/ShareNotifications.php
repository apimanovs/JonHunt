<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ShareNotifications
{
    /**
     * Handle an incoming request.
     */
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            $notifications = Auth::user()->notifications()->latest()->get();

            Inertia::share('notifications', $notifications);
        } else {
            Inertia::share('notifications', []);
        }

        return $next($request);
    }
}
