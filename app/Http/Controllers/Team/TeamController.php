<?php

namespace App\Http\Controllers\Team;

use App\Http\Controllers\Controller;
use App\Http\Requests\Team\TeamStoreRequest;
use App\Http\Requests\Team\TeamUpdateRequest;
use App\Http\Resources\TeamResource;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Str;
use Illuminate\Auth\AuthManager;
use Illuminate\Support\Facades\Gate;


class TeamController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $user = auth()->user();
        return TeamResource::collection(($user->teams));
    }

    public function store(TeamStoreRequest $request)
    {
        $input = $request->validated();
        $input['token'] = Str::uuid();

        $team = Team::query()->create($input);
        $user = auth()->user();
        setPermissionsTeamId($team->id);
        $user->assignRole('admin');

        return new TeamResource($team);
    }

    public function update(Team $team, TeamUpdateRequest $request): TeamResource
    {
        // dd($team);
        Gate::authorize('update', $team);
        // $this->authorize('update', $team);

        $input = $request->validated();

        $team->fill($input);
        $team->save();

        return new TeamResource($team);
    }

    public function destroy(Team $team): void
    {
        Gate::authorize('delete', $team);
        \DB::table('model_has_roles')
            ->where('team_id', $team->id)
            ->delete();
        $team->delete();
    }
}
