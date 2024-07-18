<?php

namespace App\Http\Controllers\Task;

use App\Http\Controllers\Controller;
use App\Http\Requests\Task\TaskStoreRequest;
use App\Http\Requests\Task\TaskUpdateRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;
use App\Helpers\TaskCodeGenerator;
use Illuminate\Support\Facades\Log;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        // return response()->json(Task::with('userOwner', 'userResponsible', 'taskStatus', 'priority', 'complexity', 'interactions','taskFiles')->paginate(10));
        $query = Task::with('userOwner', 'userResponsible', 'taskStatus', 'priority', 'complexity', 'interactions', 'taskFiles');

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('task_code', 'LIKE', "%{$search}%")
                    ->orWhere('name', 'LIKE', "%{$search}%")
                    ->orWhere('description', 'LIKE', "%{$search}%")
                    ->orWhereHas('userOwner', function ($q) use ($search) {
                        $q->where('first_name', 'LIKE', "%{$search}%");
                    })
                    ->orWhereHas('userResponsible', function ($q) use ($search) {
                        $q->where('first_name', 'LIKE', "%{$search}%");
                    })
                    ->orWhereHas('taskStatus', function ($q) use ($search) {
                        $q->where('name', 'LIKE', "%{$search}%");
                    })
                    ->orWhereHas('priority', function ($q) use ($search) {
                        $q->where('name', 'LIKE', "%{$search}%");
                    })
                    ->orWhereHas('complexity', function ($q) use ($search) {
                        $q->where('name', 'LIKE', "%{$search}%");
                    });
            });
        }

        return response()->json($query->paginate(10));
    }


    public function store(TaskStoreRequest $request)
    {
        $taskType = $request->input('task_type');
        $taskCode = TaskCodeGenerator::generateTaskCode($taskType);

        $taskData = $request->validated();
        $taskData['task_code'] = $taskCode;

        $task = Task::create($taskData);

        return response()->json($task, 201);
    }

    public function update(TaskUpdateRequest $request, $id)
    {

        $task = Task::findOrFail($id);

        $taskData = $request->validated();
        // dd($taskData);
        $currentTaskType = $task->task_type;
        $newTaskType = $request->input('task_type');

        if ($currentTaskType != $newTaskType) {
            $prefix = $newTaskType == 1 ? 'I' : 'M';
            $number = intval(substr($task->task_code, 1));
            $taskData['task_code'] = $prefix . str_pad($number, 6, '0', STR_PAD_LEFT);
        }
        // dd($taskData);
        $task->update($taskData);

        return response()->json($task, 200);
    }

    public function show($id)
    {
        $task = Task::findOrFail($id);
        return response()->json($task);
    }

    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        $task->delete();
        return response()->json(null, 204);
    }

    public function getTaskStatistics()
    {
        $totalTasks = Task::count();
        $statusOpen = Task::where('task_status_id', 1)->count();
        $statusInDevelopment = Task::where('task_status_id', 2)->count();
        $statusWaitingResponse = Task::where('task_status_id', 3)->count();
        $statusSentForTesting = Task::where('task_status_id', 4)->count();
        $statusCompleted = Task::where('task_status_id', 5)->count();

        return response()->json([
            'totalTasks' => $totalTasks,
            'statusOpen' => $statusOpen,
            'statusInDevelopment' => $statusInDevelopment,
            'statusWaitingResponse' => $statusWaitingResponse,
            'statusSentForTesting' => $statusSentForTesting,
            'statusCompleted' => $statusCompleted,
        ]);
    }
}
