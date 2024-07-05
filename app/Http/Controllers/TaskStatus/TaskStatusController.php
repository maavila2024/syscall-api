<?php

namespace App\Http\Controllers\TaskStatus;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaskStatus\TaskStatusStoreRequest;
use App\Http\Requests\TaskStatus\TaskStatusUpdateRequest;
use App\Http\Resources\TaskStatusResource;
use App\Models\TaskStatus;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Str;
use Illuminate\Auth\AuthManager;
use Illuminate\Support\Facades\Gate;


class TaskStatusController extends Controller
{
    public function index()
    {
        return response()->json(TaskStatus::paginate(10));
    }

    public function store(TaskStatusStoreRequest $request)
    {
        return response()->json(TaskStatus::create($request->validated()));
    }

    public function update(TaskStatusUpdateRequest $request, TaskStatus $taskStatus)
    {
        $taskStatus->update($request->validated());
        return response()->json($taskStatus);
    }

    public function destroy(TaskStatus $taskStatus)
    {
        $taskStatus->delete();
        return response()->json('Procedimento Realizado');
    }
}
