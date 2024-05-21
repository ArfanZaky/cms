<?php

namespace App\Http\Controllers\Engine\Categories;

use App\Http\Controllers\Controller;
use App\Models\WebChatbotCategories;
use App\Services\LogServices;
use Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CategoryChatbotController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(LogServices $LogServices)
    {
        $this->LogServices = $LogServices;

    }

    public function index(Request $request)
    {
        $category = WebChatbotCategories::with('translations')
            ->orderBy('visibility', 'desc')
            ->orderBy('sort', 'asc')
            ->get();

        $breadcrumbs = false;
        $data = $category;
        if ($request->parent) {
            $breadcrumbs = Helper::_post_type_breadcrumbs('category.chatbot', $category, $category->where('id', $request->parent)->first());
            $data = $category->where('parent', $request->parent);

            $id = $data->pluck('id')->toArray();
            $data = collect($data)->map(function ($item) {
                $item['parent'] = 0;

                return $item;
            })->union($category->whereIn('parent', $id));
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

        $data_tree = Helper::tree($data_tree);
        $data_tree = menu_table($data_tree, 0, $data = []);
        $menu_table = collect($data_tree)->sortBy('visibility');

        return view('engine.module.category.chatbot.index', compact('menu_table', 'breadcrumbs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $parent = $request->parent;
        $data = WebChatbotCategories::with('translations')->where('status', 1)->when(
            $parent,
            function ($q) use ($parent) {
                $q->where('parent', $parent)->orWhere('id', $parent);
            }
        )->get();

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
        $data_tree_helper = Helper::tree($data_tree);
        if (count($data_tree_helper) == 0) {
            $data_tree_helper = $data_tree;
        }
        $menu_table = menu_table($data_tree_helper, 0, $data = []);

        return view('engine.module.category.chatbot.create', compact('menu_table'));
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
            $data = new WebChatbotCategories();
            $data->parent = $request->parent;
            $data->status = $request->status;
            $data->visibility = 1;
            $data->image = ($request->image) ? $request->image : 'default.jpg';
            $data->image_sm = ($request->image_sm) ? $request->image_sm : 'default.jpg';
            $data->image_md = ($request->image_md) ? $request->image_md : 'default.jpg';
            $data->image_lg = ($request->image_lg) ? $request->image_lg : 'default.jpg';
            $data->image_xs = ($request->image_xs) ? $request->image_xs : 'default.jpg';
            $data->save();
            $data->createTranslations($data, $request);
            $log = $this->LogServices->handle([
                'table_id' => $data->id,
                'name' => 'Create Category Chatbot',
                'json' => json_encode($data),
            ]);
            DB::commit();

            return redirect()->route('category.chatbot')->with('success', 'Create Category Chatbot Success');
        } catch (\Exception $e) {
            DB::rollback();
            $this->LogServices->handle(['table_id' => 0, 'name' => 'Log Error',   'json' => $e]);

            return redirect()->route('category.chatbot')->with('error', 'Create Category Chatbot Error');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\WebCategorys  $webCategorys
     * @return \Illuminate\Http\Response
     */
    public function show(WebCategorys $webCategorys)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\WebCategorys  $webCategorys
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $parent = $request->parent;
        $data = WebChatbotCategories::with('translations')->whereNotin('id', [$id])->where('status', 1)->when(
            $parent,
            function ($q) use ($parent) {
                $q->where('parent', $parent)->orWhere('id', $parent);
            }
        )->get();
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
        $data_tree_helper = Helper::tree($data_tree);
        if (count($data_tree_helper) == 0) {
            $data_tree_helper = $data_tree;
        }
        $menu_table = menu_table($data_tree_helper, 0, $data = []);

        $data = WebChatbotCategories::with('translations')->find($id);

        // return $data;
        return view('engine.module.category.chatbot.edit', compact('menu_table', 'data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Models\WebCategorys  $webCategorys
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
            $data = WebChatbotCategories::find($id);
            $data->parent = $request->parent;
            $data->status = $request->status;
            $data->visibility = 1;
            $data->image = ($request->image) ? $request->image : 'default.jpg';
            $data->image_sm = ($request->image_sm) ? $request->image_sm : 'default.jpg';
            $data->image_md = ($request->image_md) ? $request->image_md : 'default.jpg';
            $data->image_lg = ($request->image_lg) ? $request->image_lg : 'default.jpg';
            $data->image_xs = ($request->image_xs) ? $request->image_xs : 'default.jpg';
            $data->save();
            $data->updateTranslations($data, $request);
            $log = $this->LogServices->handle([
                'table_id' => $data->id,
                'name' => 'Update Category Chatbot',
                'json' => json_encode($data),
            ]);
            DB::commit();

            return redirect()->route('category.chatbot')->with('success', 'Update Category Chatbot Success');
        } catch (\Exception $e) {
            DB::rollback();
            $this->LogServices->handle(['table_id' => 0, 'name' => 'Log Error',   'json' => $e]);

            return redirect()->route('category.chatbot')->with('error', 'Update Category Chatbot Error');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\WebCategorys  $webCategorys
     * @return \Illuminate\Http\Response
     */
    public function destroy(WebChatbotCategories $webCategorys, $id)
    {
        DB::beginTransaction();
        try {
            $data = WebChatbotCategories::find($id);
            $data->translations()->delete();
            $data->delete();
            $log = $this->LogServices->handle([
                'table_id' => $data->id,
                'name' => 'Delete Category Chatbot',
                'json' => json_encode($data),
            ]);
            DB::commit();

            return redirect()->route('category.chatbot')->with('success', 'Delete Category Chatbot Success');
        } catch (\Exception $e) {
            DB::rollback();
            $this->LogServices->handle(['table_id' => 0, 'name' => 'Log Error',   'json' => $e]);

            return redirect()->route('category.chatbot')->with('error', 'Delete Category Chatbot Error');
        }
    }
}
