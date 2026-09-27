<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\FacebookConversionService;

class FacebookPageViewMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if (
            get_setting('facebook_pixel_capi') == 1 &&
            $request->isMethod('GET') &&
            !$request->ajax() &&
            !$request->expectsJson()
        ) {
            (new FacebookConversionService())->sendPageView();
        }

        return $response;
    }
}