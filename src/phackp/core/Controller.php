<?php
namespace yuxblank\phackp\core;
use yuxblank\phackp\core\api\{
    ApplicationController
};

/**
 * This class is a the pHackp framework controllers superclass.
 * Contains all the methods and imports required for using the framework built-in controllers functions.
 * @author Yuri Blanc
 * @package yuxblank\phackp\core
 * @version 0.1
 * @since 0.1
 */
abstract class Controller implements ApplicationController {


    public function __construct() {
        defined('pHackpRuntime') or die ('.:: pHackp runtime never initiated! - invalid access to resources ::. ');
    }

    public abstract function onBefore();

    public abstract function onAfter();


    /**
     * Set a cookie to be used as a flash. This type of cookie is used to preserve data across request. 
     * As default the cookie duration is limited to 1s but can be overridden using the param expire. (in seconds)
     * @param string $name
     * @param string $value
     * @param int $expire in seconds
     */
    public function keep($name,$value,$expire=null) {
        if ($expire!==null) {
            $expire = time()+1; //default
        } else {
            $expire = time() + $expire;
        }
        setcookie($name, $value, $expire);
    
    }




    
    
}
