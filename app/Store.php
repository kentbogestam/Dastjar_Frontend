<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use DB;

class Store extends Model
{
    //
	protected $table = 'store';

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }


    protected $fillable = ['store_id', 'u_id', 'store_type', 'latitude', 'longitude', 'store_name', 'street', 'city', 'country', 'country_code', 'phone', 'email', 'store_link', 's_activ', 'version', 'access_type', 'chain', 'block', 'zip'];

//This function use for testing purpose

    public static function getListRestaurantsCheck($latitude,$longitude,$radius,$companytype1,$companytype2,$todayDate,$currentTime,$todayDay){
        $circle_radius = 6378.10;
        $max_distance = $radius;

        //$unit = ($unit === "km") ? 6378.10 : 3963.17;
        $unit = 6378.10;
        $lat = (float) $latitude;
        $lng = (float) $longitude;
        $radius = (double) $radius;
        
        $latLngList = Store::having('distance','<=',$radius)->select("*",DB::raw("
                        ($unit * ACOS(COS(RADIANS(".$lat."))
                            * COS(RADIANS(latitude))
                            * COS(RADIANS(".$lng.") - RADIANS(longitude))
                            + SIN(RADIANS(".$lat."))
                            * SIN(RADIANS(latitude)))) AS distance")
            )->join('company', 'company.u_id', '=', 'store.u_id')->where('store_close_dates', 'not like', '%'.$todayDate.'%')->where('store_open_days', 'like', '%'.$todayDay.'%')->where('store_open', '<', $currentTime)->where('store_close', '>', $currentTime)->where('s_activ','=','1')->where('store_type','=',$companytype1)->with('products');

        $latLngList1 = Store::having('distance','<=',$radius)->select("*",DB::raw("
                        ($unit * ACOS(COS(RADIANS(".$lat."))
                            * COS(RADIANS(latitude))
                            * COS(RADIANS(".$lng.") - RADIANS(longitude))
                            + SIN(RADIANS(".$lat."))
                            * SIN(RADIANS(latitude)))) AS distance")
            )->join('company', 'company.u_id', '=', 'store.u_id')->where('store_close_dates', 'not like', '%'.$todayDate.'%')->where('store_open_days', 'like', '%'.$todayDay.'%')->where('store_open', '<', $currentTime)->where('store_close', '>', $currentTime)->where('s_activ','=','1')->where('store_type','=',$companytype2)->with('products');


            $results = $latLngList->union($latLngList1)->get();

        dd($todayDay);
        return $latLngList;
    }

    public static function getRestaurantsList($latitude,$longitude,$radius,$companytype1,$companytype2)
    {
  
        $circle_radius = 6378.10;
        $max_distance = $radius;
        $unit = 6378.10;
        $lat = (float) $latitude;
        $lng = (float) $longitude;
        $radius = (double) $radius;
        
        $latLngList = Store::having('distance','<=',$radius)->select("*",DB::raw("
                        ($unit * ACOS(COS(RADIANS(".$lat."))
                            * COS(RADIANS(latitude))
                            * COS(RADIANS(".$lng.") - RADIANS(longitude))
                            + SIN(RADIANS(".$lat."))
                            * SIN(RADIANS(latitude)))) AS distance")
            )->join('company', 'company.u_id', '=', 'store.u_id')->where('s_activ','=','1')->where('store_type','=',$companytype1)->orWhere('store_type','=',$companytype2)->with('products')->get();
    	return $latLngList;
    }

    public static function getListRestaurants($latitude,$longitude,$radius,$companytype1,$companytype2,$todayDate,$currentTime,$todayDay)
    {
    	$circle_radius = 6378.10;
		$max_distance = $radius;
		$unit = 6378.10;
	    $lat = $latitude;
	    $lng = $longitude;
	    $radius = $radius;

        $latLngList = Store::having('distance','<=',$radius)->select("*",DB::raw("
                        ($unit * ACOS(COS(RADIANS(".$lat."))
                            * COS(RADIANS(latitude))
                            * COS(RADIANS(".$lng.") - RADIANS(longitude))
                            + SIN(RADIANS(".$lat."))
                            * SIN(RADIANS(latitude)))) AS distance")
            )->join('company', 'company.u_id', '=', 'store.u_id')->where('store_close_dates', 'not like', '%'.$todayDate.'%')->where('store_open_days', 'like', '%'.$todayDay.'%')->where('store_open', '<', $currentTime)->where('store_close', '>', $currentTime)->where('s_activ','=','1')->where('store_type','=',$companytype1)->with('products');

        $latLngList1 = Store::having('distance','<=',$radius)->select("*",DB::raw("
                        ($unit * ACOS(COS(RADIANS(".$lat."))
                            * COS(RADIANS(latitude))
                            * COS(RADIANS(".$lng.") - RADIANS(longitude))
                            + SIN(RADIANS(".$lat."))
                            * SIN(RADIANS(latitude)))) AS distance")
            )->join('company', 'company.u_id', '=', 'store.u_id')->where('store_close_dates', 'not like', '%'.$todayDate.'%')->where('store_open_days', 'like', '%'.$todayDay.'%')->where('store_open', '<', $currentTime)->where('store_close', '>', $currentTime)->where('s_activ','=','1')->where('store_type','=',$companytype2)->with('products');


        $results = $latLngList->union($latLngList1)->get();
    	return $results;
    }

    public function products()
    {
    	return $this->hasMany('App\Product','company_id','company_id');
    }

     public function companies()
    {
    	return $this->hasMany('App\Company','company_id','company_id');
    }
}
