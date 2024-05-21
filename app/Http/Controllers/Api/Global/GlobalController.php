<?php

namespace App\Http\Controllers\Api\Global;

use App\Http\Controllers\Api\BaseController as BaseController;
use App\Models\WebArticleCategories;
use App\Models\WebMenus;
use App\Models\WebPages;
use App\Services\ApiService;
use App\Services\LogServices;
use Carbon\Carbon;
use Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\Sitemap\SitemapGenerator;
use Spatie\Sitemap\Tags\Url;
use Validator;
use voku\helper\HtmlDomParser;

class GlobalController extends BaseController
{
    public function __construct(LogServices $LogServices, ApiService $ApiService)
    {
        $this->LogServices = $LogServices;
        $this->ApiService = $ApiService;
    }

    public function home($languages)
    {
        try {
            $language = _get_languages($languages);
            $section['section1'] = Helper::_category_post_id(3, $language);
            $data_section = Helper::_category_post_id(4, $language);
            $data_section = collect($data_section['items']);
            $section['section2'] = $data_section->where('sort', 1)->first();

            $section['meta'] = [
                'title' => Helper::_setting_code('web_title'),
                'description' => Helper::_setting_code('web_description'),
                'canonical' => Helper::_setting_code('web_url'),
                'image' => env('APP_URL').Helper::_setting_code('web_image'),
                'meta' => [
                    'charset' => 'utf-8',
                    'name' => [
                        'keywords' => Helper::_setting_code('web_keyword'),
                    ],
                ],
                'og:title' => Helper::_setting_code('web_title'),
                'og:description' => Helper::_setting_code('web_description'),
                'og:type' => 'website',
                'og:url' => Helper::_setting_code('web_url'),
                'og:image' => env('APP_URL').Helper::_setting_code('web_image'),
                'og:image:alt' => asset('assets/img/default.jpg'),
                'twitter:card' => 'summary',
                'twitter:site' => Helper::_setting_code('name_company'),
                'twitter:title' => Helper::_setting_code('web_title'),
                'twitter:description' => Helper::_setting_code('web_description'),
                'twitter:image' => env('APP_URL').Helper::_setting_code('web_image'),
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
                'title' => Helper::_setting_code('web_title'),
                'description' => Helper::_setting_code('web_description'),
                'canonical' => Helper::_setting_code('web_url'),
                'image' => env('APP_URL').Helper::_setting_code('web_image'),
                'meta' => [
                    'charset' => 'utf-8',
                    'name' => [
                        'keywords' => Helper::_setting_code('web_keyword'),
                    ],
                ],
                'og:title' => Helper::_setting_code('web_title'),
                'og:description' => Helper::_setting_code('web_description'),
                'og:type' => 'website',
                'og:url' => Helper::_setting_code('web_url'),
                'og:image' => env('APP_URL').Helper::_setting_code('web_image'),
                'og:image:alt' => asset('assets/img/default.jpg'),
                'twitter:card' => 'summary',
                'twitter:site' => Helper::_setting_code('name_company'),
                'twitter:title' => Helper::_setting_code('web_title'),
                'twitter:description' => Helper::_setting_code('web_description'),
                'twitter:image' => env('APP_URL').Helper::_setting_code('web_image'),
            ];
            return $this->sendResponse($section, 'Home retrieved successfully.');
        } catch (\Exception $e) {
            $this->LogServices->handle(['table_id' => 0, 'name' => 'Log Error',   'json' => $e]);

            return $this->sendError('Something went wrong', $e->getMessage(), 404);
        }
    }

