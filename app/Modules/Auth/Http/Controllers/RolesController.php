<?php

namespace App\Modules\Auth\Http\Controllers;

use App\Bootstrap\Http\Controllers\Controller;
use App\Modules\Auth\Models\Permission;
use App\Modules\Auth\Models\Role;
use App\Modules\Auth\Repositories\RoleRepository;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Exception;

class RolesController extends Controller
{
    private $roleRepository;

    public function __construct(RoleRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    public function index(Request $request)
    {
        $this->authorize('roles.index');

        $search = $request->get('search');

        $roles = $this->roleRepository->fetchAll($search);

        return Inertia::render('app/roles/list', compact('roles'));
    }

    public function create()
    {
        $this->authorize('roles.create');

        $permissionsGrouped = Permission::all()->groupBy('module');
        $permissions = Permission::all();

        return Inertia::render('app/roles/form', compact('permissionsGrouped', 'permissions'));
    }

    public function edit(Role $role)
    {
        $this->authorize('roles.edit');

        $permissionsGrouped = Permission::all()->groupBy('module');
        $permissions = Permission::all();

        return Inertia::render(
            'app/roles/form',
            compact('role', 'permissionsGrouped', 'permissions')
        );
    }

    public function store(Request $request)
    {
        $inputs = $request->only(['name', 'permissions']);
        $permissions = [];

        if (array_key_exists('permissions', $inputs)) {
            $permissions = $inputs['permissions'];
            unset($inputs['permissions']);
        }

        $role = Role::create($inputs);
        $role->permissions()->attach($permissions);

        return redirect('/roles');
    }

    public function update(Request $request, Role $role)
    {
        $inputs = $request->only(['name', 'permissions']);
        $permissions = [];

        if (array_key_exists('permissions', $inputs)) {
            $permissions = $inputs['permissions'];
            unset($inputs['permissions']);
        }

        $role->fill($inputs);
        $role->save();
        $role->permissions()->sync($permissions);

        return redirect('/roles');
    }

    public function destroy(Role $role)
    {
        $this->authorize('roles.destroy');

        try {
            $role->permissions()->sync([]);
            $role->delete();
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Não foi possível excluir o registro!');
        }

        return redirect()->back();
    }
}
