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

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function userLatLong(Request $request){        
        
        $userDetail = User::whereId(Auth()->id())->first();

        if(!empty($request->input())){
            $data = $request->input();
            if($userDetail->customer_latitude == null && $userDetail->customer_longitude == null){

                DB::table('customer')->where('id', Auth::id())->update([
                            'customer_latitude' => $data['lat'],
                            'customer_longitude' => $data['lng'],
                            'range' => '3',
                            'language' => 'ENG',
                        ]);
            }
        }
        $request->session()->forget('order_date');
        $companydetails = Store::getListRestaurants($userDetail->customer_latitude,$userDetail->customer_longitude,$userDetail->range,'1','3');

        
        return response()->json(['status' => 'success', 'response' => true,'data'=>$companydetails]); 
    }
    public function index()
    {

        return view('index', compact(''));
    }

    public function blankView(){
      return view('blankPage');    
    }

    public function eatNow()
    {

        $userDetail = User::whereId(Auth()->id())->first();
       //dd($userDetail);
        $companydetails = Store::getListRestaurants($userDetail->customer_latitude,$userDetail->customer_longitude,$userDetail->range,'1','3');
        //dd($companydetails);
        return view('eat-now', compact('companydetails'));
    }

    public function eatLaterData(){
        $userDetail = User::whereId(Auth()->id())->first();
        $companydetails = Store::getListRestaurants($userDetail->customer_latitude,$userDetail->customer_longitude,$userDetail->range,'2','3');
        return response()->json(['status' => 'success', 'response' => true,'data'=>$companydetails]); 
    }

    public function eatLater(Request $request){

        if(!empty($request->input())) {

            $data = $request->input();
            $request->session()->put('order_date', $data['dateorder']);
            //$userDetail = User::whereId(Auth()->id())->first();
            //$companydetails = Store::getListRestaurants($userDetail->customer_latitude,$userDetail->customer_longitude,$userDetail->range,'2','3');
            //$companydetails = Company::where('company_type' , '2')->orWhere('company_type' , '3')->with('products')->get();
            return view('eat_later', compact(''));
        } else {
            $userDetail = User::whereId(Auth()->id())->first();
            $companydetails = Store::getListRestaurants($userDetail->customer_latitude,$userDetail->customer_longitude,$userDetail->range,'1','3');
            
            return view('index', compact('companydetails'));
        }
    }

    public function eatLaterMap(){
        $userDetail = User::whereId(Auth()->id())->first();
        $companydetails = Store::getListRestaurants($userDetail->customer_latitude,$userDetail->customer_longitude,$userDetail->range,'2','3');
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
            $request->session()->put('storeId'.Auth()->id(), $storeId);
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
