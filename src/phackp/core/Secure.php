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
    private $cripto;
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Return an instance of Crypto class.
     * @return Crypto
     */
    private function crypto() {
        if ($this->cripto === null) {
            $this->cripto = new Crypto();
        }
        return $this->cripto;
    }
    /**
     * Encrypt a password string 
     * @param string $password
     * @return string
     */
    public  function encryptPassword($password) {
        return $this->crypto()->generateHash($password);
    }
    /**
     * 
     * @param string $password
     * @param string $string
     * @return Crypto
     */
    public  function comparePassword($password,$string) {
        return $this->crypto()->checkHash($password,$string);
    }

   
    
}
