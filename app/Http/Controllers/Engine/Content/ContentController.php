<?php

namespace App\Http\Controllers\Engine\Content;

use App\Http\Controllers\Controller;
use App\Models\PermissionRelations;
use App\Models\WebContent;
use App\Services\LogServices;
use App\Services\PermissionService;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ContentController extends Controller
{
    protected $LogServices;

    protected $PermissionService;

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

    public function index(Request $request)
    {
        $content = WebContent::with(['translations' => function ($q) {
            $q->where('language_id', 1);
        }])
            ->orderBy('sort', 'asc')
            ->get();
        $permission_content = session('permission_content');

        $breadcrumbs = false;
        $data = $content;
        if ($request->parent) {
            if (! in_array($request->parent, collect($permission_content)->pluck('id')->toArray())) {
                return redirect()->route('dashboard')->with('error', 'permission denied, please contact your administrator');
            }
            $breadcrumbs = \App\Helper\Helper::_post_type_breadcrumbs('content.article', $content, $content->where('id', $request->parent)->first());
            $data = $content->where('parent', $request->parent);

            $id = $data->pluck('id')->toArray();
            $data = collect($data)->map(function ($item) {
                $item['parent'] = 0;

                return $item;
            })->union($content->whereIn('parent', $id));
        }
        $data = collect($data)->map(function ($item) {
            $slug = $item->translations->where('language_id', 1)->first()->slug;
            $url = '/'.$slug;
            $item->url = $url;

            return $item;
        });

        $data_tree = [];
        foreach ($data as $key => $value) {
            if (! in_array($value->id, collect($permission_content)->pluck('id')->toArray())) {
                continue;
            }
            $data_tree[] = [
                'id' => $value->id,
                'parent' => $value->parent,
                'children' => [],
                'name' => $value->translations[0]->name,
                'visibility' => $value->visibility,
                'sort' => $value->sort,
                'status' => $value->status,
                'is_menu' => $value->is_menu,
                'url' => $value->url,
            ];
        }
        $data_tree = \App\Helper\Helper::tree($data_tree);
        $menu_table = menu_table($data_tree, 0, $data = []);

        return view('engine.module.content.article.index', compact('menu_table', 'breadcrumbs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $parent = $request->parent;
        $data = WebContent::with('translations')->where('status', 1)->when(
            $parent,
            function ($q) use ($parent) {
                $q->where('parent', $parent)->orWhere('id', $parent);
            }
        )->get();

        $breadcrumbs = false;
        $parentData = [];
        if ($parent) {
            $content = WebContent::with(['translations' => function ($q) {
                $q->where('language_id', 1);
            }])
                ->orderBy('visibility', 'desc')
                ->orderBy('sort', 'asc')
                ->get();
            $parentData = $content->where('id', $request->parent)->first();
            $breadcrumbs = \App\Helper\Helper::_post_type_breadcrumbs('content.article', $content, $parentData);
        }

        $data_tree = [];
        foreach ($data as $key => $value) {
            $data_tree[] = [
                'id' => $value->id,
                'parent' => $value->parent,
                'children' => [],
                'name' => $value->translations[0]->name,
                'visibility' => $value->visibility,
                'is_menu' => $value->is_menu,
                'sort' => $value->sort,
                'status' => $value->status,
            ];
        }
        $data_tree_helper = \App\Helper\Helper::tree($data_tree);
        if (count($data_tree_helper) == 0) {
            $data_tree_helper = $data_tree;
        }
        $menu_table = menu_table($data_tree_helper, 0, $data = []);

        return view('engine.module.content.article.create', compact('menu_table', 'breadcrumbs', 'parentData'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name.*' => 'required',
            'slug.*' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $data = new WebContent();
            $data->parent = $request->parent;
            $data->status = $request->status;
            $data->is_menu = $request->is_menu;
            $data->visibility = $request->visibility;
            $data->custom = $request->custom;
            $data->publish_at = $request->publish_at;
            $data->image = ($request->image) ? $request->image : 'default.jpg';
            $data->image_sm = ($request->image_sm) ? $request->image_sm : 'default.jpg';
            $data->image_md = ($request->image_md) ? $request->image_md : 'default.jpg';
            $data->image_lg = ($request->image_lg) ? $request->image_lg : 'default.jpg';
            $data->image_xs = ($request->image_xs) ? $request->image_xs : 'default.jpg';
            $data->sort = 99;
            $data->save();

            $data->createTranslations($data, $request);
            $log = $this->LogServices->handle([
                'table_id' => $data->id,
                'name' => 'Create content Article',
                'json' => json_encode($data),
            ]);

            $PermissionRelations = [
                [
                    'role_id' => Auth::user()?->role?->first()?->id,
                    'content_id' => $data->id,
                ],
                [
                    'role_id' => 1,
                    'content_id' => $data->id,
                ],
            ];

            $PermissionRelations = collect($PermissionRelations)->unique('role_id')->toArray();

            foreach ($PermissionRelations as $key => $value) {
                PermissionRelations::create($value);
            }

            $this->PermissionService->handle();
            DB::commit();

            return redirect()->route('content.article', ['parent' => $request->parent, 'component' => $request->component])->with('success', 'Create content Article Success');
        } catch (\Exception $e) {
            DB::rollback();
            $this->LogServices->handle(['table_id' => 0, 'name' => 'Log Error',   'json' => $e]);

            return redirect()->route('content.article', ['parent' => $request->parent, 'component' => $request->component])->with('error', 'Create content Article Error');
        }
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(WebContent $WebContent)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\WebContent  $WebContent
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $parent = $request->parent;
        $data = WebContent::with('translations')->whereNotin('id', [$id])->where('status', 1)->when(
            $parent,
            function ($q) use ($parent) {
                $q->where('id', $parent);
            }
        )->get();
        $breadcrumbs = false;
        $parentData = [];
        if ($parent) {
            $content = WebContent::with(['translations' => function ($q) {
                $q->where('language_id', 1);
            }])
                ->orderBy('visibility', 'desc')
                ->orderBy('sort', 'asc')
                ->get();
            $parentData = $content->where('id', $request->parent)->first();
            $breadcrumbs = \App\Helper\Helper::_post_type_breadcrumbs('content.article', $content, $parentData);
        }

        $menu_table = [];
        foreach ($data as $key => $value) {
            $menu_table[] = [
                'id' => $value->id,
                'parent' => $value->parent,
                'children' => [],
                'name' => $value->translations[0]->name,
                'visibility' => $value->visibility,
                'is_menu' => $value->is_menu,
                'sort' => $value->sort,
                'status' => $value->status,
            ];
        }

        $menu_table = menu_table($menu_table, 0, $data = []);

        $data = WebContent::with('translations')->find($id);

        // return $data;
        return view('engine.module.content.article.edit', compact('menu_table', 'data', 'breadcrumbs', 'parentData'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Models\WebContent  $WebContent
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name.*' => 'required',
            'slug.*' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $data = WebContent::find($id);

            $data->parent = $request->parent;
            $data->status = $request->status;
            $data->visibility = $request->visibility;
            $data->is_menu = $request->is_menu;
            $data->publish_at = $request->publish_at;
            $data->custom = $request->custom;
            $data->image = ($request->image) ? $request->image : 'default.jpg';
            $data->image_sm = ($request->image_sm) ? $request->image_sm : 'default.jpg';
            $data->image_md = ($request->image_md) ? $request->image_md : 'default.jpg';
            $data->image_lg = ($request->image_lg) ? $request->image_lg : 'default.jpg';
            $data->image_xs = ($request->image_xs) ? $request->image_xs : 'default.jpg';
            $data->save();
            $data->updateTranslations($data, $request);
            $log = $this->LogServices->handle([
                'table_id' => $data->id,
                'name' => 'Update content Article',
                'json' => json_encode($data),
            ]);
            DB::commit();

            return redirect()->route('content.article', ['parent' => $request->parent, 'component' => $request->component])->with('success', 'Update content Article Success');
        } catch (\Exception $e) {
            DB::rollback();
            $this->LogServices->handle(['table_id' => 0, 'name' => 'Log Error',   'json' => $e]);

            return redirect()->route('content.article', ['parent' => $request->parent, 'component' => $request->component])->with('error', 'Update content Article Error');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(WebContent $WebContent, $id)
    {
        DB::beginTransaction();
        try {
            $data = WebContent::find($id);
            $data->translations()->delete();
            $data->delete();
            $log = $this->LogServices->handle([
                'table_id' => $data->id,
                'name' => 'Delete content Article',
                'json' => json_encode($data),
            ]);
            DB::commit();

            return redirect()->back()->with('success', 'Delete content Article Success');
        } catch (\Exception $e) {
            DB::rollback();
            $this->LogServices->handle(['table_id' => 0, 'name' => 'Log Error',   'json' => $e]);

            return redirect()->back()->with('error', 'Delete content Article Error');
        }
    }
}
