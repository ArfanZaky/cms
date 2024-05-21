<?php

namespace Database\Seeders;

use App\Models\WebSettings;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'name' => 'Web Company Name',
                'slug' => 'web_company',
                'value' => '',
            ],
            [
                'name' => 'Web Email',
                'slug' => 'web_email',
                'value' => '',
            ],
            [
                'name' => 'Web Phone',
                'slug' => 'web_phone',
                'value' => '',
            ],
            [
                'name' => 'Web Customer',
                'slug' => 'web_customer',
                'value' => '',
            ],
            [
                'name' => 'Web Marquee',
                'slug' => 'web_marquee',
                'value' => '',
            ],
            [
                'name' => 'Web Report',
                'slug' => 'web_report',
                'value' => '',
            ],
            [
                'name' => 'Web URL',
                'slug' => 'web_url',
                'value' => '',
            ],
            [
                'name' => 'Web Title',
                'slug' => 'web_title',
                'value' => '',
            ],
            [
                'name' => 'Web Keyword',
                'slug' => 'web_keyword',
                'value' => '',
            ],
            [
                'name' => 'Web Description',
                'slug' => 'web_description',
                'value' => '',
            ],
            [
                'name' => 'Web Script',
                'slug' => 'web_script',
                'value' => '',
            ],
            [
                'name' => 'Web Schema',
                'slug' => 'web_schema',
                'value' => '',
            ],
            [
                'name' => 'Web No Script',
                'slug' => 'web_noscript',
                'value' => '',
            ],
            [
                'name' => 'Web Robot',
                'slug' => 'web_robot',
                'value' => '',
            ],
            [
                'name' => 'Web Social',
                'slug' => 'web_social',
                'value' => '',
            ],
            [
                'name' => 'Email Contact',
                'slug' => 'email_contact',
                'value' => '',
            ],
            [
                'name' => 'Email Demo',
                'slug' => 'email_demo',
                'value' => '',
            ],
            [
                'name' => 'Email Event',
                'slug' => 'email_event',
                'value' => '',
            ],
            [
                'name' => 'Offline Status',
                'slug' => 'offline_status',
                'value' => '',
            ],
            [
                'name' => 'Offline Message',
                'slug' => 'offline_message',
                'value' => '',
            ],
            [
                'name' => 'Whitelist Frontend',
                'slug' => 'whitelist_frontend',
                'value' => '',
            ],
            [
                'name' => 'Whitelist Backend',
                'slug' => 'whitelist_backend',
                'value' => '',
            ],
            [
                'name' => 'IP Frontend',
                'slug' => 'ip_frontend',
                'value' => '',
            ],
            [
                'name' => 'IP Backend',
                'slug' => 'ip_backend',
                'value' => '',
            ],
            [
                'name' => 'Web Office',
                'slug' => 'web_office',
                'value' => '',
            ],
            [
                'name' => 'Web Address',
                'slug' => 'web_address',
                'value' => '',
            ],
            [
                'name' => 'Web Coordinate',
                'slug' => 'web_coordinate',
                'value' => '',
            ],
            [
                'name' => 'Web Logo',
                'slug' => 'web_logo',
                'value' => '',
            ],
            [
                'name' => 'Web Caption',
                'slug' => 'web_caption',
                'value' => '',
            ],
            [
                'name' => 'Web Favicon',
                'slug' => 'web_favicon',
                'value' => '',
            ],
            [
                'name' => 'Web Fax',
                'slug' => 'web_fax',
                'value' => '',
            ],
            [
                'name' => 'Name Company',
                'slug' => 'name_company',
                'value' => '',
            ],
            [
                'name' => 'Web Contact',
                'slug' => 'web_contact',
                'value' => '',
            ],
            [
                'name' => 'Web Market',
                'slug' => 'web_market',
                'value' => '',
            ],
            [
                'name' => 'Web Manatal',
                'slug' => 'web_manatal',
                'value' => '',
            ],
            [
                'name' => 'Web Captcha Site Key',
                'slug' => 'web_captcha_site_key',
                'value' => '',
            ],
            [
                'name' => 'Web Captcha Secret Key',
                'slug' => 'web_captcha_secret_key',
                'value' => '',
            ],
            [
                'name' => 'Web Image',
                'slug' => 'web_image',
                'value' => '',
            ],
            [
                'name' => 'Web Seo',
                'slug' => 'web_seo',
                'value' => '',
            ],
            [
                'name' => 'Web Whatsapp Number',
                'slug' => 'web_whatsapp_number',
                'value' => '',
            ],
        ];

        collect($data)->each(function ($item) {
            WebSettings::create([
                'name' => $item['name'],
                'code' => $item['slug'],
                'value' => $item['value'],
            ]);
        });
    }
}
