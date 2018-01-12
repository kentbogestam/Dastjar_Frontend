<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use App\Admin;
use DB;

class UsersController extends Controller
{
   public function savePassword(Request $request){

    	$validator = Validator::make($request->input(), [
                'email' => 'required|email|max:255',
                'password' => 'required',
            ]);

        if ($validator->fails()) {
            if (count($validator->errors()) == 1) {
                return response()->json(['status' => 'exception','response' => $validator->errors()->first()]);
            } else {
                return response()->json(['status' => 'exception','response' => $validator->errors()]);
            }
        }

        $input = $request->input();
       
        $input['password'] = bcrypt($input['password']);

        $user = Admin::where(['email' => $input['email']])->first();

        if($user){

        	DB::table('user')->where('email', $input['email'])->update([
                    'password' => $input['password'],
                ]);
        	return response()->json(['status' => 'success','response' => $user]);
        }else{
        	return response()->json(['status' => 'failure','response' => 'System Error:User could not be created .Please try later.']);
        }

    }
}
