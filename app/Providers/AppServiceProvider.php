<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Request;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Request $request)
    {
        if(isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])){
          $languages = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
          $languagesServer = explode('-', $languages[0]);
          if($languagesServer[0] == 'sv'){
            $lang = 'SWE';
            \Session::put('applocale', 'sv');
          }else{
            $lang =  'ENG';
            \Session::put('applocale', 'en');
          }
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
