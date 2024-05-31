<?php

return [

    // note
    // savings = product list
    // individual-current = product detail
    // star-home-loan = product detail with tab

    'prefix' => env('APP_CMS', 'cms'),

    'visibility' => [
        'menu' => [
            '0' => 'Header Menu',
            '1' => 'Footer Menu',
            '2' => 'Footer Description',
            '3' => 'Footer Address',
        ],

        'post' => [
            'category' => [
                'Template' => [
                    '0' => 'tab',
                    '8' => 'tab-board',
                    '1' => 'news-events',
                    '2' => 'news',
                    '3' => 'events',
                    '4' => 'contact-us',
                    '5' => 'banner',
                    '7' => 'Sustainability',
                    '6' => 'home',
                ],
                'Default' => [
                    '6' => 'home',
                    '50' => 'Default',
                ],
            ],

            'article' => [
                '0' => 'detail-news',
                '1' => 'detail-news',
                '2' => 'detail-gallery',
            ],

            'slug' => [
                '0' => 'tab',
                '1' => 'news-events',
                '2' => 'news',
                '3' => 'events',
                '4' => 'contact-us',
                '5' => 'banner',
                '6' => 'home',
                '7' => 'Sustainability',
                '8' => 'tab-board',
                '50' => 'default',
            ],

            'item' => [
                'default' => [
                    '1' => 'Description',
                    '2' => 'Description Gallery',
                ],
            ],
        ],

    ],
];
