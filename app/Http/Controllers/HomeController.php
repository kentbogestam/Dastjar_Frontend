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
        $data = $request->input();

        DB::table('customer')->where('id', Auth::id())->update([
                    'customer_latitude' => $data['lat'],
                    'customer_longitude' => $data['lng'],
                ]);
        $companydetails = Store::getListRestaurants($data['lat'],$data['lng'],'3','1','3');

        
        return response()->json(['status' => 'success', 'response' => true,'data'=>$companydetails]); 
    }
    public function index()
    {

        $userDetail = User::whereId(Auth()->id())->first();
        $companydetails = Store::getListRestaurants($userDetail->customer_latitude,$userDetail->customer_longitude,'1','1','3');
        
        return view('index', compact('companydetails'));
    }

    public function eatLater(Request $request){
        $data = $request->input();
        $request->session()->put('order_date', $data['dateorder']);
        $userDetail = User::whereId(Auth()->id())->first();
        $companydetails = Store::getListRestaurants($userDetail->customer_latitude,$userDetail->customer_longitude,'1','2','3');
        //$companydetails = Company::where('company_type' , '2')->orWhere('company_type' , '3')->with('products')->get();
        return view('eat_later', compact('companydetails'));
    }

    public function eatLaterMap(){
        $userDetail = User::whereId(Auth()->id())->first();
        $companydetails = Store::getListRestaurants($userDetail->customer_latitude,$userDetail->customer_longitude,'1','2','3');
        return view('eat_later', compact('companydetails'));
    }

    public function menuList(Request $request, $companyId){
        $menuDetails = Product::where('company_id' , $companyId)->with('menuPrice')->get();
        $menuTypes = DishType::where('company_id' , $companyId)->where('dish_activate','1')->get();
        $companydetails = Company::where('company_id' , $companyId)->first();
        return view('menulist.index', compact('menuDetails','companydetails','menuTypes'));

    }

    public function selectOrderDate(){

        return view('select-datetime', compact('')); 
    }
}
