<?php

namespace App\Http\Controllers\Engine\Users;

use App\Http\Controllers\Controller;
use App\Models\RoleRelations;
use App\Models\Roles;
use App\Models\User;
use App\Services\LogServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    public function __construct(LogServices $LogServices)
    {
        $this->LogServices = $LogServices;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::where('id', '!=', 2)->get();

        return view('engine.module.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $role = Roles::all();

        return view('engine.module.users.create', compact('role'));
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
            'email' => 'required|email',
            'password' => 'required',
            'password_confirmation' => 'required|same:password',
            'role' => 'required',
            'status' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'email_verified_at' => now(),
                'password' => Hash::make($request->password),
                'status' => $request->status,
            ]);
            $role = RoleRelations::create([
                'admin_id' => $user->id,
                'role_id' => $request->role,
            ]);
            $log = $this->LogServices->handle([
                'table_id' => $user->id,
                'name' => 'create User',
                'json' => json_encode($request->except(['_token', 'password_confirmation', 'password'])),
            ]);
            DB::commit();

            return redirect()->route('user')->with('success', 'User created successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            $this->LogServices->handle(['table_id' => 0, 'name' => 'Log Error',   'json' => $e]);

            return redirect()->route('user')->with('error', 'User created failed.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Users  $users
     * @return \Illuminate\Http\Response
     */
    public function show(User $users)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Users  $users
     * @return \Illuminate\Http\Response
     */
    public function edit(User $users, $id)
    {
        $rolerelation = RoleRelations::where('admin_id', $id)->first();
        $user = User::find($id);
        $role = Roles::all();

        return view('engine.module.users.edit', compact('user', 'role', 'rolerelation'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Models\Users  $users
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $users)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'role' => 'required',
            'status' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $user = User::find($request->id);
            $user->name = $request->name;
            $user->email = $request->email;
            if ($request->password != null) {
                $user->password = Hash::make($request->password);
            }
            $user->status = $request->status;
            $user->save();

            $role = RoleRelations::where('admin_id', $request->id)->first();

            if ($role) {
                $role->role_id = $request->role;
                $role->save();
            } else {
                $role = RoleRelations::create([
                    'admin_id' => $user->id,
                    'role_id' => $request->role,
                ]);
            }

            $log = $this->LogServices->handle([
                'table_id' => $user->id,
                'name' => 'update User',
                'json' => json_encode($request->except(['_token', 'password_confirmation', 'password'])),
            ]);

            DB::commit();

            return redirect()->route('user')->with('success', 'User updated successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            $this->LogServices->handle(['table_id' => 0, 'name' => 'Log Error',   'json' => $e]);

            return redirect()->route('user')->with('error', 'User updated failed.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Users  $users
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $users, $id)
    {
        DB::beginTransaction();
        try {
            $user = User::find($id);
            $log = $this->LogServices->handle([
                'table_id' => $user->id,
                'name' => 'delete User',
                'json' => json_encode($user),
            ]);
            $user->delete();
            $role = RoleRelations::where('admin_id', $id)->first();
            $role->delete();
            DB::commit();

            return redirect()->route('user')->with('success', 'User deleted successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            $this->LogServices->handle(['table_id' => 0, 'name' => 'Log Error',   'json' => $e]);

            return redirect()->route('user')->with('error', 'User deleted failed.');
        }
    }
}
