<?php

namespace App\Http\Middleware;

use Closure;
use Log;
class HandleCors
{
    /**
     * Handle an incoming request.
     * add header to response so request can be processed by API calls
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $headers = [
            'Access-Control-Allow-Origin'      => '*',
            'Access-Control-Allow-Methods'     => 'POST, GET, OPTIONS, PUT, DELETE',
            'Access-Control-Allow-Credentials' => true,
            'Access-Control-Max-Age'           => '86400',
            'Access-Control-Allow-Headers'     => 'Origin, Content-Type, X-Auth-Token, Authorization'
        ];

        if ($request->isMethod('OPTIONS'))
        {
            return response()->json('{"method":"OPTIONS"}', 200, $headers);
        }

        $response = $next($request);
        foreach($headers as $key => $value)
        {
            $response->header($key, $value);
        }
        
        return $response;
    }
}
