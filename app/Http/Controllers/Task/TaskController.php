<?php

namespace App\Http\Controllers\Task;

use App\Http\Controllers\Controller;
use App\Http\Requests\Task\TaskStoreRequest;
use App\Http\Requests\Task\TaskUpdateRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        return response()->json(Task::with('user', 'taskStatus', 'priority', 'interactions')->paginate(10));
    }

    public function store(TaskStoreRequest $request)
    {
        return response()->json(Task::create($request->validated()));
    }

    public function update(TaskUpdateRequest $request, Task $task)
    {
        $task->update($request->validated());
        return response()->json($task);
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return response()->json('Procedimento Realizado');
    }
}
