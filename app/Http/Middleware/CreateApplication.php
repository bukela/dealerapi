<?php

namespace App\Http\Middleware;

use Closure;

class CreateApplication
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
        if(isset($request->user()->permissions) && in_array('create-application', $request->user()->permissions)) {

            return $next($request);
            
        }
        
        return response()->json('Permission Denied');
    }
}
