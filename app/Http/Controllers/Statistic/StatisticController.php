<?php

namespace App\Http\Controllers\Statistic;

use App\Http\Controllers\Controller;
use App\Http\Requests\Statistic\StatisticStoreRequest;
use App\Http\Requests\Statistic\StatisticUpdateRequest;
use App\Models\Statistic;
use Illuminate\Http\Request;

class StatisticController extends Controller
{
    public function index()
    {
        return response()->json(Statistic::paginate(10));
    }

    public function store(StatisticStoreRequest $request)
    {
        return response()->json(Statistic::create($request->validated()));
    }

    public function update(StatisticUpdateRequest $request, Statistic $statistic)
    {
        $statistic->update($request->validated());
        return response()->json($statistic);
    }

    public function destroy(Statistic $statistic)
    {
        $statistic->delete();
        return response()->json('Procedimento Realizado');
    }
}
