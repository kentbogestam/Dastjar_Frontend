<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use DB;
use Session;

use \Gumlet\ImageResize;

use Auth;
use App\Order;
use App\DishType;
use App\Store;
use App\StoreVirtualMapping;
use App\ProductOfferSloganLangList;

// 
use Aws\Ses\SesClient;
use Aws\Exception\AwsException;

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

use Illuminate\Support\Facades\File;

class Helper extends Model
{
    public static function getProductName($product_id)
    {
        if(!empty(Auth::user()->language)){
            $lg = Auth::user()->language;
        }else{
            $lg = 'ENG';
        }
        $data = ProductOfferSloganLangList::where('product_id', $product_id)
                        ->pluck('offer_slogan_lang_list');
        $lang = LangText::whereIn('id',$data)->where('lang',$lg)->first();
        if(!empty($lang)){
            return $lang->text;
        }
        return 'empty';
    }

    public static function getProductDesc($product_id)
    {
        if(!empty(Auth::user()->language)){
            $lg = Auth::user()->language;
        }else{
            $lg = 'ENG';
        }
        $data = ProductOfferSubSloganLangList::where('product_id', $product_id)
                        ->pluck('offer_sub_slogan_lang_list');
        $lang = LangText::whereIn('id',$data)->where('lang',$lg)->first();
        if(!empty($lang)){
            return $lang->text;
        }
        return 'empty';
    }

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
        $image->gamma(false);
        $image->resizeToWidth($w)->save($path.$fileName, $imgType);
        // ->save(BASEPATH.'upload/store_image/image1.jpg', $imgType);
        
