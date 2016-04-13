<?php
namespace yuxblank\phackp\core;
/*
 * Copyright (C) 2015 yuri.blanc
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * This class is a the PlayPHP framework controllers superclass. 
 * Contains all the methods and imports required for using the framework built-in controllers functions.
 * @author yuri.blanc
 * @version 0.1
 * @since 0.1
 */
class Controller {
    private static $session;
    
    public function __construct() {
        defined('pHackpRuntime') or die ('.:: pHackp runtime never initiated! - invalid access to resources ::. ');
    }
    /**
     * @static
     * Return the current session object.
     * @return Sessions
     */
    public static function getSessionInstance() {
        if (self::$session == null) {
            self::$session = new Sessions ();
        }
        return self::$session;
    }
    /**
     * Set a new session variable with name and object content.
     * @static
     * @param string $name
     * @param mixed $object
     */
    public static function setSession($name, $object) {
        self::getSessionInstance()->setSession($name, $object);
    }
    /**
     * Return current session objects from name.
     * @static
     * @param string $name
     * @return Sessions
     */
    public static function getSession($name) {
        return self::getSessionInstance()->getSession($name);
    }
    /**
     * Stop the current sessions and unset all variables.
     */
    public static function stopSession() {
        self::getSessionInstance()->stop();
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
