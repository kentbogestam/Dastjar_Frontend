<?php

public static function getListRestaurants($latitude,$longitude,$radius,$companytype1,$companytype2,$todayDate,$currentTime,$todayDay)
    {
        if($radius == null){
            $radius = 10;
        }

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
            )->join('company', 'company.u_id', '=', 'store.u_id')->where('store_close_dates', 'not like', '%'.$todayDate.'%')->where('store_open_close_day_time', 'like', '%'.$todayDay.'%')->where('s_activ','=','1')->where('store_type','=',$companytype1)->with('products')->union(Store::having('distance','<=',$radius)->select("*",DB::raw("
                        ($unit * ACOS(COS(RADIANS(".$lat."))
                            * COS(RADIANS(latitude))
                            * COS(RADIANS(".$lng.") - RADIANS(longitude))
                            + SIN(RADIANS(".$lat."))
                            * SIN(RADIANS(latitude)))) AS distance")
            )->join('company', 'company.u_id', '=', 'store.u_id')->where('store_close_dates', 'not like', '%'.$todayDate.'%')->where('store_open_close_day_time', 'like', '%'."All".'%')->where('s_activ','=','1')->where('store_type','=',$companytype1)->with('products'))->union(Store::having('distance','<=',$radius)->select("*",DB::raw("
                        ($unit * ACOS(COS(RADIANS(".$lat."))
                            * COS(RADIANS(latitude))
                            * COS(RADIANS(".$lng.") - RADIANS(longitude))
                            + SIN(RADIANS(".$lat."))
                            * SIN(RADIANS(latitude)))) AS distance")
            )->join('company', 'company.u_id', '=', 'store.u_id')->where('store_close_dates', 'not like', '%'.$todayDate.'%')->where('store_open_close_day_time', 'like', '%'.$todayDay.'%')->where('s_activ','=','1')->where('store_type','=',$companytype2)->with('products'))->union(Store::having('distance','<=',$radius)->select("*",DB::raw("
                        ($unit * ACOS(COS(RADIANS(".$lat."))
                            * COS(RADIANS(latitude))
                            * COS(RADIANS(".$lng.") - RADIANS(longitude))
                            + SIN(RADIANS(".$lat."))
                            * SIN(RADIANS(latitude)))) AS distance")
            )->join('company', 'company.u_id', '=', 'store.u_id')->where('store_close_dates', 'not like', '%'.$todayDate.'%')->where('store_open_close_day_time', 'like', '%'.'All'.'%')->where('s_activ','=','1')->where('store_type','=',$companytype2)->with('products'))->get();

        // $latLngList = Store::having('distance','<=',$radius)->select("*",DB::raw("
        //                 ($unit * ACOS(COS(RADIANS(".$lat."))
        //                     * COS(RADIANS(latitude))
        //                     * COS(RADIANS(".$lng.") - RADIANS(longitude))
        //                     + SIN(RADIANS(".$lat."))
        //                     * SIN(RADIANS(latitude)))) AS distance")
        //     )->join('company', 'company.u_id', '=', 'store.u_id')->where('store_close_dates', 'not like', '%'.$todayDate.'%')->where('store_open_days', 'like', '%'.$todayDay.'%')->where('store_open', '<', $currentTime)->where('store_close', '>', $currentTime)->where('s_activ','=','1')->where('store_type','=',$companytype1)->with('products');

        // $latLngList1 = Store::having('distance','<=',$radius)->select("*",DB::raw("
        //                 ($unit * ACOS(COS(RADIANS(".$lat."))
        //                     * COS(RADIANS(latitude))
        //                     * COS(RADIANS(".$lng.") - RADIANS(longitude))
        //                     + SIN(RADIANS(".$lat."))
        //                     * SIN(RADIANS(latitude)))) AS distance")
        //     )->join('company', 'company.u_id', '=', 'store.u_id')->where('store_close_dates', 'not like', '%'.$todayDate.'%')->where('store_open_days', 'like', '%'.$todayDay.'%')->where('store_open', '<', $currentTime)->where('store_close', '>', $currentTime)->where('s_activ','=','1')->where('store_type','=',$companytype2)->with('products');


        // $results = $latLngList->union($latLngList1)->get();
    	return $latLngList;
    }