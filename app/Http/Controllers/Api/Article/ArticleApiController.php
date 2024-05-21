<?php

namespace App\Http\Controllers\Api\Article;

use App\Events\UpdateViewCount;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Models\WebArticles;
use App\Services\LogServices;
use Helper;

class ArticleApiController extends BaseController
{
    private $LogServices;

    private $IsArticles;

    public function __construct(LogServices $LogServices)
    {
        $this->LogServices = $LogServices;
        $this->IsArticles = false;
    }

    public function filter_by_slug($languages, $slug)
    {
        try {
            $language = _get_languages($languages);

            $db = WebArticles::with('translations')->whereSlug($slug, $language)->first();
            if (! $db) {
                return $this->sendResponse([], 'Article retrieved successfully.');
            }
            event(new UpdateViewCount($db));

            $id = $db->id;
            $data = $db->getResponeses($db, $language);
            $data = collect($data)->toArray();

            $result = $data;
            $result['template'] = config('cms.visibility.post.article')[$data['visibility']];

            $category = $db?->categoryArticles()?->first();
            $related = $category?->relation()->orderBy('created_at', 'desc')->where('article_id', '!=', $id)->limit(4)->get()->map(function ($item) {
                return $item->article()->first();
            });
            $category = $category->getResponeses($category, $language);
            $category = collect($category)->toArray();

            $related = collect($related)->map(function ($item) use ($language) {
                $item = $item->getResponeses($item, $language);
                $item = collect($item)->toArray();
                return $item;
            })->values()->toArray();

            $result['related'] = [
                'link' => $category['url'],
                'label' => $category['label'],
                'data' => $related,
            ];

            $result['breadcrumb'] = generateBreadcrumbArray($data['url'], $languages);

            return $this->sendResponse($result, 'Article retrieved successfully.');
        } catch (\Exception $e) {
            $this->LogServices->handle(['table_id' => 0, 'name' => 'Log Error',   'json' => $e]);

            return $this->sendError('Something went wrong', [], 404);
        }
    }

    public function seo($languages, $slug)
    {
        try {
            $language = _get_languages($languages);

            $db = WebArticles::with('translations')->whereSlug($slug, $language)->first();
            if (! $db) {
                return $this->sendResponse([], 'Article retrieved successfully.');
            }
            $id = $db->id;
            $data = $db->getResponeses($db, $language);
            $data = collect($data)->toArray();

            $result = [];
            $result['meta'] = $data['meta'];
            $result['template'] = config('cms.visibility.post.article')[$data['visibility']];

            return $this->sendResponse($result, 'Article retrieved successfully.');
        } catch (\Exception $e) {
            $this->LogServices->handle(['table_id' => 0, 'name' => 'Log Error',   'json' => $e]);

            return $this->sendError('Something went wrong', [], 404);
        }
    }
}
