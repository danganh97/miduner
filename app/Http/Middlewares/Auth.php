<?php

namespace App\Http\Middlewares;

use App\Http\Exceptions\Exception;
use App\Main\Middleware;

class Auth extends Middleware
{
    public function handle()
    {
        // return simpleView('403');
        return true;
        // return response()->json([
        //     'success' => false,
        //     'message' => 'Access denied !'
        // ], 401);
    }
}
