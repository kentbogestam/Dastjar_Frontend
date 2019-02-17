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
use Cookie;
use DB;
use Auth;
use App\ProductPriceList;
use App\WebVersion;
use Carbon\Carbon;
use App\App42\App42API;
use Artisan;
use Helper;
use Illuminate\Support\Facades\Input;

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
            
            if(Session::get('with_login_address') != null){
                $lat = $request->session()->get('with_login_lat');
                $lng = $request->session()->get('with_login_lng');
            }else{
                $lat = $request->session()->get('with_out_login_lat');
                $lng = $request->session()->get('with_out_login_lng');
            }

            $companydetails = Store::getListRestaurantsCheck($lat,$lng,$userDetail->range,'1','3',$todayDate,$currentTime,$todayDay);
        }else{
            $lat = $request->session()->get('with_out_login_lat');
            $lng = $request->session()->get('with_out_login_lng');
            $rang = $request->session()->get('rang');
             $companydetails = Store::getListRestaurantsCheck($lat,$lng, $rang,'1','3',$todayDate,$currentTime,$todayDay);
        }

        return response()->json(['status' => 'success', 'response' => true,'data'=>$companydetails]);
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

        $helper = new Helper();

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

            /*if($request->session()->get('updateThreeHundrMeterAfterLogin') == null && $request->session()->get('updateLocationBySettingAfterLogin') == null){
                                   

                if(empty($data['lat'])){
                    if(Session::get('with_login_address') != null){
                        $data['lat'] = $request->session()->get('with_login_lat');
                        $data['lng'] = $request->session()->get('with_login_lng');
                    }else if(Session::get('with_out_login_lat') != null){
                        $data['lat'] = $request->session()->get('with_out_login_lat');
                        $data['lng'] = $request->session()->get('with_out_login_lng');
                    }
                }

                    $request->session()->put('with_login_lat', $data['lat']);
                    $request->session()->put('with_login_lng', $data['lng']);

                    $lat =  $data['lat'];
                    $lng =  $data['lng'];   
            }else if($request->session()->get('updateThreeHundrMeterAfterLogin') == 1 && $request->session()->get('updateLocationBySettingAfterLogin') == null){
                $lat = $request->session()->get('with_login_lat');
                $lng = $request->session()->get('with_login_lng');
                $request->session()->put('with_login_lat', $request->session()->get('with_login_lat'));
            }else if($request->session()->get('updateThreeHundrMeterAfterLogin') == null && $request->session()->get('updateLocationBySettingAfterLogin') == 1){

                    if(Session::get('with_login_address') != null){
                        $lat = $request->session()->get('with_login_lat');
                        $lng = $request->session()->get('with_login_lng');
                    }else{
                        $lat = $request->session()->get('with_out_login_lat');
                        $lng = $request->session()->get('with_out_login_lng');
                    }

                $request->session()->put('with_login_lat', $request->session()->get('with_login_lat'));
            }else{
                $lat = $request->session()->get('with_login_lat');
                $lng = $request->session()->get('with_login_lng');
                $request->session()->put('with_login_lat', $request->session()->get('with_login_lat')); 
            }*/

            // Get and update lat/lng
            if( empty($data['lat']) || empty($data['lng']) )
            {
                $lat = Session::get('with_login_lat');
                $lng = Session::get('with_login_lng');
            }
            else
            {
                $lat = $data['lat'];
                $lng = $data['lng'];
            }

            $request->session()->put('with_login_lat', $lat);
            $request->session()->put('with_login_lng', $lng);

            //
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

            $companydetails = "";

            try{
                $companydetails = Store::getListRestaurants($lat,$lng,$currentUser->range,'1','3',$todayDate,$currentTime,$todayDay);
            }catch(\Exception $ex){
                  $helper->logs("getListRestaurants " . $ex->getMessage());
            }

            // Check if restaurant found and send translated message
            $restaurantStatusMsg = '';
            if( $companydetails == '' || !count($companydetails) )
            {
                $restaurantStatusMsg = __('messages.noRestaurantFound');
            }

            return response()->json(['status' => 'success', 'response' => true,'data'=>$companydetails, 'restaurantStatusMsg' => $restaurantStatusMsg]);
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

                /*if($request->session()->get('setLocationBySettingValue') == null){
                    $request->session()->put('with_out_login_lat', $data['lat']);
                    $request->session()->put('with_out_login_lng', $data['lng']);
                    $lat =  $data['lat'];
                    $lng =  $data['lng'];
                }else{
                    $lat = $request->session()->get('with_out_login_lat');
                    $lng = $request->session()->get('with_out_login_lng');
                }*/

                // Get and update lat/lng
                if( empty($data['lat']) || empty($data['lng']) )
                {
                    $lat = Session::get('with_out_login_lat');
                    $lng = Session::get('with_out_login_lng');
                }
                else
                {
                    $lat = $data['lat'];
                    $lng = $data['lng'];
                }

                $request->session()->put('with_out_login_lat', $lat);
                $request->session()->put('with_out_login_lng', $lng);

                //
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

            // Check if restaurant found and send translated message
            $restaurantStatusMsg = '';
            if( $companydetails == '' || !count($companydetails) )
            {
                $restaurantStatusMsg = __('messages.noRestaurantFound');
            }

            return response()->json(['status' => 'success', 'response' => true,'data'=>$companydetails, 'restaurantStatusMsg' => $restaurantStatusMsg]);
        }
    }


    public function index(Request $request)
    {
        Artisan::call('view:clear');

        // dd(Session::all());
       
       if($request->session()->get('type_selection') == null){ //code added by saurabh to render the view for the selection of eat later nd eat now
         return view('includes.popupSelection', compact(''));
       }else{

       $request->session()->put('route_url', url('/').'/eat-now'); // code added by saurabh to update correct url for eat-later and eat-now
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

                if(Session::get('with_login_address') != null){
                    $lat = $request->session()->get('with_login_lat');
                    $lng = $request->session()->get('with_login_lng');
                }else{
                    $lat = $request->session()->get('with_out_login_lat');
                    $lng = $request->session()->get('with_out_login_lng');
                }

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
      
                if(Session::get('with_login_address') != null){
                    $lat = $request->session()->get('with_login_lat');
                    $lng = $request->session()->get('with_login_lng');
                }else{
                    $lat = $request->session()->get('with_out_login_lat');
                    $lng = $request->session()->get('with_out_login_lng');
                }

            $companydetails = Store::getEatLaterListRestaurants($lat,$lng,$userDetail->range,'2','3',$todayDate,$currentTime,$todayDay);
        }else{
            $lat = $request->session()->get('with_out_login_lat');
            $lng = $request->session()->get('with_out_login_lng');
            if($request->session()->get('rang') != null){
                $rang = $request->session()->get('rang');
            }else{
                $rang = '7';
                $request->session()->put('rang', $rang);
            } 
            $companydetails = Store::getEatLaterListRestaurants($lat,$lng,$rang,'2','3',$todayDate,$currentTime,$todayDay);
        }

        // Check if restaurant found and send translated message
        $restaurantStatusMsg = '';
        if( $companydetails == '' || !count($companydetails) )
        {
            $restaurantStatusMsg = __('messages.noRestaurantFound');
        }
        
        return response()->json(['status' => 'success', 'response' => true,'data'=>$companydetails, 'restaurantStatusMsg' => $restaurantStatusMsg]); 
    }

    public function eatLater(Request $request){

       // if($request->session()->get('order_date') != null){
        $pieces = explode(" ", $request->session()->get('current_date_time'));
        $todayDate = date('d-m-Y', strtotime($request->session()->get('current_date_time')));
        $currentTime = $pieces[4];
        $todayDay = $pieces[0];

         $request->session()->put('route_url', url('/').'/eat-later'); // code added by saurabh to update correct url for eat-later and eat-now

        if(!empty($request->input())) {

            $data = $request->input();
            $request->session()->put('order_date', $data['dateorder']);
            return view('eat_later', compact(''));
            
        } else {
            if(Auth::check()){
                $userDetail = User::whereId(Auth()->id())->first();

                if(Session::get('with_login_address') != null){
                    $lat = $request->session()->get('with_login_lat');
                    $lng = $request->session()->get('with_login_lng');
                }else{
                    $lat = $request->session()->get('with_out_login_lat');
                    $lng = $request->session()->get('with_out_login_lng');
                }

                $companydetails = Store::getEatLaterListRestaurants($lat,$lng,$userDetail->range,'1','3',$todayDate,$currentTime,$todayDay);
            }else{
                $lat = $request->session()->get('with_out_login_lat');
                $lng = $request->session()->get('with_out_login_lng');
                if($request->session()->get('rang') != null){
                    $rang = $request->session()->get('rang');
                }else{
                    $rang = '7';
                    $request->session()->put('rang', $rang);
                } 
                $companydetails = Store::getEatLaterListRestaurants($lat,$lng,$rang,'1','3',$todayDate,$currentTime,$todayDay);
            }
             return view('eat_later', compact(''));
            //return view('index', compact('companydetails')); //commeted by saurabh to stop the redirection of et later
        }
    }

    public function eatLaterMap(Request $request){
        $pieces = explode(" ", $request->session()->get('current_date_time'));
        $todayDate = date('d-m-Y', strtotime($request->session()->get('current_date_time')));
        $currentTime = $pieces[4];
        $todayDay = $pieces[0];
        if(Auth::check()){
            $userDetail = User::whereId(Auth()->id())->first();

            if(Session::get('with_login_address') != null){
                $lat = $request->session()->get('with_login_lat');
                $lng = $request->session()->get('with_login_lng');
            }else{
                $lat = $request->session()->get('with_out_login_lat');
                $lng = $request->session()->get('with_out_login_lng');
            }

            $companydetails = Store::getEatLaterListRestaurants($lat,$lng,$userDetail->range,'2','3',$todayDate,$currentTime,$todayDay);
        }else{
            $companydetails = Store::getEatLaterListRestaurants($request->session()->get('with_out_login_lat'),$request->session()->get('with_out_login_lng'),$request->session()->get('rang'),'2','3',$todayDate,$currentTime,$todayDay);
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

            // dd($menuDetails->toArray());

        $storedetails = Store::where('store_id' , $storeId)->first();
        $request->session()->put('storeId', $storeId);

        if(count($menuDetails) !=0 ){
            foreach ($menuDetails as $menuDetail) {
                foreach ($menuDetail->storeProduct as $storeProduct) {
                    $companyId = $storeProduct->company_id;
                    $dish_typeId[] = $storeProduct->dish_type;
                    
                }
            }

            if(isset($companyId)){
                $menuTypes = DishType::where('company_id' , $companyId)->whereIn('dish_id', array_unique($dish_typeId))->where('dish_activate','1')->get();
                $dish_typeId = null;
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
        if(\App::getLocale() == "en"){
                $lan = "eng";
        }else{
                $lan = "swe";
        }

        if($lan == "eng"){
            return view('terms.terms-english');
        }else{
            return view('terms.terms-swedish');
        }
    }

    public function write_logs(Request $request){
        $helper = new Helper();
        $helper->logs($request->log);

        return response()->json(['status' => 'success', 'response' => true,'data'=>'Logs written successfully']);
    }

    public function test(){
        return view('test');
    }

    public function goToLogin(){
        return redirect()->route('customer-login')->with('error', 'Your session has expired');
    }


    public function updateLocation(Request $request){
        $lat  = $request->input('lat');
        $long = $request->input('long');

        if(Auth::check()){
                
            $request->session()->put('with_login_lat', $lat);
            $request->session()->put('with_login_lng', $long);
            $request->session()->put('with_login_address', null);
            $request->session()->put('updateLocationBySettingAfterLogin', 1);
            $request->session()->put('setLocationBySettingValueAfterLogin', null);
            
        }else{
              $request->session()->put('with_out_login_lat', $lat);
              $request->session()->put('with_out_login_lng', $long);
              $request->session()->put('address', null);
              $request->session()->put('setLocationBySettingValue', null);
        }
       
    }

    /**
     * Call from 'popupSelection' view when user select 'Eat Now/Eat Later' first time on app
     * @param Request $request [description]
     */
    public function setRestarurantType(Request $request){
        if(!empty($request->input())){
            $data = $request->input();
            
            $request->session()->put('current_date_time', $data['currentdateTime']);
            $request->session()->put('type_selection', "checked");
            
            if($data['restType']=="eatnow"){
                $url= url("/");
            }
            else{
                $url= url("/selectOrder-date");
            }

            return response()->json(['status' => 'success', 'response' => true,'data'=>$url]);
        }
    }


    /**
     * Print session and cookie
     */
    function prettySessionCookie(Request $request)
    {
        echo '<pre>Session<br>'; print_r(Session::all());
        // echo '<pre>Cookie<br>'; print_r(Cookie::get());
        
        echo '<br>Cookie:<br>';
        foreach($_COOKIE as $key => $value)
        {
            echo $key.' => '.$value.'<br>';
        }
    }


}


