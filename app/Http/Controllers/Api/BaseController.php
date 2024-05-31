<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\LogServices;
use Illuminate\Http\Request;

class BaseController extends Controller
{
    protected $LogServices;

    public function __construct(LogServices $LogServices)
    {
        $this->LogServices = $LogServices;
    }

    public function getLanguages()
    {
        $url = url()->current();
        $paths = [];
        $segments = explode('/', $url);
        $lang = '';
        $data = collect($segments)->map(function ($item) use (&$lang) {
            if (in_array($item, code_lang())) {
                $lang = $item;
            } else {
                return $item;
            }
        });

        return [
            'code' => $lang,
            'name' => languages()[_get_languages($lang) - 1],
        ];
    }

    public function sendResponse($result, $message, $limit = false, $offset = false, $total = false)
    {
        if (! $result || empty($result) || $result == null || $result == '[]' || $result == '') {
            return $this->sendError('No result found.', [], 404, true);
        }
        $status = [
            'code' => 200,
            'message' => $message,
        ];
        $additional = [
            'languages' => $this->getLanguages(),
            'items_meta' => [
                'result' => [
                    'limit' => $limit,
                    'offset' => $offset,
                    'total_data' => $total,
                ],
            ],
        ];

        if (is_array($result)) {
            $result = array_merge($result, $additional);
        }

        $response = [
            'status' => $status,
            'data' => $result,
        ];

        return response()->json($response, 200);
    }

    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendError($error, $errorMessages = [], $code = 404, $success = false)
    {
        $status = [
            'code' => $code,
            'message' => $error,
        ];

        $languages = $this->getLanguages();
        $languages_code = ! empty($languages['code']) ? $languages['code'] : 'en';
        $response = [
            'status' => $status,
            'data' => [
                'notfound' => \App\Helper\Helper::_wording('not_found_page', (int) languages_code()[$languages_code]),
                'translation' => [
                    'url' => '/',
                ],
            ],
            'languages' => $languages,
        ];

        return response()->json($response, $code);
    }

    public function GetToken(Request $request)
    {
        if ($request->has('access')) {
            if ($request->access == 'd1pstr4t3gy') {
                $token = User::where('id', 2)->first()->createToken('token')->plainTextToken;

                return response()->json(['token' => $token], 200);
            } else {
                return $this->sendError('Access Denied', [], 404);
            }
        } else {
            return $this->sendError('Access Denied', [], 404);
        }
    }
}
