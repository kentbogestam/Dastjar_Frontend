<?php
namespace App\App42;

use App\App42\Image;
use App\App42\App42ResponseBuilder;

/**
 *
 * ImageProcessResponseBuilder class converts the JSON response retrieved from
 * the server to the value object i.e Image
 *
 */
class ImageProcessorResponseBuilder extends App42ResponseBuilder {

    /**
     * Converts the response in JSON format to the value object i.e Image
     *
     * @param json
     *            - response in JSON format
     *
     * @return Image object filled with json data
     *
     */
    public function buildResponse($json) {
        $imageJSONObj = $this->getServiceJSONObject("image", $json);
        $imageObj = new Image();
        $imageObj->setStrResponse($json);
        $imageObj->setResponseSuccess($this->isRespponseSuccess($json));
        $this->buildObjectFromJSONTree($imageObj, $imageJSONObj);
        return $imageObj;
    }

}
?>