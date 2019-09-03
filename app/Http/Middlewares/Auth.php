<?php

namespace App\Http\Middlewares;

use App\Main\Middleware;

class Auth extends Middleware
{
    public function handle()
    {
        return true;
        return response()->json([
            'success' => false,
            'message' => 'Access denied !'
        ], 401);
    }
}
