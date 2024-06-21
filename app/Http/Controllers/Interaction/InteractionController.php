<?php

namespace App\Http\Controllers\Interaction;

use App\Http\Controllers\Controller;
use App\Http\Requests\Interaction\InteractionStoreRequest;
use App\Http\Requests\Interaction\InteractionUpdateRequest;
use App\Models\Interaction;
use Illuminate\Http\Request;

class InteractionController extends Controller
{
    public function index()
    {
        return response()->json(Interaction::paginate(10));
    }

    public function show(Interaction $interaction, $id)
    {
        // dd($id);
        // $input = $request->validated();

        $interaction = Interaction::query()
            ->whereTask_id($id)
            ->first();

        // dd($interaction);
        return response()->json($interaction);
    }

    public function store(InteractionStoreRequest $request)
    {
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
