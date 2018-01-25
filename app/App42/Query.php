<?php
namespace App\App42;

use App\App42\JSONObject;

class Query {

    private $jsonObject;
    private $jsonArray;

     public function __construct($jsonQuery) {
       
        if ($jsonQuery instanceof JSONObject) {

            $objectArray = array();
            array_push($objectArray, $jsonQuery);
            return $this->jsonObject = $objectArray;
        } else {
            return $this->jsonArray = $jsonQuery;
        }
    }
}

?>
