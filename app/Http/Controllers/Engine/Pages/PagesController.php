<?php

namespace App\Http\Controllers\Engine\Pages;

use App\Http\Controllers\Controller;
use App\Models\WebArticleCategories;
use App\Models\WebMenus;
use App\Models\WebPages;
use App\Models\WebTemplates;
use App\Models\WebViewCategoryMaps;
use App\Services\LogServices;
use App\Helper\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PagesController extends Controller
{
    public function __construct(LogServices $LogServices)
    {
        $this->LogServices = $LogServices;
    }

    public function index(Request $request)
    {
        $data = WebPages::with(['translations' => function ($query) {
            $query->where('language_id', '=', 1);
        }, 'menu_relation.translations'])
            ->orderBy('sort', 'asc')
            ->whereIn('visibility', [99])
            ->orderBy('menu_id', 'asc')
            ->get();

        return view('engine.module.pages.index', compact('data'));
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

        return view('engine.module.pages.create', compact('menu_table'));
    }

    public function store(Request $request)
    {
        $type = (request()->has('type')) ? true : false;
        $request->validate([
            'menu_id' => 'required',
            'name.*' => 'required|max:255',
            'slug.*' => 'required',
            'status' => 'required|numeric',
        ]);
        DB::beginTransaction();
        try {
            $data = new WebPages();
            $data->menu_id = $request->menu_id;
            $data->admin_id = Auth::user()->id;
            $data->visibility = 99;
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
            if ($type) {
                return redirect()->route('page.generic', ['type' => 'section'])->with('success', 'Page created successfully.');
            }

            return redirect()->route('page.generic')->with('success', 'Page created successfully.');
        } catch (\Throwable $th) {
            DB::rollback();
            $this->LogServices->handle(['table_id' => 0, 'name' => 'Log Error',   'json' => $th]);
            if ($type) {
                return redirect()->route('page.generic', ['type' => 'section'])->with('success', 'Page created successfully.');
            }

            return redirect()->route('page.generic')->with('error', 'Page created failed.');
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

        return view('engine.module.pages.edit', compact('data', 'menu_table'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'menu_id' => 'required',
            'name.*' => 'required|max:255',
            'slug.*' => 'required',
            'status' => 'required|numeric',
        ]);
        DB::beginTransaction();
        try {
            $data = WebPages::where('id', '=', $id)->first();
            $data->menu_id = $request->menu_id;
            $data->admin_id = Auth::user()->id;
            $data->custom = $request->custom;
            $data->visibility = 99;
            $data->status = $request->status;
            $data->image = ($request->image) ? $request->image : 'default.jpg';
            $data->image_sm = ($request->image_sm) ? $request->image_sm : 'default.jpg';
            $data->image_md = ($request->image_md) ? $request->image_md : 'default.jpg';
            $data->image_lg = ($request->image_lg) ? $request->image_lg : 'default.jpg';
            $data->image_xs = ($request->image_xs) ? $request->image_xs : 'default.jpg';
            $data->save();
            $data->updateTranslations($data, $request);
            DB::commit();
            $this->LogServices->handle(['table_id' => $data->id, 'name' => 'Update Page',   'json' => json_encode($data)]);

            return redirect()->route('page.generic')->with('success', 'Page updated successfully.');
        } catch (\Throwable $th) {
            DB::rollback();
            $this->LogServices->handle(['table_id' => 0, 'name' => 'Log Error',   'json' => $th]);

            return redirect()->route('page.generic')->with('error', 'Page updated failed.');
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

            return redirect()->route('page.generic')->with('success', 'Page deleted successfully.');
        } catch (\Throwable $th) {
            DB::rollback();
            $this->LogServices->handle(['table_id' => 0, 'name' => 'Log Error',   'json' => $th]);

            return redirect()->route('page.generic')->with('error', 'Page deleted failed.');
        }
    }

    public function builder($id)
    {
        $templates = WebTemplates::all();
        $removeCategory = WebArticleCategories::whereIn('visibility', _custom_visibility_menu())->pluck('id')->toArray();
        $categories = WebViewCategoryMaps::whereNotIn('model_id', $removeCategory)->get()->groupBy('type');
        $data = WebPages::where('id', $id)->first();
        $builder = $data->builder()->get()->groupBy('language_id')->toArray();

        return view('engine.module.pages.builder.index', compact('data', 'categories', 'templates', 'builder'));
    }

    public function builderStore(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $data = WebPages::find($id);
            $data->builder()->delete();

            $json = [];

            foreach ($request->type as $key => $value) {

                foreach ($value as $keys => $values) {

                    if ($values == 'TEMPLATE') {
                        $category = explode('-', $request->category_id[$key][$keys]);
                        $model_id = $category[0];
                        $model_type = $category[1];
                    } else {
                        $model_id = null;
                        $model_type = null;
                    }
                    $arr = [
                        'page_id' => $id,
                        'type' => $values,
                        'template_id' => $request->template[$key][$keys],
                        'model_id' => $model_id,
                        'model_type' => $model_type,
                        'language_id' => $key + 1,
                        'description' => isset($request->description[$key][$keys]) ? $request->description[$key][$keys] : [null],
                    ];
                    $json[] = $arr;
                    $builder = $data->builder()->create($arr);

                }
            }

            DB::commit();

            $this->LogServices->handle(['table_id' => $data->id, 'name' => 'Update Page',   'json' => json_encode($data)]);

            return redirect()->route('page.generic.builder', ['id' => $id])->with('success', 'Page updated successfully.');
        } catch (\Throwable $th) {
            return $th;
            DB::rollback();
            $this->LogServices->handle(['table_id' => 0, 'name' => 'Log Error',   'json' => $th]);

            return redirect()->route('page.generic.builder', ['id' => $id])->with('error', 'Page updated failed.');
        }
    }
}
