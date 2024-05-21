<?php

use App\Helper\Helper;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Token
Route::post('/token', [App\Http\Controllers\Api\BaseController::class, 'GetToken']);
Route::get('/get-aes-encrypt', function (Request $request) {
    $body = $request->getContent();
    $decryptedBody = encryptAES($body);

    return $decryptedBody;
});

Route::get('/get-aes-decrypt', function (Request $request) {
    $body = $request->getContent();
    $decryptedBody = decryptAES($body);
    $decryptedBody = json_decode($decryptedBody, true);

    return $decryptedBody;
});
// group middleware
Route::group(['middleware' => ['auth:sanctum','decrypted', 'encrypted']], function () {

    Route::get('/check', function (Request $request) {
        return response()->json([
            'code' => 200,
            'message' => 'Success',
            'data' => 'Token is valid',
        ], 200);
    });

    // Contact
    Route::post('/{lang}/contact', [App\Http\Controllers\Api\Contact\ContactApiController::class, 'store'])->middleware('doNotCacheResponse');

    // Search
    Route::get('/{lang}/search', [App\Http\Controllers\Api\Search\SearchApiController::class, 'search'])->middleware('cacheResponse:31536000');

    // global
    Route::get('/settings', [App\Http\Controllers\Api\Settings\SettingsApiController::class, 'index'])->middleware('cacheResponse:31536000');
    
    Route::get('/seo/{lang}/home', [App\Http\Controllers\Api\Global\GlobalController::class, 'home_seo'])->middleware('cacheResponse:31536000');
    Route::get('/{lang}/home', [App\Http\Controllers\Api\Global\GlobalController::class, 'home'])->middleware('cacheResponse:31536000');
    Route::get('/{lang}/navigasi', [App\Http\Controllers\Api\Global\GlobalController::class, 'navigasi'])->middleware('cacheResponse:31536000');
    Route::get('/{lang}/wordings', [App\Http\Controllers\Api\Global\GlobalController::class, 'wording'])->middleware('cacheResponse:31536000');

    // seo
    Route::get('/seo/{lang}/{slug?}', [App\Http\Controllers\Api\Global\GlobalController::class, 'seo'])->where('slug', '(.*)')->middleware('cacheResponse:31536000');

    // mapping
    Route::get('/{lang}/{slug?}', [App\Http\Controllers\Api\Global\GlobalController::class, 'mapping'])->where('slug', '(.*)')->middleware('doNotCacheResponse');
    

    // page
    Route::get('/{lang}/page/data/{slug}', [App\Http\Controllers\Api\Page\PageApiController::class, 'page_filter_by_slug'])->middleware('cacheResponse:31536000');

    // category
    Route::get('/{lang}/category/by_slug/{slug}', [App\Http\Controllers\Api\Category\CategoryApiController::class, 'filter_by_article_slug'])->middleware('cacheResponse:31536000');

    // article
    Route::get('/{lang}/article/slug/{slug}', [App\Http\Controllers\Api\Article\ArticleApiController::class, 'filter_by_slug'])->middleware('cacheResponse:31536000');

    Route::any('{path}', function () {
        $status = [
            'code' => 404,
        ];

        $response = [
            'status' => $status,
            'data' => [
                'notfound' => Helper::_wording('not_found_page', 1),
                'translation' => [
                    'url' => '/',
                ],
            ],
        ];

        return response()->json($response, 404);
    })->where('path', '.*');

});
