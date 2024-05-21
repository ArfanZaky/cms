<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $languages = [
            [
                'name' => 'English',
                'code' => 'en',
                'value' => 'en_US',
            ],
            [
                'name' => 'Bahasa Indonesia',
                'code' => 'id',
                'value' => 'id_ID',
            ],

            [
                'name' => '中文',
                'code' => 'zh',
                'value' => 'zh_CN',
            ],
            [
                'name' => '日本語',
                'code' => 'ja',
                'value' => 'ja_JP',
            ],
            [
                'name' => '한국어',
                'code' => 'ko',
                'value' => 'ko_KR',
            ],
            [
                'name' => 'العربية',
                'code' => 'ar',
                'value' => 'ar_SA',
            ],
            [
                'name' => 'Español',
                'code' => 'es',
                'value' => 'es_ES',
            ],
            [
                'name' => 'Français',
                'code' => 'fr',
                'value' => 'fr_FR',
            ],
        ];

        DB::table('web_languages')->insert($languages);
    }
}
