<?php
namespace App\App42;
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use App\App42\JSONObject;

class GeoQuery {

    private $jsonObject;
    private $jsonArray;

    public function Query($jsonQuery) {

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
