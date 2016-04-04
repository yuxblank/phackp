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
 * Description of Security
 *
 * @author yuri.blanc
 */
class Sessions {
    private $token;
    private $lifetime;// = SESSION_LIFETIME;
    
    function __construct() {
        
    }
    
    public function setSession($name, $object, $lifetime=null){
        if(isset($lifetime)){
            $this->lifetime = $lifetime;
        }
        $this->init();
        if ($this->checkValidity($this->token)) {
            $_SESSION[$name] = $object;
        }
        
    }
    
    public function getSession($name) {
        $this->init();
        if($this->checkValidity($this->token) && isset($_SESSION[$name])) {
            return $_SESSION[$name];
        }
    }
    

    
    private function init() {
            if(!session_id()) {
                if (isset($lifetime)) {
                    session_set_cookie_params($this->lifetime, "/");
                }
                session_start();
                $this->token =  $_SESSION['TOKEN'] = $this->createToken();
                //echo "session id: ".session_id()."<br>";
                // echo "session token: ".$_SESSION['TOKEN']."<br>";
            } else {
                //echo "session already set<br>";
            }
    }
    
    public function checkValidity($token) {
        if ($this->token == $token) {
            return true;
        } else {
            die("unvalid token");
            //return false;
        }
    }
    
    public function stop(){
        $this->init();
        session_unset();
     
    }
    
    
    private function createToken() {
        $size = mcrypt_get_iv_size(MCRYPT_CAST_256, MCRYPT_MODE_CFB);
        $iv = mcrypt_create_iv($size, MCRYPT_DEV_RANDOM);
        return  bin2hex($iv);
    }
    
    
    function getToken() {
        return $this->token;
    }

    function setToken($token) {
        $this->token = $token;
    }

   
}
