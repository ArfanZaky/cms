<?php

namespace App\Http\Controllers\Api\Global;

use App\Http\Controllers\Api\BaseController as BaseController;
use App\Models\WebContent;
use App\Models\WebMenus;
use App\Services\ApiService;
use App\Services\LogServices;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use voku\helper\HtmlDomParser;

class GlobalController extends BaseController
{
    private $LogServices;

    private $ApiService;

    public function __construct(LogServices $LogServices, ApiService $ApiService)
    {
        $this->LogServices = $LogServices;
        $this->ApiService = $ApiService;
    }

    public function home($languages)
    {
        try {
            $language = _get_languages($languages);
            $section['section1'] = \App\Helper\Helper::_content_post_id(3, $language);
            $data_section = \App\Helper\Helper::_content_post_id(4, $language);
            $data_section = collect($data_section['items']);
            $section['section2'] = $data_section->where('sort', 1)->first();

            $section['meta'] = [
                'title' => \App\Helper\Helper::_setting_code('web_title'),
                'description' => \App\Helper\Helper::_setting_code('web_description'),
                'canonical' => \App\Helper\Helper::_setting_code('web_url'),
                'image' => env('APP_URL').\App\Helper\Helper::_setting_code('web_image'),
                'meta' => [
                    'charset' => 'utf-8',
                    'name' => [
                        'keywords' => \App\Helper\Helper::_setting_code('web_keyword'),
                    ],
                ],
                'og:title' => \App\Helper\Helper::_setting_code('web_title'),
                'og:description' => \App\Helper\Helper::_setting_code('web_description'),
                'og:type' => 'website',
                'og:url' => \App\Helper\Helper::_setting_code('web_url'),
                'og:image' => env('APP_URL').\App\Helper\Helper::_setting_code('web_image'),
                'og:image:alt' => asset('assets/img/default.jpg'),
                'twitter:card' => 'summary',
                'twitter:site' => \App\Helper\Helper::_setting_code('name_company'),
                'twitter:title' => \App\Helper\Helper::_setting_code('web_title'),
                'twitter:description' => \App\Helper\Helper::_setting_code('web_description'),
                'twitter:image' => env('APP_URL').\App\Helper\Helper::_setting_code('web_image'),
            ];
            $section['template'] = 'home';
            $section['breadcrumb'] = [];
            $section['translation'] = [
                'name' => 'Home',
                'slug' => 'home',
                'code' => ($languages == 'id') ? 'en' : 'id',
                'url' => ($languages == 'id') ? '/en/home/' : '/id/home/',
                'template' => 'home',
            ];

            $section['active_page'] = -1;

            $section['breadcrumb'] = [];

            return $this->sendResponse($section, 'Home retrieved successfully.');
        } catch (\Exception $e) {
            $this->LogServices->handle(['table_id' => 0, 'name' => 'Log Error',   'json' => $e]);

            return $this->sendError('Something went wrong', $e->getMessage(), 404);
        }
    }

    public function home_seo($languages)
    {
        try {
            $section['meta'] = [
                'title' => \App\Helper\Helper::_setting_code('web_title'),
                'description' => \App\Helper\Helper::_setting_code('web_description'),
                'canonical' => \App\Helper\Helper::_setting_code('web_url'),
                'image' => env('APP_URL').\App\Helper\Helper::_setting_code('web_image'),
                'meta' => [
                    'charset' => 'utf-8',
                    'name' => [
                        'keywords' => \App\Helper\Helper::_setting_code('web_keyword'),
                    ],
                ],
                'og:title' => \App\Helper\Helper::_setting_code('web_title'),
                'og:description' => \App\Helper\Helper::_setting_code('web_description'),
                'og:type' => 'website',
                'og:url' => \App\Helper\Helper::_setting_code('web_url'),
                'og:image' => env('APP_URL').\App\Helper\Helper::_setting_code('web_image'),
                'og:image:alt' => asset('assets/img/default.jpg'),
                'twitter:card' => 'summary',
                'twitter:site' => \App\Helper\Helper::_setting_code('name_company'),
                'twitter:title' => \App\Helper\Helper::_setting_code('web_title'),
                'twitter:description' => \App\Helper\Helper::_setting_code('web_description'),
                'twitter:image' => env('APP_URL').\App\Helper\Helper::_setting_code('web_image'),
            ];

            return $this->sendResponse($section, 'Home retrieved successfully.');
        } catch (\Exception $e) {
            $this->LogServices->handle(['table_id' => 0, 'name' => 'Log Error',   'json' => $e]);

            return $this->sendError('Something went wrong', $e->getMessage(), 404);
        }
    }

