<?php
namespace App\App42;
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
use App\App42\JSONObject;
use App\App42\ABTesting;
use App\App42\App42ResponseBuilder;
/**
 *
 * UserResponseBuilder class converts the JSON response retrieved from the
 * server to the value object i.e User
 *
 */
class ABTestResponseBuilder extends App42ResponseBuilder {

    /**
     * Converts the response in JSON format to the value object i.e User
     *
     * @params json
     *            - response in JSON format
     *
     * @return User object filled with json data
     *
     */
    public function buildResponse($json) {
        $abTestJSONObj = $this->getServiceJSONObject("abtest", $json);
        $abtest = $this->buildABTestObject($abTestJSONObj);
        $abtest->setStrResponse($json);
        $abtest->setResponseSuccess($this->isRespponseSuccess($json));
        return $abtest;
    }

    /**
     * Converts the User JSON object to the value object i.e User
     *
     * @param userJSONObj
     *            - user data as JSONObject
     *
     * @return User object filled with json data
     *
     */
    private function buildABTestObject($abTestJSONObj) {
        $abTester = new ABTesting();
        $this->buildObjectFromJSONTree($abTester, $abTestJSONObj);
         if ($abTestJSONObj->has("variant")) {
            $profileJSONObj = $abTestJSONObj->__get("variant");
            $variant = new TestVariants($abTester);
            $this->buildObjectFromJSONTree($variant,$profileJSONObj);
            $variant->setProfileJSON($profileJSONObj->__get("profile"));
        }
         return $abTester;
    }
}
?>
