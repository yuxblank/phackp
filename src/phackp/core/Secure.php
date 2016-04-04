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
 * Secure is a sub-class of Controller. it adds some security methods and dependecies. 
 * When your controller needs to use authentications or data encryption, extend your controller with Secure.
 *
 * @author yuri.blanc
 */
class Secure extends Controller {
    private static $cripto;
    public function __construct() {
        parent::__construct();
    }

    public static function getSession($name) {
        return parent::getSession($name);
    }

    public static function getSessionInstance() {
        return parent::getSessionInstance();
    }

    public static function keep($name, $value, $expire = null) {
        parent::keep($name, $value, $expire);
    }

    public static function renderJSON($data, $options = null) {
        parent::renderJSON($data, $options);
    }

    public static function setSession($name, $object) {
        parent::setSession($name, $object);
    }

    public static function stopSession() {
        parent::stopSession();
    }
    
    
    /**
     * Return an instance of Crypto class.
     * @return Crypto
     */
    private static function Crypto() {
        if (self::$cripto == null) {
            self::$cripto = new \PlayPHP\Classes\Security\Crypto();
        }
        return self::$cripto;
    }
    /**
     * Encrypt a password string 
     * @param string $password
     * @return string
     */
    public static function encryptPassword($password) {
        return self::Crypto()->generateHash($password);
    }
    /**
     * 
     * @param string $password
     * @param string $string
     * @return Crypto
     */
    public static function comparePassword($password,$string) {
        return self::Crypto()->checkHash($password,$string);
    }

   
    
}
