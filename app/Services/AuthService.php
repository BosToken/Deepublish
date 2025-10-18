<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;

class AuthService
{
    
    public function getUser()
    {
        return Auth::user();
    }
    
    public function login($request)
    {
        $credential = [
            'email' => $request['email'],
            'password' => $request['password'],
        ];

        if (Auth::attempt($credential)) {
            return Auth::user();
        }

        return false;
    }

    public function logout(): void
    {
        Auth::logout();
    }

}