    private function order_menu($value)
    {
        if ($value == 'quick_access') {
            return 2;
        } elseif ($value == 'internal_engagements') {
            return 3;
        } elseif ($value == 'useful_links') {
            return 4;
        } elseif ($value == 'other_links') {
            return 5;
        } elseif ($value == 'contact_us') {
            return 1;
        } elseif ($value == 'install_boi_mobile_banking') {
            return 6;
        } elseif ($value == 'connect_with_us') {
            return 7;
        } elseif ($value == 'copy_right') {
            return 8;
        } elseif ($value == 'recommended_products') {
            return 9;
        } elseif ($value == 'services_you_might_be_interested_in') {
            return 10;
        } elseif ($value == 'quick_links') {
            return 11;
        } elseif ($value == 'shield') {
            return 12;
        } elseif ($value == 'social_media') {
            return 13;
        } elseif ($value == 'feedback') {
            return 14;
        } elseif ($value == 'internet_banking') {
            return 15;
        } else {
            return 0;
        }

        return 0;
    }

    public function navigasi(Request $request, $languages)
    {
        try {
            $language = _get_languages($languages);
            $menus = WebMenus::with(['translations' => function ($q) use ($language) {
                $q->where('language_id', $language);
            }])
                ->whereIn('visibility', [0,1,2,3])
                ->orderBy('sort', 'asc')
                ->get();

            $menus = $this->ApiService->toArray($menus, true, $languages);
            $menus = Helper::tree($menus);
            $menus = collect($menus)->groupBy('visibility')->toArray();

            $menus = collect($menus)->map(function ($value, $key) use ($language) {
                $items['name'] = Helper::_wording($key, $language) != '' ? Helper::_wording($key, $language) : $key;
                $items['items'] = $value;
                $items['order'] = $this->order_menu($key);

                return $items;
            })->sortBy('order')->values();

            $data['menu'] = $menus;
            $data['setting'] = [
                'name' => Helper::_setting_code('name_company'),
                'address' => Helper::_setting_code('web_address'),
                'phone' => Helper::_setting_code('web_phone'),
                'fax' => Helper::_setting_code('web_fax'),
                'social' => json_decode(Helper::_setting_code('web_social'), true),
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
        $company = Helper::_setting_code('web_manatal');
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

    public function mapping(Request $request, $lang,$slug = null)
    { 
        if ($slug == null) {
            return $this->sendResponse([], 'Data retrieved successfully.');
        }

        $slug = explode('/', $slug);
        $slug = $slug[count($slug) - 1];
        $code_map = DB::table('web_sitemaps')->where('slug', $slug)->first()?->code;
        if (! $code_map) {
            return $this->sendResponse([], 'Category retrieved successfully.');
        }
        if ($code_map == 'category') {
            $category = new \App\Http\Controllers\Api\Category\CategoryApiController($this->LogServices, $request, $lang, $slug);

            return $category->filter_by_article_slug();
        } elseif ($code_map == 'article') {
            $article = new \App\Http\Controllers\Api\Article\ArticleApiController($this->LogServices);

            return $article->filter_by_slug($lang, $slug);
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
            return $this->sendResponse([], 'Category retrieved successfully.');
        }
        if ($code_map == 'category') {
            $category = new \App\Http\Controllers\Api\Category\CategoryApiController($this->LogServices, $request, $lang, $slug);

            return $category->seo();
        } elseif ($code_map == 'article') {
            $article = new \App\Http\Controllers\Api\Article\ArticleApiController($this->LogServices);

            return $article->seo($lang, $slug);
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

    public function chat(Request $request, $lang)
    {
        $languages = _get_languages($lang);
        $searchTerm = $request->word ?? 'ligula';
        // $results = WebPages::searchData($searchTerm);
        $foo = new WebPages();
        $results = $foo->SearchData($searchTerm);

        dd($results->toArray());
    }

    public function sitemap()
    {
        $languages = config('app.language_setting');
        $data = [];
        for ($i = 0; $i < $languages; $i++) {
            $menus = WebMenus::with(['translations' => function ($q) use ($i) {
                $q->where('language_id', ($i + 1));
            }])
                ->where('visibility', 0)
                ->orderBy('sort', 'asc')
                ->get();
            $language = code_lang()[$i];
            $data[] = $this->ApiService->toArray($menus, true, $language);
        }
        $slug = collect($data)->collapse()->unique('slug')->pluck('slug')->filter(
            function ($value, $key) {
                return $value != '#';
            }
        )->toArray();

        $websitemap = DB::table('web_sitemaps')->get();
        $websitemap = collect($websitemap)->whereIn('code', ['variant', 'product'])->map(function ($item, $key) {
            $language = code_lang()[($item->language - 1)];

            return '/product-detail'.'/'.$language.'/'.$item->code.'/'.$item->slug;
        })->toArray();

        $slug = array_merge($slug, $websitemap);
        $path = public_path('sitemap.xml');
        $sitemapGenerator = SitemapGenerator::create('https://bukitbaroscempaka.co.id');
        $sitemap = $sitemapGenerator->getSitemap();
        foreach ($slug as $url) {
            $sitemap->add(Url::create('https://bukitbaroscempaka.co.id'.$url)
                ->setLastModificationDate(Carbon::yesterday())
                ->setChangeFrequency(
                    $this->frequencySitemap($url)
                )
                ->setPriority(
                    $this->prioritySitemap($url)
                )
            );
        }
        $sitemap->writeToFile($path);
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

    public function get_city_by_province($id)
    {
        $data = DB::table('city')->select('id as value', 'kabupaten_kota as label')->where('provinsi_id', $id)
            ->get();

        $data = collect($data)->map(function ($item, $key) {
            return [
                'label' => $item->label,
                'value' => $item->value,
                'selected' => ($key == 0) ? true : false,
            ];
        })->values();

        return $this->sendResponse($data, 'Data retrieved successfully.');
    }

    public function get_category_by_parent(Request $request, $languages, $id)
    {
        try {
            $language = _get_languages($languages);
            $db = WebArticleCategories::with(['translations', 'children.translations'])->find($id);
            if (! $db) {
                return $this->sendResponse([], 'Category retrieved successfully.');
            }
            $children = $db?->children;
            $children = collect($children)->filter()->map(function ($item, $key) use ($language) {
                $item = $item->getResponeses($item, $language);
                $item = collect($item)->toArray();

                return [
                    'label' => $item['name'],
                    'value' => $item['id'],
                    'selected' => false,
                ];
            })->toArray();

            if (empty($children)) {
                $item = $db->getResponeses($db, $language);
                $item = collect($item)->toArray();

                $all = [
                    [
                        'label' => Helper::_wording('choose', $language),
                        'value' => '',
                        'selected' => true,
                    ], [
                        'label' => $item['name'],
                        'value' => $item['id'],
                        'selected' => false,
                    ],

                ];

                $product = collect($all)->values();

                return $this->sendResponse($product, 'Category retrieved successfully.');
            }

            $all = [
                [
                    'label' => Helper::_wording('choose', $language),
                    'value' => '',
                    'selected' => true,
                ],
            ];

            $product = collect($all)->merge($children)->values();

            return $this->sendResponse($product, 'Category retrieved successfully.');
        } catch (\Exception $e) {
            $this->LogServices->handle(['table_id' => 0, 'name' => 'Log Error',   'json' => $e]);

            return $this->sendError($e->getMessage(), [], 404);
        }
    }

    public function feedbackStore(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'point' => 'required|numeric',
        ], [
            'point.required' => 'Point is required',
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(), [], 404);
        }

        try {
            $data = DB::table('web_feedbacks')->insert(
                [
                    'value' => (int) $request->point,
                ]
            );
            $result['success'] = true;
            $result['description'] = Helper::_wording('feedback_success', 1);

            return $this->sendResponse($result, 'Feedback retrieved successfully.');
        } catch (\Exception $e) {
            $this->LogServices->handle(['table_id' => 0, 'name' => 'Log Error',   'json' => $e]);

            return $this->sendError('Something went wrong', [], 404);
        }
    }

    public function calculator(Request $request)
    {
        try {
            $input = $request->all();
            $validator = Validator::make($input, [
                'id_product' => 'required|numeric',
                'type' => 'required',
            ], [
                'id_product.required' => 'Id Product is required',
                'type.required' => 'Type is required',
            ]);

            if ($validator->fails()) {
                return $this->sendError($validator->errors()->first(), [], 404);
            }

            $data = WebArticleCategories::where('id', $request->id_product)->first();

            if (! $data) {
                return $this->sendError('Data not found', [], 404);
            }

            $result['description'] = '';
            if ($request->type == 'loan') {
                $validator = Validator::make($input, [
                    'loan' => 'required|numeric',
                    'tenure' => 'required|numeric',
                    'interest_rate' => 'required',
                    'metode' => 'required',
                ], [
                    'loan.required' => 'Loan is required',
                    'tenure.required' => 'Tenure is required',
                    'interest_rate.required' => 'Interest Rate is required',
                    'metode.required' => 'Metode is required',
                ]);

                if ($validator->fails()) {
                    return $this->sendError($validator->errors()->first(), [], 404);
                }

                $result['description'] = $data->loan_calculator($request);
            } elseif ($request->type == 'deposit') {
                $validator = Validator::make($input, [
                    'deposito' => 'required|numeric',
                    'periode' => 'required|numeric',
                    'tax' => 'required',
                ], [
                    'deposito.required' => 'Deposito is required',
                    'periode.required' => 'Periode is required',
                    'tax.required' => 'Tax is required',
                ]);
                $result['description'] = $data->deposit_calculator($request);
            }

            return $this->sendResponse($result, 'Calculator retrieved successfully.');
        } catch (\Exception $e) {
            $this->LogServices->handle(['table_id' => 0, 'name' => 'Log Error',   'json' => $e]);

            return $this->sendError('Something went wrong', [], 404);
        }
    }

    public function calculatorKurs(Request $request)
    {
        try {
            $input = $request->all();
            $validator = Validator::make($input, [
                'type' => 'required',
                'value' => 'required',
                'from' => 'required',
                'to' => 'required',
            ], [
                'type.required' => 'Type is required',
                'value.required' => 'Value is required',
                'from.required' => 'From is required',
                'to.required' => 'To is required',
            ]);

            if ($validator->fails()) {
                return $this->sendError($validator->errors()->first(), [], 404);
            }

            $kurs = DB::table('web_kurs')->orderBy('created_at', 'desc')->get();
            if (! $kurs) {
                return $this->sendError('Data not found', [], 404);
            }

            $kurs = collect($kurs)->map(function ($item, $key) {
                return [
                    'currency' => $item->currency,
                    'buy' => $item->bn_buy,
                    'sell' => $item->bn_sell,
                ];
            })->toArray();

            $idr = [
                [
                    'currency' => 'IDR',
                    'buy' => 1,
                    'sell' => 1,
                ],
            ];

            $kurs = collect($kurs)->merge($idr)->values()->toArray();

            $to = collect($kurs)->where('currency', $request->to)?->first();
            $from = collect($kurs)->where('currency', $request->from)?->first();

            if (! $to || ! $from) {
                return $this->sendError('Data not found', [], 404);
            }

            $result = [];
            if (strtolower($request->type) == 'buy') {
                $rate = ((float) $from['buy'] / (float) $to['buy']) * (float) $request->value;
                $result['result'] = number_format($rate, 2);
            } elseif (strtolower($request->type) == 'sell') {
                $rate = ((float) $from['sell'] / (float) $to['sell']) * (float) $request->value;
                $result['result'] = number_format($rate, 2);
            }

            return $this->sendResponse($result, 'Calculator retrieved successfully.');
        } catch (\Exception $e) {
            return $e;
            $this->LogServices->handle(['table_id' => 0, 'name' => 'Log Error',   'json' => $e]);

            return $this->sendError('Something went wrong', [], 404);
        }
    }
}
