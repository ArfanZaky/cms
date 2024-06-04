<?php

namespace App\Http\Controllers\Api\content;

use App\Events\UpdateViewCount;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Models\WebContent;
use App\Services\LogServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContentApiController extends BaseController
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

    protected $content;

    protected $children;

    protected $dev;

    public function __construct(LogServices $LogServices, Request $request, $languages, $slug)
    {
        $this->LogServices = $LogServices;
        $this->request = $request;
        $this->languages = $languages;
        $this->slug = $slug;
        $this->content = [];
        $this->children = [];
        $this->limit = $this->request->input('limit', 10);
        $this->dev = $this->request->input('dev', false);

        $this->db = WebContent::with([
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
            'parents.parents.children.children.translations',
        ])->whereSlug($this->slug, _get_languages($this->languages))->first();

        if (! $this->db) {
            return $this->sendResponse([], 'content retrieved successfully.');
        }
        $this->offset = $this->request->active != config('cms.visibility.post.slug')[$this->db->visibility] ? 0 : $this->request->input('offset', 0);

    }

    public function filter_by_article_slug()
    {
        try {
            if (! $this->db) {
                return $this->sendResponse([], 'content retrieved successfully.');
            }
            event(new UpdateViewCount($this->db));

            if (! empty($this->db->translations->first()->redirection)) {
                $slug_r = explode('/', $this->db->translations->first()->redirection);
                $slug_r = $slug_r[count($slug_r) - 1];
                $code_map = DB::table('web_sitemaps')->where('slug', $slug_r)->first()?->code;
                if (! $code_map) {
                    return $this->sendResponse([], 'content retrieved successfully.');
                }
                if ($code_map == 'content') {
                    $content = new \App\Http\Controllers\Api\Content\ContentApiController($this->LogServices, $this->request, $this->languages, $slug_r);

                    return $content->filter_by_article_slug();
                } else {
                    return $this->sendResponse([], 'Data retrieved successfully.');
                }
            }

            $language = _get_languages($this->languages);

            $this->content = collect($this->db->getResponeses($this->db, $language))->toArray();

            $children = [];
            collect($this->db->children)->filter()->map(function ($item, $key) use (&$children) {

                $item = $item->getResponeses($item, _get_languages($this->languages));
                $item = collect($item)->toArray();
                $children[] = $item;

                return $item;
            })->values()->toArray();

            $this->children = $children;

            $this->content['template'] = config('cms.visibility.post.slug')[$this->db->visibility];

            $this->content['list'] = $this->children;
            $breadcrumbGet = (new WebContent())->breadcrumb($this->db->parent, $this->languages);
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
                'id' => $this->db->id,
                'name' => $this->db->translations->first()->name,
                'url' => '/'.$this->languages.'/'.$this->db->translations->first()->slug,
            ];

            $this->content['breadcrumb'] = $breadcrumb;

            $this->MapVisibility();

            return $this->sendResponse($this->content, 'content retrieved successfully.', $this->limit, $this->offset);
        } catch (\Exception $e) {
            $this->LogServices->handle(['table_id' => 0, 'name' => 'Log Error',   'json' => $e]);

            return $this->sendError($e->getMessage(), [], 404);
        }
    }

    public function seo()
    {
        try {
            if (! $this->db) {
                return $this->sendResponse([], 'content retrieved successfully.');
            }

            if (! empty($this->db->translations->first()->redirection)) {
                $slug_r = explode('/', $this->db->translations->first()->redirection);
                $slug_r = $slug_r[count($slug_r) - 1];
                $code_map = DB::table('web_sitemaps')->where('slug', $slug_r)->first()?->code;
                if (! $code_map) {
                    return $this->sendResponse([], 'content retrieved successfully.');
                }
                if ($code_map == 'content') {
                    $content = new \App\Http\Controllers\Api\Content\ContentApiController($this->LogServices, $this->request, $this->languages, $slug_r);

                    return $content->seo();
                } else {
                    return $this->sendResponse([], 'Data retrieved successfully.');
                }
            }

            $language = _get_languages($this->languages);

            $this->content = collect($this->db->getResponeses($this->db, $language))->toArray();

            $data = [];
            $data['meta'] = $this->content['meta'];
            $data['template'] = $this->content['template'];

            return $this->sendResponse($data, 'content retrieved successfully.', $this->limit, $this->offset);
        } catch (\Exception $e) {
            $this->LogServices->handle(['table_id' => 0, 'name' => 'Log Error',   'json' => $e]);

            return $this->sendError($e->getMessage(), [], 404);
        }
    }

    public function MapVisibility()
    {
    }
}
