<?php

namespace App\Http\Controllers;

use App\Http\Requests\{StoreTaskRequest, StoreUserRequest, UpdateTaskRequest, UpdateUserWithAdminRequest, UpdateUserRequest};
use App\Services\{RoleService, TaskService, UserService};

class AdminController extends Controller
{
    //
    function dashboard()
    {
        $user = auth()->user();
        return view('admin.dashboard', ['user' => $user]);
    }

    function taskIndex(TaskService $taskService)
    {
        $tasks = $taskService->getAllTasks();
        return view('admin.task', ['tasks' => $tasks]);
    }

    function taskCreate(UserService $userService)
    {
        $method = 'CREATE';
        $users = $userService->get();
        return view('admin.task-form', ['method' => $method, 'users' => $users]);
    }

    function taskStore(StoreTaskRequest $request, TaskService $taskService)
    {
        $taskService->createTask($request->validated());
        return redirect()->route('task.admin');
    }

    function taskEdit($id, TaskService $taskService, UserService $userService)
    {
        $method = 'EDIT';
        $task = $taskService->getTaskById($id);
        $users = $userService->get();
        return view('admin.task-form', ['method' => $method, 'task' => $task, 'users' => $users]);
    }

    function taskUpdate($id, UpdateTaskRequest $request, TaskService $taskService)
    {
        $taskService->updateTask($id, $request->validated());
        return redirect()->route('task.admin');
    }

    function taskDelete($id, TaskService $taskService)
    {
        $taskService->deleteTask($id);
        return redirect()->route('task.admin');
    }

    function userIndex(UserService $userService)
    {
        $users = $userService->getAllUsers();
        return view('admin.user', ['users' => $users]);
    }

    function userCreate(RoleService $roleService)
    {
        $method = 'CREATE';
        $roles = $roleService->getAllRoles();
        return view('admin.user-form', ['method' => $method, 'roles' => $roles]);
    }

    function userStore(StoreUserRequest $request, UserService $userService)
    {
        $userService->createUser($request->validated());
        return redirect()->route('user.admin');
    }

    function userEdit($id, UserService $userService, RoleService $roleService)
    {
        $method = 'EDIT';
        $user = $userService->getUserById($id);
        $roles = $roleService->getAllRoles();
        return view('admin.user-form', ['method' => $method, 'user' => $user, 'roles' => $roles]);
    }

    function userUpdate($id, UpdateUserWithAdminRequest $request, UserService $userService)
    {
        $userService->updateUser($id, $request->validated());
        return redirect()->route('user.admin');
    }

    function userDelete($id, UserService $userService)
    {
        $userService->deleteUser($id);
        return redirect()->route('user.admin');
    }
}
