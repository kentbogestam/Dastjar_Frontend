<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class OrderController extends Controller
{
    //
    public function saveOrder(Request $request){
    	//dd(Auth::id());
    	$i = 1;
    	foreach (array_slice($request->input(),1) as $key => $value) {
    		# code...
    		if(Product_)
    		dd($key);
    	}
    	dd($request->input());
    }
}
