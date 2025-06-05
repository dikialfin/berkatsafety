<?php

namespace App\Http\Middleware;

use Closure;

class SetLocale
{
    public function handle($request, Closure $next)
    {
        $locale = $request->route('lang', config('app.locale')); // Default to app locale
        app()->setLocale($locale);

        return $next($request);
    }
}