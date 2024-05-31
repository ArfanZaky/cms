<?php

namespace App\Traits;

trait HasGlobalQueryTrait
{
    public function createTranslations($data, $request)
    {
        foreach ($request->name as $key => $value) {
            $arr = [
                'language_id' => $key + 1,
                'name' => $value,
                'sub_name' => $request->sub_name[$key] ?? '',
                'slug' => $request->slug[$key] ?? '',
                'description' => $request->description[$key] ?? '',
                'overview' => $request->overview[$key] ?? '',
                'info' => $request->info[$key] ?? '',
                'meta_title' => $request->meta_title[$key] ?? '',
                'meta_description' => $request->meta_description[$key] ?? '',
                'meta_keyword' => $request->meta_keyword[$key] ?? '',
            ];

            if ($request->has('url_1')) {
                $arr['url_1'] = str_replace(env('FRONTEND_APP_URL'), '', _isExist($request->url_1, $key));
            }

            if ($request->has('label_1')) {
                $arr['label_1'] = _isExist($request->label_1, $key);
            }

            if ($request->has('target_1')) {
                $arr['target_1'] = _isExist($request->target_1, $key);
            }

            if ($request->has('url_2')) {
                $arr['url_2'] = str_replace(env('FRONTEND_APP_URL'), '', _isExist($request->url_2, $key));
            }

            if ($request->has('label_2')) {
                $arr['label_2'] = _isExist($request->label_2, $key);
            }

            if ($request->has('target_2')) {
                $arr['target_2'] = _isExist($request->target_2, $key);
            }

            if ($request->has('redirection')) {
                $arr['redirection'] = str_replace(env('FRONTEND_APP_URL'), '', _isExist($request->redirection, $key));
            }

            if ($request->has('label')) {
                $arr['label'] = _isExist($request->label, $key);
            }

            if ($request->has('target')) {
                $arr['target'] = _isExist($request->target, $key);
            }

            $data->translations()->create($arr);
        }
    }

    public function UpdateTranslations($data, $request)
    {
        foreach ($request->name as $key => $value) {
            $translation = $data->translations()->where('language_id', $key + 1)->first();
            if ($translation) {
                $translation->name = $value;
                $translation->sub_name = $request->sub_name[$key] ?? '';
                $translation->slug = $request->slug[$key] ?? '';
                $translation->description = $request->description[$key] ?? '';
                $translation->overview = $request->overview[$key] ?? '';
                $translation->info = $request->info[$key] ?? '';
                $translation->meta_title = $request->meta_title[$key] ?? '';
                $translation->meta_description = $request->meta_description[$key] ?? '';
                $translation->meta_keyword = $request->meta_keyword[$key] ?? '';
                if ($request->has('url_1')) {
                    $translation->url_1 = str_replace(env('FRONTEND_APP_URL'), '', $request->url_1[$key] ?? '');
                }

                if ($request->has('label_1')) {
                    $translation->label_1 = _isExist($request->label_1, $key);
                }

                if ($request->has('target_1')) {
                    $translation->target_1 = _isExist($request->target_1, $key);
                }

                if ($request->has('url_2')) {
                    $translation->url_2 = str_replace(env('FRONTEND_APP_URL'), '', $request->url_2[$key] ?? '');
                }

                if ($request->has('label_2')) {
                    $translation->label_2 = _isExist($request->label_2, $key);
                }

                if ($request->has('target_2')) {
                    $translation->target_2 = _isExist($request->target_2, $key);
                }

                if ($request->has('redirection')) {
                    $translation->redirection = str_replace(env('FRONTEND_APP_URL'), '', $request->redirection[$key] ?? '');
                }
                if ($request->has('label')) {
                    $translation->label = _isExist($request->label, $key);
                }

                if ($request->has('target')) {
                    $translation->target = _isExist($request->target, $key);
                }

                $translation->save();
            }

        }
    }

    public function UpdateBuilder($data, $request)
    {
        foreach ($request->type as $key => $value) {
            $translation = $data->translations()->where('language_id', $key + 1)->first();
            if ($translation) {
                $json = [];
                foreach ($value as $keys => $value) {
                    $json[code_lang()[$key]]['Section'][$keys]['type'] = $value;
                    $json[code_lang()[$key]]['Section'][$keys]['category_id'] = $request->category_id[$key][$keys];
                    $json[code_lang()[$key]]['Section'][$keys]['template'] = $request->template[$key][$keys];
                    $json[code_lang()[$key]]['Section'][$keys]['col'] = $request->col[$key][$keys] ?? 0;

                }
                $translation->additionals = $json;
                $translation->save();
            }

        }
    }
}
