<?php

namespace App\Http\Controllers\Engine\Users;

use App\Http\Controllers\Controller;
use App\Models\PermissionRelations;
use App\Models\Permissions;
use App\Models\Roles;
use App\Models\WebContent;
use App\Services\LogServices;
use App\Services\PermissionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RolesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(LogServices $LogServices, PermissionService $PermissionService)
    {
        $this->LogServices = $LogServices;
        $this->PermissionService = $PermissionService;
    }

    public function index()
    {
        $roles = Roles::all();

        return view('engine.module.roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('engine.module.roles.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
        $role = Roles::create($request->all());
        $log = $this->LogServices->handle([
            'table_id' => $role->id,
            'name' => 'create role',
            'json' => json_encode($role),
        ]);

        return redirect()->route('role')->with('success', 'Role created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Roles $roles)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Roles $roles, $id)
    {
        $role = Roles::find($id);

        return view('engine.module.roles.edit', compact('role'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Roles $roles, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
        $role = Roles::find($id);
        $role->name = $request->get('name');
        $role->save();

        $log = $this->LogServices->handle([
            'table_id' => $role->id,
            'name' => 'update role',
            'json' => json_encode($role),
        ]);

        return redirect()->route('role')->with('success', 'Role updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Roles $roles, $id)
    {
        $role = Roles::find($id);
        $role->delete();

        $log = $this->LogServices->handle([
            'table_id' => $role->id,
            'name' => 'delete role',
            'json' => json_encode($role),
        ]);

        return redirect()->route('role')->with('success', 'Role deleted successfully');
    }

    public function permission(Roles $roles, $id)
    {
        $role = Roles::all();
        $role_user = Roles::find($id);
        $permissions = Permissions::orderBy('sort', 'ASC')->get();
        $PermissionRelations = PermissionRelations::where('role_id', $id)->get();

        $permission_global = $PermissionRelations->pluck('permission_id')->filter()->values()->toArray();
        $permission_content = $PermissionRelations->pluck('content_id')->filter()->values()->toArray();
        $permission_page = $PermissionRelations->pluck('page_id')->filter(function ($value) {
            return ! is_null($value);
        })->values()->toArray();

        $array = [];
        foreach ($permissions as $permission) {
            $data = [
                'id' => $permission->id,
                'name' => $permission->name,
                'code' => $permission->code,
                'status' => in_array($permission->id, $permission_global) ? true : false,
            ];
            array_push($array, $data);
        }

        $content = WebContent::with(['translations' => function ($q) {
            $q->where('language_id', 1);
        }])
            ->orderBy('sort', 'asc')
            ->get();

        $data_tree_content = [];
        foreach ($content as $key => $value) {
            $data_tree_content[] = [
                'id' => $value->id,
                'parent' => $value->parent,
                'children' => [],
                'name' => $value->translations[0]->name,
                'visibility' => $value->visibility,
                'sort' => $value->sort,
                'status' => $value->status,
                'url' => $value->url,
            ];
        }
        $data_tree_content = \App\Helper\Helper::tree($data_tree_content);
        $data_tree_content = menu_table($data_tree_content, 0, $data = []);

        return view('engine.module.roles.permission', compact('role', 'id', 'role_user', 'array', 'data_tree_content', 'permission_content', 'permission_page'));
    }

    public function permissionStore(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            PermissionRelations::where('role_id', $id)->delete();
            $permissions = $request->get('permission');
            if (! empty($permissions)) {
                foreach ($permissions as $permission) {
                    $data = [
                        'role_id' => $id,
                        'permission_id' => $permission,
                    ];
                    PermissionRelations::create($data);
                }
            }
            $permission_content = $request->get('permission_content');
            if (! empty($permission_content)) {
                foreach ($permission_content as $permission_content_value) {
                    $data = [
                        'role_id' => $id,
                        'content_id' => $permission_content_value,
                    ];
                    PermissionRelations::create($data);
                }
            }

            $permission_page = $request->get('permission_page');
            if (! empty($permission_page)) {
                foreach ($permission_page as $permission_page_value) {
                    $data = [
                        'role_id' => $id,
                        'page_id' => $permission_page_value,
                    ];
                    PermissionRelations::create($data);
                }
            }

            $logs = $this->LogServices->handle([
                'table_id' => $id,
                'name' => 'update role permission',
                'json' => json_encode($request->except('_token')),
            ]);
            $this->PermissionService->handle();
            DB::commit();

            return redirect()->back()->with('success', 'Permission updated successfully');
        } catch (\Exception $e) {
            DB::rollback();
            $this->LogServices->handle([
                'table_id' => 0,
                'name' => 'log error',
                'json' => $e->getMessage(),
            ]);

            return redirect()->back()->with('error', 'Permission updated failed');
        }
    }
}
