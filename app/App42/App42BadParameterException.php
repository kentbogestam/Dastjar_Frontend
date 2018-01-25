<?php 
namespace App\App42;
/*  File Name : App42BadParameterException.php
 *  Description : To calculate charges
 *  Author : Sushil Singh  04-02-2011
 */
 
use App\App42\App42Exception;

class App42BadParameterException extends App42Exception{
	
	
        /**
         * Constructor which takes message, httpErrorCode and the appErrorCode
	 * @param detailMessage
         * @param httpErrorCode
         * @param appErrorCode
	 */
	public function __construct($detailMessage, $httpErrorCode, $appErrorCode) {
		parent::__construct($detailMessage, $httpErrorCode, $appErrorCode);
	}
        
}

?>
