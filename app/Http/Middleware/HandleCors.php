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
        // $output = $next($request);
        // $response=$output
        //             ->header('Access-Control-Allow-Origin','*')
        //             ->header('Access-Control-Allow-Headers','api_token,X-Requested-With, Cache-Control, Content-Type, Authorization, Accept-Language,Content-Language,Last-Event-ID,X-HTTP-Method-Override')
        //             ->header('Access-Control-Allow-Methods','GET, POST, PUT, DELETE, OPTIONS')
        //             ->header('Access-Control-Allow-Credentials', true);
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
