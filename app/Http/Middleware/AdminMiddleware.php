<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(
        Request $request,
        Closure $next
    )
    {
        $user = Auth::user();

        if (
            !$user ||
            $user->role_id != 1
        ) {
            return response()->json([

                'status' => false,

                'message' => 'Only Admin Can Access'

            ], 403);
        }else{


        
        }

        return $next($request);
    }
}