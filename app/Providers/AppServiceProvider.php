<?php

namespace App\Providers;

use App\Http\Resources\LanguageResource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    private function registerBladeDirectives(): void
    {
        Blade::if('routehas', function ($route) {
            return Route::has($route);
        });

        Blade::if('routeis', function ($route) {
            return Route::is($route);
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Str::macro('currency', function ($price) {
            return number_format($price, 0);
        });

        $this->registerBladeDirectives();
        Model::preventLazyLoading(! $this->app->isProduction());
        Paginator::useBootstrap();
        LanguageResource::withoutWrapping();

        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }
    }
}
