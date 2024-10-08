<?php

namespace App\Http\Controllers\Interaction;

use App\Notifications\InteractionCreated;
use App\Http\Controllers\Controller;
use App\Http\Requests\Interaction\InteractionStoreRequest;
use App\Http\Requests\Interaction\InteractionUpdateRequest;
use App\Http\Resources\InteractionResource;
use App\Models\Interaction;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Notification;

class InteractionController extends Controller
{
    public function index()
    {
        return response()->json(Interaction::paginate(10));
    }

    public function show($taskId, Request $request)
    {
        $query = Interaction::where('task_id', $taskId)
            ->with(['interactionFiles', 'user']);

        // Se o parâmetro 'task_updated' for passado e for igual a 1, aplicar o filtro
        if ($request->has('task_updated')) {
            $query->where('task_updated', false);
        }

        $interactions = $query->get();

        // Adicionar a URL completa do arquivo e formatar a data
        foreach ($interactions as $interaction) {
            foreach ($interaction->interactionFiles as $file) {
                $file->file_url = Storage::url($file->path);
            }
            $interaction->user_email = $interaction->user->email;
            $interaction->created_at = $interaction->created_at->format('d-m-Y H:i');
        }

        return response()->json($interactions);
    }


    public function store(InteractionStoreRequest $request)
    {
        $validated = $request->validated();

        $interaction = Interaction::create([
            'task_id' => $request->task_id,
            'user_id' => $request->user_id,
            'comment' => $request->comment,
        ]);

        // Verifique se a task existe e tem um criador
        $task = Task::find($request->task_id);

        if ($task === null) {
            return response()->json(['message' => 'Task not found'], 404);
        }

        $creator = $task->userOwner;
        $responsible = $task->userResponsible;

        if ($creator === null) {
            return response()->json(['message' => 'Task creator not found'], 404);
        }

        // Verificar usuário logado
        $loggedInUserId = auth()->user()->id;

        // Determinar o destinatário da notificação
        $recipient = $loggedInUserId === $creator->id ? $responsible : $creator;

        if ($recipient === null) {
            return response()->json(['message' => 'No notification recipient found'], 404);
        }

        // Crie o título da notificação
        $title = 'Uma nota de trabalho foi criada na task ' . $task->task_code . '. Favor verificar!';

        // Enviar notificação para o criador da task
        $recipient->notify(new InteractionCreated($title, $task->task_code, $request->comment));

        // Enviar notificação para o criador da task
        // $creator->notify(new InteractionCreated($title, $interaction, $task));


        // Broadcast de evento, se necessário
        // broadcast(new InteractionCreated($interaction))->toOthers();

        return response()->json($interaction, 201);
    }


    public function update(InteractionUpdateRequest $request, Interaction $interaction)
    {
        $interaction->update($request->validated());
        return response()->json($interaction);
    }

    public function destroy(Interaction $interaction)
    {
        $interaction->delete();
        return response()->json('Procedimento Realizado');
    }
}
