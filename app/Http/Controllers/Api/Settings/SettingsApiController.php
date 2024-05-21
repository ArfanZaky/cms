<?php

namespace App\Http\Controllers\Api\Settings;

use App\Http\Controllers\Api\BaseController as BaseController;
use App\Models\WebSettings as WebSettingsModel;
use App\Services\LogServices;
use Illuminate\Support\Str;

class SettingsApiController extends BaseController
{
    public function __construct(LogServices $LogServices)
    {
        $this->LogServices = $LogServices;
    }

    public function index()
    {
        try {
            $settings = WebSettingsModel::all();
            $array = [];
            foreach ($settings as $setting) {
                $json = json_decode($setting->value);
                if (is_object($json)) {
                    $array[$setting->code] = $json;
                } else {
                    $array[$setting->code] = Str::contains($setting->value, '/storage/') ? ENV('APP_URL').$setting->value : $setting->value;
                }
            }

            return $this->sendResponse($array, 'Settings retrieved successfully.');
        } catch (\Exception $e) {
            $this->LogServices->handle(['table_id' => 0, 'name' => 'Log Error',   'json' => $e]);

            return $this->sendError('Something went wrong', [], 404);
        }
    }
}
