<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Authenticate extends Middleware
{
    protected function redirectTo(Request $request): ?string
    {
        if ($request->expectsJson()) {
            return null;
        }

        if (!Auth::check()) {
            return route('index');
        }

        $user = Auth::user();
        $currentRoute = $request->route()->getName();

        if ($user->role->name === 'Admin') {
            if (!str_starts_with($currentRoute, 'dashboard.admin')) {
                return route('dashboard.admin');
            }
        } else {
            if (!str_starts_with($currentRoute, 'dashboard.user')) {
                return route('dashboard.user');
            }
        }
        return null;
    }
}
