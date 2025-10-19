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
        return User::with('role')->paginate(10);
    }

    public function get()
    {
        return User::all();
    }

    public function createUser($request)
    {
        $data = is_array($request) ? $request : $request->all();
        $trashedUser = User::onlyTrashed()->where('email', $data['email'])->first();

        if ($trashedUser) {
            return $this->restoreUser($data);
        }

        if (auth()->user()->can('create', User::class)) {
            return User::create($data);
        }

        return null;
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
        if (!$user) {
            return false;
        }
        if (!auth()->user()->can('delete', $user)) {
            return false;
        }
        $hasTasks = $user->tasks()->exists();
        if ($hasTasks) {
            return $user->delete();
        } else {
            return $user->forceDelete();
        }
    }

    public function restoreUser($request): ?User
    {
        $data = is_array($request) ? $request : $request->all();
        $user = User::onlyTrashed()->where('email', $data['email'])->first();
        if (!$user) {
            return null;
        }


        $user->restore();
        $user->update($data);
        return $user;
    }
}
