<?php

namespace App\Http\Controllers;

use App\Helpers\Response;
use App\Http\Requests\{UpdateUserWithAdminRequest, UpdateTaskRequest, StoreUserRequest, StoreTaskRequest};
use App\Services\{RoleService, TaskService, UserService};

class ApiController extends Controller
{

    public function getTask(TaskService $taskService)
    {
        try {
            $tasks = $taskService->getAllTasks();
            return Response::success($tasks);
        } catch (\Throwable $th) {
            return Response::error($th->getMessage());
        }
    }

    public function getByIdTask($id, TaskService $taskService)
    {
        try {
            $task = $taskService->getTaskById($id);
            return Response::success($task);
        } catch (\Throwable $th) {
            return Response::error($th->getMessage());
        }
    }

    public function storeTask(StoreTaskRequest $request, TaskService $taskService)
    {
        try {
            $task = $taskService->createTask($request->validated());
            return Response::success($task);
        } catch (\Throwable $th) {
            return Response::error($th->getMessage());
        }
    }

    public function updateTask($id, UpdateTaskRequest $request, TaskService $taskService)
    {
        try {
            $task = $taskService->updateTask($id, $request->validated());
            return Response::success($task);
        } catch (\Throwable $th) {
            return Response::error($th->getMessage());
        }
    }

    public function deleteTask($id, TaskService $taskService)
    {
        try {
            $task = $taskService->deleteTask($id);
            return Response::success($task);
        } catch (\Throwable $th) {
            return Response::error($th->getMessage());
        }
    }

    public function getUser(UserService $userService)
    {
        try {
            $users = $userService->getAllUsers();
            return Response::success($users);
        } catch (\Throwable $th) {
            return Response::error($th->getMessage());
        }
    }

    public function getByIdUser($id, UserService $userService)
    {
        try {
            $user = $userService->getUserById($id);
            return Response::success($user);
        } catch (\Throwable $th) {
            return Response::error($th->getMessage());
        }
    }

    public function storeUser(StoreUserRequest $request, UserService $userService)
    {
        try {
            $user = $userService->createUser($request->validated());
            return Response::success($user);
        } catch (\Throwable $th) {
            return Response::error($th->getMessage());
        }
    }

    public function updateUser($id, UpdateUserWithAdminRequest $request, UserService $userService)
    {
        try {
            $user = $userService->updateUser($id, $request->validated());
            return Response::success($user);
        } catch (\Throwable $th) {
            return Response::error($th->getMessage());
        }
    }

    public function deleteUser($id, UserService $userService)
    {
        try {
            $user = $userService->deleteUser($id);
            return Response::success($user);
        } catch (\Throwable $th) {
            return Response::error($th->getMessage());
        }
    }

    public function getRole(RoleService $roleService)
    {
        try {
            $roles = $roleService->getAllRoles();
            return Response::success($roles);
        } catch (\Throwable $th) {
            return Response::error($th->getMessage());
        }
    }
}
