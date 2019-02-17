<?php

namespace App\Http\Middleware;

use Closure;

class CheckSubsModule
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $module)
    {
        // Check if module exist in subscribed module, otherwise return to store page
        $subscribedModule = array();

        if( $request->session()->has('subscribedPlans') )
        {
            $subscribedModule = array_keys($request->session()->get('subscribedPlans'));
        }
        
        if( empty($subscribedModule) || !in_array($module, $subscribedModule) )
        {
            return redirect(url('kitchen/store'));
        }

        return $next($request);
    }
}
