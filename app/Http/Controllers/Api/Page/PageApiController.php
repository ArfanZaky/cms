<?php

namespace App\Http\Controllers\Api\Page;

use App\Http\Controllers\Api\BaseController as BaseController;
use App\Models\WebPages;
use App\Services\LogServices;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PageApiController extends BaseController
{
    public function __construct(LogServices $LogServices)
    {
        $this->LogServices = $LogServices;
    }

    public function page_filter_by_slug(Request $request, $languages, $slug)
    {
        try {
            $language = _get_languages($languages);
            $visibility = WebPages::with('translations')->whereSlug($slug, $language)->first();
            if (! $visibility) {
                return $this->sendResponse([], 'Page retrieved successfully.');
            }

            $page = WebPages::with('translations')->where('visibility', $visibility->visibility)
                ->orderBy('sort', 'asc')
                ->get();
            if (! $page) {
                return $this->sendResponse([], 'Page retrieved successfully.');
            }

            $_custom_slug = '';
            $data = [];
            $itemsList = collect($page)->map(function ($item) use ($language, &$data, &$_custom_slug) {
                $item = $item->getResponeses($item, $language);
                $item = collect($item)->toArray();
                if ($item['custom'] == 'banner') {
                    $data[$item['custom']] = $item;
                } else {
                    $data[$item['custom']][] = $item;
                }
                $_custom_slug = $item['url'];

                return $item;
            });

            $data['meta'] = collect($itemsList)->first()['meta'];
            $data['translation'] = collect($itemsList)->first()['translation'];
            $data['template'] = collect($itemsList)->first()['template'];

            if (isset(config('cms.visibility.post.connect.page')[$visibility->visibility])) {
                $category = config('cms.visibility.post.connect.page')[$visibility->visibility];
                $id_active = 0;
                $category = collect($category)->map(function ($item) use ($languages, $request, $slug, &$data, $_custom_slug, &$id_active) {
                    $request->visibility = $item;
                    $request->_custom_slug = $_custom_slug;
                    $categories = new \App\Http\Controllers\Api\Category\CategoryApiController($this->LogServices, $request, $languages, $slug);
                    $categories = $categories->filter_by_article_slug();
                    $id_active = $id_active < collect($categories)['id_active'] ? collect($categories)['id_active'] : $id_active;
                    $data['tab'][] = $categories;
                });

                $data['tab'] = collect($data['tab'])->map(function ($item) use ($id_active) {
                    $item['id_active'] = $id_active;

                    return $item;
                });
            }

            $data['list'] = [];

            if ($languages == 'en') {
                $breadcrumbs = [
                    ['url' => '/', 'slug' => '/', 'target' => '_self', 'name' => 'Home'],
                    ['url' => 'javascript:void(0);', 'slug' => $slug, 'target' => '_self', 'name' => Str::replace('-', ' ', Str::title(preg_replace('/[0-9]+/', '', $slug)))],
                ];
            } else {
                $breadcrumbs = [
                    ['url' => '/', 'slug' => '/', 'target' => '_self', 'name' => 'Beranda'],
                    ['url' => 'javascript:void(0);', 'slug' => $slug, 'target' => '_self', 'name' => Str::replace('-', ' ', Str::title(preg_replace('/[0-9]+/', '', $slug)))],
                ];
            }

            $data['breadcrumb'] = $breadcrumbs;

            return $this->sendResponse($data, 'Page retrieved successfully.');
        } catch (\Exception $e) {
            $this->LogServices->handle(['table_id' => 0, 'name' => 'Log Error',   'json' => $e]);

            return $this->sendError('Something went wrong', [], 404);
        }
    }

    public function seo(Request $request, $languages, $slug)
    {
        try {
            $language = _get_languages($languages);
            $visibility = WebPages::with('translations')->whereSlug($slug, $language)->first();
            if (! $visibility) {
                return $this->sendResponse([], 'Seo retrieved successfully.');
            }

            $page = WebPages::with('translations')->where('visibility', $visibility->visibility)
                ->orderBy('sort', 'asc')
                ->get();
            if (! $page) {
                return $this->sendResponse([], 'Page retrieved successfully.');
            }

            $_custom_slug = '';
            $data = [];
            $itemsList = collect($page)->map(function ($item) use ($language, &$data, &$_custom_slug) {
                $item = $item->getResponeses($item, $language);
                $item = collect($item)->toArray();
                if ($item['custom'] == 'banner') {
                    $data[$item['custom']] = $item;
                } else {
                    $data[$item['custom']][] = $item;
                }
                $_custom_slug = $item['url'];

                return $item;
            });

            $data['meta'] = collect($itemsList)->first()['meta'];
          
            return $this->sendResponse($data, 'Page retrieved successfully.');
        } catch (\Exception $e) {
            $this->LogServices->handle(['table_id' => 0, 'name' => 'Log Error',   'json' => $e]);

            return $this->sendError('Something went wrong', [], 404);
        }
    }

    public function builder($data, $builder, $languages)
    {
        try {
            $language = _get_languages($languages);
            $description = view('engine.module.template-builder.index', compact('builder'))->render();
            $data = $data->getResponeses($data, $language);
            $data = collect($data)->toArray();
            $data['builder'] = $description;
        } catch (\Throwable $th) {
            dd($th);
        }

        return $data;
    }
}
