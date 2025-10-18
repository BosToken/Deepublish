<?php

namespace App\Http\Controllers;

use App\Services\{AuthService, TaskService, UserService};
use App\Http\Requests\{UpdateUserRequest, UpdateTaskRequest};

class UserController extends Controller
{
    function dashboard(AuthService $authService, TaskService $taskService)
    {
        $userId = $authService->getUser()->id;
        $tasks = $taskService->getTasksByUserId($userId);
        return view('user.dashboard', ['tasks' => $tasks]);
    }

    function profile(AuthService $authService)
    {
        $method = 'GET';
        $user = $authService->getUser();
        return view('user.profile', ['user' => $user, 'method' => $method]);
    }

    function editProfile(AuthService $authService)
    {
        $method = 'EDIT';
        $user = $authService->getUser();
        return view('user.profile', ['user' => $user, 'method' => $method]);
    }

    function updateProfile(UpdateUserRequest $request, UserService $userService, AuthService $authService)
    {
        $user = $authService->getUser();
        $userService->updateUser($user->id, $request->validated());
        return redirect()->route('profile.user');
    }

    function taskDetail($id, TaskService $taskService)
    {
        $method = 'GET';
        $task = $taskService->getTaskById($id);
        return view('user.task', ['task' => $task, 'method' => $method]);
    }

    function editTask($id, TaskService $taskService)
    {
        $method = 'EDIT';
        $task = $taskService->getTaskById($id);
        return view('user.task', ['task' => $task, 'method' => $method]);
    }

    function updateTask($id, UpdateTaskRequest $request, TaskService $taskService)
    {
        $taskService->updateTask($id, $request->validated());
        return redirect()->route('task.user', ['id' => $id]);
    }
}
