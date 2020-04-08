<?php

namespace App\Http\Middleware;

use Closure;

class HandleCors
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $output = $next($request);
        $response=$output
                    ->header('Access-Control-Allow-Origin','*')
                    ->header('Access-Control-Allow-Headers','*')
                    ->header('Access-Control-Allow-Methods','GET, POST, PUT, DELETE, OPTIONS')
                    ->header('Access-Control-Allow-Credentials', true);
        return $response;
    }
}
