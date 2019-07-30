<?php

namespace App\Http\Middleware;

use Closure;

class MerchantCreateMiddleware
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
        if($request->user()->role == 'broker' || $request->user()->role == 'super_user' || $request->user()->role == 'analyst') {

            
            return $next($request);
            
        }
        return response()->json('You Don\'t Have Access Permissions');
    }
}
