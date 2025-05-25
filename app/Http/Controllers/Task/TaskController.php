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
use App\Models\TaskStatus;
use App\Models\Priority;
use App\Models\Complexity;

class TaskController extends Controller
{     
    public function index(Request $request)
    {
        try {
            Log::info('Dados recebidos na requisição:', $request->all());
            
            // Melhor validação do per_page
            $perPage = is_numeric($request->per_page) && (int)$request->per_page > 0
                ? (int)$request->per_page
                : 10;

            $query = Task::query()
                ->select('tasks.*')
                ->with([
                    'userOwner:id,first_name,email',
                    'userResponsible:id,first_name,email',
                    'taskStatus:id,name,color,bg_color',
                    'priority:id,name',
                    'complexity:id,name'
                ]);

            // Busca global
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%")
                      ->orWhereHas('userOwner', fn($q) => 
                          $q->where('first_name', 'like', "%{$search}%")
                      )
                      ->orWhereHas('userResponsible', fn($q) => 
                          $q->where('first_name', 'like', "%{$search}%")
                      );
                });
            }

            // Filtros específicos com suporte a arrays
            if ($request->filled('taskStatus')) {
                $statuses = (array) $request->taskStatus;
                $query->whereHas('taskStatus', fn($q) => 
                    $q->whereIn('name', $statuses)
                );
            }

            if ($request->filled('userResponsible')) {
                $responsibles = (array) $request->userResponsible;
                $query->whereHas('userResponsible', fn($q) => 
                    $q->whereIn('first_name', $responsibles)
                );
            }

            if ($request->filled('userOwner')) {
                $owners = (array) $request->userOwner;
                $query->whereHas('userOwner', fn($q) => 
                    $q->whereIn('first_name', $owners)
                );
            }

            if ($request->filled('priority')) {
                $priorities = (array) $request->priority;
                $query->whereHas('priority', fn($q) => 
                    $q->whereIn('name', $priorities)
                );
            }

            if ($request->filled('complexity')) {
                $complexities = (array) $request->complexity;
                $query->whereHas('complexity', fn($q) => 
                    $q->whereIn('name', $complexities)
                );
            }

            if ($request->filled('segment') && $request->segment !== '0') {
                $query->where('segment', $request->segment);
            }
            
            // Filtros de data
            if ($request->filled('filter_month') && $request->filled('filter_year')) {
                $month = (int) $request->filter_month;
                $year = (int) $request->filter_year;
            
                if ($month > 0 && $month <= 12 && $year > 2000) {
                    $query->whereMonth('finish_date', $month)
                          ->whereYear('finish_date', $year);
                }
            }
            

            if (!$request->boolean('show_all')) {
                $query->whereHas('taskStatus', function ($q) {
                    $q->whereNotIn('name', ['Concluído', 'Cancelado']);
                });
            }

            // Paginação
            $tasks = $query->paginate($perPage);

            return TaskResource::collection($tasks);
            
        } catch (\Exception $e) {
            Log::error('Erro ao buscar tasks: ' . $e->getMessage());
            return response()->json(['error' => 'Erro ao buscar tasks'], 500);
        }
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

    public function getFiltersData()
    {
        try {
            // Buscar owners únicos
            $owners = Task::with('userOwner')
                ->get()
                ->pluck('userOwner')
                ->filter()
                ->unique('id')
                ->values()
                ->map(fn($user) => [
                    'id' => $user->id,
                    'first_name' => $user->first_name,
                ]);

            // Buscar responsáveis únicos
            $responsibles = Task::with('userResponsible')
                ->get()
                ->pluck('userResponsible')
                ->filter()
                ->unique('id')
                ->values()
                ->map(fn($user) => [
                    'id' => $user->id,
                    'first_name' => $user->first_name,
                ]);

            // Buscar outros dados de filtro
            $statuses = TaskStatus::select('id', 'name', 'color', 'bg_color')
                ->distinct()
                ->get();
                
            $priorities = Priority::select('id', 'name')
                ->distinct()
                ->get();
                
            $complexities = Complexity::select('id', 'name')
                ->distinct()
                ->get();

            return response()->json([
                'owners' => $owners,
                'responsibles' => $responsibles,
                'statuses' => $statuses,
                'priorities' => $priorities,
                'complexities' => $complexities,
            ]);
        } catch (\Exception $e) {
            Log::error('Erro ao buscar dados dos filtros: ' . $e->getMessage());
            return response()->json(['error' => 'Erro ao carregar dados dos filtros'], 500);
        }
    }
}
