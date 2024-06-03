<?php

namespace App\Services;

use App\Models\WebContent;
use App\Models\WebMenu;
use App\Models\WebPages;
use Illuminate\Support\Str;

class ApiService
{
    public function toArray($data, $type = false, $lang = 'id')
    {
        $data_tree = [];
        foreach ($data as $key => $value) {
            if ($type == true) {
                if ($value->menu_id == 0) {
                    $url = $this->menu($value);
                    $target = $value->target;
                } else {
                    $temp = WebMenu::where('id', $value->menu_id)->with(['translations' => function ($query) use ($value) {
                        $query->where('language_id', $value->translations[0]->language_id);
                    }])->first();
                    $url = $this->menu($temp);
                    $target = $temp->target;
                }

                if (strpos($url, env('APP_URL')) !== false) {
                    $url = str_replace(env('APP_URL'), '/', $url);
                    $url = str_replace('/#', '#', $url);
                }
            }
            if ($url != '#' && $url != '/' && $url != 'javascript:void(0);' && strpos($url, 'tel') === false && strpos($url, 'mailto') === false && strpos($url, '#') === false) {
                $url = (isset($url)) ? $url : $value->translations[0]->slug;
                if (! preg_match('/http(s)?:\/\//', $url)) {
                    $url = (isset($lang)) ? '/'.$lang.'/'.$url : $url;
                    if (isset($value->parameter)) {
                        $url = $url.$value->parameter;
                    }
                }
            }

            if ($url == '#' || $url == '/' || $url == 'javascript:void(0);' || strpos($url, 'tel') !== false || strpos($url, 'mailto') !== false || strpos($url, '#') !== false) {
                $slug = $url;
            } else {
                if ($value->menu_id == 0) {
                    $slug = $value?->translations?->first()?->slug;
                } else {
                    $slug = $temp?->translations?->first()?->slug;
                }

            }

            $data_tree[] = [
                'id' => $value->id,
                'parent' => ($value->parent == 0) ? false : $value->parent,
                'image' => $this->getImage($value, 'image'),
                'image_sm' => $this->getImage($value, 'image_sm'),
                'type' => ($value->image == 'default.jpg') ? 'text' : (Str::contains($value->visibility, [6, 8]) ? 'icon' : 'image'),
                'name' => $value->translations[0]->name,
                'target' => ($value->target == 0) ? '_self' : '_blank',
                'url' => $url,
                'slug' => $slug,
                'visibility' => Str::slug(config('cms.visibility.menu')[$value->visibility], '_'),
            ];
        }

        return $data_tree;
    }

    private function getImage($value, $type)
    {
        if ($type == 'image') {
            return $value->translations[0]->image_lang != 'default.jpg' ? (env('APP_URL').$value->translations[0]->image_lang)
                    : (($value->image == 'default.jpg') ? asset('assets/img/default.jpg') : (env('APP_URL').$value->image));
        } elseif ($type == 'image_sm') {
            return $value->translations[0]->image_sm_lang != 'default.jpg' ? (env('APP_URL').$value->translations[0]->image_sm_lang)
                    : (($value->image_sm == 'default.jpg') ? asset('assets/img/default.jpg') : (env('APP_URL').$value->image_sm));
        }

        return asset('assets/img/default.jpg');
    }

    public function getvisibility($value)
    {
        $page = WebPages::where('menu_id', $value->id)->first();
        if (! $page) {
            return false;
        }

        return $page->visibility;
    }

    private function menu($value)
    {
        // pages
        $url = '#';
        if ($value->url == '#' && $value->content_id == 0 && $value->catalog_id == 0 && $value->gallery_id == 0) {
            $page = WebPages::where('menu_id', $value->id)->with(['translations' => function ($query) use ($value) {
                $query->where('language_id', $value->translations[0]->language_id);
            }])->first();
            if ($page) {
                $url = ($page->translations[0]->slug) ? 'page/'.$page->translations[0]->slug : 'javascript:void(0);';
            }

            // url link
        } elseif ($value->url != '#' && $value->content_id == 0 && $value->catalog_id == 0 && $value->gallery_id == 0) {
            if ($value->url == '/' || $value->url == 'javascript:void(0);' || strpos($value->url, 'tel') !== false || strpos($value->url, 'mailto') !== false || strpos($value->url, '#') !== false) {
                $url = $value->url;
            } else {
                $url = (preg_match('/http(s)?:\/\//', $value->url)) ? $value->url : env('APP_URL').ltrim($value->url, '/');
            }

            // article content
        } elseif ($value->url == '#' && $value->content_id != 0 && $value->catalog_id == 0 && $value->gallery_id == 0) {
            $content = WebContent::where('id', $value->content_id)->with(['translations' => function ($query) use ($value) {
                $query->where('language_id', $value->translations[0]->language_id);
            }])->first();
            if ($content) {
                $url = false;
                $slug = $content->translations['0']->slug;

                $url = (isset($slug)) ? $slug : 'javascript:void(0);';
            }
        }

        return $url;
    }
}
