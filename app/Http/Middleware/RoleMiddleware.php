<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    // public function handle(Request $request, Closure $next): Response
    // {
    //     if (!$request->user() || !$request->user()->hasRole($roles)) {
    //         return response()->json(['message' => 'Accès refusé'], 403);
    //     }

    //     return $next($request);
    // }

    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!$request->user() || !$request->user()->hasAnyRole($roles)) {
            return response()->json(['message' => 'Accès refusé'], 403);
        }

        return $next($request);
    }
}
