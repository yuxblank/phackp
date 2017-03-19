<?php
namespace yuxblank\phackp\core;
use Psr\Http\Message\ServerRequestInterface;

/**
 * This class is a the pHackp framework controllers superclass.
 * Contains all the methods and imports required for using the framework built-in controllers functions.
 * @author Yuri Blanc
 * @package yuxblank\phackp\core
 * @version 0.1
 * @since 0.1
 */
class Controller {

    protected $request;

    public function __construct(ServerRequestInterface $request=null) {
        $this->request = $request;
        defined('pHackpRuntime') or die ('.:: pHackp runtime never initiated! - invalid access to resources ::. ');
    }


    /**
     * Set a cookie to be used as a flash. This type of cookie is used to preserve data across request. 
     * As default the cookie duration is limited to 1s but can be overridden using the param expire. (in seconds)
     * @param string $name
     * @param string $value
     * @param int $expire in seconds
     */
    public static function keep($name,$value,$expire=null) {
        if (!isset($expire)) {
            $expire = time()+1; //default
        } else {
            $expire = time() + $expire;
        }
        setcookie($name, $value, $expire);
    
    }
     /**
     * 
     * @param array $data
     * @param 3const $options
     */
    public static function renderJSON($data, $options=null) {
        header('Content-Type: application/json');
        echo json_encode($data, $options);
    }



    
    
}
