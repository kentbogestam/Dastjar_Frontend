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
        return view('settings.index', compact(''));
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
        $discount = PromotionDiscount::select(['id', 'code'])
            ->where(['code' => $data['code'], 'status' => '1'])
            ->where('start_date', '<=', $todayDate)
            ->where('end_date', '>=', $todayDate)
            ->first();

        if($discount)
        {
            // Check if discount is not already applied
            if(!CustomerDiscount::where(['customer_id' => Auth::id(), 'discount_id' => $discount->id])->first())
            {
                $status = 1;
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
        $discount = PromotionDiscount::select(['id', 'code'])
            ->where(['code' => $data['code'], 'status' => '1'])
            ->where('start_date', '<=', $todayDate)
            ->where('end_date', '>=', $todayDate)
            ->first();

        if($discount)
        {
            // Check if discount is not already applied and then add
            if(!CustomerDiscount::where(['customer_id' => Auth::id(), 'discount_id' => $discount->id])->first())
            {
                $status = 1;
                CustomerDiscount::create(['customer_id' => Auth::id(), 'discount_id' => $discount->id]);
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
            DB::table('customer')->where('id', Auth::id())->update([
                'language' => $data['radio-choice-v-2'],
                'range' => $data['range-1b'],
            ]);
        }else{
            $request->session()->put('sessionBrowserLanguageValue', 1);
            $request->session()->put('browserLanguageWithOutLogin', $lang);
            $request->session()->put('rang', $data['range-1b']);
        }
        return redirect('customer')->with('success', 'Setting updated successfully.');
    }

    public function selectLocation(){
        return view('settings.location', compact(''));
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
            return redirect('customer')->with('success', 'Location updated successfully.');
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
