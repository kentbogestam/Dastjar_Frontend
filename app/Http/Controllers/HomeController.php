<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Store;
use App\Product;
use App\Company;
use App\DishType;
use Session;
use DB;
use App\User;
use Auth;
use App\ProductPriceList;
use App\WebVersion;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function getList(Request $request){
       
      
        $pieces = explode(" ", $request->session()->get('current_date_time'));
        $todayDate = date('d-m-Y', strtotime($request->session()->get('current_date_time')));
        $currentTime = $pieces[4];
        $todayDay = $pieces[0];
        if(Auth::check()){

           $userDetail = User::whereId(Auth()->id())->first();
           //dd($userDetail);
            $companydetails = Store::getListRestaurantsCheck($userDetail->customer_latitude,$userDetail->customer_longitude,$userDetail->range,'1','3',$todayDate,$currentTime,$todayDay);
        }else{

             $lat = $request->session()->get('with_out_login_lat');
            $lng = $request->session()->get('with_out_login_lng');
            $rang = $request->session()->get('rang');
             $companydetails = Store::getListRestaurantsCheck($lat,$lng, $rang,'1','3',$todayDate,$currentTime,$todayDay);
        }
        dd($companydetails);  
    }

    public function checkUserLogin(){
        if(Auth::check()){
            $userDetail = User::whereId(Auth()->id())->first();
            return response()->json(['status' => 'success', 'response' => true,'data'=>$userDetail->id]);
        }
        return response()->json(['status' => 'success', 'response' => true,'data'=>false]);
    }

    public function saveCurrentLatLong(Request $request){
        $data = $request->input();
        if(Auth::check()){
            if($data['lat'] != null || $data['lng'] != null){
                DB::table('customer')->where('id', Auth::id())->update([
                                    'customer_latitude' => $data['lat'],
                                    'customer_longitude' => $data['lng'],
                                    'address' => NULL,
                                ]);
                $request->session()->put('setLocationBySettingValueAfterLogin', null);
            }else{
                
                DB::table('customer')->where('id', Auth::id())->update([
                                    'customer_latitude' => 59.303566,
                                    'customer_longitude' => 18.0065041,
                                    'address' => NULL,
                                ]);
            }
        }else{
            $request->session()->put('with_out_login_lat', $data['lat']);
            $request->session()->put('with_out_login_lng', $data['lng']);
            $request->session()->put('address', null);
            $request->session()->put('setLocationBySettingValue', null);

        }
       return response()->json(['status' => 'success', 'response' => true,'data'=>true]);  
    }

    public function userLatLong(Request $request){ 
        if(Auth::check()){

            $request->session()->forget('current_date_time');
          
            $versionDetail = WebVersion::orderBy('created_at', 'DESC')->first();
            $userDetail = User::whereId(Auth()->id())->first();

            if(!empty($request->input())){
                $data = $request->input();

                $request->session()->put('current_date_time', $data['currentdateTime']);
                $pieces = explode(" ", $request->session()->get('current_date_time'));
                $todayDate = date('d-m-Y', strtotime($request->session()->get('current_date_time')));
                $currentTime = $pieces[4];
                $todayDay = $pieces[0];
                
                if($userDetail->customer_latitude == null && $userDetail->customer_longitude == null){

                    DB::table('customer')->where('id', Auth::id())->update([
                                'customer_latitude' => $data['lat'],
                                'customer_longitude' => $data['lng'],
                                'range' => '6',
                                'language' => 'ENG',
                                'web_version' => $versionDetail->version,
                                'browser' => $data['browserVersion'],
                            ]);
                }
            }

            //DB::table('customer')->where('id', Auth::id())->update(['browser' => $data['browserVersion'],]);

            if($userDetail->web_version != $versionDetail->version){
                DB::table('customer')->where('id', Auth::id())->update(['web_version' => $versionDetail->version,]);
                Auth::logout();
                return redirect()->action('HomeController@versionUpdate');
            }
            $request->session()->put('with_login_lat', $data['lat']);
            $request->session()->put('browserTodayDate', $todayDate);
            $request->session()->put('browserTodayTime', $currentTime);
            $request->session()->put('browserTodayDay', $todayDay);

            $request->session()->forget('order_date');
            $currentUser = User::whereId(Auth()->id())->first();
            $companydetails = Store::getListRestaurants($currentUser->customer_latitude,$currentUser->customer_longitude,$currentUser->range,'1','3',$todayDate,$currentTime,$todayDay);

            
            return response()->json(['status' => 'success', 'response' => true,'data'=>$companydetails]);
            
        } else{

            if($request->session()->get('sessionBrowserLanguageValue') == null){
                if(isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])){
                  $languages = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
                  $languagesServer = explode('-', $languages[0]);
                  if($languagesServer[0] == 'sv'){
                    $lang = 'SWE';
                    Session::put('applocale', 'sv');
                  }else{
                    $lang =  'ENG';
                    Session::put('applocale', 'en');
                  }
                }
                $request->session()->put('browserLanguageWithOutLogin', $lang);
            }

            if(!empty($request->input())){

                $data = $request->input();

                if($request->session()->get('setLocationBySettingValue') == null){
                    $request->session()->put('with_out_login_lat', $data['lat']);
                    $request->session()->put('with_out_login_lng', $data['lng']);
                    $lat =  $data['lat'];
                    $lng =  $data['lng'];
                }else{
                    $lat = $request->session()->get('with_out_login_lat');
                    $lng = $request->session()->get('with_out_login_lng');
                }

                $request->session()->put('current_date_time', $data['currentdateTime']);
                $pieces = explode(" ", $request->session()->get('current_date_time'));
                $todayDate = date('d-m-Y', strtotime($request->session()->get('current_date_time')));
                $currentTime = $pieces[4];
                $todayDay = $pieces[0];
                if($request->session()->get('rang') != null){
                    $rang = $request->session()->get('rang');
                }else{
                    $rang = '7';
                    $request->session()->put('rang', $rang);
                }
                $companydetails = Store::getListRestaurants($lat,$lng,$rang,'1','3',$todayDate,$currentTime,$todayDay);

            }
            return response()->json(['status' => 'success', 'response' => true,'data'=>$companydetails]);
        }
    }


    public function index()
    {
        if(Auth::check()){
            $versionDetail = WebVersion::orderBy('created_at', 'DESC')->first();
            $userDetail = User::whereId(Auth()->id())->first();
            if($userDetail->web_version == null){
                DB::table('customer')->where('id', Auth::id())->update(['web_version' => $versionDetail->version,]);
                return view('index', compact(''));
            }else if($userDetail->web_version != $versionDetail->version){
                DB::table('customer')->where('id', Auth::id())->update(['web_version' => $versionDetail->version,]);
                Auth::logout();
                return redirect('/login')->with('success', 'App version is updated.Please login again');
            }else{
                return view('index', compact(''));
            }
        }else{
            return view('index', compact(''));
        }
    }

    public function blankView(){
      return view('blankPage');    
    }

    public function eatNow(Request $request)
    {

        $userDetail = User::whereId(Auth()->id())->first();
       //dd($userDetail);
        $pieces = explode(" ", $request->session()->get('current_date_time'));
        $todayDate = date('d-m-Y', strtotime($request->session()->get('current_date_time')));
        $currentTime = $pieces[4];
        $todayDay = $pieces[0];

        $companydetails = Store::getListRestaurants($userDetail->customer_latitude,$userDetail->customer_longitude,$userDetail->range,'1','3',$todayDate,$currentTime,$todayDay);
        //dd($companydetails);
        return view('eat-now', compact('companydetails'));
    }

    public function eatLaterData(Request $request){
        $pieces = explode(" ", $request->session()->get('current_date_time'));
        $todayDate = date('d-m-Y', strtotime($request->session()->get('current_date_time')));
        $currentTime = $pieces[4];
        $todayDay = $pieces[0];
        if(Auth::check()){
            $userDetail = User::whereId(Auth()->id())->first();
            $companydetails = Store::getListRestaurants($userDetail->customer_latitude,$userDetail->customer_longitude,$userDetail->range,'2','3',$todayDate,$currentTime,$todayDay);
        }else{
            $lat = $request->session()->get('with_out_login_lat');
            $lng = $request->session()->get('with_out_login_lng');
            if($request->session()->get('rang') != null){
                $rang = $request->session()->get('rang');
            }else{
                $rang = '7';
                $request->session()->put('rang', $rang);
            } 
            $companydetails = Store::getListRestaurants($lat,$lng,$rang,'2','3',$todayDate,$currentTime,$todayDay);
        }
        
        return response()->json(['status' => 'success', 'response' => true,'data'=>$companydetails]); 
    }

    public function eatLater(Request $request){

        $pieces = explode(" ", $request->session()->get('current_date_time'));
        $todayDate = date('d-m-Y', strtotime($request->session()->get('current_date_time')));
        $currentTime = $pieces[4];
        $todayDay = $pieces[0];


        if(!empty($request->input())) {

            $data = $request->input();
            $request->session()->put('order_date', $data['dateorder']);
            return view('eat_later', compact(''));
        } else {
            if(Auth::check()){
                $userDetail = User::whereId(Auth()->id())->first();
                $companydetails = Store::getListRestaurants($userDetail->customer_latitude,$userDetail->customer_longitude,$userDetail->range,'1','3',$todayDate,$currentTime,$todayDay);
            }else{
                $lat = $request->session()->get('with_out_login_lat');
                $lng = $request->session()->get('with_out_login_lng');
                if($request->session()->get('rang') != null){
                    $rang = $request->session()->get('rang');
                }else{
                    $rang = '7';
                    $request->session()->put('rang', $rang);
                } 
                $companydetails = Store::getListRestaurants($lat,$lng,$rang,'1','3',$todayDate,$currentTime,$todayDay);
            }
            
            return view('index', compact('companydetails'));
        }
    }

    public function eatLaterMap(Request $request){
        $pieces = explode(" ", $request->session()->get('current_date_time'));
        $todayDate = date('d-m-Y', strtotime($request->session()->get('current_date_time')));
        $currentTime = $pieces[4];
        $todayDay = $pieces[0];
        if(Auth::check()){
            $userDetail = User::whereId(Auth()->id())->first();
            $companydetails = Store::getListRestaurants($userDetail->customer_latitude,$userDetail->customer_longitude,$userDetail->range,'2','3',$todayDate,$currentTime,$todayDay);
        }else{
            $companydetails = Store::getListRestaurants($request->session()->get('with_out_login_lat'),$request->session()->get('with_out_login_lng'),$request->session()->get('rang'),'2','3',$todayDate,$currentTime,$todayDay);
        }
        return view('eat_later', compact('companydetails'));
    }

    public function menuList(Request $request, $storeId){
        $userDetail = User::whereId(Auth()->id())->first();
        //$menuDetails = Product::where('company_id' , $companyId)->with('menuPrice')->get();
        $menuDetails = ProductPriceList::where('store_id',$storeId)->with('menuPrice')->with('storeProduct')->get();
        if(count($menuDetails) !=0 ){
            foreach ($menuDetails as $menuDetail) {
                foreach ($menuDetail->storeProduct as $storeProduct) {
                    $companyId = $storeProduct->company_id;
                    $dish_typeId[] = $storeProduct->dish_type;
                }
            }
            //dd(array_unique($dish_typeId));
            $menuTypes = DishType::where('company_id' , $companyId)->whereIn('dish_id', array_unique($dish_typeId))->where('dish_activate','1')->get();
            $dish_typeId = null;
            $request->session()->put('storeId', $storeId);
            $companydetails = Company::where('company_id' , $companyId)->first();
            $storedetails = Store::where('store_id' , $storeId)->first();
            return view('menulist.index', compact('menuDetails','companydetails','menuTypes','storeId','storedetails'));
        }else{
            $storedetails = Store::where('store_id' , $storeId)->first();

            return view('menulist.blankMenu', compact('storedetails'));
        }
    }

    public function selectOrderDate(){

        return view('select-datetime', compact('')); 
    }
}
