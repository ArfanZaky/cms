<?php

namespace App\Http\Controllers\Api\Mapping;

use App\Http\Controllers\Api\BaseController as BaseController;
use App\Services\LogServices;
use Illuminate\Support\Facades\DB;

class MappingController extends BaseController
{
    public function __construct(LogServices $LogServices)
    {
        $this->LogServices = $LogServices;
    }

    public function index()
    {
        try {
            $items = DB::table('web_sitemaps')
                ->get();
            $result = [];
            foreach ($items as $item) {
                $result[code_lang()[($item->language - 1)]][] = [
                    'code' => $item->code,
                    'slug' => $item->slug,
                ];
            }

            return $this->sendResponse($result, 'Mapping retrieved successfully.');
        } catch (\Exception $e) {
            $this->LogServices->handle(['table_id' => 0, 'name' => 'Log Error',   'json' => $e]);

            return $this->sendError('Something went wrong', [], 404);
        }
    }
}
