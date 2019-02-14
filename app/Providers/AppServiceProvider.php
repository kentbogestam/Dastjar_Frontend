<?php

namespace App\Providers;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

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

        View::share('RAND_APP_VERSION', mt_rand(100,1000));
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
