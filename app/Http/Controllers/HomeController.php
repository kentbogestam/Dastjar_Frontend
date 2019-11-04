<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Store;
use App\Product;
use App\Company;
use App\DishType;
use App\Order;
use App\OrderDetail;
use App\Gdpr;
use App\User;
use App\PromotionLoyalty;
use App\OrderCustomerLoyalty;
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

            // Get customer discount from cookie
            $customerDiscount = isset($_COOKIE['discount']) ? $_COOKIE['discount'] : '';

            // Check if restaurant found and send translated message
            $restaurantStatusMsg = '';
            if( $companydetails == '' || !count($companydetails) )
            {
                $restaurantStatusMsg = __('messages.noRestaurantFound');
            }

            return response()->json(['status' => 'success', 'response' => true,'data'=>$companydetails, 'restaurantStatusMsg' => $restaurantStatusMsg, 'customerDiscount' => $customerDiscount]);
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
                    $rang = '10';
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
        // Artisan::call('view:clear');
        // dd(Session::all());
       
       if($request->session()->get('type_selection') == null){ //code added by saurabh to render the view for the selection of eat later nd eat now
         // return view('includes.popupSelection', compact(''));
         return view('v1.user.pages.home');
       }else{

       $request->session()->put('route_url', url('/').'/eat-now'); // code added by saurabh to update correct url for eat-later and eat-now
        if(Auth::check()){
            $versionDetail = WebVersion::orderBy('created_at', 'DESC')->first();
            $userDetail = User::whereId(Auth()->id())->first();
            if($userDetail->web_version == null){
                DB::table('customer')->where('id', Auth::id())->update(['web_version' => $versionDetail->version,]);
                // return view('index', compact(''));
                return view('v1.user.pages.eat-now');
            }else if($userDetail->web_version != $versionDetail->version){
                DB::table('customer')->where('id', Auth::id())->update(['web_version' => $versionDetail->version,]);
                Auth::logout();
                return redirect('/login')->with('success', 'App version is updated.Please login again');
            }else{
                // return view('index', compact(''));
                return view('v1.user.pages.eat-now');
            }
        }else{
            // return view('index', compact(''));
            return view('v1.user.pages.eat-now');
        }
    }
}

    public function blankView(){
        // return view('blankPage');
        return view('v1.user.pages.blank-page');
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
      
                if(Session::get('with_login_lat') != null){
                    $lat = $request->session()->get('with_login_lat');
                    $lng = $request->session()->get('with_login_lng');
                }else{
                    $lat = $request->session()->get('with_out_login_lat');
                    $lng = $request->session()->get('with_out_login_lng');
                }

            $companydetails = Store::getEatLaterListRestaurants($lat,$lng,$userDetail->range,'2','3',$todayDate,$currentTime,$todayDay);

            // Get customer discount from cookie
            $customerDiscount = isset($_COOKIE['discount']) ? $_COOKIE['discount'] : '';
        }else{
            $lat = $request->session()->get('with_out_login_lat');
            $lng = $request->session()->get('with_out_login_lng');
            if($request->session()->get('rang') != null){
                $rang = $request->session()->get('rang');
            }else{
                $rang = '10';
                $request->session()->put('rang', $rang);
            } 
            $companydetails = Store::getEatLaterListRestaurants($lat,$lng,$rang,'2','3',$todayDate,$currentTime,$todayDay);

            $customerDiscount = null;
        }

        // Check if restaurant found and send translated message
        $restaurantStatusMsg = '';
        if( $companydetails == '' || !count($companydetails) )
        {
            $restaurantStatusMsg = __('messages.noRestaurantFound');
        }
        
        return response()->json(['status' => 'success', 'response' => true,'data'=>$companydetails, 'restaurantStatusMsg' => $restaurantStatusMsg, 'customerDiscount' => $customerDiscount]);
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
            // return view('eat_later', compact(''));
            return view('v1.user.pages.eat-later');
            
        } else {
            if(Auth::check()){
                $userDetail = User::whereId(Auth()->id())->first();

                if(Session::get('with_login_lat') != null){
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
                    $rang = '10';
                    $request->session()->put('rang', $rang);
                } 
                $companydetails = Store::getEatLaterListRestaurants($lat,$lng,$rang,'1','3',$todayDate,$currentTime,$todayDay);
            }
             // return view('eat_later', compact(''));
             return view('v1.user.pages.eat-later');
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
        // return view('eat_later', compact('companydetails'));
        return view('v1.user.pages.eat-later', compact('companydetails'));
    }

    public function menuList(Request $request, $storeId, $styleType = 0){
        // Get store detail
        $request->session()->put('storeId', $storeId);
        $storedetails = Store::where('store_id' , $storeId)->first();

        // Get the dish_type ids have products available
        $dish_ids = array();
        /*$productPriceList = ProductPriceList::where('store_id',$storeId)->where('publishing_start_date','<=',Carbon::now())->where('publishing_end_date','>=',Carbon::now())->with('menuPrice')->with('storeProduct')->leftJoin('product', 'product_price_list.product_id', '=', 'product.product_id')->orderBy('product.product_rank', 'ASC')->orderBy('product.product_id')->get();
        if($productPriceList->count())
        {
            foreach ($productPriceList as $row) {
                foreach ($row->storeProduct as $storeProduct) {
                    $dish_ids[] = $storeProduct->dish_type;
                }
            }
        }*/
        $productPriceList = ProductPriceList::select('dish_type')
            ->where('store_id',$storeId)
            ->where('publishing_start_date','<=',Carbon::now())
            ->where('publishing_end_date','>=',Carbon::now())
            ->where('dish_type', '!=', null)
            ->join('product', 'product_price_list.product_id', '=', 'product.product_id')
            ->orderBy('product.product_rank', 'ASC')
            ->orderBy('product.product_id')
            ->get();

        if($productPriceList->count())
        {
            foreach ($productPriceList as $row) {
                $dish_ids[] = $row->dish_type;
            }

            $dish_ids = array_unique($dish_ids);
        }

        // 
        if( !empty($dish_ids) )
        {
            $menuTypes = array();

            $dishType = DishType::from('dish_type')
                ->where('u_id' , $storedetails->u_id)
                // ->where('parent_id', null)
                ->where('dish_activate','1')
                ->whereIn('dish_id', $dish_ids)
                ->orderBy('rank')
                ->orderBy('dish_id')
                ->get();

            if($dishType)
            {
                $dishIds = array();

                foreach($dishType as $dish)
                {
                    if( !is_null($dish->parent_id) )
                    {
                        // Get 'dish id' from parent ID
                        $dishTypeLevel0 = DishType::from('dish_type AS DT1')
                            ->select(['DT1.dish_id', 'DT1.dish_name', 'DT1.dish_image'])
                            ->leftJoin('dish_type AS DT2', 'DT2.parent_id', '=', 'DT1.dish_id')
                            ->leftJoin('dish_type AS DT3', 'DT3.parent_id', '=', 'DT2.dish_id')
                            ->whereRaw("(DT1.dish_id = '{$dish->dish_id}' OR DT2.dish_id = '{$dish->dish_id}' OR DT3.dish_id = '{$dish->dish_id}') AND DT1.parent_id IS NULL")
                            ->groupBy('DT1.dish_id')
                            ->first();
                        
                        if($dishTypeLevel0)
                        {
                            if( !in_array($dishTypeLevel0->dish_id, $dishIds) )
                            {
                                $dishIds[] = $dishTypeLevel0->dish_id;

                                $menuTypes[] = (object) array(
                                    'dish_id' => $dishTypeLevel0->dish_id,
                                    'dish_name' => $dishTypeLevel0->dish_name,
                                    'dish_image' => $dishTypeLevel0->dish_image,
                                );
                            }
                        }
                    }
                    else
                    {
                        if( !in_array($dish->dish_id, $dishIds) )
                        {
                            $dishIds[] = $dish->dish_id;

                            $menuTypes[] = (object) array(
                                'dish_id' => $dish->dish_id,
                                'dish_name' => $dish->dish_name,
                                'dish_image' => $dish->dish_image,
                            );
                        }
                    }
                }
            }

            if( !empty($menuTypes) )
            {
                // Get loyalty offer
                $promotionLoyalty = PromotionLoyalty::from('promotion_loyalty AS PL')
                    ->select(['PL.id', 'PL.quantity_to_buy', 'PL.quantity_get', 'PL.validity', DB::raw('DATE_FORMAT(PL.end_date, "%d/%m-%Y") AS end_date'), DB::raw('GROUP_CONCAT(dish_type_id) AS dish_type_ids')])
                    ->join('promotion_loyalty_dish_type AS PLDT', 'PLDT.loyalty_id', '=', 'PL.id')
                    ->where(['PL.store_id' => $storedetails->store_id, 'PL.status' => '1'])
                    ->where('PL.start_date', '<=', Carbon::now()->format('Y-m-d h:i:00'))
                    ->where('PL.end_date', '>=', Carbon::now()->format('Y-m-d h:i:00'))
                    ->groupBy('PL.id')
                    ->first();
                
                $customerLoyalty = null;
                
                if( Auth::check() && $promotionLoyalty )
                {
                    // Get count of 'loyalty' used number of times
                    $orderCustomerLoyalty = OrderCustomerLoyalty::from('order_customer_loyalty AS OCL')
                        ->select([DB::raw('COUNT(OCL.id) AS cnt')])
                        ->join('orders', 'orders.order_id', '=', 'OCL.order_id')
                        ->where(['OCL.customer_id' => Auth::id(), 'OCL.loyalty_id' => $promotionLoyalty->id])
                        ->where('orders.online_paid', '!=', 2)
                        ->first();

                    // Check if loyalty validity is 'false' so user can use n number of times or, validity should be greater than used validity of user
                    if( (!$promotionLoyalty->validity) || ($promotionLoyalty->validity > $orderCustomerLoyalty->cnt) )
                    {
                        // Get customer loyalty
                        $customerLoyalty = PromotionLoyalty::from('promotion_loyalty AS PL')
                            ->select(['OD.loyalty_id', DB::raw('SUM(OD.product_quality) AS quantity_bought')])
                            ->join('order_details AS OD', 'OD.loyalty_id', '=', 'PL.id')
                            ->join('orders', 'orders.order_id', '=', 'OD.order_id')
                            ->where(['PL.id' => $promotionLoyalty->id, 'OD.user_id' => Auth::id()])
                            ->where('orders.online_paid', '!=', 2)
                            ->where('OD.loyalty_id', '!=', null)
                            ->groupBy('OD.loyalty_id')
                            ->first();
                    }
                }
            }
        }

        // dd($menuTypes);
        return view('v1.user.pages.store-menu-list', compact('storedetails', 'menuTypes', 'promotionLoyalty', 'customerLoyalty', 'orderCustomerLoyalty', 'styleType'));
        // return view('v1.user.pages.store-menu-grid', compact('storedetails', 'menuTypes', 'promotionLoyalty', 'customerLoyalty', 'orderCustomerLoyalty'));
    }

    function getMenuDetail($dishType, $level)
    {
        $status = false;
        $html = '';

        // Check if sub-menu exist, or get products belongs to dish_id
        $subMenu = DishType::select(['dish_id', 'dish_name'])
                ->where(['parent_id' => $dishType, 'dish_activate' => '1'])
                ->orderBy('dish_id')
                ->get();
        
        if($subMenu->count())
        {
            $status = true;
            $html .= '<div class="hotel-ser-sub">';

            foreach($subMenu as $row)
            {
                $level++;
                $html .= "
                    <div class='product-sub'>
                        <a href='#sub-menu-{$row->dish_id}' onclick='getMenuDetail(this, {$row->dish_id}, {$level})' data-toggle='collapse'>
                            <span>{$row->dish_name} </span>
                        </a>
                        <div class='collapse sub-menu-detail' id='sub-menu-{$row->dish_id}'>
                            <div class='text-center'><i class='fa fa-spinner' aria-hidden='true'></i></div>
                        </div>
                    </div>
                ";
            }

            $html .= '</div>';
        }

        // If no 'sub-cat' found
        if($status == false)
        {
            $products = Product::from('product AS P')
                ->select(['P.product_id', 'P.product_name', 'P.product_description', 'P.preparation_Time', 'P.small_image', 'PPL.price', 'S.extra_prep_time'])
                ->join('product_price_list AS PPL', 'P.product_id', '=', 'PPL.product_id')
                ->join('store AS S', 'S.store_id', '=', 'PPL.store_id')
                ->where(['P.dish_type' => $dishType, 'PPL.store_id' => Session::get('storeId')])
                ->where('PPL.publishing_start_date','<=',Carbon::now())
                ->where('PPL.publishing_end_date','>=',Carbon::now())
                ->groupBy('P.product_id')
                ->orderBy('P.product_rank', 'ASC')
                ->orderBy('P.product_id')
                ->get();

            if($products->count())
            {
                $status = true;
                $html .= '<div class="list-menu-items">';
                
                foreach($products as $row)
                {
                    // 
                    $time = $row->preparation_Time;
                    if(!is_null($row->extra_prep_time)){
                        $time2 = $row->extra_prep_time;
                    }else{
                        $time2 = "00:00:00";
                    }
                    $secs = strtotime($time2)-strtotime("00:00:00");
                    $result = date("H:i:s",strtotime($time)+$secs);

                    if(date_create($result) != false)
                    {
                        $result = date_format(date_create($result), 'H').':'.date_format(date_create($result), 'i');
                    }

                    // 
                    // <img src='{$row->small_image}' alt='' onerror='this.src=\"".url('images/placeholder-image.png')."\"'>
                    $html .= "
                        <div class='hotel-product'>
                            <div class='product' id='item{$row->product_id}'>
                                <div class='col-sm-10 col-md-10 col-xs-8'>
                                    <div class='product-detail'>
                                        <img src='{$row->small_image}' alt=''>
                                    </div>
                                    <div class='discription'>
                                        <h3>{$row->product_name}</h3>
                                        <p>{$row->product_description}</p>
                                        <p class='price'>".number_format((float)$row->price, 2, '.', '')." SEK</p>
                                    </div>
                                </div>
                                <div class='col-md-2 col-sm-2 col-xs-4 quantity-sec'>
                                    <div class='quantity'>
                                        <span class='minus min' onclick='decrementValue(\"{$row->product_id}\")'><i class='fa fa-minus'></i></span>
                                        <span class='inputBox'>
                                            <input type='text' name='product[{$row->product_id}][prod_quant]' maxlength='2' size='1' value='0' readonly id='{$row->product_id}' />
                                        </span>
                                        <span class='plus max' onclick='incrementValue(\"{$row->product_id}\")'><i class='fa fa-plus'></i></span>
                                    </div>
                                    <input type='hidden' name='product[{$row->product_id}][id]' value='{$row->product_id}' />
                                </div>
                                <div class='additional-set extra-btn'>
                                    <a href='javascript:void(0)'><i class='fa fa-clock-o'></i> {$result}</a>
                                    <a href='#transitionExample' id='{$row->product_id}' data-toggle='modal'>
                                        <span class='add_comment'><i class='fa fa-comments-o'></i>".__('messages.Add Comments')."</span>
                                        <span class='edit_comment' style='display: none;'><i class='fa fa-comments-o'></i>".__('messages.Edit Comments')."</span>
                                    </a>
                                    <input type='hidden' id='orderDetail{$row->product_id}' name='product[{$row->product_id}][prod_desc]' value='' />
                                </div>
                                <div class='clearfix'></div>
                            </div>
                        </div>
                    ";
                }

                $html .= '</div>';
            }
            else
            {
                $status = 1;
                $html .= "<div class='text-center'>No product found.</div>";
            }
        }

        return response()->json(['status' => $status, 'html' => $html]);
    }

    public function selectOrderDate()
    {
        // return view('select-datetime', compact('')); 
        return view('v1.user.pages.select-datetime');
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


