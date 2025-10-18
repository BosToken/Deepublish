<?php

namespace App\Services;

use App\Models\Task;

class TaskService
{

    public function getTaskById($id)
    {
        $task = Task::find($id);
        if(auth()->user()->can('view', $task)) return $task;
    }

    public function getAllTasks()
    {
        $task = Task::paginate(10);
        return $task;
    }

    public function getTasksByUserId($userId)
    {
        $task = Task::where('user_id', $userId)->paginate(10);
        return $task;
    }

    public function createTask($request)
    {
        if(auth()->user()->can('create', Task::class)) return Task::create($request);
    }

    public function updateTask($id, $request)
    {
        $Task = Task::find($id);
        if ($Task && auth()->user()->can('update', $Task)) {
            $Task->update($request);
            return $Task;
        }
        return null;
    }

    public function deleteTask($id): bool
    {
        $Task = Task::find($id);
        if ($Task && auth()->user()->can('delete', $Task)) {
            return $Task->delete();
        }
        return false;
    }
}
?>