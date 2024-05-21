<?php

namespace App\Http\Controllers\Api\Search;

use App\Http\Controllers\Api\BaseController as BaseController;
use App\Models\WebPages;
use Helper;
use Illuminate\Http\Request;

class SearchApiController extends BaseController
{
    private $offset;
    private $limit;
    public function __construct(Request $request)
    {
        $this->offset = $request->offset ?? 0;
        $this->limit = $request->limit ?? 10;
    }

    public function search($languages)
    {
        try {
            $keyword = request()->keyword;
            if ($keyword == '') {
                return $this->sendError('Keyword is required', [], 404);
            }
            $search = Helper::_search($languages, $keyword, $this->offset, $this->limit);
            $data = $search['items'];
            $total_data = $search['total'];

            $language = _get_languages($languages);
            

            $resources = [];
            $routeSearch = '/'.$languages.'/search';
            $resources['next'] = $total_data > $this->offset + $this->limit ? $routeSearch.'?keyword='.$keyword.'&offset='.($this->offset + $this->limit).'&limit='.$this->limit : false;
            $resources['prev'] = $this->offset > 0 ? $routeSearch.'?keyword='.$keyword.'&offset='.($this->offset - $this->limit).'&limit='.$this->limit : false;
            $resources['items'] = $data;

            $result = [];
            $result['meta'] = [
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

            $result['translation'] = ($languages == 'id') ? [
                'code' => 'en',
                'url' => '/en/search?keyword='.$keyword,
            ]
             : [
                 'code' => 'id',
                 'url' => '  /id/search?keyword='.$keyword,
             ];

            $result['template'] = 'search';
            $result['items'] = $resources;

            return $this->sendResponse($result, 'Search retrieved successfully.');
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);

            return $this->sendError('Something went wrong', [], 404);
        }
    }
}
