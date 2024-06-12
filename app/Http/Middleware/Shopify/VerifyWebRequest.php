<?php

namespace App\Http\Middleware\Shopify;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Auth\Access\AuthorizationException;

class VerifyWebRequest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $hmac = $request->input('hmac', '');
        $params = $request->all();
        $secret = env('SHOPIFY_CLIENT_SECRET');
        unset($params['hmac']);
        $calculatedHmac = hash_hmac('sha256', http_build_query($params), $secret);

        if (!hash_equals($calculatedHmac, $hmac)) {
            throw new AuthorizationException();
        }

        return $next($request);
    }
}
