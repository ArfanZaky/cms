<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EncryptResponseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($response->headers->get('Content-Type') === 'application/json') {
            if (env('APP_ENV') == 'production') {
                $response->headers->set('Content-Type', 'text/plain');
                $response->setContent(encryptAES($response->getContent()));
            }
        }

        return $response;
    }
}