    public function navigasi(Request $request, $languages)
    {
        try {
            $language = _get_languages($languages);
            $menus = WebMenus::with(['translations' => function ($q) use ($language) {
                $q->where('language_id', $language);
            }])
                ->whereIn('visibility', [0, 1, 2, 3])
                ->orderBy('sort', 'asc')
                ->get();

            $menus = $this->ApiService->toArray($menus, true, $languages);
            $menus = \App\Helper\Helper::tree($menus);
            $menus = collect($menus)->groupBy('visibility')->toArray();

            $menus = collect($menus)->map(function ($value, $key) use ($language) {
                $items['name'] = \App\Helper\Helper::_wording($key, $language) != '' ? \App\Helper\Helper::_wording($key, $language) : $key;
                $items['items'] = $value;

                return $items;
            })->values();

            $data['menu'] = $menus;
            $data['setting'] = [
                'name' => \App\Helper\Helper::_setting_code('name_company'),
                'address' => \App\Helper\Helper::_setting_code('web_address'),
                'phone' => \App\Helper\Helper::_setting_code('web_phone'),
                'fax' => \App\Helper\Helper::_setting_code('web_fax'),
                'social' => json_decode(\App\Helper\Helper::_setting_code('web_social'), true),
            ];

            return $this->sendResponse($data, 'Footer retrieved successfully.');
        } catch (\Exception $e) {
            $this->LogServices->handle(['table_id' => 0, 'name' => 'Log Error',   'json' => $e]);

            return $this->sendError('Something went wrong', $e->getMessage(), 404);
        }
    }

    public function wording($languages)
    {
        try {
            $language = _get_languages($languages);

            $directory = 'wording';
            $wording = Storage::disk('public')->get($directory.'/wording.json');
            $wording = json_decode($wording, true);
            $result = [];
            if (isset($wording[$languages])) {
                $result = $wording[$languages];
            }

            return $this->sendResponse($result, 'Wording retrieved successfully.');
        } catch (\Exception $e) {
            $this->LogServices->handle(['table_id' => 0, 'name' => 'Log Error',   'json' => $e]);

            return $this->sendError('Something went wrong', [], 404);
        }
    }

    public function Manatal()
    {
        $company = \App\Helper\Helper::_setting_code('web_manatal');
        $manatal = 'https://www.careers-page.com/'.$company;
        $data = file_get_contents($manatal);
        $html = HtmlDomParser::str_get_html($data);
        $li = $html->find('li[class=media]');
        $json = [];
        foreach ($li as $element) {
            $a = $element->find('a', 0);
            $h5 = $element->find('h5', 0);
            $code = str_replace('/'.$company.'/job/', '', $a->href);
            if (! empty($code)) {
                $url = $manatal.'/job/'.$code;
                @$data = file_get_contents($url);
                if ($data) {
                    $html = HtmlDomParser::str_get_html($data);
                    $title = $html->find('h4', 0);
                    $description = $html->find('div[class=redactor-styles]', 0);
                    $description = $description->innertext;
                    $description = htmlspecialchars($description);
                    // create to db web_manatal
                    $get = DB::table('web_manatal')->where('title', trim($title->plaintext))->first();
                    if ($get) {
                        DB::table('web_manatal')->where('title', trim($title->plaintext))->update(
                            [
                                'description' => trim($description),
                                'link_detail' => 'https://www.careers-page.com/'.$company.'/job/'.$code,
                                'link_apply' => 'https://www.careers-page.com/'.$company.'/job/'.$code.'/apply',
                                'link_refer' => 'https://www.careers-page.com/'.$company.'/job/'.$code.'/refer',
                            ]
                        );
                        $json[] = [
                            'status' => 'updated',
                            'title' => trim($title->plaintext),
                            'link' => [
                                'detail' => 'https://www.careers-page.com/'.$company.'/job/'.$code,
                                'apply' => 'https://www.careers-page.com/'.$company.'/job/'.$code.'/apply',
                                'refer' => 'https://www.careers-page.com/'.$company.'/job/'.$code.'/refer',
                            ],
                        ];
                    } else {
                        DB::table('web_manatal')->insert(
                            [
                                'title' => trim($title->plaintext),
                                'description' => trim($description),
                                'link_detail' => 'https://www.careers-page.com/'.$company.'/job/'.$code,
                                'link_apply' => 'https://www.careers-page.com/'.$company.'/job/'.$code.'/apply',
                                'link_refer' => 'https://www.careers-page.com/'.$company.'/job/'.$code.'/refer',
                            ]
                        );
                        $json[] = [
                            'status' => 'inserted',
                            'title' => trim($title->plaintext),
                            'link' => [
                                'detail' => 'https://www.careers-page.com/'.$company.'/job/'.$code,
                                'apply' => 'https://www.careers-page.com/'.$company.'/job/'.$code.'/apply',
                                'refer' => 'https://www.careers-page.com/'.$company.'/job/'.$code.'/refer',
                            ],
                        ];

                    }
                }
            }
        }

        return response()->json($json, 200);
    }

