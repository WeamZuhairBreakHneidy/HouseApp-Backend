<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    protected function unauthenticated($request, array $guards)
    {
        abort(response()->json([
            'status' => false,
            'message' => 'Unauthorized',
            'data' => null,
        ], 401));
    }
}
