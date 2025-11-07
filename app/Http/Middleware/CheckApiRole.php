<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckApiRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  ...$roles
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!$request->user()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        $userRole = $request->user()->role;

        if (!in_array($userRole, $roles)) {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden - Anda tidak memiliki akses'
            ], 403);
        }

        return $next($request);
    }
}
