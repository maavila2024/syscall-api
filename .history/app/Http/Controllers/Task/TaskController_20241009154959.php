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
use Illuminate\Support\Facades\Response;

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
                    // ->orWhere('description', 'LIKE', "%{$search}%")
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

        if ($request->has('priority') && $request->input('priority')) {
            $priority = $request->input('priority');
            $query->whereHas('priority', function ($q) use ($priority) {
                $q->where('name', $priority);
            });
        }

        if ($request->has('complexity') && $request->input('complexity')) {
            $complexity = $request->input('complexity');
            $query->whereHas('complexity', function ($q) use ($complexity) {
                $q->where('name', $complexity);
            });
        }

        if ($request->has('taskStatus') && $request->input('taskStatus')) {
            $taskStatus = $request->input('taskStatus');
            $query->whereHas('taskStatus', function ($q) use ($taskStatus) {
                $q->where('name', $taskStatus);
            });
        }

        if ($request->has('userResponsible') && $request->input('userResponsible')) {
            $userResponsible = $request->input('userResponsible');
            $query->whereHas('userResponsible', function ($q) use ($userResponsible) {
                $q->where('name', $userResponsible);
            });
        }

        if ($request->has('userOwner') && $request->input('userOwner')) {
            $userOwner = $request->input('userOwner');
            $query->whereHas('userOwner', function ($q) use ($userOwner) {
                $q->where('name', $userOwner);
            });
        }

        $tasks = $query->paginate(500);

        return response()->json($tasks);

        // return response()->json($query->get());
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

        // Formatar as datas
        $finishDate = $task->finish_date ? Carbon::parse($task->finish_date)->format('d-m-Y') : '';
        $expectedDate = $task->expected_date ? Carbon::parse($task->expected_date)->format('d-m-Y') : '';

        $complexityName = $task->complexity->name ?? '';
        $priorityName = $task->priority->name ?? '';
        $taskStatusName = $task->taskStatus->name ?? '';

        // Criar registro na tabela interactions
        $userId = auth()->user()->id;
        Interaction::create([
            'task_id' => $task->id,
            'user_id' => $userId,
            'comment' => "Chamado atualizado! " .
                "Status do chamado: $taskStatusName , Sequência: {$task->sequence}, " .
                "Complexidade: $complexityName, Justificativa complexidade: {$task->complexity_justification}, " .
                "Prioridade: $priorityName, Justificativa prioridade: {$task->priority_justification}, " .
                "Data esperada: $expectedDate, Data conclusão: $finishDate .",
            'task_updated' => true
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

    public function getChartTaskStatistics(Request $request)
    {
        // Obtém os parâmetros segment e date da requisição
        $segment = $request->input('segment');
        $date = $request->input('date');

        // Define o intervalo de datas para o filtro, considerando o mês completo
        $startDate = Carbon::parse($date)->startOfMonth();
        $endDate = Carbon::parse($date)->endOfMonth();

        // Aplica os filtros de segment e date se presentes
        $query = Task::query();

        if ($segment) {
            $query->where('segment', $segment);
        }

        if ($date) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        // Obtém as estatísticas filtradas
        $totalTasks = $query->count();
        $statusOpen = $query->clone()->where('task_status_id', 1)->count();
        $statusInDevelopment = $query->clone()->where('task_status_id', 2)->count();
        $statusWaitingResponse = $query->clone()->where('task_status_id', 3)->count();
        $statusSentForTesting = $query->clone()->where('task_status_id', 4)->count();
        $statusCompleted = $query->clone()->where('task_status_id', 5)->count();

        return response()->json([
            'totalTasks' => $totalTasks,
            'statusOpen' => $statusOpen,
            'statusInDevelopment' => $statusInDevelopment,
            'statusWaitingResponse' => $statusWaitingResponse,
            'statusSentForTesting' => $statusSentForTesting,
            'statusCompleted' => $statusCompleted,
        ]);
    }

    public function exportTasks()
    {
        $tasks = Task::where('task_status_id', 1)->get();

        $csvData = "id,name,description,created_at\n";
        foreach ($tasks as $task) {
            $csvData .= "{$task->id},{$task->name},{$task->description},{$task->created_at}\n";
        }

        $filename = "tasks_export_" . date('Y-m-d_H-i-s') . ".csv";
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        return Response::make(rtrim($csvData, "\n"), 200, $headers);
    }
}
