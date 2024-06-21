<?php

namespace App\Http\Controllers\Priority;

use App\Http\Controllers\Controller;
use App\Http\Requests\Priority\PriorityStoreRequest;
use App\Http\Requests\Priority\PriorityUpdateRequest;
use App\Models\Priority;
use Illuminate\Http\Request;

class PriorityController extends Controller
{
    public function index()
    {
        return response()->json(Priority::paginate(10));
    }

    public function store(PriorityStoreRequest $request)
    {
        return response()->json(Priority::create($request->validated()));
    }

    public function update(PriorityUpdateRequest $request, Priority $priority)
    {
        $priority->update($request->validated());
        return response()->json($priority);
    }

    public function destroy(Priority $priority)
    {
        $priority->delete();
        return response()->json('Procedimento Realizado');
    }

}
