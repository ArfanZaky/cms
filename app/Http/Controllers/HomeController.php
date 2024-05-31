<?php

namespace App\Http\Controllers;

use App\Models\WebLog;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('engine.module.home.dashboard');
    }

    public function Apilogs()
    {
        $log = WebLog::select('web_logs.*', 'users.name as admin_name')
            ->leftjoin('users', 'users.id', '=', 'web_logs.admin_id')->OrderBy('web_logs.id', 'desc')
            ->where('web_logs.name', '!=', 'Log Error')
            ->limit(100)
            ->get();

        return Datatables::of($log)
            ->addIndexColumn()
            ->addColumn('name_main', function ($row) {
                return \App\Helper\Helper::getLogName($row);
            })
            ->addColumn('name', function ($row) {
                return str_replace('Article', 'Post', $row->name);
            })
            ->make(true);
    }

    public function sortable(Request $request)
    {
        $data = $request->array;
        $model = $request->model;

        foreach ($data as $key => $value) {
            $menu = $model::find($value);
            $menu->sort = $key + 1;
            $menu->save();
        }

        return response()->json(['success' => 'success']);
    }
}
