<?php

namespace App\Http\Middleware;

use App\Models\WebSettings;
use Closure;
use Illuminate\Http\Request;

class WhitelistIpAddressMiddleware
{
    /**
     * @var string[]
     */

    /**
     * Handle an incoming request.
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {

        if (env('APP_ENV') === 'development') {
            return $next($request);
        }
        $ip_backend = WebSettings::where('code', 'ip_backend')->first()->value;
        $whitelist_backend = WebSettings::where('code', 'whitelist_backend')->first()->value;

        $ip_backend = collect(explode(',', $ip_backend))->map(function ($item) {
            return trim($item);
        })->toArray();

        if (collect($ip_backend)->contains($request->getClientIp())) {
            return $next($request);
        }

        if ($whitelist_backend == 0) {
            return $next($request);
        }

        abort(403, 'You are restricted to access the site.');

    }
}
