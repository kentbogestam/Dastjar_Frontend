<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\PromotionDiscount;
use App\CustomerDiscount;
use Carbon\Carbon;
use DB;
use Auth;
use App\Helper;
use Session;
use Cache;
use App\App42\App42API;

class CustomerController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customerDiscount = null;

        if(Auth::check())
        {
            // Get customer added discount
            $todayDate = Carbon::now()->format('Y-m-d h:i:00');
            $customerDiscount = CustomerDiscount::from('customer_discount AS CD')
                ->select(['CD.id', 'PD.code'])
                ->join('promotion_discount AS PD', 'CD.discount_id', '=', 'PD.id')
                ->where(['CD.customer_id' => Auth::id(), 'CD.status' => '1', 'PD.status' => '1'])
                ->get();
        }
        
        return view('settings.index', compact('customerDiscount'));
    }

    /**
     * Check if promocode is valid and not already applied
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function ajaxIsValidDiscountCode(Request $request)
    {
        $status = 0; $msg = '';

        $data = $request->input();
        $todayDate = Carbon::now()->format('Y-m-d h:i:00');
        
        // Check if discount exist
        $discount = PromotionDiscount::select(['id', 'store_id'])
            ->where(['code' => $data['code'], 'status' => '1'])
            ->where('start_date', '<=', $todayDate)
            ->where('end_date', '>=', $todayDate)
            ->first();

        if($discount)
        {
            // Check if discount is not already applied
            if(!CustomerDiscount::where(['customer_id' => Auth::id(), 'discount_id' => $discount->id, 'status' => '1'])->first())
            {
                // Check if discount belongs to the same restaurant
                $customerDiscount = CustomerDiscount::from('customer_discount AS CD')
                    ->select(['CD.id'])
                    ->join('promotion_discount AS PD', 'CD.discount_id', '=', 'PD.id')
                    ->where(['CD.customer_id' => Auth::id(), 'CD.status' => '1', 'PD.store_id' => $discount->store_id])
                    ->first();

                if(!$customerDiscount)
                {
                    $status = 1;
                }
                else
                {
                    $status = 2;
                    $msg = __('messages.discountStoreDuplicate');
                }
            }
            else
            {
                $msg = __('messages.discountAlreadyApplied');
            }
        }
        else
        {
            $msg = __('messages.invalidDiscount');
        }

        return response()->json(['status' => $status, 'msg' => $msg]);
    }

    /**
     * Add customer discount
     * @param Request $request [description]
     */
    public function addCustomerDiscount(Request $request)
    {
        $status = 0;

        // Validation
        $validatedData = $request->validate([
            'code' => 'required'
        ]);

        $data = $request->input();
        $todayDate = Carbon::now()->format('Y-m-d h:i:00');
        
        // Check if discount exist
        $discount = PromotionDiscount::select(['id', 'store_id', 'discount_value', 'end_date'])
            ->where(['code' => $data['code'], 'status' => '1'])
            ->where('start_date', '<=', $todayDate)
            ->where('end_date', '>=', $todayDate)
            ->first();

        if($discount)
        {
            // Check if discount is not already applied
            if(!CustomerDiscount::where(['customer_id' => Auth::id(), 'discount_id' => $discount->id, 'status' => '1'])->first())
            {
                $status = 1;
                
                // Check if discount code belongs to the same restaurant user has already added discount for
                $customerDiscount = CustomerDiscount::from('customer_discount AS CD')
                    ->select(['CD.id', 'PD.store_id'])
                    ->join('promotion_discount AS PD', 'CD.discount_id', '=', 'PD.id')
                    ->where(['CD.customer_id' => Auth::id(), 'CD.status' => '1', 'PD.store_id' => $discount->store_id])
                    ->first();

                // Delete if discount already exists and replace discount cookie with new discount value
                if($customerDiscount)
                {
                    // Delete discount
                    CustomerDiscount::where(['id' => $customerDiscount->id])
                        ->update(['status' => '2']);

                    // Add new customer discount
                    CustomerDiscount::create(['customer_id' => Auth::id(), 'discount_id' => $discount->id]);

                    // Update discount cookie at same index with new discounted value
                    if( isset($_COOKIE['discount']) )
                    {
                        foreach($_COOKIE['discount'] as $key => $value)
                        {
                            $value = json_decode($value);

                            if($value->store_id == $customerDiscount->store_id)
                            {
                                setcookie("discount[{$key}]", json_encode(array('store_id' => $discount->store_id, 'discount_value' => $discount->discount_value)), strtotime($discount->end_date), '/');
                                break;
                            }
                        }
                    }
                }
                else
                {
                    // Add new customer discount
                    CustomerDiscount::create(['customer_id' => Auth::id(), 'discount_id' => $discount->id]);

                    // Add discount in cookie
                    // $discountLength = isset($_COOKIE['discount']) ? sizeof($_COOKIE['discount']) : 0;
                    $discountLength = 0;
                    if( isset($_COOKIE['discount']) )
                    {
                        // Get last discount key
                        $cookieDiscount = $_COOKIE['discount'];
                        end($cookieDiscount);
                        $discountLength = (key($cookieDiscount)+1);
                    }

                    setcookie("discount[{$discountLength}]", json_encode(array('store_id' => $discount->store_id, 'discount_value' => $discount->discount_value)), strtotime($discount->end_date), '/');
                }
            }
        }

        // Redirect
        if($status)
        {
            return redirect('user-setting')->with('success', __('discountAddedSuccessfully'));
        }
        else
        {
            return redirect('user-setting')->with('success', __('somethingWentWrong'));
        }
    }

    /**
     * Remove discount and update discount in cookie also
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    function removeCustomerDiscount(Request $request)
    {
        $status = 0; $msg = '';

        // Get customer discount to delete
        $customerDiscount = CustomerDiscount::from('customer_discount AS CD')
            ->select(['CD.id', 'PD.store_id'])
            ->join('promotion_discount AS PD', 'CD.discount_id', '=', 'PD.id')
            ->where(['CD.customer_id' => Auth::id(), 'PD.code' => $request->input('code'), 'CD.status' => '1'])
            ->first();

        if($customerDiscount)
        {
            $status = 1;

            // Update discount
            CustomerDiscount::where(['id' => $customerDiscount->id])
                ->update(['status' => '2']);

            // Update discount in cookie
            if( isset($_COOKIE['discount']) )
            {
                foreach($_COOKIE['discount'] as $key => $value)
                {
                    $value = json_decode($value);

                    if($value->store_id == $customerDiscount->store_id)
                    {
                        setcookie("discount[{$key}]", null, -1, '/');
                        break;
                    }
                }
            }
        }

        return response()->json(['status' => $status, 'msg' => $msg]);
    }

    public function saveSetting(Request $request){
        $data = $request->input();
      
        if($data['radio-choice-v-2'] == 'ENG'){
            Session::put('applocale', 'en');
            $lang =  'ENG';
        }else{
            Session::put('applocale', 'sv');
            $lang = 'SWE';
        }

        if(Auth::check()){
            $arr['language'] = $data['radio-choice-v-2'];

            if($request->has('range-1b'))
            {
                $arr['range'] = $data['range-1b'];
            }

            DB::table('customer')->where('id', Auth::id())->update($arr);
        }else{
            $request->session()->put('sessionBrowserLanguageValue', 1);
            $request->session()->put('browserLanguageWithOutLogin', $lang);

            if($request->has('range-1b'))
            {
                $request->session()->put('rang', $data['range-1b']);
            }
        }
        return redirect('user-setting')->with('success', 'Setting updated successfully.');
    }

    public function selectLocation(){
        // return view('settings.location', compact(''));
        return view('v1.user.pages.location');
    }

    public function saveLocation(Request $request){
       
        if(!empty($request->input())){

            $data = $request->input();
            $address = Helper::getLocation($data['street_address']);
            //dd($address['latitude'] != null && $address['longitude'] != null);
            if($address['latitude'] != null && $address['longitude'] != null){

                if(Auth::check()){
                    // DB::table('customer')->where('id', Auth::id())->update([
                    //     'customer_latitude' => $address['latitude'],
                    //     'customer_longitude' => $address['longitude'],
                    //     'address' => $address['street_address'],
                    // ]);
                    $request->session()->put('with_login_lat', $address['latitude']);
                    $request->session()->put('with_login_lng', $address['longitude']);
                    $request->session()->put('with_login_address', $address['street_address']);
                    $request->session()->put('updateLocationBySettingAfterLogin', 1);
                    $request->session()->put('setLocationBySettingValueAfterLogin', 1);
                }else{
                    $helper = new Helper();
                    $helper->logs("set location " . $address['latitude'] . " " . $address['longitude']);

                    $request->session()->put('with_out_login_lat', $address['latitude']);
                    $request->session()->put('with_out_login_lng', $address['longitude']);
                    $request->session()->put('address', $address['street_address']);
                    $request->session()->put('setLocationBySettingValue', 1);
                }
            }
        }

        if($data['redirect_to_home'] == 1){
            return redirect('home')->with('success', 'Location updated successfully.');
        }else{
            return redirect('user-setting')->with('success', 'Location updated successfully.');
        }
    }


    public function storeDeviceToken(Request $request){
        $helper = new Helper();

        $phone = explode("-", $request->phone);

        if(Auth::check()){
            $email = Auth::user()->email;
        }elseif(count($phone)==2){
            if(User::where('phone_number_prifix', $phone[0])
                    ->where('phone_number', $phone[1])->exists()){
                        $helper->logs($phone[1]);

                $email = User::where('phone_number_prifix', $phone[0])
                    ->where('phone_number', $phone[1])->first()->email;
            }
        }

        if(isset($email)){
           if(User::where('email',$email)->first()->device_token == null){
                $cust = User::where('email',$email)->first(); 
                $cust->device_token = $request->deviceToken;
                $cust->save();
                $response = "device id doesnt exist";
                $helper->logs($email . " " . $request->deviceToken);
            }else if(User::where('email',$email)->whereRaw("find_in_set('$request->deviceToken',device_token)")->doesntExist()){
                $cust = User::where('email',$email)->first(); 
                $cust->device_token =  $cust->device_token.",".$request->deviceToken;
                $cust->save();
                $response = "device id doesnt exist";
                $helper->logs($email . " " . $request->deviceToken);            
            }else{
                $response = "device id " . $request->deviceToken . " exist";
                $helper->logs($response);
            } 
        }else{
            $response = "Email not exists";
            $helper->logs($response);
        }
        

        return response()->json(['status' => 'success', 'response' => $response,'data'=>true]);        
    }

    public function storeDeviceTokenOrderView(Request $request){
        $helper = new Helper();

        if(User::where('email',$request->email)->first()->device_token == null){
            $cust = User::where('email',$request->email)->first(); 
            $cust->device_token = $request->deviceToken;
            $cust->save();
            $response = "device id doesnt exist";
            $helper->logs($request->email . " " . $request->deviceToken);
        }else if(User::where('email',$request->email)->whereRaw("find_in_set('$request->deviceToken',device_token)")->doesntExist()){
            $cust = User::where('email',$request->email)->first(); 
            $cust->device_token =  $cust->device_token.",".$request->deviceToken;
            $cust->save();
            $response = "device id doesnt exist";
            $helper->logs($request->email . " " . $request->deviceToken);            
        }else{
            $response = "device id " . $request->deviceToken . " exist";
            $helper->logs($request->email . " " . $response);
        }

        return response()->json(['status' => 'success', 'response' => $response,'data'=>true]);        
    }

    public function setTimezone(Request $request){
        Session::put('timezone',$request->tz);
        return response()->json(['status' => 'success', 'response' => 'timezone is ' . $request->tz,'data'=>true]);        
    }
}
