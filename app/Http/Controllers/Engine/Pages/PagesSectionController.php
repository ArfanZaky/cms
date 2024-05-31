<?php

namespace App\Http\Controllers\Engine\Pages;

use App\Http\Controllers\Controller;
use App\Models\WebMenus;
use App\Models\WebPages;
use App\Services\LogServices;
use App\Helper\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PagesSectionController extends Controller
{
    public function __construct(LogServices $LogServices)
    {
        $this->LogServices = $LogServices;
        $this->visibility = config('cms.visibility.page.section');
    }

    public function index(Request $request)
    {
        $req = $request->visibility;
        if (! isset($req)) {
            $data = [];

            return view('engine.module.pages.section.index', compact('data'));
        }

        $permission_page = session('permission_page');

        if (! in_array($req, $permission_page)) {
            return redirect()->route('dashboard')->with('error', 'permission denied, please contact your administrator');
        }

        $data = WebPages::with(['translations' => function ($query) {
            $query->where('language_id', '=', 1);
        }, 'menu_relation.translations'])
            ->whereNotIn('visibility', [99])
            ->orderBy('sort', 'asc');

        if (isset($req) && $req != '-') {
            $data = $data->where('visibility', '=', $req);
        }
        $data = $data->orderBy('menu_id', 'asc')
            ->get();

        return view('engine.module.pages.section.index', compact('data'));
    }

    public function create()
    {
        $menu = WebMenus::with(['translations' => function ($query) {
            $query->where('language_id', '=', 1);
        }])->where('status', '=', 1)->get();
        $data_tree = [];
        foreach ($menu as $key => $value) {
            $data_tree[] = [
                'id' => $value->id,
                'parent' => $value->parent,
                'children' => [],
                'name' => $value->translations[0]->name,
                'visibility' => $value->visibility,
                'sort' => $value->sort,
                'status' => $value->status,
            ];
        }
        $data_tree_helper = \App\Helper\Helper::tree($data_tree);
        if (count($data_tree_helper) == 0) {
            $data_tree_helper = $data_tree;
        }
        $menu_table = menu_table($data_tree_helper, 0, $data = []);

        return view('engine.module.pages.section.create', compact('menu_table'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'menu_id' => 'required',
            'name.*' => 'required',
            'slug.*' => 'required',
            'status' => 'required|numeric',
        ]);

        DB::beginTransaction();
        try {
            $data = new WebPages();
            $data->menu_id = $request->menu_id;
            $data->admin_id = Auth::user()->id;
            $data->visibility = $request->visibility;
            $data->custom = $request->custom;
            $data->status = $request->status;
            $data->image = ($request->image) ? $request->image : 'default.jpg';
            $data->image_sm = ($request->image_sm) ? $request->image_sm : 'default.jpg';
            $data->image_md = ($request->image_md) ? $request->image_md : 'default.jpg';
            $data->image_lg = ($request->image_lg) ? $request->image_lg : 'default.jpg';
            $data->image_xs = ($request->image_xs) ? $request->image_xs : 'default.jpg';
            $data->save();
            $data->createTranslations($data, $request);
            $this->LogServices->handle(['table_id' => $data->id, 'name' => 'Create Page',   'json' => json_encode($data)]);
            DB::commit();

            return redirect()->route('page.section')->with('success', 'Page created successfully.');
        } catch (\Throwable $th) {
            DB::rollback();
            $this->LogServices->handle(['table_id' => 0, 'name' => 'Log Error',   'json' => $th]);

            return redirect()->route('page.section')->with('error', 'Page created failed.');
        }
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $menu = WebMenus::with(['translations' => function ($query) {
            $query->where('language_id', '=', 1);
        }])->where('status', '=', 1)->get();
        $data_tree = [];
        foreach ($menu as $key => $value) {
            $data_tree[] = [
                'id' => $value->id,
                'parent' => $value->parent,
                'children' => [],
                'name' => $value->translations[0]->name,
                'visibility' => $value->visibility,
                'sort' => $value->sort,
                'status' => $value->status,
            ];
        }
        $data_tree_helper = \App\Helper\Helper::tree($data_tree);
        if (count($data_tree_helper) == 0) {
            $data_tree_helper = $data_tree;
        }
        $menu_table = menu_table($data_tree_helper, 0, $data = []);
        $data = WebPages::where('id', '=', $id)->first();

        return view('engine.module.pages.section.edit', compact('data', 'menu_table'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'menu_id' => 'required',
            'name.*' => 'required',
            'slug.*' => 'required',
            'status' => 'required|numeric',
        ]);
        DB::beginTransaction();
        try {
            $data = WebPages::where('id', '=', $id)->first();
            $data->menu_id = $request->menu_id;
            $data->admin_id = Auth::user()->id;
            $data->status = $request->status;
            $data->custom = $request->custom;
            $data->visibility = $request->visibility;
            $data->image = ($request->image) ? $request->image : 'default.jpg';
            $data->image_sm = ($request->image_sm) ? $request->image_sm : 'default.jpg';
            $data->image_md = ($request->image_md) ? $request->image_md : 'default.jpg';
            $data->image_lg = ($request->image_lg) ? $request->image_lg : 'default.jpg';
            $data->image_xs = ($request->image_xs) ? $request->image_xs : 'default.jpg';
            $data->save();
            $data->updateTranslations($data, $request);
            DB::commit();
            $this->LogServices->handle(['table_id' => $data->id, 'name' => 'Update Page',   'json' => json_encode($data)]);

            return redirect()->route('page.section', ['visibility' => $request->visibility])->with('success', 'Page updated successfully.');
        } catch (\Throwable $th) {
            DB::rollback();
            $this->LogServices->handle(['table_id' => 0, 'name' => 'Log Error',   'json' => $th]);

            return redirect()->route('page.section')->with('error', 'Page updated failed.');
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $data = WebPages::where('id', '=', $id)->first();

            $data->delete();
            DB::commit();
            $this->LogServices->handle(['table_id' => $data->id, 'name' => 'Delete Page',   'json' => json_encode($data)]);

            return redirect()->route('page.section')->with('success', 'Page deleted successfully.');
        } catch (\Throwable $th) {
            DB::rollback();
            $this->LogServices->handle(['table_id' => 0, 'name' => 'Log Error',   'json' => $th]);

            return redirect()->route('page.section')->with('error', 'Page deleted failed.');
        }
    }
}
