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
                    ->header('Access-Control-Allow-Origin','http://localhost:3000')
                    ->header('Access-Control-Allow-Methods','POST,PUT,DELETE,GET');

        return $response;
    }
}
