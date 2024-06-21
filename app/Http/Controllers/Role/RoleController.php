<?php

namespace App\Http\Controllers\Role;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Role\RoleStoreRequest;
use App\Http\Requests\Role\RoleUpdateRequest;
use App\Http\Resources\RoleResource;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Role;


class RoleController extends Controller
{

    public function index()
    {
        // Gate::authorize('is-admin');
        $roles = Role::paginate(10);
        // return view('dashboard/role/index', compact('roles'));
        return RoleResource::collection(($roles));
    }

    public function create()
    {
        // Gate::authorize('is-admin');
        $role = new Role();
        // dd($role );
        // return view('dashboard.role.create', compact('role'));
        return new RoleResource($role);
    }

    public function store(RoleStoreRequest $request)
    {
        $input = $request->validated();
        // $input['token'] = Str::uuid();

        $role = Role::query()->create($input);
        // dd($role);
        // Gate::authorize('is-admin');
        // Role::create($request->validated());

        // return to_route('role.index')->with('status', 'Role created');
        return new RoleResource($role);
    }

    public function show(Role $role)
    {
        Gate::authorize('is-admin');
        return view('dashboard/role/show',['role'=> $role]);
    }

    public function edit(Role $role)
    {
        Gate::authorize('is-admin');
        // return view('dashboard.role.edit', compact('role'));
        return new RoleResource($role);
    }

    public function update(RoleUpdateRequest $request, Role $role)
    {
        Gate::authorize('is-admin');
        $role->update($request->validated());
        // return to_route('role.index')->with('status', 'Role updated');
        return new RoleResource($role);
    }

    public function destroy(Role $role)
    {
        Gate::authorize('is-admin');
        $role->delete();
        // return to_route('role.index')->with('status', 'Role delete');
    }
}
