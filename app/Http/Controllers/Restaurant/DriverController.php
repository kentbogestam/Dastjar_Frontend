<?php

namespace App\Http\Controllers\Restaurant;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;

use Auth;
use Session;
use App\Helper;
use App\Company;
// use App\Store;
use App\Driver;

class DriverController extends Controller
{
	/**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

	/**
	 * Display listing of resource
	 * 
	 * @return [type] [description]
	 */
    public function index()
    {
        $companyId = Company::where('u_id', Auth::user()->u_id)->first()->company_id;
        $driver = Driver::where('company_id', $companyId)
            ->where('status', '!=', '2')
            ->get();
    	
        return view('kitchen.driver.index', compact('driver'));
    }

    /**
     * Create
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    function store(Request $request)
    {
        // Validation
        $this->validate($request, [
            'name'  => 'required|string',
            'email' => ['required', 'string', 'email', Rule::unique('drivers')->where(function ($query) use ($request) {
                return $query->where('status', '!=', '2');
            })],
            'phone_prefix' => 'required|string',
            'phone' => ['required', 'numeric', Rule::unique('drivers')->where(function ($query) use ($request) {
                return $query->where('status', '!=', '2');
            })],
        ]);

        // Prepare data
        $data = $request->only(['name', 'email', 'phone_prefix', 'phone']);
        $data['company_id'] = Company::where('u_id', Auth::user()->u_id)->first()->company_id;
        $password = str_random(4);
        $data['password'] = Hash::make($password);

        $helper = new Helper();
        $id = $helper->uuid();

        while(Driver::where('id', $id)->exists()){
            $id = $helper->uuid();
        }

        $data['id'] = $id;

        // 
        if(Driver::create($data))
        {
            $recipients = array();
            $recipients = [$data['phone_prefix'].$data['phone']];
            $message = "You are now assigned as delivery person in Dastjar Driver.";
            $result = Helper::apiSendTextMessage($recipients, $message);
        }

        return redirect('kitchen/driver/list')->with('success', __('messages.moduleCreated', ['module' => __('messages.driver')]));
    }

    /**
     * Get by ID
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    function ajaxGetDriver($id)
    {
        $driver = Driver::where(['id' => $id])->first();

        return response()->json(['driver' => $driver]);
    }

    /**
     * [update description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    function update(Request $request)
    {
        // Validation
        $this->validate($request, [
            'name_upd'  => 'required|string',
            'email_upd' => ['required', 'string', 'email', Rule::unique('drivers', 'email')->ignore($request->input('id'))->where(function ($query) use ($request) {
                return $query->where('status', '!=', '2');
            })],
            'phone_prefix_upd' => 'required|string',
            'phone_upd' => ['required', 'numeric', Rule::unique('drivers', 'phone')->ignore($request->input('id'))->where(function ($query) use ($request) {
                return $query->where('status', '!=', '2');
            })],
        ]);

        // 
        $data['name'] = $request->input('name_upd');
        $data['email'] = $request->input('email_upd');
        $data['phone_prefix'] = $request->input('phone_prefix_upd');
        $data['phone'] = $request->input('phone_upd');

        // 
        Driver::where(['id' => $request->id])
            ->update(['name' => $data['name'], 'email' => $data['email'], 'phone_prefix' => $data['phone_prefix'], 'phone' => $data['phone']]);

        return redirect('kitchen/driver/list')->with('success', __('messages.moduleUpdated', ['module' => __('messages.driver')]));
    }

    /**
     * [destroy description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    function destroy($id)
    {
        $driver = Driver::where(['id' => $id])->get();

        if($driver)
        {
            Driver::where(['id' => $id])->update(['status' => '2']);
        }

        return redirect('kitchen/driver/list')->with('success', __('messages.moduleDeleted', ['module' => __('messages.driver')]));
    }
}
