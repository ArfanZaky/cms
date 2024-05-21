<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DecryptRequestMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next): Response
    {
        if ($request->header('Content-Type') === 'text/plain') {
            $bodyContent = $request->getContent();
            $decryptedContent = decryptAES($bodyContent);
            $decodedContent = json_decode($decryptedContent, true);

            foreach ($decodedContent as $key => $value) {
                $request->request->add([$key => $value]);
            }
        }

        return $next($request);
    }
}
