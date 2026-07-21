<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetLocaleMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->hasHeader('Accept-locale')) {
            app()->setLocale($request->header('Accept-locale'));
        }

        return $next($request);
    }
}
