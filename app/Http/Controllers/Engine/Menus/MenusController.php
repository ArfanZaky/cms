<?php

namespace App\Http\Controllers\Engine\Menus;

use App\Http\Controllers\Controller;
use App\Models\WebContent;
use App\Models\WebMenus;
use App\Services\LogServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MenusController extends Controller
{
    public function __construct(LogServices $LogServices)
    {
        $this->LogServices = $LogServices;
    }

    public function index(Request $request)
    {
        $menus = WebMenus::with(['translations' => function ($q) {
            $q->where('language_id', 1);
        }])
            ->orderBy('visibility', 'desc')
            ->orderBy('sort', 'asc')
            ->get();
        $data = $menus;
        if ($request->parent) {
            $data = $menus->where('parent', $request->parent);
            $id = $data->pluck('id')->toArray();
            $data = collect($data)->map(function ($item) {
                $item['parent'] = 0;

                return $item;
            })->union($menus->whereIn('parent', $id));
        }

        $data_tree = [];
        foreach ($data as $key => $value) {
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
        $data_tree = \App\Helper\Helper::tree($data_tree);
        $menu_table = menu_table($data_tree, 0, $data = []);
        $menu_table = collect($menu_table)->sortBy('visibility');

        return view('engine.module.menus.index', compact('menu_table'));
    }

    public function create()
    {
        $menu = WebMenus::with(['translations' => function ($q) {
            $q->where('language_id', 1);
        }])->where('status', 1)->orderBy('sort', 'asc')->get();
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
        $menu_table_menu = menu_table($data_tree_helper, 0, $data = []);

        $category = WebContent::with(['translations' => function ($q) {
            $q->where('language_id', 1);
        }])->where('status', 1)->get();
        $data_tree2 = [];
        foreach ($category as $key => $value) {
            $data_tree2[] = [
                'id' => $value->id,
                'parent' => $value->parent,
                'children' => [],
                'name' => $value->translations[0]->name,
                'visibility' => $value->visibility,
                'sort' => $value->sort,
                'status' => $value->status,
            ];
        }
        $data_tree_helper = \App\Helper\Helper::tree($data_tree2);
        if (count($data_tree_helper) == 0) {
            $data_tree_helper = $data_tree2;
        }
        $menu_table_category = menu_table($data_tree_helper, 0, $data = []);

        return view('engine.module.menus.create', compact('menu_table_menu', 'menu_table_category'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'parent' => 'required|numeric',
            'target' => 'required',
            'url' => 'required',
            'visibility' => 'required|numeric',
            'status' => 'required|numeric',
        ]);
        DB::beginTransaction();
        try {
            $menu = new WebMenus;
            $menu->admin_id = Auth::user()->id;
            if (isset($request->menu_id)) {
                $menu->menu_id = $request->menu_id;
            }
            if (isset($request->category_id)) {
                $menu->category_id = $request->category_id;
            }
            if (isset($request->gallery_id)) {
                $menu->gallery_id = $request->gallery_id;
            }
            if (isset($request->catalog_id)) {
                $menu->catalog_id = $request->catalog_id;
            }
            $menu->parent = $request->parent;
            $menu->target = $request->target;
            $menu->parameter = $request->parameter;
            $menu->url = $request->url;
            $menu->image = ($request->image) ? $request->image : 'default.jpg';
            $menu->image_sm = ($request->image_sm) ? $request->image_sm : 'default.jpg';
            $menu->visibility = $request->visibility;
            $menu->status = $request->status;
            $menu->save();
            foreach ($request->name as $key => $value) {
                $menu->translations()->create([
                    'language_id' => $key + 1,
                    'name' => $value,
                    'slug' => Str::slug($value),
                    'image_lang' => $request->image_lang[$key] ?? 'default.jpg',
                    'image_sm_lang' => $request->image_sm_lang[$key] ?? 'default.jpg',
                ]);
            }
            $this->LogServices->handle([
                'table_id' => $menu->id,
                'name' => 'Create Menu',
                'json' => json_encode($menu),
            ]);
            DB::commit();

            return redirect()->route('menu')->with('success', 'Menu created successfully');
        } catch (\Exception $e) {
            DB::rollback();
            $this->LogServices->handle(['table_id' => 0, 'name' => 'Log Error',   'json' => $e]);

            return redirect()->route('menu')->with('error', 'Menu created failed');
        }
    }

    public function show($id)
    {
        $menu = WebMenus::find($id);

        return view('engine.module.menus.show', compact('menu'));
    }

    public function edit($id)
    {
        $menu = WebMenus::with(['translations' => function ($q) {
            $q->where('language_id', 1);
        }])->where('status', 1)
            ->where('id', '!=', $id)
            ->orderBy('sort', 'asc')->get();

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
        $menu_table_menu = menu_table($data_tree_helper, 0, $data = []);

        $category = WebContent::with(['translations' => function ($q) {
            $q->where('language_id', 1);
        }])->where('status', 1)->get();

        $data_tree2 = [];
        foreach ($category as $key => $value) {
            $data_tree2[] = [
                'id' => $value->id,
                'parent' => $value->parent,
                'children' => [],
                'name' => $value->translations[0]->name,
                'visibility' => $value->visibility,
                'sort' => $value->sort,
                'status' => $value->status,
            ];
        }
        $data_tree_helper = \App\Helper\Helper::tree($data_tree2);
        $menu_table_category = menu_table($data_tree_helper, 0, $data = []);

        $data = WebMenus::with('translations')->find($id);

        return view('engine.module.menus.edit', compact('menu_table_menu', 'menu_table_category', 'data'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'parent' => 'required|numeric',
            'target' => 'required',
            'url' => 'required',
            'visibility' => 'required|numeric',
            'status' => 'required|numeric',
        ]);
        DB::beginTransaction();
        try {
            $menu = WebMenus::find($id);
            $menu->admin_id = Auth::user()->id;
            if (isset($request->menu_id)) {
                $menu->menu_id = $request->menu_id;
            }
            if (isset($request->category_id)) {
                $menu->category_id = $request->category_id;
            }
            if (isset($request->gallery_id)) {
                $menu->gallery_id = $request->gallery_id;
            }
            if (isset($request->catalog_id)) {
                $menu->catalog_id = $request->catalog_id;
            }
            $menu->parent = $request->parent;
            $menu->parameter = $request->parameter;
            $menu->target = $request->target;
            $menu->image = ($request->image) ? $request->image : 'default.jpg';
            $menu->image_sm = ($request->image_sm) ? $request->image_sm : 'default.jpg';
            $menu->url = $request->url;
            $menu->visibility = $request->visibility;
            $menu->status = $request->status;
            $menu->save();
            foreach ($request->name as $key => $value) {
                $menu->translations()->updateOrCreate(
                    [
                        'language_id' => $key + 1,
                    ], [
                        'language_id' => $key + 1,
                        'name' => $value,
                        'slug' => Str::slug($value),
                        'image_lang' => $request->image_lang[$key],
                        'image_sm_lang' => $request->image_sm_lang[$key],
                    ]
                );
            }
            $this->LogServices->handle([
                'table_id' => $menu->id,
                'name' => 'Update Menu',
                'json' => json_encode($menu),
            ]);
            DB::commit();

            return redirect()->route('menu')->with('success', 'Menu updated successfully');
        } catch (\Exception $e) {
            DB::rollback();
            $this->LogServices->handle(['table_id' => 0, 'name' => 'Log Error',   'json' => $e]);

            return redirect()->route('menu')->with('error', 'Menu updated failed');
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $menu = WebMenus::find($id);
            $menu->delete();
            $this->LogServices->handle(['table_id' => $menu->id, 'name' => 'Delete Page',   'json' => json_encode($menu)]);
            DB::commit();

            return redirect()->route('menu')->with('success', 'Menu deleted successfully');
        } catch (\Exception $e) {
            DB::rollback();
            $this->LogServices->handle(['table_id' => 0, 'name' => 'Log Error',   'json' => $e]);

            return redirect()->route('menu')->with('error', 'Menu deleted failed');
        }
    }

    public function sortable(Request $request)
    {
        $data = $request->array;
        foreach ($data as $key => $value) {
            $menu = WebMenus::find($value);
            $menu->sort = $key + 1;
            $menu->save();
        }

        return response()->json(['success' => 'success']);
    }
}
