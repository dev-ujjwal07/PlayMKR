<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
class InternalTeamMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->user() || $request->user()->role_id != 3) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized.'
            ], 403);
        }

        return $next($request);
    }
}