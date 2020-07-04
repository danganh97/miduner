<?php

namespace App\Http\Controllers;

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
            return $this->respond($user);
        }
        return $this->respondError('wrong');
    }

    /**
     * Logout user
     *
     * @return boolean
     */
    public function logout()
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
        return $this->respond($request->user());
    }
}
