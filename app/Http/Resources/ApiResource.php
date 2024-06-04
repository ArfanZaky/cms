<?php

namespace App\Http\Resources;

use App\Models\User;
use App\Models\WebContent;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class ApiResource extends JsonResource
{
    protected $lang;
    protected $array_lang;
    protected $get_model;
    protected $url;

    public function __construct($resource, int $language, $array_lang = [], $get_model = false, $url = false)
    {
        parent::__construct($resource);
        $this->lang = $language;
        $this->array_lang = $array_lang;
        $this->get_model = $get_model;
        $this->url = $url;
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $lang = code_lang()[$this->lang - 1];
        if ($this->url) {
            $url = $this->url;
        } else {
            $url = isset($this->translations[($this->lang - 1)]->slug) ? $this->translations[($this->lang - 1)]->slug : false;
            $url = $this->get_model ? '/'.$lang.'/'.$url : $url;
        }

        if ($this->responseData('redirection')) {
            $url = $this->responseData('redirection');
            // check if has /storage/
            if (strpos($url, '/storage/') !== false) {
                $url = env('APP_URL').$url;
            }
        }

        $redirection = $this->responseData('redirection');
        if (strpos($redirection, '/storage/') !== false) {
            $redirection = env('APP_URL').$redirection;
        }

        $url1 = $this->responseData('url_1');
        if (strpos($url1, '/storage/') !== false) {
            $url1 = env('APP_URL').$url1;
        }

        $url2 = $this->responseData('url_2');
        if (strpos($url2, '/storage/') !== false) {
            $url2 = env('APP_URL').$url2;
        }

        $data = [
            'id' => isset($this->id) ? $this->id : false,
            'name' => $this->responseData('name'),
            'sub_name' => $this->responseData('sub_name'),
            'slug' => $this->responseData('slug'),

            'redirection' => $redirection,

            'label' => $this->responseData('label'),
            'url' => $url,
            'target' => $this->responseData('target'),

            'label1' => $this->responseData('label_1'),
            'url1' => $url1,
            'url1_target' => $this->responseData('target_1'),

            'label2' => $this->responseData('label_2'),
            'url2' => $url2,
            'url2_target' => $this->responseData('target_2'),

            'parent' => isset($this->parent) ? $this->parent : false,
            'overview' => (! empty($this->translations[($this->lang - 1)]->overview)) ? $this->translations[($this->lang - 1)]->overview : '',
            'description' => $this->shortCode(description((! empty($this->translations[($this->lang - 1)]->description)) ? $this->translations[($this->lang - 1)]->description : '')),
            'info' => description((! empty($this->translations[($this->lang - 1)]->info)) ? $this->translations[($this->lang - 1)]->info : ''),
            'meta' => [
                'title' => $this->meta('title'),
                'description' => $this->meta('description'),
                'canonical' => \App\Helper\Helper::_setting_code('web_url'),
                'image' => (isset($this->image)) ? (($this->image == 'default.jpg') ? asset('assets/img/default.jpg') : env('APP_URL').$this->image) : false,
                'meta' => [
                    'charset' => 'utf-8',
                    'name' => [
                        'keywords' => $this->meta('keywords'),
                    ],
                ],
                'og:title' => $this->meta('title'),
                'og:description' => $this->meta('description'),
                'og:type' => 'website',
                'og:url' => \App\Helper\Helper::_setting_code('web_url'),
                'og:image' => (isset($this->image)) ? (($this->image == 'default.jpg') ? asset('assets/img/default.jpg') : env('APP_URL').$this->image) : false,
                'og:image:alt' => asset('assets/img/default.jpg'),
                'twitter:card' => 'summary',
                'twitter:site' => \App\Helper\Helper::_setting_code('name_company'),
                'twitter:title' => $this->meta('title'),
                'twitter:description' => $this->meta('description'),
                'twitter:image' => (isset($this->image)) ? (($this->image == 'default.jpg') ? asset('assets/img/default.jpg') : env('APP_URL').$this->image) : false,
            ],
            'translation' => $this->array_lang,
            'language' => new LanguageResource(DB::table('web_languages')->where('id', $this->lang)->first()),
            'custom' => (! empty($this->custom)) ? $this->custom : false,
            'video' => (! empty($this->video)) ? env('APP_URL').$this->video : false,
            'attachment' => (isset($this->attachment)) ? (($this->attachment == 'default.pdf') ? asset('assets/img/default.pdf') : env('APP_URL').$this->attachment) : false,
            'image' => [
                'default' => (isset($this->image)) ? (($this->image == 'default.jpg') ? asset('assets/img/default.jpg') : env('APP_URL').$this->image) : false,
                'thumbnail' => (isset($this->image_lg)) ? (($this->image_lg == 'default.jpg') ? asset('assets/img/default.jpg') : env('APP_URL').$this->image_lg) : false,
                'tablet' => (isset($this->image_md)) ? (($this->image_md == 'default.jpg') ? asset('assets/img/default.jpg') : env('APP_URL').$this->image_md) : false,
                'mobile' => (isset($this->image_sm)) ? (($this->image_sm == 'default.jpg') ? asset('assets/img/default.jpg') : env('APP_URL').$this->image_sm) : false,
                'apps' => (isset($this->image_xs)) ? (($this->image_xs == 'default.jpg') ? asset('assets/img/default.jpg') : env('APP_URL').$this->image_xs) : false,
            ],
            'author' => ((User::find((isset($this->admin_id) ? $this->admin_id : 0)) !== null) ? (User::find((isset($this->admin_id) ? $this->admin_id : 0)))->name : false),
            'visibility' => isset($this->visibility) ? $this->visibility : false,
            'sort' => isset($this->sort) ? $this->sort : false,
            'status' => isset($this->status) ? $this->status : false,
            'publish_date' => ! empty($this->publish_at) ? Carbon::parse($this->publish_at)->format('d M Y') : Carbon::parse($this->created_at)->format('d M Y'),
            'template' => ($this->get_model == 'page') ? (isset(config('cms.visibility.page.slug')[$this->visibility]) ? config('cms.visibility.page.slug')[$this->visibility] : 'page') : (isset(config('cms.visibility.post.slug')[$this->visibility]) ? config('cms.visibility.post.slug')[isset($this->visibility) ? $this->visibility : 0] : 'page'),
        ];

        if (isset($this->menu)) {
            $data['menu'] = $this->menu;
        }
        if (isset($this->parent)) {
            $data['parent'] = $this->parent;
        }

        return $data;
    }

    private function meta($string)
    {
        if ($string == 'title') {
            return (! empty($this->translations[($this->lang - 1)]->meta_title)) ? $this->translations[($this->lang - 1)]->meta_title : (isset($this->translations[($this->lang - 1)]->name) ? $this->translations[($this->lang - 1)]->name : false);
        } elseif ($string == 'description') {
            return (! empty($this->translations[($this->lang - 1)]->meta_description)) ? $this->translations[($this->lang - 1)]->meta_description : ((isset($this->translations[($this->lang - 1)]->description)) ? strip_tags($this->translations[($this->lang - 1)]->description) : false);
        } elseif ($string == 'keywords') {
            return (! empty($this->translations[($this->lang - 1)]->meta_keyword)) ? $this->translations[($this->lang - 1)]->meta_keyword : (isset($this->translations[($this->lang - 1)]->name) ? $this->translations[($this->lang - 1)]->name : false);
        }

        return false;
    }

    private function responseData($string)
    {
        return (! empty($this->translations[($this->lang - 1)]->$string)) ? $this->translations[($this->lang - 1)]->$string : false;
    }

    private function shortCode($text)
    {
        $zpreg = preg_match_all('#\[\[([a-zA-Z0-9]+)\]\]#', $text, $matches);
        if ($zpreg > 0) {
            $newtext = preg_replace_callback(
                '#\[\[([a-zA-Z0-9]+)\]\]#',
                function ($matches) {
                    return $this->getText($matches[1]);
                },
                $text
            );

            return $newtext;
        }

        return $text;
    }

    private function getText($id)
    {
        return $id;
    }
}
