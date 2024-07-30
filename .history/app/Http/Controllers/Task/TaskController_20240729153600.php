<?php

namespace App\Http\Controllers\Task;

use App\Http\Controllers\Controller;
use App\Http\Requests\Task\TaskStoreRequest;
use App\Http\Requests\Task\TaskUpdateRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;
use App\Helpers\TaskCodeGenerator;
use App\Models\Interaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TaskController extends Controller
{
    public function index(Request $request)
    {
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

        if ($request->has('segment') && $request->segment != '0') {
            $segment = $request->input('segment');
            $query->where('segment', $segment);
        }

        return response()->json($query->get());
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
        $currentTaskType = $task->task_type;
        $newTaskType = $request->input('task_type');

        if ($currentTaskType != $newTaskType) {
            $prefix = $newTaskType == 1 ? 'I' : 'M';
            $number = intval(substr($task->task_code, 1));
            $taskData['task_code'] = $prefix . str_pad($number, 6, '0', STR_PAD_LEFT);
        }

        $task->update($taskData);

        // Recupera o usuário autenticado
        $user = Auth::user();

        $finishDate = $task->finish_date ? Carbon::parse($task->finish_date)->format('d-m-Y') : 'N/A';
        $expectedDate = $task->expected_date ? Carbon::parse($task->expected_date)->format('d-m-Y') : 'N/A';


        // Busca valores relacionados
        $complexityName = $task->complexity->name ?? 'N/A';
        $priorityName = $task->priority->name ?? 'N/A';
        $statusName = $task->status == 1 ? 'Ativo' : 'Inativo';
        $taskStatusName = $task->taskStatus->name ?? 'N/A';

        // Monta o comentário detalhado
        $comment = sprintf(
            "Complexidade: %s, Justificativa complexidade: %s, Data esperada: %s, Data conclusão: %s, Prioridade: %s, Justificativa prioridade: %s, Sequência: %s, Status: %s, Status do chamado: %s",
            $complexityName,
            $task->complexity_justification,
            $task->expected_date,
            $task->finish_date,
            $priorityName,
            $task->priority_justification,
            $task->sequence,
            $statusName,
            $taskStatusName
        );

        // Cria uma nova interação
        Interaction::create([
            'task_id' => $task->id,
            'user_id' => $user->id,
            'comment' => $comment
        ]);

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
