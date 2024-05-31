<?php

namespace App\Http\Controllers\Engine\Settings;

use App\Http\Controllers\Controller;
use App\Models\WebSettings;
use App\Services\LogServices;
use App\Helper\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WebSettingsController extends Controller
{
    public function __construct(LogServices $LogServices, Request $request)
    {
        $this->LogServices = $LogServices;
        $this->request = $request;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $settings = WebSettings::all();

        // return $settings;
        return view('engine.module.settings.index', compact('settings'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(WebSettings $webSettings)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(WebSettings $webSettings)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, WebSettings $webSettings)
    {
        // return response()->json(['status' => 'error', 'message' => $request->all()]);
        DB::beginTransaction();
        try {
            $this->updateSettings('web_company');
            $this->updateSettings('web_contact');
            $this->updateSettings('name_company');
            $this->updateSettings('web_email', true);
            $this->updateSettings('web_whatsapp_me');
            $this->updateSettings('web_phone');
            $this->updateSettings('web_whatsapp_number');
            $this->updateSettings('web_fax');
            $this->updateSettings('web_customer');
            $this->updateSettings('web_marquee');
            $this->updateSettings('web_report');
            $this->updateSettings('web_contact');
            $this->updateSettings('web_favicon');
            $this->updateSettings('web_logo');
            $this->updateSettings('web_manatal');
            $this->updateSettings('web_captcha_site_key');
            $this->updateSettings('web_captcha_secret_key');
            $this->updateSettings('web_url');
            $this->updateSettingsSeo();
            $this->updateSettings('web_title');
            $this->updateSettings('web_keyword');
            $this->updateSettings('web_description');
            $this->updateSettings('web_image');
            $this->updateSettings('web_icon_user_female');
            $this->updateSettings('web_icon_user_male');
            $this->updateSettings('web_icon_admin_chatbot');
            $this->updateSettings('web_icon_chatbot');
            $this->updateSettings('web_script');
            $this->updateSettings('web_schema');
            $this->updateSettings('web_noscript');
            $this->updateSettings('web_robot', true);
            $this->updateSettings('web_social');
            $this->updateSettings('web_market');
            $this->updateSettings('email_contact');
            $this->updateSettings('email_demo');
            $this->updateSettings('email_event');
            $this->updateSettings('offline_status');
            $this->updateSettings('offline_message');
            $this->updateSettings('whitelist_frontend');
            $this->updateSettings('whitelist_backend');
            $this->updateSettings('ip_frontend');
            $this->updateSettings('ip_backend');
            $this->updateSettings('web_office');
            $this->updateSettings('web_address');
            $this->updateSettings('web_coordinate');

            $this->LogServices->handle(['table_id' => 0, 'name' => 'Update Settings',   'json' => '']);
            DB::commit();

            return response()->json(['status' => 'success', 'message' => 'Update Success']);
        } catch (\Exception $e) {
            DB::rollback();
            $this->LogServices->handle(['table_id' => 0, 'name' => 'Log Error',   'json' => $e]);

            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function updateSettingsSeo()
    {
        $title = request()->web_title;
        $title = $title.' - '.env('APP_NAME');
        $description = request()->web_description;
        $keyword = request()->web_keyword;
        $image = request()->web_image;
        $url = request()->web_url;

        $value = [
            'title' => $title,
            'description' => $description,
            'canonical' => env('APP_URL').$image,
            'meta' => [
                'charset' => 'utf-8',
                'name' => [
                    'keywords' => $keyword,
                ],
            ],
            'meta' => [
                'property' => [
                    'og:title' => $title,
                    'og:description' => $description,
                    'og:type' => 'website',
                    'og:url' => $url,
                    'og:image' => env('APP_URL').$image,
                    'og:image:alt' => env('APP_URL').$image,
                    'twitter:card' => 'summary',
                    'twitter:site' => \App\Helper\Helper::_setting_code('name_company'),
                    'twitter:title' => $title,
                    'twitter:description' => $description,
                    'twitter:image' => env('APP_URL').$image,
                ],
            ],
        ];

        $value = json_encode($value);

        $data = WebSettings::where('code', 'web_seo')->first();
        $data->value = $value;
        $data->save();

    }

    public function updateSettings($code, $nl2br = false)
    {
        $value = request()->$code;

        if (is_array($value)) {
            $array = array_map('array_filter', $value);
            $array = array_filter($array);

            if (empty($array)) {
                $value = '';
            } else {
                $value = [];

                foreach ($array['name'] as $k => $v) {
                    $value[$v] = [
                        'value' => $array['value'][$k],
                        'image' => $array['icon'][$k],
                    ];
                }

                $value = json_encode($value);
            }
        } else {
            $value = isset($value) ? trim($value) : $value;
            $value = isset($value) ? (string) stripslashes($value) : $value;
        }

        $data = WebSettings::where('code', $code)->first();
        if ($value != null) {
            $data->value = ($nl2br) ? isset($value) ? nl2br($value) : $value : $value;
            $data->save();
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(WebSettings $webSettings)
    {
        //
    }
}
