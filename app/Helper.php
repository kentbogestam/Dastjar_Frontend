<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use DB;
use Session;

use \Gumlet\ImageResize;

use App\Order;

// 
use App\App42\PushNotificationService;
use App\App42\DeviceType;
use App\App42\App42Log;
use App\App42\App42Exception;
use App\App42\App42NotFoundException;
use App\App42\App42BadParameterException;
use App\App42\StorageService;
use App\App42\QueryBuilder;
use App\App42\Query;
use App\App42\App42API;
use App\App42\Util;

class Helper extends Model
{
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

    /* This is a general function which we are using to generate 36 Char unique id. */
    function uuid()
    {
        $chars = md5(uniqid(mt_rand(), true));
        $uuid  = substr($chars,0,8) . '-';
        $uuid .= substr($chars,8,4) . '-';
        $uuid .= substr($chars,12,4) . '-';
        $uuid .= substr($chars,16,4) . '-';
        $uuid .= substr($chars,20,12);
        return $uuid;
    }

    function is_image($path)
    {
        $a = getimagesize($path);
        $image_type = $a[2];
        
        if(in_array($image_type , array(IMAGETYPE_GIF , IMAGETYPE_JPEG ,IMAGETYPE_PNG , IMAGETYPE_BMP)))
        {
            return true;
        }
        return false;
    }

    //this function convert string to UTC time zone
    function convertTimeToUTCzone($str, $userTimezone, $format = 'Y-m-d H:i:s'){
            
        $new_str = new \DateTime($str, new \DateTimeZone(  $userTimezone  ) );
        $new_str->setTimeZone(new \DateTimeZone('UTC'));
        return $new_str->format( $format);
    }

    function logs($str = ""){
            $myfile = fopen(public_path() . "/upload/images/log" . Carbon::now()->format('Ymd') . ".txt", "a") or die("Unable to open file!");
            $txt = Carbon::now() . " - " . $str . "  \n";
            fwrite($myfile, $txt);
            fclose($myfile);
    }

    /**
     * Function to generate alphanumeric random number
     * @param  [type] $size [description]
     * @return [type]       [description]
     */
    function random_num($size) {
        $alpha_key = '';
        $keys = range('A', 'Z');

        for ($i = 0; $i < 3; $i++) {
            $alpha_key .= $keys[array_rand($keys)];
        }

        $length = $size - 3;

        $key = '';
        $keys = range(0, 9);

        for ($i = 0; $i < $length; $i++) {
            $key .= $keys[array_rand($keys)];
        }

        return $alpha_key . $key;
    }

    /**
     * Image resize using 'gumlet/php-image-resize' lib
     * @return [type] [description]
     */
    function gumletImageResize($tmpName, $fileName, $path, $w, $h = '', $imgType = IMAGETYPE_JPEG)
    {
        // If doesn't exist directory, create one
        if( !file_exists($path) )
        {
            mkdir($path, 0755, true);
        }

        // 
        $image = new ImageResize($tmpName);
        $image->resizeToWidth($w)->save($path.$fileName, $imgType);
        // ->save(BASEPATH.'upload/store_image/image1.jpg', $imgType);
        
        return $fileName;
    }

    // Return user address
    public static function convertAddressToStr($address)
    {
        $arr = array();

        if( !empty($address->full_name) )
        {
            array_push($arr, $address->full_name);
        }

        if( !empty($address->address) )
        {
            array_push($arr, $address->address);
        }

        if( !empty($address->street) )
        {
            array_push($arr, $address->street);
        }

        if( !empty($address->landmark) )
        {
            array_push($arr, $address->landmark);
        }

        if( !empty($address->city) )
        {
            array_push($arr, $address->city);
        }

        if( !empty($address->zipcode) )
        {
            array_push($arr, $address->zipcode);
        }

        return implode(', ', $arr);
    }

    // Check if package is subscribed
    public static function isPackageSubscribed($packageId)
    {
        // 
        $status = false;
        $date = date('Y-m-d H:i:s'); $storeId = Session::get('storeId');

        // 
        if($packageId && $storeId)
        {
            $query = "SELECT bp.id, bpp.package_id FROM billing_products bp INNER JOIN billing_product_packages bpp ON bp.id = bpp.billing_product_id INNER JOIN anar_packages AP ON AP.id = bpp.package_id INNER JOIN user_plan UP ON (bp.plan_id = UP.plan_id AND UP.store_id='{$storeId}' AND UP.subscription_start_at <= '{$date}' AND UP.subscription_end_at >= '{$date}') WHERE bp.s_activ != 2 AND AP.id = '{$packageId}' AND AP.status = '1'";
            $res = DB::select($query);
            
            if($res)
            {
                $status = true;
            }
        }

        return $status;
    }

    /**
     * Send text message to recipients using API
     * @return [type] [description]
     */
    public static function apiSendTextMessage($recipients = array(), $message = '')
    {
        if( !is_array($recipients) && empty($recipients) )
        {
            return false;
        }

        //
        $url = "https://gatewayapi.com/rest/mtsms";
        $api_token = "BP4nmP86TGS102YYUxMrD_h8bL1Q2KilCzw0frq8TsOx4IsyxKmHuTY9zZaU17dL";
        
        $json = [
            'sender' => 'Dastjar',
            'message' => ''.$message.'',
            'recipients' => [],
        ];

        foreach ($recipients as $msisdn)
        {
            $json['recipients'][] = ['msisdn' => $msisdn];
        }

        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
        curl_setopt($ch,CURLOPT_USERPWD, $api_token.":");
        curl_setopt($ch,CURLOPT_POSTFIELDS, json_encode($json));
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }

    // 
    public static function sendNotifaction($userName, $message)
    {
        try{
            App42API::initialize(env('APP42_API_KEY'),env('APP42_API_SECRET'));
            $pushNotificationService = App42API::buildPushNotificationService();
            $pushNotification = $pushNotificationService->sendPushMessageToUser($userName, $message);

            return $pushNotification;
        } catch(\Exception $e){
            return $e->getMessage();
        }
    }
}
