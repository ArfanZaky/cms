<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

Route::middleware('guest')->group(function () {
    // Route::get('register', [RegisteredUserController::class, 'create'])->name('register');

    // Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    // Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');

    // Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');

    // Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');

    // Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.update');
});

Route::middleware('auth')->group(function () {
    Route::get('verify-email', [EmailVerificationPromptController::class, '__invoke'])->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])->middleware(['signed', 'throttle:6,1'])->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])->middleware('throttle:6,1')->name('verification.send');

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/storage-link', function () {
        Artisan::call('storage:link');
    });
    
    Route::get('dahsboard', [App\Http\Controllers\HomeController::class, 'index'])->name('dashboard');
    Route::get('apilogs', [App\Http\Controllers\HomeController::class, 'Apilogs'])->name('Apilogs');
    Route::get('sort', [App\Http\Controllers\HomeController::class, 'Apilogs'])->name('Apilogs');
    Route::post('sortable', [App\Http\Controllers\HomeController::class, 'sortable'])->name('sortable');

    // role
    Route::prefix('user')->group(function () {
        Route::get('/', [App\Http\Controllers\Engine\Users\UsersController::class, 'index'])->name('user')->middleware('permission:user');
        Route::get('/create', [App\Http\Controllers\Engine\Users\UsersController::class, 'create'])->name('user.create')->middleware('permission:user/create');
        Route::post('/store', [App\Http\Controllers\Engine\Users\UsersController::class, 'store'])->name('user.store')->middleware('permission:user/create');
        Route::get('/edit/{id}', [App\Http\Controllers\Engine\Users\UsersController::class, 'edit'])->name('user.edit')->middleware('permission:user/edit');
        Route::post('/update/{id}', [App\Http\Controllers\Engine\Users\UsersController::class, 'update'])->name('user.update')->middleware('permission:user/edit');
        Route::get('/delete/{id}', [App\Http\Controllers\Engine\Users\UsersController::class, 'destroy'])->name('user.delete')->middleware('permission:user/delete');
    });

    // role
    Route::prefix('role')->group(function () {
        Route::get('/', [App\Http\Controllers\Engine\Users\RolesController::class, 'index'])->name('role')->middleware('permission:role');
        Route::get('/create', [App\Http\Controllers\Engine\Users\RolesController::class, 'create'])->name('role.create')->middleware('permission:role/create');
        Route::post('/store', [App\Http\Controllers\Engine\Users\RolesController::class, 'store'])->name('role.store')->middleware('permission:role/create');
        Route::get('/edit/{id}', [App\Http\Controllers\Engine\Users\RolesController::class, 'edit'])->name('role.edit')->middleware('permission:role/edit');
        Route::post('/update/{id}', [App\Http\Controllers\Engine\Users\RolesController::class, 'update'])->name('role.update')->middleware('permission:role/edit');
        Route::get('/delete/{id}', [App\Http\Controllers\Engine\Users\RolesController::class, 'destroy'])->name('role.delete')->middleware('permission:role/delete');
        Route::get('/permission/{id?}', [App\Http\Controllers\Engine\Users\RolesController::class, 'permission'])->name('role.permission')->middleware('permission:role/permission');
        Route::post('/permission/{id}', [App\Http\Controllers\Engine\Users\RolesController::class, 'permissionStore'])->name('role.permission.store')->middleware('permission:role/permission');
    });

    // permission
    Route::prefix('permission')->group(function () {
        Route::get('/', [App\Http\Controllers\Engine\Users\PermissionsController::class, 'index'])->name('permission')->middleware('permission:permission');
        Route::get('/create', [App\Http\Controllers\Engine\Users\PermissionsController::class, 'create'])->name('permission.create')->middleware('permission:permission/create');
        Route::post('/store', [App\Http\Controllers\Engine\Users\PermissionsController::class, 'store'])->name('permission.store')->middleware('permission:permission/create');
        Route::get('/edit/{id}', [App\Http\Controllers\Engine\Users\PermissionsController::class, 'edit'])->name('permission.edit')->middleware('permission:permission/edit');
        Route::post('/update/{id}', [App\Http\Controllers\Engine\Users\PermissionsController::class, 'update'])->name('permission.update')->middleware('permission:permission/edit');
        Route::get('/delete/{id}', [App\Http\Controllers\Engine\Users\PermissionsController::class, 'destroy'])->name('permission.delete')->middleware('permission:permission/delete');
        Route::post('/permission/sorable', [App\Http\Controllers\Engine\Users\PermissionsController::class, 'sortable'])->name('permission.sortable')->middleware('permission:permission');
    });

    // settings
    Route::prefix('settings')->group(function () {
        Route::get('/', [App\Http\Controllers\Engine\Settings\WebSettingsController::class, 'index'])->name('settings')->middleware('permission:settings')->middleware('password.confirm');
        Route::post('/update', [App\Http\Controllers\Engine\Settings\WebSettingsController::class, 'update'])->name('settings.update')->middleware('permission:settings');
    });

    // Menus
    Route::prefix('menu')->group(function () {
        Route::get('/', [App\Http\Controllers\Engine\Menus\MenusController::class, 'index'])->name('menu')->middleware('permission:menu');
        Route::get('/create', [App\Http\Controllers\Engine\Menus\MenusController::class, 'create'])->name('menu.create')->middleware('permission:menu/create');
        Route::post('/store', [App\Http\Controllers\Engine\Menus\MenusController::class, 'store'])->name('menu.store')->middleware('permission:menu/create');
        Route::get('/edit/{id}', [App\Http\Controllers\Engine\Menus\MenusController::class, 'edit'])->name('menu.edit')->middleware('permission:menu/edit');
        Route::post('/update/{id}', [App\Http\Controllers\Engine\Menus\MenusController::class, 'update'])->name('menu.update')->middleware('permission:menu/edit');
        Route::get('/delete/{id}', [App\Http\Controllers\Engine\Menus\MenusController::class, 'destroy'])->name('menu.delete')->middleware('permission:menu/delete');
        Route::post('/menu/sorable', [App\Http\Controllers\Engine\Menus\MenusController::class, 'sortable'])->name('menu.sortable')->middleware('permission:menu');
    });

    Route::middleware(['cacheResponse:31536000'])->group(function () {
        // category
        Route::prefix('category')->group(function () {
            // prefix post
            Route::prefix('article')->group(function () {
                Route::get('/', [App\Http\Controllers\Engine\Categories\CategoryArticlesController::class, 'index'])->name('category.article')->middleware('permission:category/article');
                Route::get('/create', [App\Http\Controllers\Engine\Categories\CategoryArticlesController::class, 'create'])->name('category.article.create')->middleware('permission:category/article/create');
                Route::get('/edit/{id}', [App\Http\Controllers\Engine\Categories\CategoryArticlesController::class, 'edit'])->name('category.article.edit')->middleware('permission:category/article/edit');
            });
        });
    });

    Route::prefix('category')->group(function () {
        // prefix post
        Route::prefix('article')->group(function () {
            Route::post('/store', [App\Http\Controllers\Engine\Categories\CategoryArticlesController::class, 'store'])->name('category.article.store')->middleware('permission:category/article/create');
            Route::post('/update/{id}', [App\Http\Controllers\Engine\Categories\CategoryArticlesController::class, 'update'])->name('category.article.update')->middleware('permission:category/article/edit');
            Route::get('/delete/{id}', [App\Http\Controllers\Engine\Categories\CategoryArticlesController::class, 'destroy'])->name('category.article.delete')->middleware('permission:category/article/delete');
        });
    });

    // form
    Route::prefix('form')->group(function () {
        // contact
        Route::prefix('contact')->group(function () {
            Route::get('/', [App\Http\Controllers\Engine\Forms\ContactsController::class, 'index'])->name('form.contact')->middleware('permission:form/contact');
            // logs
            Route::get('/logs/{id?}', [App\Http\Controllers\Engine\Forms\ContactsController::class, 'logs'])->name('form.contact.logs')->middleware('permission:form/contact');
            Route::post('/update', [App\Http\Controllers\Engine\Forms\ContactsController::class, 'update'])->name('form.contact.update')->middleware('permission:form/contact');
            Route::get('/export', [App\Http\Controllers\Engine\Forms\ContactsController::class, 'export'])->name('form.contact.export')->middleware('permission:form/contact');
            Route::get('/list', [App\Http\Controllers\Engine\Forms\ContactsController::class, 'list'])->name('form.contact.filter')->middleware('permission:form/contact');
        });

    });

    // wordings
    Route::prefix('wordings')->group(function () {
        Route::get('/', [App\Http\Controllers\Engine\Wordings\WordingController::class, 'index'])->name('wordings')->middleware('permission:wordings')->middleware('password.confirm');
        Route::post('/store', [App\Http\Controllers\Engine\Wordings\WordingController::class, 'store'])->name('wordings.store')->middleware('permission:wordings/create');
        Route::PUT('/update', [App\Http\Controllers\Engine\Wordings\WordingController::class, 'update'])->name('wordings.update')->middleware('permission:wordings/edit');
        Route::get('/delete', [App\Http\Controllers\Engine\Wordings\WordingController::class, 'destroy'])->name('wordings.delete')->middleware('permission:wordings/delete');
    });

    // session
    Route::get('/session', function () {
        return session()->all();
    })->middleware('password.confirm');
});
