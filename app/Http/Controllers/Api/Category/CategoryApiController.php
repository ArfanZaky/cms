<?php

namespace App\Http\Controllers\Api\Category;

use App\Events\UpdateViewCount;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Models\WebArticleCategories;
use App\Models\WebArticles;
use App\Models\WebMenus;
use App\Services\LogServices;
use App\Services\ApiService;
use App\Helper\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategoryApiController extends BaseController
{
    protected $LogServices;

    protected $request;

    protected $languages;

    protected $slug;

    protected $db;

    protected $IS_PAGE;

    protected $offset;

    protected $limit;

    protected $year;

    protected $category;

    protected $article;

    protected $total_article;

    protected $children;

    protected $dev;

    public function __construct(LogServices $LogServices, Request $request, $languages, $slug)
    {
        $this->LogServices = $LogServices;
        $this->request = $request;
        $this->languages = $languages;
        $this->slug = $slug;
        $this->category = [];
        $this->article = [];
        $this->children = [];
        $this->total_article = 0;
        $this->limit = $this->request->input('limit', 10);
        $this->dev = $this->request->input('dev', false);

        $this->db = WebArticleCategories::with([
            'translations', 

            'menu_relation.translations', 
            'menu_relation.parentData.translations',
            'menu_relation.parentData.parentData.translations',

            'children.translations', 
            'children.children.translations',
            'parents.children.translations',
            'parents.children.children.translations', 
            'parents.parents.translations',
            'parents.parents.children.translations', 
            'parents.parents.children.children.translations'
            ])->whereSlug($this->slug, _get_languages($this->languages))->first();


        if (! $this->db) {
            return $this->sendResponse([], 'Category retrieved successfully.');
        }
        $this->offset = $this->request->active != config('cms.visibility.post.slug')[$this->db->visibility] ? 0 : $this->request->input('offset', 0);


    }

    public function filter_by_article_slug()
    {
        try {
            if (! $this->db) {
                return $this->sendResponse([], 'Category retrieved successfully.');
            }
            event(new UpdateViewCount($this->db));

            if (! empty($this->db->translations->first()->redirection)) {
                $slug_r = explode('/', $this->db->translations->first()->redirection);
                $slug_r = $slug_r[count($slug_r) - 1];
                $code_map = DB::table('web_sitemaps')->where('slug', $slug_r)->first()?->code;
                if (! $code_map) {
                    return $this->sendResponse([], 'Category retrieved successfully.');
                }
                if ($code_map == 'category') {
                    $category = new \App\Http\Controllers\Api\Category\CategoryApiController($this->LogServices, $this->request, $this->languages, $slug_r);

                    return $category->filter_by_article_slug();
                } elseif ($code_map == 'article') {
                    $article = new \App\Http\Controllers\Api\Article\ArticleApiController($this->LogServices);

                    return $article->filter_by_slug($this->languages, $slug_r);
                } else {
                    return $this->sendResponse([], 'Data retrieved successfully.');
                }
            }

            $language = _get_languages($this->languages);

            $currentDB = null;
            $currentDBName = null;
            $currentDBTranslation = null;
            $currentDBMeta = null;
            $currentData = false;
            $databoard = false;
            $current = $this->db;
            if ($this->db->menu_relation?->parent != null) {

                $menu_id = $this->db?->menu_relation?->parent;
                if ($this->db->menu_relation?->parentData?->parent != null) {
                    $menu_id = $this->db->menu_relation?->parentData?->parent;
                }
            }else{
                $menu_id = $this->db->menu_relation?->id;
            }


            if ($this->db->visibility == 0 || $this->db->visibility == 8) {
               
                $menus = WebMenus::where('id', $menu_id)->with([
                    'translations'=> function ($q) use ($language) {
                        $q->where('language_id', $language);
                    }, 
                    'children.translations' => function ($q) use ($language) {
                        $q->where('language_id', $language);
                    },
                    'children.children.translations' => function ($q) use ($language) {
                        $q->where('language_id', $language);
                    },
                ])->first();

                $menusModel[] = $menus;
                collect($menus->children)->filter()->map(function ($item) use (&$menusModel) {
                    collect($item->children)->filter()->map(function ($item) use (&$menusModel) {
                        $menusModel[] = $item;
                        return $item;
                    });
                    $menusModel[] = $item;
                    return $item;
                });

                $menus = new ApiService();
                $menus = $menus->toArray($menusModel,true, $this->languages);
                $menus = \App\Helper\Helper::tree($menus);
            }

            if ( $this->db->visibility == 0 && $this->db->parent != 0 || $this->db->visibility == 8 && $this->db->parent != 0) {
                $dataDB =  collect($this->db->getResponeses($this->db, $language))->toArray();
                $currentDB = $dataDB['description'];
                $currentDBName = $dataDB['name'];
                $currentDBTranslation = $dataDB['translation'];
                $currentDBMeta = $dataDB['meta'];
              
                if ($dataDB['visibility'] == 8) {
                    $databoard = $this->db;
                    $currentData = $dataDB['visibility'];
                }

                if ($this->db->parents->parent != 0) {
                    $this->db = $this->db->parents->parents;
                }else{
                    $this->db = $this->db->parents;
                }
            }


            $this->category = collect($this->db->getResponeses($this->db, $language))->toArray();
            $this->category['description'] = $currentDB == null ? $this->category['description'] : $currentDB;
            $this->category['name'] = $currentDBName == null ? $this->category['name'] : $currentDBName;
            $this->category['translation'] = $currentDBTranslation == null ? $this->category['translation'] : $currentDBTranslation;
            $this->category['meta'] = $currentDBMeta == null ? $this->category['meta'] : $currentDBMeta;
            

            $article = WebArticles::whereHas('categoryArticles', function ($q) {
                $q->where('category_id', $this->db->id);
            })
                ->with('translations')
                ->orderBy('sort', 'asc')
                ->when($this->limit, function ($q) {
                    $q->limit($this->limit);
                })
                ->when($this->offset, function ($q) {
                    $q->offset($this->offset);
                })
                ->get();

            $this->article = collect($article)->map(function ($item) use ($language,$currentData) {
                $data = $item->getResponeses($item, $language);
                $item = collect($data)->toArray();

               

                return $item;
            })->sortBy('sort')->values()->toArray();

            if ($currentData) {
                $this->article = [];
            }

            $this->total_article = WebArticles::whereHas('categoryArticles', function ($q) {
                $q->where('category_id', $this->db->id);
            })
            ->count();

            $children = [];
            collect($this->db->children)->filter()->map(function ($item, $key) use (&$children,$currentDB,$currentData) {

                $item = $item->getResponeses($item, _get_languages($this->languages));
                $item = collect($item)->toArray();

                if ($this->db->visibility == 0 && $key == 0 && $currentDB == null) {
                    $this->category['description'] = $item['description'];
                    $this->category['name'] = $item['name'];
                    $item['url'] = $this->category['url'];
                }

              
                
                if ($this->db->visibility == 1) {
                    $article_child = WebArticles::whereHas('categoryArticles', function ($q) use ($item) {
                        $q->where('category_id', $item['id']);
                    })
                        ->with('translations')
                        ->orderBy('sort', 'asc')
                        ->orderBy('created_at', 'desc')
                        ->limit(3)
                        ->get();
        
                    $article_child = collect($article_child)->map(function ($item)  {
                        $data = $item->getResponeses($item, _get_languages($this->languages));
                        $item = collect($data)->toArray();
        
                        return $item;
                    })->sortBy('sort')->values()->toArray();

                    $item['list'] = $article_child;
                }
                $children[] = $item;
                return $item;
            })->values()->toArray();


            if ($currentData) {
                collect($databoard->children)->filter()->map(function ($item)  {
                    $item = $item->getResponeses($item, _get_languages($this->languages));
                    $item = collect($item)->toArray();
                    $itemArticle = [
                        'id' => $item['id'],
                        'name' => $item['name'],
                        'position' => $item['sub_name'],
                        'description' => $item['description'],
                        'image' => $item['image']['default'],
                    ];
                    $this->article[] = $itemArticle;
                });
                $this->category['image']['apps'] = collect($databoard->getResponeses($databoard, $language))->toArray()['image']['default'];
                $this->category['sub_name'] = collect($databoard->getResponeses($databoard, $language))->toArray()['sub_name'];
            }

            if ($this->db->visibility == 0 || $this->db->visibility == 8) {
                if (isset(collect($menus)->first()['children'])) {
                    $children = collect($menus)->first()['children'];
                }else{
                    $children = $menus;
                }
            }
            
            $this->children = $children;
            $this->category['active_page'] = $menu_id;
            $this->category['tab'] = $this->children;

            $this->category['template'] = config('cms.visibility.post.slug')[$this->db->visibility];

            if ($currentData) {
                $this->category['template'] = config('cms.visibility.post.slug')[$currentData];
            }

            $this->category['list'] = $this->article;
            $breadcrumbGet = (new WebArticleCategories())->breadcrumb($current->parent, $this->languages);
            $breadcrumb[] = [
                'id' => 0,
                'name' => $this->languages == 'id' ? 'Beranda' : 'Home',
                'url' => '/',
            ];

            foreach ($breadcrumbGet as $key => $value) {
                $breadcrumb[] = [
                    'id' => $value['id'],
                    'name' => $value['name'],
                    'url' => $value['url'],
                ];
            }

            $breadcrumb[] = [
                'id' => $current->id,
                'name' => $current->translations->first()->name,
                'url' => '/'.$this->languages.'/'.$current->translations->first()->slug,
            ];
            $this->category['breadcrumb'] = $breadcrumb;

            $MapVisibility = $this->MapVisibility();

            return $this->sendResponse($this->category, 'Category retrieved successfully.', $this->limit, $this->offset);
        } catch (\Exception $e) {
            return $e;
            $this->LogServices->handle(['table_id' => 0, 'name' => 'Log Error',   'json' => $e]);

            return $this->sendError($e->getMessage(), [], 404);
        }
    }

    public function seo()
    {
        try {
            if (! $this->db) {
                return $this->sendResponse([], 'Category retrieved successfully.');
            }

            if (! empty($this->db->translations->first()->redirection)) {
                $slug_r = explode('/', $this->db->translations->first()->redirection);
                $slug_r = $slug_r[count($slug_r) - 1];
                $code_map = DB::table('web_sitemaps')->where('slug', $slug_r)->first()?->code;
                if (! $code_map) {
                    return $this->sendResponse([], 'Category retrieved successfully.');
                }
                if ($code_map == 'category') {
                    $category = new \App\Http\Controllers\Api\Category\CategoryApiController($this->LogServices, $this->request, $this->languages, $slug_r);

                    return $category->seo();
                } elseif ($code_map == 'article') {
                    $article = new \App\Http\Controllers\Api\Article\ArticleApiController($this->LogServices);

                    return $article->seo($this->languages, $slug_r);
                } else {
                    return $this->sendResponse([], 'Data retrieved successfully.');
                }
            }

            $language = _get_languages($this->languages);

            $this->category = collect($this->db->getResponeses($this->db, $language))->toArray();

            $data = [];
            $data['meta'] = $this->category['meta'];
            $data['template'] =$this->category['template'];

            return $this->sendResponse($data, 'Category retrieved successfully.', $this->limit, $this->offset);
        } catch (\Exception $e) {
            $this->LogServices->handle(['table_id' => 0, 'name' => 'Log Error',   'json' => $e]);

            return $this->sendError($e->getMessage(), [], 404);
        }
    }

    public function MapVisibility()
    {
    }
}
