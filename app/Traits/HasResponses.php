<?php

namespace App\Traits;

use App\Http\Resources\ApiResource;
use Helper;
use Illuminate\Database\Eloquent\Builder;

trait HasResponses
{
    public function getResponeses($data, $language)
    {

        $get_model = false;
        try {
            $get_model = get_class($data);
            if ($get_model == 'App\Models\WebPages') {
                $get_model = 'page';
            } else {
                $get_model = 'category';
            }
        } catch (\Throwable $th) {
        }
        $lang = code_lang()[($language - 1)];

        $url = false;
        if ($get_model == 'category') {
            $slug = $data->translations->where('language_id', $language)->first()->slug;
            $url = '/'.$lang.'/'.$slug;
        }

        $array_lang = $data?->translations->filter(function ($item) use ($language) {
            return $item->language_id != $language;
        })->mapWithKeys(function ($item, $key) use ($get_model, $data) {
            $lang = code_lang()[($item->language_id - 1)];

            $slug = $item->slug;

            return [
                'id' => $item['id'],
                'name' => $item['name'],
                'slug' => $item['slug'],
                'code' => $lang,
                'url' => '/'.$lang.'/'.$slug,
                'template' => ($get_model == 'page') ? (isset(config('cms.visibility.page.slug')[$data->visibility]) ? config('cms.visibility.page.slug')[$data->visibility] : 'page') : (isset(config('cms.visibility.post.slug')[$data->visibility]) ? config('cms.visibility.post.slug')[$data->visibility] : 'page'),
            ];
        })->toArray();

        $category_data = new ApiResource($data, $language, $array_lang, $get_model, $url);

        return $category_data;
    }

    public function scopeWhereSlug($query, $slug, $language)
    {
        return $query->whereHas('translations', function (Builder $query) use ($slug, $language) {
            $query->where('slug', $slug)->where('language_id', $language);
        });
    }

    public function scopeWhereNotSlug($query, $slug, $language)
    {
        return $query->whereHas('translations', function (Builder $query) use ($slug, $language) {
            $query->where('slug', '!=', $slug)->where('language_id', $language);
        });
    }
}
