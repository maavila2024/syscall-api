<?php

namespace App\Http\Controllers\Interaction;

use App\Http\Controllers\Controller;
use App\Http\Requests\Interaction\InteractionStoreRequest;
use App\Http\Requests\Interaction\InteractionUpdateRequest;
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
        $interactions = Interaction::where('task_id', $taskId)->with('interactionFiles')->get();

        // Adicionar a URL completa do arquivo
        foreach ($interactions as $interaction) {
            foreach ($interaction->interactionFiles as $file) {
                $file->file_url = Storage::url($file->path);
            }
        }

        return response()->json($interactions);

        // $interactions = Interaction::where('task_id', $taskId)->with('interactionFiles')->get();
        // return response()->json($interactions);
    }

    public function store(InteractionStoreRequest $request)
    {
        Log::info('Request Data:', $request->all());
        return response()->json(Interaction::create($request->validated()));
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
