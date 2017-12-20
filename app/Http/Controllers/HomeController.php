<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Store;
use App\Product;
use App\Company;
use App\DishType;
use Session;

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
    public function index()
    {
        $companydetails = Store::getListRestaurants('28.584732','77.063363','1','1','3');
         //dd($companydetails);
        // $companydetails = Company::where('company_type' , '1')->orWhere('company_type' , '3')->with('products')->get();
        return view('index', compact('companydetails'));
    }

    public function eatLater(Request $request){
        $data = $request->input();
        $request->session()->put('order_date', $data['dateorder']);
        $companydetails = Store::getListRestaurants('28.584732','77.063363','1','2','3');
        //$companydetails = Company::where('company_type' , '2')->orWhere('company_type' , '3')->with('products')->get();
        return view('eat_later', compact('companydetails'));
    }

    public function eatLaterMap(){
        $companydetails = Store::getListRestaurants('28.584732','77.063363','1','2','3');
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
