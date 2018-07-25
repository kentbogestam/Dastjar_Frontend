<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Store;
use App\Product;
use App\Company;
use App\DishType;
use App\Order;
use App\Gdpr;
use App\User;
use Session;
use DB;
use Auth;
use App\ProductPriceList;
use App\WebVersion;
use Carbon\Carbon;
use App\App42\App42API;

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
            $lat = $request->session()->get('with_login_lat');
            $lng = $request->session()->get('with_login_lng');
           //dd($userDetail);
            $companydetails = Store::getListRestaurantsCheck($lat,$lng,$userDetail->range,'1','3',$todayDate,$currentTime,$todayDay);
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
            if(!empty($data['lat']) || !empty($data['lng'])){
                $request->session()->put('with_login_lat', $data['lat']);
                $request->session()->put('with_login_lng', $data['lng']);
                $request->session()->put('with_login_address', null);
                // DB::table('customer')->where('id', Auth::id())->update([
                //                     'customer_latitude' => $data['lat'],
                //                     'customer_longitude' => $data['lng'],
                //                     'address' => NULL,
                //                 ]);
                $request->session()->put('updateLocationBySettingAfterLogin', 1);
                $request->session()->put('setLocationBySettingValueAfterLogin', null);
            }else{
                $request->session()->put('with_login_lat', 59.303566);
                $request->session()->put('with_login_lng', 18.0065041);
                $request->session()->put('updateLocationBySettingAfterLogin', 1);
                $request->session()->put('setLocationBySettingValueAfterLogin', null);
                $request->session()->put('with_login_address', null);
                
                // DB::table('customer')->where('id', Auth::id())->update([
                //                     'customer_latitude' => 59.303566,
                //                     'customer_longitude' => 18.0065041,
                //                     'address' => NULL,
                //                 ]);
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
        $request->session()->forget('order_date');
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
                if($userDetail->range == null){
                    DB::table('customer')->where('id', Auth::id())->update([
                                'range' => 7,
                                'language' => 'ENG',
                                'web_version' => $versionDetail->version,
                                'browser' => $data['browserVersion'],
                            ]);
                }
            }

            if($request->session()->get('updateThreeHundrMeterAfterLogin') == null && $request->session()->get('updateLocationBySettingAfterLogin') == null){
                    $request->session()->put('with_login_lat', $data['lat']);
                    $request->session()->put('with_login_lng', $data['lng']);
                    $request->session()->put('with_login_lat', $data['lat']);
                    $lat =  $data['lat'];
                    $lng =  $data['lng'];   
            }else if($request->session()->get('updateThreeHundrMeterAfterLogin') == 1 && $request->session()->get('updateLocationBySettingAfterLogin') == null){
                $lat = $request->session()->get('with_login_lat');
                $lng = $request->session()->get('with_login_lng');
                $request->session()->put('with_login_lat', $request->session()->get('with_login_lat'));
            }else if($request->session()->get('updateThreeHundrMeterAfterLogin') == null && $request->session()->get('updateLocationBySettingAfterLogin') == 1){
                $lat = $request->session()->get('with_login_lat');
                $lng = $request->session()->get('with_login_lng');
                $request->session()->put('with_login_lat', $request->session()->get('with_login_lat'));
            }else{
                $lat = $request->session()->get('with_login_lat');
                $lng = $request->session()->get('with_login_lng');
                $request->session()->put('with_login_lat', $request->session()->get('with_login_lat')); 
            }

            if($userDetail->web_version != $versionDetail->version){
                DB::table('customer')->where('id', Auth::id())->update(['web_version' => $versionDetail->version,]);
                Auth::logout();
                return redirect()->action('HomeController@versionUpdate');
            }
            $request->session()->put('browserTodayDate', $todayDate);
            $request->session()->put('browserTodayTime', $currentTime);
            $request->session()->put('browserTodayDay', $todayDay);

            $request->session()->forget('order_date');
            $currentUser = User::whereId(Auth()->id())->first();
            $companydetails = Store::getListRestaurants($lat,$lng,$currentUser->range,'1','3',$todayDate,$currentTime,$todayDay);

            
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

    public function page_404(){
        return view('404');    
    }

    public function eatNow(Request $request)
    {
        $userDetail = User::whereId(Auth()->id())->first();
        $pieces = explode(" ", $request->session()->get('current_date_time'));
        $todayDate = date('d-m-Y', strtotime($request->session()->get('current_date_time')));
        $currentTime = $pieces[4];
        $todayDay = $pieces[0];
        $lat = $request->session()->get('with_login_lat');
        $lng = $request->session()->get('with_login_lng');
        $companydetails = Store::getListRestaurants($lat,$lng,$userDetail->range,'1','3',$todayDate,$currentTime,$todayDay);

        return view('eat-now', compact('companydetails'));
    }

    public function eatLaterData(Request $request){
        if($request->session()->get('order_date')){
            $pieces = explode(" ", $request->session()->get('order_date'));
            $month = ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];
            for($i=0;$i<count($month);$i++){
                if($month[$i] == $pieces[1]){
                 $months = $i;
                }
            } 
            $monthadd = $months+1;
            if($monthadd < 10){
                $monthFinal = sprintf("%02d",$monthadd);
            }else{
                $monthFinal = $monthadd;
            }
            $todayDate = $pieces[2].'-'.$monthFinal.'-'.$pieces[3];
            $currentTime = $pieces[4];
            $todayDay = $pieces[0]; 
        }else{
            $pieces = explode(" ", $request->session()->get('current_date_time'));
            $todayDate = date('d-m-Y', strtotime($request->session()->get('current_date_time')));
            $currentTime = $pieces[4];
            $todayDay = $pieces[0]; 
        }
        if(Auth::check()){
            $userDetail = User::whereId(Auth()->id())->first();
            $lat = $request->session()->get('with_login_lat');
            $lng = $request->session()->get('with_login_lng');
            $companydetails = Store::getListRestaurants($lat,$lng,$userDetail->range,'2','3',$todayDate,$currentTime,$todayDay);
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
                $lat = $request->session()->get('with_login_lat');
                $lng = $request->session()->get('with_login_lng');
                $companydetails = Store::getListRestaurants($lat,$lng,$userDetail->range,'1','3',$todayDate,$currentTime,$todayDay);
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
            $lat = $request->session()->get('with_login_lat');
            $lng = $request->session()->get('with_login_lng');
            $companydetails = Store::getListRestaurants($lat,$lng,$userDetail->range,'2','3',$todayDate,$currentTime,$todayDay);
        }else{
            $companydetails = Store::getListRestaurants($request->session()->get('with_out_login_lat'),$request->session()->get('with_out_login_lng'),$request->session()->get('rang'),'2','3',$todayDate,$currentTime,$todayDay);
        }
        return view('eat_later', compact('companydetails'));
    }

    public function menuList(Request $request, $storeId){
        if(!Store::where('store_id' , $storeId)->exists()){
            return redirect()->route('home');
        }

        $userDetail = User::whereId(Auth()->id())->first();
        $menuDetails = ProductPriceList::where('store_id',$storeId)->where('publishing_start_date','<=',Carbon::now())->where('publishing_end_date','>=',Carbon::now())->with('menuPrice')->with('storeProduct')
            ->leftJoin('product', 'product_price_list.product_id', '=', 'product.product_id')
           ->orderBy('product.product_rank', 'ASC')
            ->get();
        $storedetails = Store::where('store_id' , $storeId)->first();

        if(count($menuDetails) !=0 ){
            foreach ($menuDetails as $menuDetail) {
                foreach ($menuDetail->storeProduct as $storeProduct) {
                    $companyId = $storeProduct->company_id;
                    $dish_typeId[] = $storeProduct->dish_type;
                    try{
                        getimagesize($storeProduct->small_image);
                    } catch (\Exception $ex) {
                        $storeProduct->small_image = asset('images/placeholder-image.png');
                    }
                }
            }

            if(isset($companyId)){
                $menuTypes = DishType::where('company_id' , $companyId)->whereIn('dish_id', array_unique($dish_typeId))->where('dish_activate','1')->get();
                $dish_typeId = null;
                $request->session()->put('storeId', $storeId);
                $companydetails = Company::where('company_id' , $companyId)->first();
                return view('menulist.index', compact('menuDetails','companydetails','menuTypes','storeId','storedetails'));
            }else{
                return view('menulist.blankMenu', compact('storedetails'));
            }

        }else{
            return view('menulist.blankMenu', compact('storedetails'));
        }
    }

    public function selectOrderDate(){
        return view('select-datetime', compact('')); 
    }
	
	public function contact_us(Request $request){        
        $data = array('msg'=>$request->message);

        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";   
        $headers .='X-Mailer: PHP/' . phpversion();
        $headers .= "From: Anar <admin@dastjar.com> \r\n"; // header of mail content

        mail('info@dastjar.com', 'Dastjar Contact Mail', $request->message, $headers);

        return redirect()->back();
    }

    public function deleteUser(){
        if(!Auth::check()){
            return redirect()->back();
        }
        
        $user_id = Auth::user()->id;

        $gdpr = new Gdpr();
        $user = new User();

        $userName = $user->where('id' , '=', $user_id)->first()->email;  
        $deviceToken = $user->where('id' , '=', $user_id)->first()->device_token;  
 
        if($deviceToken != null){
            try{
                App42API::initialize(env('APP42_API_KEY'),env('APP42_API_SECRET'));   
                $pushNotificationService = App42API::buildPushNotificationService();   
                $tokens = explode(",", $deviceToken);
                foreach ($tokens as $key => $value) {
                    $response = $pushNotificationService->deleteDeviceToken($userName, $value);  
                }
            }catch(\Exception $ex){
                //Handle exception
            }
        }

        $gdpr->where('user_id' , '=', $user_id)->delete();
        $user->where('id' , '=', $user_id)->delete();

        return redirect()->back();
    }

    public function terms(){
        if(Auth::check()){
            if(Auth::user()->language == 'ENG'){
                $lan = "eng";
            }else{
                $lan = "swe";            
            }
        }else{
            if(Session::get('browserLanguageWithOutLogin') == 'ENG'){
                $lan = "eng";
            }
            else{
                $lan = "swe";
            }
        }

        if($lan == "eng"){
            return view('terms.terms-english');
        }else{
            return view('terms.terms-swedish');
        }
    }

    public function test(){
        return view('test');
    }
}
