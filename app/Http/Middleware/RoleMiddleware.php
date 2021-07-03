<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, string $role)
    {
        $user = auth()->user();
        if ($user && $user->role === $role) {
            return $next($request);
        }
        return response()->json(['error' => "Only $role can access this route. Permission denied!"], 403);
    }
}
