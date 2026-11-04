<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Closure;
use Auth;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  mixed  ...$guards
     * @return mixed
     */
    public function handle($request, Closure $next, ...$guards)
    {
        if (Auth::check()) {
            \Log::info("user is authenticated");
            return $next($request);
        }

        if ($request->ajax() || $request->expectsJson()) {
            // Return JSON response for AJAX requests indicating a redirect
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthenticated access. Redirecting to login.',
                'redirect_url' => route('user.login'), // Full login page URL
            ], 401);
        }

        return redirect()->route('user.login');
    }
}
