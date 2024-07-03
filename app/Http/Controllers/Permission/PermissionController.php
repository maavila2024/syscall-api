<?php

namespace App\Http\Controllers\Permission;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Permission\PermissionStoreRequest;
use App\Http\Requests\Permission\PermissionUpdateRequest;
use App\Http\Resources\RoleResource;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionController extends Controller
{

    public function index()
    {
        Gate::authorize('is-admin');
        $permissions = Permission::paginate(10);
        // return view('dashboard/permission/index', compact('permissions'));
    }

    public function create()
    {
        Gate::authorize('is-admin');
        $permission = new Permission();
        // return view('dashboard.permission.create', compact('permission'));
    }

    public function store(StoreRequest $request)
    {
        Gate::authorize('is-admin');
        Permission::create($request->validated());
        // return to_route('permission.index')->with('status', 'Permission created');
    }

    public function show(Permission $permission)
    {
        Gate::authorize('is-admin');
        // return view('dashboard/permission/show',['permission'=> $permission]);
    }

    public function edit(Permission $permission)
    {
        Gate::authorize('is-admin');
        // return view('dashboard.permission.edit', compact('permission'));
    }

    public function update(PutRequest $request, Permission $permission)
    {
        Gate::authorize('is-admin');
        $permission->update($request->validated());
        // return to_route('permission.index')->with('status', 'Permission updated');
    }

    public function destroy(Permission $permission)
    {
        Gate::authorize('is-admin');
        $permission->delete();
        // return to_route('permission.index')->with('status', 'Permission delete');
    }
}
