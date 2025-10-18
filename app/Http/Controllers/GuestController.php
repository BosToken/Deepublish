<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Services\AuthService;

class GuestController extends Controller
{
    function index()
    {
        return view('welcome');
    }

    function login(LoginRequest $request, AuthService $authService)
    {
        $login = $authService->login($request->validated());
        if ($login && $login->role->name === 'Admin') {
            return redirect()->route('dashboard.admin');
        } elseif ($login && $login->role->name === 'User') {
            return redirect()->route('dashboard.user');
        } else {
            return redirect()->route('index');
        }
    }

    function logout(AuthService $authService)
    {
        $authService->logout();
        return redirect()->route('index');
    }
}