    public function mapping(Request $request, $lang, $slug = null)
    {
        if ($slug == null) {
            return $this->sendResponse([], 'Data retrieved successfully.');
        }

        $slug = explode('/', $slug);
        $slug = $slug[count($slug) - 1];
        $code_map = DB::table('web_sitemaps')->where('slug', $slug)->first()?->code;
        if (! $code_map) {
            return $this->sendResponse([], 'content retrieved successfully.');
        }
        if ($code_map == 'content') {
            $content = new \App\Http\Controllers\Api\content\contentApiController($this->LogServices, $request, $lang, $slug);

            return $content->filter_by_article_slug();
        } else {
            return $this->sendResponse([], 'Data retrieved successfully.');
        }

        return $this->sendResponse([], 'No result found.');
    }

    public function seo(Request $request, $lang, $slug = null)
    {
        if ($slug == null) {
            return $this->sendResponse([], 'Data retrieved successfully.');
        }

        $slug = explode('/', $slug);
        $slug = $slug[count($slug) - 1];
        $code_map = DB::table('web_sitemaps')->where('slug', $slug)->first()?->code;
        if (! $code_map) {
            return $this->sendResponse([], 'content retrieved successfully.');
        }
        if ($code_map == 'content') {
            $content = new \App\Http\Controllers\Api\content\contentApiController($this->LogServices, $request, $lang, $slug);

            return $content->seo();
        } else {
            return $this->sendResponse([], 'Data retrieved successfully.');
        }

        return $this->sendResponse([], 'No result found.');
    }

    public function getManatal()
    {
        $data = DB::table('web_manatal')->get();

        $manatal = [];
        collect($data)->map(function ($item) use (&$manatal) {
            $manatal[] = [
                'title' => $item->title,
                'description' => html_entity_decode($item->description),
                'link' => $item->link_apply,
            ];
        });

        return $this->sendResponse($manatal, 'Manatal retrieved successfully.');
    }

    public function sitemap(Request $request)
    {
        $language = _get_languages($request->lang);
        $lang = $request->lang.'-sitemap.xml';
        $path = public_path($lang);
        $sitemap = Sitemap::create();

        $content = WebContent::with(['translations'])->orderBy('sort', 'asc')->get();
        $content = collect($content)->map(function ($item, $key) use ($language) {
            $article = $item->getResponeses($item, $language);
            $article = collect($article)->toArray();

            return [
                'id' => $item->id,
                'parent' => $item->parent,
                'children' => [],
                'name' => $article['name'],
                'visibility' => $item->visibility,
                'sort' => $item->sort,
                'status' => $item->status,
                'url' => $article['url'],
            ];
        })->toArray();

        foreach ($content as $key => $value) {
            $sitemap->add(Url::create(\App\Helper\Helper::_setting_code('web_url').$value['url'])
                ->setLastModificationDate(Carbon::yesterday())
                ->setChangeFrequency(
                    $this->frequencySitemap($value['url'])
                )
                ->setPriority(
                    $this->prioritySitemap($value['url'])
                )
            );
        }

        $sitemap->writeToFile($path);
        $path = public_path($lang);
        $file = file_get_contents($path);

        return response($file, 200)
            ->header('Content-Type', 'text/xml');
    }

    public function frequencySitemap($url)
    {
        if ($url == '/') {
            return Url::CHANGE_FREQUENCY_WEEKLY;
        } elseif (Str::contains($url, 'page')) {
            return Url::CHANGE_FREQUENCY_MONTHLY;
        } elseif (Str::contains($url, 'product')) {
            return Url::CHANGE_FREQUENCY_DAILY;
        } elseif (Str::contains($url, 'variant')) {
            return Url::CHANGE_FREQUENCY_DAILY;
        } elseif (Str::contains($url, 'catalog')) {
            return Url::CHANGE_FREQUENCY_DAILY;
        } else {
            return Url::CHANGE_FREQUENCY_WEEKLY;
        }
    }

    public function prioritySitemap($url)
    {
        if ($url == '/') {
            return 1;
        } elseif (Str::contains($url, 'page')) {
            return 0.8;
        } elseif (Str::contains($url, 'product')) {
            return 0.6;
        } elseif (Str::contains($url, 'variant')) {
            return 0.6;
        } elseif (Str::contains($url, 'catalog')) {
            return 0.6;
        } else {
            return 0.8;
        }
    }
}
