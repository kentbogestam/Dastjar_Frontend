<?php 
namespace App\App42;
/*  File Name : ConfigurationException.php
 *  Description : To calculate charges
 *  Author : Sushil Singh  04-02-2011
 */

use App\App42\App42Exception;

class ConfigurationException extends App42Exception{
	
	
	/**
     * Constructor which takes message
	 * @param Message
	 */
	public function ConfigurationException($message){
        parent::__construct($message);
    }

    
}

?>
