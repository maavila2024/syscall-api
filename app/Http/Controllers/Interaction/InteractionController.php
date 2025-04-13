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

    public function show($id, Request $request)
    {
        Log::info('Buscando interações para task:', ['taskId' => $id]);
        
        try {
            $query = Interaction::where('task_id', $id)
                ->with(['interactionFiles', 'user'])
                ->orderBy('created_at', 'desc');

            if ($request->has('task_updated')) {
                $query->where('task_updated', false);
            }

            $interactions = $query->get();

            Log::info('Interações encontradas:', [
                'count' => $interactions->count(),
                'ids' => $interactions->pluck('id')
            ]);

            foreach ($interactions as $interaction) {
                foreach ($interaction->interactionFiles as $file) {
                    $file->file_url = Storage::disk('s3')->url($file->path);
                    Log::info('URL do arquivo:', ['url' => $file->file_url]);
                }
                $interaction->user_email = $interaction->user->email;
                $interaction->created_at = $interaction->created_at->format('d-m-Y H:i');
            }

            return response()->json($interactions);
        } catch (\Exception $e) {
            Log::error('Erro ao buscar interações:', [
                'error' => $e->getMessage(),
                'taskId' => $id
            ]);
            return response()->json(['error' => 'Erro ao buscar interações'], 500);
        }
    }

    public function store(InteractionStoreRequest $request)
{
    $validated = $request->validated();

    // Cria a interação normalmente
    $interaction = Interaction::create([
        'task_id' => $request->task_id,
        'user_id' => $request->user_id,
        'comment' => $request->comment,
    ]);

    // Busca a task relacionada
    $task = Task::find($request->task_id);

    if (!$task) {
        return response()->json(['message' => 'Task not found'], 404);
    }

    $creator = $task->userOwner;
    $responsible = $task->userResponsible;
    $loggedInUserId = auth()->user()->id;

    // Define o destinatário da notificação, se possível
    $recipient = null;

    if ($responsible && $loggedInUserId !== $responsible->id) {
        $recipient = $responsible;
    } elseif ($creator && $loggedInUserId !== $creator->id) {
        $recipient = $creator;
    }

    if ($recipient) {
        $title = 'Uma nota de trabalho foi criada na task ' . $task->task_code . '. Favor verificar!';
        $recipient->notify(new InteractionCreated($title, $task->task_code, $request->comment));
    } else {
        Log::info('Interação criada sem destinatário para notificação', [
            'task_id' => $task->id,
            'creator_id' => $creator?->id,
            'responsible_id' => $responsible?->id,
            'logged_user_id' => $loggedInUserId,
        ]);
    }

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
