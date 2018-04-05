<?php

namespace App\Http\Middleware;

use Closure;

class CheckLatLng
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
        if($request->session()->get('with_out_login_lat') != null || $request->session()->get('with_login_lat') != null){

            return $next($request);
        }else{
            return redirect()->route('home');
        }
    }
}
