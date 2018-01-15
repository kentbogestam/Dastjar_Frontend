<?php

namespace App\Http\Middleware;

use Closure;

class HttpsProtocol
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
        //dd('dddd');
        if (!$request->secure() && env('APP_ENV') === 'pro') {
            dd(redirect()->secure($request->getRequestUri()));
            return redirect()->secure($request->getRequestUri());
        }
        return $next($request); 
    }
}
