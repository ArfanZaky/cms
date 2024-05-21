<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// php artisan vendor:publish --tag="responsecache-config"

Route::get('/', function () {
    if (Auth::check()) {
        Artisan::call('optimize:clear');

        return redirect()->route('dashboard');
    }

    return view('auth.login');
})->name('engine');


Route::get('/GetAESEncrypt', function (Request $request) {
    $body = $request->getContent();
    $decryptedBody = encryptAES($body);

    return $decryptedBody;
});

Route::get('/GetAESDecrypt', function (Request $request) {
    $body = $request->getContent();
    $decryptedBody = decryptAES($body);
    $decryptedBody = json_decode($decryptedBody, true);
    return $decryptedBody;
});


// file manager plugins
Route::group(['prefix' => 'laravel-filemanager', 'middleware' => ['web', 'auth']], function () {
    \UniSharp\LaravelFilemanager\Lfm::routes();
});
