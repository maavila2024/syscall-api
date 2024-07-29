<?php

namespace App\Http\Controllers\Interaction;

use App\Events\InteractionCreated;
use App\Http\Controllers\Controller;
use App\Http\Requests\Interaction\InteractionStoreRequest;
use App\Http\Requests\Interaction\InteractionUpdateRequest;
use App\Http\Resources\InteractionResource;
use App\Models\Interaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class InteractionController extends Controller
{
    public function index()
    {
        return response()->json(Interaction::paginate(10));
    }

    public function show($taskId)
    {
        $interactions = Interaction::where('task_id', $taskId)
            ->with(['interactionFiles', 'user'])
            ->get();

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

        // broadcast(new InteractionCreated($interaction))->toOthers();
        $interaction->task->creator->notify(new InteractionCreated($interaction));

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
