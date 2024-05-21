<?php

namespace App\Http\Controllers\Engine\Users;

use App\Http\Controllers\Controller;
use App\Models\Permissions;
use App\Services\LogServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PermissionsController extends Controller
{
    public function __construct(LogServices $LogServices)
    {
        $this->LogServices = $LogServices;
    }

    public function index()
    {
        $permissions = Permissions::orderBy('sort', 'ASC')->get();

        return view('engine.module.permissions.index', compact('permissions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('engine.module.permissions.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'code' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $data = Permissions::create($request->all());
            $this->LogServices->handle([
                'table_id' => $data->id,
                'name' => 'create permission',
                'json' => json_encode($data),
            ]);
            DB::commit();

            return redirect()->route('permission')->with('success', 'Permissions created successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            $this->LogServices->handle(['table_id' => 0, 'name' => 'Log Error',   'json' => $e]);

            return redirect()->route('permission')->with('error', 'Permissions created failed.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Roles  $roles
     * @return \Illuminate\Http\Response
     */
    public function show(Permissions $roles)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Roles  $roles
     * @return \Illuminate\Http\Response
     */
    public function edit(Permissions $Permissions, $id)
    {
        $Permissions = Permissions::find($id);

        return view('engine.module.permissions.edit', compact('Permissions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Models\Roles  $roles
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $data = Permissions::where('id', $id)->first();
            $data->update($request->all());

            $this->LogServices->handle([
                'table_id' => $data->id,
                'name' => 'Update permission',
                'json' => json_encode($data),
            ]);
            DB::commit();

            return redirect()->route('permission')->with('success', 'Permissions Updated successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            $this->LogServices->handle(['table_id' => 0, 'name' => 'Log Error',   'json' => $e]);

            return redirect()->route('permission')->with('error', 'Permissions Updated failed.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Roles  $roles
     * @return \Illuminate\Http\Response
     */
    public function destroy(Permissions $Permissions, $id)
    {
        $Permissions = Permissions::find($id);
        $Permissions->delete();

        $log = $this->LogServices->handle([
            'table_id' => $Permissions->id,
            'name' => 'delete permission',
            'json' => json_encode($Permissions),
        ]);

        return redirect()->route('permission')->with('success', 'Permissions deleted successfully');

        DB::beginTransaction();
        try {
            $Permissions = Permissions::find($id);
            $Permissions->delete();
            $this->LogServices->handle([
                'table_id' => $data->id,
                'name' => 'Delete permission',
                'json' => json_encode($data),
            ]);
            DB::commit();

            return redirect()->route('permission')->with('success', 'Permissions deleted successfully');
        } catch (\Exception $e) {
            DB::rollback();
            $this->LogServices->handle(['table_id' => 0, 'name' => 'Log Error',   'json' => $e]);

            return redirect()->route('permission')->with('error', 'Permissions deleted failed.');
        }
    }

    public function sortable(Request $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->get('array');
            foreach ($data as $key => $value) {
                $role = Permissions::find($value);
                $role->sort = $key + 1;
                $role->save();
            }

            $log = $this->LogServices->handle([
                'table_id' => 0,
                'name' => 'sort permission',
                'json' => json_encode($data),
            ]);
            DB::commit();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollback();
            $this->LogServices->handle(['table_id' => 0, 'name' => 'Log Error',   'json' => $e]);

            return response()->json(['success' => false]);
        }
    }
}
