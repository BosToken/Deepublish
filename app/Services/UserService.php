<?php

namespace App\Services;

use App\Models\User;

class UserService
{

    public function getUserById($id): ?User
    {
        return User::find($id);
    }

    public function getAllUsers()
    {
        return User::paginate(10);
    }

    public function get() {
        return User::all();
    }

    public function createUser($request)
    {
        if(auth()->user()->can('create', User::class)) return User::create($request);
    }

    public function updateUser($id, $request): ?User
    {
        $user = User::find($id);
        if ($user && auth()->user()->can('update', $user)) {
            $user->update($request);
            return $user;
        }
        return null;
    }

    public function deleteUser($id): bool
    {
        $user = User::find($id);
        if ($user && auth()->user()->can('delete', $user)) {
            return $user->delete();
        }
        return false;
    }
}

?>