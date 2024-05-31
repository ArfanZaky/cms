<?php

namespace App\Helper;

use App\Models\WebArticles;
use App\Models\WebContent;
use App\Models\WebPages;
use App\Models\WebSettings;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Helper
{
    public static function _category_post_id($id, $language, $limit = false)
    {
        $category = WebContent::with('translations')->find($id);
        if (! $category) {
            return [];
        }
        $id = $category->id;
        $category = $category->getResponeses($category, $language);
        $category = collect($category)->toArray();

        $article = WebArticles::whereHas('categoryArticles', function ($q) use ($id) {
            $q->where('category_id', $id);
        })
            ->with('translations')
            ->orderBy('sort', 'asc')
            ->when($limit, function ($q) use ($limit) {
                $q->limit($limit);
            })
            ->get();

        if (! $article) {
            return [];
        }

        $data = collect($article)->map(function ($item) use ($language) {
            $data = $item->getResponeses($item, $language);
            $item = collect($data)->toArray();

            return $item;
        });

        $category['items'] = $data;

        return $category;
    }

    public static function _category_post_id_top_search($language, $limit = 3)
    {
        $language = _get_languages($language);
        $category = WebContent::with('translations')->orderBy('view', 'desc')->limit($limit)->get();
        $article = WebArticles::with('translations')->orderBy('view', 'desc')->limit($limit)->get();

        // merge
        $merge = collect($category)->merge($article)->sortByDesc('view')->take($limit)->values()->all();
        if (empty($merge)) {
            return [];
        }
        $category = collect($merge)->map(function ($item) use ($language) {
            $category = $item->getResponeses($item, $language);
            $category = collect($category)->toArray();

            return [
                'id' => $category['id'],
                'image' => $category['image']['default'],
                'parent' => '_self',
                'type' => 'text',
                'name' => $category['name'],
                'target' => '_self',
                'url' => $category['url'],
                'slug' => $category['slug'],
                'visibility' => $category['visibility'],
            ];
        });

        return $category;
    }

    public static function _text_chatbot($languages, $keyword, $limit = 3)
    {
        $language = _get_languages($languages);
        $db = DB::table('web_keywords')->where('language_id', $language)->get();
        $removeNonWords = ['?', '!', '.', ',', ';', ':', '"', "'", '(', ')', '[', ']', '{', '}', '-', '_', '+', '=', '/', '\\', '|', '<', '>', '@', '#', '$', '%', '^', '&', '*', '~', '`'];
        $removeBasicWord = DB::table('web_basic_word')->get()->pluck('basic_word')->map(function ($item) {
            return trim(strtolower($item));
        })->toArray();

        $merge = collect($removeNonWords)->merge($removeBasicWord)->unique()->toArray();

        $db = collect($db)->map(function ($item) use ($merge) {
            $item->final_keyword = str_replace($merge, '', $item->final_keyword);
            $item->final_keyword = strtolower($item->final_keyword);

            return $item;
        });

        $finalKeywords = collect($db)->pluck('final_keyword')->toArray();
        $finalKeywords = collect($finalKeywords)->map(function ($item) {
            $item = explode(',', $item);

            return $item;
        })->flatten()->toArray();

        $keyword = trim(strtolower(str_replace($merge, '', $keyword)));
        $questionLower = explode(' ', $keyword);
        if (count($questionLower) > 1) {
            $matchedKeywords = collect($questionLower)->map(function ($items) use ($finalKeywords) {
                $matchedKeywords = collect($finalKeywords)->filter(function ($item) use ($items) {
                    $item = trim(strtolower($item));

                    return stristr($item, $items) !== false
                                        || strpos($items, $item) !== false;
                });

                return $matchedKeywords;
            })->flatten()->toArray();
        } else {
            $matchedKeywords = collect($finalKeywords)->filter(function ($item) use ($keyword) {
                $item = trim(strtolower($item));

                return stristr($item, $keyword) !== false
                                    || strpos($keyword, $item) !== false;
            });
        }

        $matchedKeywords = collect($matchedKeywords)->sortBy(function ($item) use ($keyword) {
            return levenshtein($keyword, $item);
        })->unique()->map(function ($item) use ($db) {
            $data = collect($db)->filter(function ($items) use ($item) {
                // replace stristr with your choice of matching function
                return stristr($items->final_keyword, $item) !== false;
            })->map(function ($item) {
                $item->unique = $item->model_id.'-'.$item->model_type;

                return $item;
            })->values()->toArray();

            return $data;
        })->flatten()->unique('unique')->toArray();

        $data = collect($matchedKeywords)->map(function ($item) use ($language) {
            if ($item->model_type == 'article') {
                $data = WebArticles::with('translations')->where('id', $item->model_id)->first();
            } elseif ($item->model_type == 'category') {
                $data = WebContent::with('translations')->where('id', $item->model_id)->first();
            } elseif ($item->model_type == 'page') {
                $data = WebPages::with('translations')->where('id', $item->model_id)->first();
            }
            if (! $data) {
                return [];
            }
            $data = $data->getResponeses($data, $language);
            $item = collect($data)->toArray();

            return [
                'id' => $item['id'],
                'image' => $item['image']['default'],
                'overview' => isset($item['meta']['description']) ? $item['meta']['description'] : $item['description'],
                'parent' => '_self',
                'type' => 'text',
                'name' => $item['name'],
                'target' => '_self',
                'url' => $item['url'],
                'label' => $item['label'],
                'slug' => $item['slug'],
                'visibility' => $item['visibility'],
            ];
        });

        return collect($data)->filter(function ($item) {
            return ! empty($item);
        })->values()->toArray();
    }

    public static function _search($language, $keyword, $offset, $limit)
    {
        $language = _get_languages($language);
        $category = WebContent::with('translations')->whereHas('translations', function ($q) use ($keyword, $language) {
            $q->where('language_id', $language)->where(function ($q) use ($keyword) {
                $q->where('name', 'like', '%'.$keyword.'%')->orWhere('meta_keyword', 'like', '%'.$keyword.'%')->orWhere('description', 'like', '%'.$keyword.'%');
            });
        })->whereNotIn('visibility', [50]) // remove default
            ->get();
        $article = WebArticles::with('translations')->whereHas('translations', function ($q) use ($keyword, $language) {
            $q->where('language_id', $language)->where(function ($q) use ($keyword) {
                $q->where('name', 'like', '%'.$keyword.'%')->orWhere('meta_keyword', 'like', '%'.$keyword.'%')->orWhere('description', 'like', '%'.$keyword.'%');
            });
        })->get();

        // merge
        $merge = collect($category)->merge($article)->sortByDesc('view')->values()->all();
        $items = collect($merge)->skip($offset)->take($limit)->values()->all();
        $total = collect($merge)->count();
        if (empty($merge)) {
            return [
                'total' => $total,
                'items' => [],
            ];
        }
        $items = collect($items)->map(function ($item) use ($language) {
            $items = $item->getResponeses($item, $language);
            $items = collect($items)->toArray();
            $items['overview'] = strip_tags($items['overview']);

            return $items;
        });

        return [
            'total' => $total,
            'items' => $items,
        ];
    }

    public static function _customize_search_name($url)
    {

        $url = explode('/', $url);
        $url = collect($url)->filter(function ($item, $key) {
            return ! empty($item) && $key > 2;
        })->values()->toArray();

        if (count($url) < 2) {
            return '';
        }

        $html = '( ';
        foreach ($url as $key => $value) {
            $value = str_replace('-', ' ', $value);
            $value = preg_replace('/[0-9]+/', '', $value);
            $value = ucwords($value);
            if (count($url) - 1 == $key) {
                $html .= $value.' )';
                break;
            } else {
                $html .= $value.' / ';
            }
        }

        return $html;
    }

    public static function _setting_code($code)
    {
        $setting = WebSettings::where('code', $code)->first();

        return $setting?->value;
    }

    public static function _view_page()
    {
        return self::_setting_code('web_url').'/en';
    }

    public static function _get_logo()
    {
        $logo = self::_setting_code('web_logo');

        return $logo ? env('APP_URL').$logo : asset('assets/img/logo.svg');
    }

    public static function _wording($code, $language)
    {
        $directory = 'wording';
        $wording = Storage::disk('public')->get($directory.'/wording.json');
        $wording = json_decode($wording, true);
        $result = [];
        $languages = code_lang()[$language - 1];
        if (isset($wording[$languages][$code])) {
            return $wording[$languages][$code];
        }

        return '';
    }

    public static function _get_wording_partial($languages)
    {
        $directory = 'wording';
        $wording = Storage::disk('public')->get($directory.'/wording.json');
        $wording = json_decode($wording, true);
        $result = [];
        if (isset($wording[$languages])) {
            $result = $wording[$languages];
        }

        return $result;
    }

    public static function MasterTree($data)
    {
        $data = self::parent($data);
        $data = self::tree($data);
        $data = self::obj($data);

        return $data;
    }

    public static function parent($array, $data = ['parent_id' => 'parent', 'children' => 'children', 'id' => 'id', 'tree' => 'tree'], $parent = 0)
    {
        $tree = [];
        foreach ($array as $key => $value) {
            if ($value[$data['parent_id']] !== false) {
                $children = self::parent2($array, $data, $value[$data['parent_id']]);
                if ($children) {
                    $value[$data['tree']] = [
                        'parent' => $children,
                    ];
                }
            }
            $tree[] = $value;
        }

        return $tree;
    }

    public static function parent2($array, $data = ['parent_id' => 'parent', 'children' => 'children', 'id' => 'id', 'tree' => 'tree'], $parent = 0)
    {
        $tree = [];

        foreach ($array as $key => $value) {
            if ($value[$data['id']] == $parent) {
                if ($value[$data['parent_id']] !== false) {
                    $children = self::parent2($array, $data, $value[$data['parent_id']]);
                    if ($children) {
                        $value[$data['tree']] = [
                            'parent' => $children,
                        ];
                    }
                }
                $tree[] = $value;
            }
        }

        return $tree;
    }

    public static function tree($array, $data = ['parent_id' => 'parent', 'children' => 'children', 'id' => 'id'], $parent = 0)
    {
        $tree = [];
        foreach ($array as $key => $value) {
            if ($value[$data['parent_id']] == $parent) {
                $children = self::tree($array, $data, $value[$data['id']]);
                if ($children) {
                    $value[$data['children']] = $children;
                }

                $tree[] = $value;
            }
        }

        return $tree;
    }

    public static function obj($data)
    {
        if (empty($data)) {
            return false;
        }

        return is_array($data) ? (object) array_map([__CLASS__, __METHOD__], $data) : $data;
    }

    public static function getLogName($row)
    {
        $name = $row->name;
        $data = [];
        if (Str::contains($name, 'login')) {
            return 'Login';
        } elseif (Str::contains($name, 'logout')) {
            return 'Logout';
        } elseif (Str::contains($name, 'permission')) {
            $data = DB::table('user_permissions')->where('id', $row->table_id)->first();
        } elseif (Str::contains($name, 'Menu')) {
            $data = DB::table('web_menu_translations')->where('menu_id', $row->table_id)->first();
        } elseif (Str::contains($name, 'Settings')) {
            $data = DB::table('web_settings')->where('id', $row->table_id)->first();
        } elseif (Str::contains($name, 'Page')) {
            $data = DB::table('web_page_translations')->where('page_id', $row->table_id)->first();
        } elseif (Str::contains($name, 'Category Article')) {
            $data = DB::table('web_content_translations')->where('category_id', $row->table_id)->first();
        } elseif (Str::contains($name, 'Article')) {
            $data = DB::table('web_article_translations')->where('article_id', $row->table_id)->first();
        } elseif (Str::contains($name, 'Wording')) {
            $data = DB::table('web_wordings')->where('id', $row->table_id)->first();
        } elseif (Str::contains($name, 'Update Form Application')) {
            $data = DB::table('web_contacts')->where('id', $row->table_id)->first();
        }

        if (! empty($data)) {
            if (Str::contains($name, 'Wording')) {
                return $data->code;
            }
            if (Str::contains($name, 'Update Form Application')) {
                return $data->full_name;
            }

            return isset($data->name) ? $data->name : '-';
        } else {
            return '-';
        }
    }

    public static function _post_type_breadcrumbs($route, $data, $post, $array = [])
    {
        $array[] = ['url' => route($route, ['parent' => $post?->id, 'component' => request()->get('component')]), 'name' => $post?->translations?->first()?->name];
        if ($post?->parent != 0) {
            $post = $data->where('id', $post->parent)->first();
            $result = self::_post_type_breadcrumbs($route, $data, $post, $array);
        } else {
            $result = array_reverse($array);
            $result[count($result) - 1]['url'] = 'javascript:void(0);';
        }

        return $result;
    }

    public static function _category_slug_map_loop($data, $post, $array = [])
    {

        $array[] = ['slug' => $post?->translations?->first()?->slug, 'name' => $post?->translations?->first()?->name];
        if ($post?->parent != 0) {
            $post = $data->where('id', $post->parent)->first();
            $result = self::_category_slug_map_loop($data, $post, $array);
        } else {
            $result = array_reverse($array);
        }

        return $result;
    }

    public static function _category_slug_map($post, $lang)
    {

        $get_model = get_class($post);

        $id = $post->id;
        if ($get_model == 'App\Models\WebArticles') {
            $temp = $post;
            $id = $post?->categoryArticles()?->first()?->id;
        }

        $category = WebContent::with([
            'translations' => function ($q) use ($lang) {
                $q->where('language_id', $lang);
            },
        ])
            ->orderBy('visibility', 'desc')
            ->orderBy('sort', 'asc')
            ->get();

        $result = self::_category_slug_map_loop($category, $category->where('id', $id)->first());

        if ($get_model == 'App\Models\WebArticles') {
            $result[] = [
                'slug' => $temp->translations?->where('language_id', $lang)->first()?->slug,
                'name' => $temp->translations?->where('language_id', $lang)->first()?->name,
            ];
        }

        return $result;
    }
}
