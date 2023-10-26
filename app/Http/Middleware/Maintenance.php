<?php

namespace App\Http\Middleware;

use App\Http\Request;

class Maintenance
{
    public function handle(Request $request, \Closure $next)
    {
        return $next($request);
    }

}
