<?php

namespace App\Http\Controllers\Api;

use Auth;
use Request;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    /**
     * Login to the application
     *
     * @param LoginRequest $request
     *
     * @return JsonResponse
     */
    public function login(LoginRequest $request)
    {
        $email = $request->get('email');
        $password = $request->get('password');
        if (Auth::attempt(['email' => $email, 'password' => $password])) {
            $user = Auth::user();
            $token = $user->createToken([
                'exp' => 60 * 24 * 30 // 60 min x 24 hours x 30 days
            ]);

            return $this->respond([
                'token' => $token,
                'user' => $user
            ]);
        }
        return $this->respondError('Unauthorized', 401);
    }

    /**
     * Logout user
     *
     * @return boolean
     */
    public function logout(): bool
    {
        return Auth::logout();
    }

    /**
     * Get current user
     * 
     * @return JsonResponse
     */
    public function getCurrentUser(Request $request)
    {
        return $this->respond($request->user('api'));
    }
}
