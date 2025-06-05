<?php

namespace App\Http\Middleware;

use Closure;

class XSSHandle 
{
    public function handle($request, Closure $next)
    {
        $userInput = $request->all();
        array_walk_recursive($userInput, function (&$userInput) {
            $userInput = strip_tags($userInput);
        });
        $request->merge($userInput);
        return $next($request);
    }
}
