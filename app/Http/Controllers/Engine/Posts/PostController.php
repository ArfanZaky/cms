<?php

namespace App\Http\Controllers\Engine\Posts;

use App\Http\Controllers\Controller;
use App\Models\WebArticleCategories;
use App\Models\WebArticleCategoryRelations;
use App\Models\WebArticles;
use App\Models\WebTenderCategoryRelationToZone;
use App\Services\LogServices;
use App\Helper\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
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
        $category = $request->category;
        $data = WebArticles::with(['translations' => function ($q) {
            $q->where('language_id', 1);
        }, 'categoryArticles'])
            ->whereHas('categoryArticles', function ($q) use ($category) {
                $q->when($category, function ($q) use ($category) {
                    $q->where('category_id', $category);
                });
            })
            ->orderBy('sort', 'asc')
            ->get();

        $categories = false;
        $breadcrumbs = false;
        if ($category) {
            $permission_category = session('permission_category');
            if (! in_array($category, $permission_category)) {
                return redirect()->route('dashboard')->with('error', 'permission denied, please contact your administrator');
            }
            $categories = WebArticleCategories::with('translations')->where('status', 1)->get();
            $breadcrumbs = \App\Helper\Helper::_post_type_breadcrumbs('category.article', $categories, $categories->where('id', $category)->first());
            $categories = $categories->where('id', $category)->first();
        } else {
            return redirect()->route('dashboard')->with('error', 'permission denied, please contact your administrator');
        }

        return view('engine.module.post.index', compact('data', 'categories', 'breadcrumbs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $category = $request->category;
        $categories = WebArticleCategories::with('translations')->where('status', 1)->when($category, function ($q) use ($category) {
            $q->where('id', $category);
        })->get();

        $parent_categories = false;
        $breadcrumbs = false;
        $zone = false;
        if ($category) {
            $parent_categories = WebArticleCategories::with('translations')->where('status', 1)->get();
            $breadcrumbs = \App\Helper\Helper::_post_type_breadcrumbs('category.article', $parent_categories, $parent_categories->where('id', $category)->first());
            $parent_categories = $parent_categories->where('id', $category)->first();

            // custom tender / auction
            if ($parent_categories?->visibility == 41 || $parent_categories?->visibility == 52) {
                $zone = WebArticleCategories::with('translations')->where('visibility', 19)
                    ->orderBy('sort', 'asc')->first();
                $zone = $zone?->relation;
                $zone = collect($zone)->map(function ($item, $key) {
                    $article = $item?->article?->getResponeses($item->article, 1);
                    $article = collect($article)->toArray();

                    return $article;
                })->toArray();
            }
        }

        return view('engine.module.post.create', compact('categories', 'breadcrumbs', 'parent_categories', 'zone'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required',
            'name.*' => 'required',
            'slug.*' => 'required',
            'status' => 'required',
        ]);

        DB::beginTransaction();
        try {

            // custom tender
            $reference_no = '';
            if (isset($request->zone)) {
                $db = WebArticles::where('visibility', 29)->latest()->first();
                $lastCounter = 0;
                if ($db) {
                    $lastCounter = substr($db->custom, 0, 4);
                }
                $reference_no = str_pad($lastCounter + 1, 4, '0', STR_PAD_LEFT).'01'.date('dmY');
            }

            $data = new WebArticles();
            $data->admin_id = Auth::user()->id;
            $data->gallery_id = 0;
            $data->attachment = ($request->attachment) ? $request->attachment : 'default.pdf';
            $data->image = ($request->image) ? $request->image : 'default.jpg';
            $data->image_sm = ($request->image_sm) ? $request->image_sm : 'default.jpg';
            $data->image_md = ($request->image_md) ? $request->image_md : 'default.jpg';
            $data->image_lg = ($request->image_lg) ? $request->image_lg : 'default.jpg';
            $data->image_xs = ($request->image_xs) ? $request->image_xs : 'default.jpg';
            $data->publish_at = $request->publish_at;
            $data->unpublish_at = $request->unpublish_at;
            $data->status = $request->status;
            $data->custom = isset($request->zone) ? (! empty($request->custom) ? $request->custom : $reference_no) : $request->custom;
            $data->visibility = $request->visibility ?? 20;
            $data->save();
            $data->createTranslations($data, $request);
            WebArticleCategoryRelations::create([
                'article_id' => $data->id,
                'category_id' => $request->category_id,
            ]);

            if (isset($request->zone)) {
                WebTenderCategoryRelationToZone::create([
                    'id_tender' => $data->id,
                    'category_zone' => $request->zone,
                ]);
            }

            DB::commit();
            $this->LogServices->handle(['table_id' => $data->id, 'name' => 'Create Article',   'json' => json_encode($data)]);

            return redirect()->route('article', ['category' => $request->category_id])->with('success', 'Create Item Success');
        } catch (\Exception $e) {
            DB::rollback();
            $this->LogServices->handle(['table_id' => 0, 'name' => 'Log Error',   'json' => $e]);

            return redirect()->route('article', ['category' => $request->category_id])->with('error', 'Create Item Failed');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\WebArticles  $webArticles
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\WebArticles  $webArticles
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $category = $request->category;
        $categories = WebArticleCategories::with('translations')->where('status', 1)->when($category, function ($q) use ($category) {
            $q->where('id', $category);
        })->get();

        $data = WebArticles::with('translations', 'categoryArticles')->where('id', $id)->first();

        $parent_categories = false;
        $breadcrumbs = false;
        $zone = false;
        $relatedZone = false;
        if ($category) {
            $parent_categories = WebArticleCategories::with('translations')->where('status', 1)->get();
            $breadcrumbs = \App\Helper\Helper::_post_type_breadcrumbs('category.article', $parent_categories, $parent_categories->where('id', $category)->first());
            $parent_categories = $parent_categories->where('id', $category)->first();

            // custom tender
            if ($parent_categories?->visibility == 41 || $parent_categories?->visibility == 52) {
                $zone = WebArticleCategories::with('translations')->where('visibility', 19)->orderBy('sort', 'asc')->first();
                $zone = $zone?->relation;
                $zone = collect($zone)->map(function ($item, $key) {
                    $article = $item?->article?->getResponeses($item->article, 1);
                    $article = collect($article)->toArray();

                    return $article;
                })->toArray();

                $relatedZone = WebTenderCategoryRelationToZone::where('id_tender', $id)->first();
            }
        }

        return view('engine.module.post.edit', compact('data', 'categories', 'breadcrumbs', 'parent_categories', 'zone', 'relatedZone'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Models\WebArticles  $webArticles
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'category_id' => 'required',
            'name.*' => 'required',
            'slug.*' => 'required',
            'status' => 'required',
        ]);
        DB::beginTransaction();
        try {
            $reference_no = '';
            if (isset($request->zone)) {
                $db = WebArticles::where('visibility', 29)->latest()->first();
                $lastCounter = 0;
                if ($db) {
                    $lastCounter = substr($db->custom, 0, 4);
                }
                $reference_no = str_pad($lastCounter + 1, 4, '0', STR_PAD_LEFT).'01'.date('dmY');
            }

            $data = WebArticles::find($id);
            $data->admin_id = Auth::user()->id;
            $data->attachment = ($request->attachment) ? $request->attachment : 'default.pdf';
            $data->image = ($request->image) ? $request->image : 'default.jpg';
            $data->image_sm = ($request->image_sm) ? $request->image_sm : 'default.jpg';
            $data->image_md = ($request->image_md) ? $request->image_md : 'default.jpg';
            $data->image_lg = ($request->image_lg) ? $request->image_lg : 'default.jpg';
            $data->image_xs = ($request->image_xs) ? $request->image_xs : 'default.jpg';
            $data->publish_at = $request->publish_at;
            $data->unpublish_at = $request->unpublish_at;
            $data->custom = (isset($request->zone)) ? (! empty($request->custom) ? $request->custom : $reference_no) : $request->custom;
            $data->status = $request->status;
            $data->visibility = $request->visibility ?? 20;
            $data->save();
            $data->updateTranslations($data, $request);

            WebArticleCategoryRelations::updateOrCreate(
                ['article_id' => $data->id],
                [
                    'category_id' => $request->category_id,
                ]
            );

            if (isset($request->zone)) {
                WebTenderCategoryRelationToZone::updateOrCreate(
                    ['id_tender' => $data->id],
                    [
                        'category_zone' => $request->zone,
                    ]
                );
            }
            DB::commit();
            $this->LogServices->handle(['table_id' => $data->id, 'name' => 'Update Article',   'json' => json_encode($data)]);

            return redirect()->route('article', ['category' => $request->category_id])->with('success', 'Update Item Success');
        } catch (\Exception $e) {
            DB::rollback();
            $this->LogServices->handle(['table_id' => 0, 'name' => 'Log Error',   'json' => $e]);

            return redirect()->route('article', ['category' => $request->category_id])->with('error', 'Update Item Failed');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\WebArticles  $webArticles
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $data = WebArticles::find($id);
            $relation = WebArticleCategoryRelations::where('article_id', $id)->first();
            $tender = WebTenderCategoryRelationToZone::where('id_tender', $id)->first();

            $data->delete();
            $relation->delete();
            if ($tender) {
                $tender->delete();
            }
            DB::commit();
            $this->LogServices->handle(['table_id' => $data->id, 'name' => 'Delete Article',   'json' => json_encode($data)]);

            return redirect()->route('article', ['category' => $request->category_id])->with('success', 'Delete Item Success');
        } catch (\Exception $e) {
            DB::rollback();
            $this->LogServices->handle(['table_id' => 0, 'name' => 'Log Error',   'json' => $e]);

            return redirect()->route('article', ['category' => $request->category_id])->with('error', 'Delete Item Failed');
        }
    }
}
