<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Helper extends Model
{
    //
    public static function getLocation($address)
    {
        $address = str_replace(' ', '+', $address);
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL,"https://maps.googleapis.com/maps/api/geocode/json?address=".$address."&key=".'AIzaSyByLiizP2XW9JUAiD92x57u7lFvU3pS630');
        
        curl_setopt($ch, CURLOPT_POST, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $serverOutput = curl_exec ($ch);

        curl_close ($ch);
        
        if (empty(json_decode($serverOutput, true)['results']) || json_decode($serverOutput, true)['status'] == 'ZERO_RESULTS') {
            return false;
        }

        $response = json_decode($serverOutput, true)['results'][0];

        $result = array(
            "locality" => null,
            "state" => null,
            "country" => null,
            "postal_code" => null,
            "street_address" => $response["formatted_address"],
            "latitude" => $response["geometry"]["location"]["lat"],
            "longitude" => $response["geometry"]["location"]["lng"],
        );

        foreach($response['address_components'] as $component) {
            if(isset($component["types"][0])) {
                $result["locality"] = ($component["types"][0] == "locality") ? $component["long_name"] : $result['locality'];
                $result["state"] = ($component["types"][0] == "administrative_area_level_1") ? $component["long_name"] : $result['state'];
                $result["country"] = ($component["types"][0] == "country") ? $component["long_name"] : $result['country'];
                $result["postal_code"] = ($component["types"][0] == "postal_code") ? $component["long_name"] : $result['postal_code'];
            }
        }
        return $result;
    }
}
