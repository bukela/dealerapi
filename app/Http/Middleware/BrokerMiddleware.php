<?php

namespace App\Http\Middleware;

use Closure;

class BrokerMiddleware
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
        if($request->user() && $request->user()->role == 'broker') {

            return $next($request);
            
        }
        
        return response()->json('Permission Denied');
    }
}
