<?php

namespace App\Http\Middleware;

use Closure;

class CreateContact
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
        if(isset($request->user()->permissions) && in_array('create-new-contact', $request->user()->permissions)) {

            return $next($request);
            
        }
        
        return response()->json('Permission Denied');
    }
}
