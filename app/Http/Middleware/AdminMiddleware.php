<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized',
                'data' => null,
            ], 401);
        }

        if (Auth::user()->role !== 'admin') {
            return response()->json([
                'status' => false,
                'message' => 'Admin only',
                'data' => null,
            ], 403);
        }

        return $next($request);
    }
}
