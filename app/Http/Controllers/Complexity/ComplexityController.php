<?php

namespace App\Http\Controllers\Complexity;

use App\Http\Controllers\Controller;
use App\Http\Requests\Complexity\ComplexityStoreRequest;
use App\Http\Requests\Complexity\ComplexityUpdateRequest;
use App\Models\Complexity;
use Illuminate\Http\Request;

class ComplexityController extends Controller
{
    public function index()
    {
        return response()->json(Complexity::paginate(10));
    }

    public function store(ComplexityStoreRequest $request)
    {
        return response()->json(Complexity::create($request->validated()));
    }

    public function update(ComplexityUpdateRequest $request, Complexity $complexity)
    {
        $complexity->update($request->validated());
        return response()->json($complexity);
    }

    public function destroy(Complexity $complexity)
    {
        $complexity->delete();
        return response()->json('Procedimento Realizado');
    }

}