        return $fileName;
    }

    // Return custome address 'customer_addresses'
    public static function convertAddressToStr($address, $isComplete = 1)
    {
        $arr = array();

        if( !empty($address->full_name) )
        {
            array_push($arr, $address->full_name);
        }

        if($isComplete)
        {
            if( !empty($address->address) )
            {
                array_push($arr, $address->address);
            }
            else
            {
                if( !empty($address->entry_code) )
                {
                    array_push($arr, $address->entry_code);
                }

                if( !empty($address->apt_no) )
                {
                    array_push($arr, $address->apt_no);
                }

                if( !empty($address->company_name) )
                {
                    array_push($arr, $address->company_name);
                }

                if( !empty($address->other_info) )
                {
                    array_push($arr, $address->other_info);
                }
            }
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
    public static function isPackageSubscribed($packageId, $storeId = null)
    {
        // 
        $status = false;
        $date = Carbon::parse(Carbon::now())->format('Y-m-d');
        $storeId = !is_null($storeId) ? $storeId : Session::get('storeId');

        // 
        if($packageId && $storeId)
        {
            $query = "SELECT bp.id, bpp.package_id, USI.id AS aa FROM billing_products bp INNER JOIN billing_product_packages bpp ON bp.id = bpp.billing_product_id INNER JOIN anar_packages AP ON AP.id = bpp.package_id INNER JOIN user_subscription_items USI ON bp.plan_id = USI.plan_id INNER JOIN user_plan UP ON (USI.subscription_id = UP.subscription_id AND UP.store_id='{$storeId}' AND date(UP.subscription_start_at) <= '{$date}' AND date(UP.subscription_end_at) >= '{$date}') WHERE bp.s_activ = 1 AND AP.id = '{$packageId}' AND AP.status = '1'";
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

    /**
     * Get co-ordinate from address
     * @param  [type] $address [description]
     * @return [type]          [description]
     */
    public static function getCoordinates($address)
    {
        $url = "https://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($address)."&key=".'AIzaSyByLiizP2XW9JUAiD92x57u7lFvU3pS630';

        $response = Helper::getCurl($url);
        $response = json_decode($response);

        if($response->status == 'ZERO_RESULTS')
        {
            $data = false;
        }
        else
        {
            $data = array('lat' => $response->results[0]->geometry->location->lat, 'lng' => $response->results[0]->geometry->location->lng);
        }

        return $data;
    }

    /**
     * Get driving distance using address/latlng
     * @param  [type] $origin      [description]
     * @param  [type] $destination [description]
     * @param  string $from        [description]
     * @return [type]              [description]
     */
    public static function getDrivingDistance($origin, $destination, $from = 'address')
    {
        if($from == 'address')
        {
            $url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=".urlencode($origin)."&destinations=".urlencode($destination)."&mode=driving&language=pl-PL&key=".'AIzaSyByLiizP2XW9JUAiD92x57u7lFvU3pS630';
        }
        elseif($from == 'latlng')
        {
            $url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=".$origin['lat'].",".$origin['lng']."&destinations=".$origin['lat'].",".$origin['lng']."&mode=driving&language=pl-PL&key=".'AIzaSyByLiizP2XW9JUAiD92x57u7lFvU3pS630';
        }

        $response = Helper::getCurl($url);
        $response = json_decode($response, true);

        if($response['status'] == 'OK')
        {
            $data = $response['rows'][0]['elements'][0];
        }
        else
        {
            $data = false;
        }

        return $data;
    }

    /**
     * Call url using curl and return result
     * @param  [type] $url [description]
     * @return [type]      [description]
     */
    public static function getCurl($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }

    /**
     * Add multiple time and return format 'H:i:s'
     * @param [type] $times [description]
     */
    public static function addTimes($times)
    {
        $seconds = 0;

        if(is_array($times) && !empty($times))
        {
            foreach($times as $time)
            {
                $seconds += strtotime("1970-01-01 $time UTC");
            }
        }

        return gmdate("H:i", $seconds);
    }

    function getDishTypeTree($uId)
    {
        $dishType = array();

        // Level1
        $dishTypeLevel1 = $this->getdDishTypeBy($uId, null, 'rank');

        if($dishTypeLevel1)
        {
            foreach($dishTypeLevel1 as $level1)
            {
                $dishType[] = array(
                    'dish_id' => $level1->dish_id,
                    'dish_lang' => $level1->dish_lang,
                    'dish_name' => $level1->dish_name,
                    'rank' => $level1->rank,
                    'level' => 0
                );

                // Level2
                $dishTypeLevel2 = $this->getdDishTypeBy(null, $level1->dish_id, 'rank');
                
                if($dishTypeLevel2)
                {
                    foreach($dishTypeLevel2 as $level2)
                    {
                        $dishType[] = array(
                            'dish_id' => $level2->dish_id,
                            'dish_lang' => $level2->dish_lang,
                            'dish_name' => $level2->dish_name,
                            'rank' => $level2->rank,
                            'level' => 1
                        );

                        // Level3
                        $dishTypeLevel3 = $this->getdDishTypeBy(null, $level2->dish_id, 'rank');
                        
                        if($dishTypeLevel3)
                        {
                            foreach($dishTypeLevel3 as $level3)
                            {
                                $dishType[] = array(
                                    'dish_id' => $level3->dish_id,
                                    'dish_lang' => $level3->dish_lang,
                                    'dish_name' => $level3->dish_name,
                                    'rank' => $level3->rank,
                                    'level' => 2
                                );
                            }
                        }
                    }
                }
            }
        }

        return $dishType;
    }

    // 
    public function getdDishTypeBy($uId = null, $parentId = null, $orderBy = 'dish_id')
    {
        $dishType = DishType::select(['dish_id', 'dish_lang', 'dish_name', 'rank'])
            ->where(['dish_activate' => 1]);

        if( !is_null($uId) )
        {
            $dishType->where('u_id', $uId);
        }

        if( is_null($parentId) )
        {
            $dishType->where('parent_id', null);
        }
        else
        {
            $dishType->where('parent_id', $parentId);
        }

        $dishType->orderBy($orderBy);

        return $dishType->get();
    }

    // Get DishType level 0
    function getdDishTypeLevel0($dishId)
    {
        $dishTypeLevel0 = DishType::from('dish_type AS DT1')
            ->select(['DT1.dish_id', 'DT1.dish_name', 'DT1.dish_image', 'DT1.rank', 'DT1.extras'])
            ->leftJoin('dish_type AS DT2', 'DT2.parent_id', '=', 'DT1.dish_id')
            ->leftJoin('dish_type AS DT3', 'DT3.parent_id', '=', 'DT2.dish_id')
            ->whereRaw("(DT1.dish_id = '{$dishId}' OR DT2.dish_id = '{$dishId}' OR DT3.dish_id = '{$dishId}') AND DT1.parent_id IS NULL")
            ->groupBy('DT1.dish_id')
            ->first();

        if($dishTypeLevel0)
        {
            $dishType0 = $dishTypeLevel0->dish_id;
        }
        else
        {
            $dishType0 = $dishId;
        }

        return $dishType0;
    }

    /**
     * Add specific character into string
     * @param  string  $input  [description]
     * @param  integer $length [description]
     * @param  string  $string [description]
     * @return [type]          [description]
     */
    public static function strReplaceBy($input = '', $length = 0, $string = '&nbsp; ')
    {
        $str = '';
        for($i = 1; $i <= $length; $i++)
        {
            $str .= $string;
        }

        return $str.$input;
    }

    // Get store open/close time, e.g. All :: 02:00:00 to 07:00:00 / Mon :: 02:00:00 to 07:00:00
    public static function getStoreOpenCloseTime($store_open_close_day_time)
    {
        $openCloseTime = array();
        $storeOpenCloseTime = explode(",", $store_open_close_day_time);

        foreach($storeOpenCloseTime as $key => $value)
        {
            $getDay = explode("::",$value);
            $getDay[0] = str_replace(' ', '', $getDay[0]);

            if($getDay[0] == 'All' || $getDay[0] == 'Mon' || $getDay[0] == 'Tue' || $getDay[0] == 'Wed' || $getDay[0] == 'Thu' || $getDay[0] == 'Fri' || $getDay[0] == 'Sat' || $getDay[0] == 'Sun')
            {
                $getTime = explode("to",$getDay[1]);
                $openCloseTime[] = $getTime[0];
                $openCloseTime[] = $getTime[1];
            }
        }

        return $openCloseTime;
    }

    // Get all subscribed packages belongs to store
    public function getStorePackages($storeId = null)
    {
        // 
        $status = false;
        $date = Carbon::parse(Carbon::now())->format('Y-m-d');
        $storeId = !is_null($storeId) ? $storeId : Session::get('storeId');

        // 
        if($storeId)
        {
            $query = "SELECT bpp.package_id FROM billing_products bp INNER JOIN billing_product_packages bpp ON bp.id = bpp.billing_product_id INNER JOIN anar_packages AP ON AP.id = bpp.package_id INNER JOIN user_subscription_items USI ON bp.plan_id = USI.plan_id INNER JOIN user_plan UP ON (USI.subscription_id = UP.subscription_id AND UP.store_id='{$storeId}' AND date(UP.subscription_start_at) <= '{$date}' AND date(UP.subscription_end_at) >= '{$date}') WHERE bp.s_activ = 1 AND AP.status = '1' GROUP BY bpp.package_id";
            $res = DB::select($query);

            if($res)
            {
                foreach($res as $row)
                {
                    $status[] = $row->package_id;
                }
            }
            
            return $status;
        }

        return $status;
    }

    // Update store heartbeat
    public static function updateStoreIslive($store_id)
    {
        // Check if store has virtual store and update them heartbeat too
        $virtualStore = StoreVirtualMapping::select(['virtual_store_id'])
            ->where(['store_id' => $store_id])
            ->get();
        
        if($virtualStore)
        {
            foreach ($virtualStore as $row)
            {
                Store::where('store_id', $row->virtual_store_id)->update(['islive' => Carbon::now()->format('Y-m-d H:i:s')]);
            }
        }

        // Update store heartbeat
        Store::where('store_id', $store_id)->update(['islive' => Carbon::now()->format('Y-m-d H:i:s')]);
    }

    // Return the store heartbeat (in minute)
    public static function isStoreLive($storeId)
    {
        $heartbeat = Store::select([DB::raw("TIMESTAMPDIFF(MINUTE, islive, UTC_TIMESTAMP()) AS heartbeat")])
            ->where('store_id', $storeId)->first()->heartbeat;
        return $heartbeat;
    }

    // Upload all txt file from printdata directory to printer server
    public static function uploadPrintFile()
    {
        $PRINTER_BASE_URL = env('PRINTER_BASE_URL');
        $filePath = storage_path('app/printerdata');
        $files = File::files($filePath);

        // Check if files and printer server URL exist
        if(!empty($files) && $PRINTER_BASE_URL)
        {
            $fields = $arrFiles = array();

            foreach($files as $file)
            {
                $fields[] = array(
                    'fileName' => $file->getFilename(),
                    'fileData' => base64_encode( file_get_contents($file->getPathname()) )
                );

                $arrFiles[] = $file->getPathname();
            }

            $fieldsString = http_build_query($fields);

            // Post files to print server
            $uploadUrl = $PRINTER_BASE_URL.'/kitchen/handle-upload.php';
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $uploadUrl);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fieldsString);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

            $result = curl_exec($ch);
            curl_close($ch);

            // Delete files
            File::delete($arrFiles);

            return $result;
        }
    }

    // Send email thorugh AWS PHP SDK
    function awsSendEmail($to = array(), $subject = 'Test subject', $message = 'Test email')
    {
        if( is_array($to) && !empty($to) )
        {
            $SesClient = new SesClient([
                'version' => '2010-12-01',
                'region'  => env('AWS_DEFAULT_REGION'),
                // 'profile' => 'default',
                'credentials' => [
                    'key' => env('AWS_ACCESS_KEY_ID'),
                    'secret' => env('AWS_SECRET_ACCESS_KEY')
                ]
            ]);

            // This address must be verified with Amazon SES.
            $sender_email = 'admin@dastjar.com';
            
            $plaintext_body = 'This email was sent with Dastjar SES using the AWS SDK for PHP.';
            $char_set = 'UTF-8';

            try {
                $result = $SesClient->sendEmail([
                    'Destination' => [
                        'ToAddresses' => $to,
                    ],
                    'ReplyToAddresses' => [$sender_email],
                    'Source' => $sender_email,
                    'Message' => [
                      'Body' => [
                          'Html' => [
                              'Charset' => $char_set,
                              'Data' => $message,
                          ],
                          'Text' => [
                              'Charset' => $char_set,
                              'Data' => $plaintext_body,
                          ],
                      ],
                      'Subject' => [
                          'Charset' => $char_set,
                          'Data' => $subject,
                      ],
                    ],
                ]);

                return $result;
            } catch (AwsException $e) {
                // output error message if fails
                echo $e->getMessage();
                echo("The email was not sent. Error message: ".$e->getAwsErrorMessage()."\n");
                echo "\n";
            }
        }
    }

    public static function getDishTypeToOpen($id)
    {
        $dishType = Product::where('product_id', $id)->first()->dish_type;
        if(!empty($dishType)){
            $parent_id1 = DishType::where('dish_id',$dishType)->first()->parent_id;
            if(!empty($parent_id1)){
                $parent_id2 = DishType::where('dish_id',$parent_id1)->first()->parent_id;
                if(!empty($parent_id2)){
                    $parent_id3 = DishType::where('dish_id',$parent_id2)->first()->parent_id;
                    if(!empty($parent_id3)){
                        $parent_id3 = DishType::where('dish_id',$parent_id3)->first()->parent_id;
                        return $parent_id3;
                    }
                    return $parent_id2;
                }
                return $parent_id1;
            }
            return $dishType;
        }
        return '';
    }
}
