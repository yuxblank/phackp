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
class Session
{
    private $name;
    private $token;
    private $lifetime;
    private $cookie;
    private $useCookies;

    public function __construct()
    {

        $this->lifetime = Application::getConfig()['SESSION']['LIFETIME'];

        if (Application::getConfig()['SESSION']['USE_COOKIES']) {
            $this->useCookies = true;
            $this->cookie = Application::getConfig()['SESSION']['COOKIE'];
        }
        $this->name = Application::getConfig()['SESSION']['NAME'];


        if (!isset($_SESSION)) {
            $this->init();
        }
    }

    public function setValue($name, $object)
    {
        $_SESSION[$name] = $object;
    }

    public function getValue($name)
    {
        if (array_key_exists($name,$_SESSION)){ //&& $this->checkValidity($this->token)  ) {
            return $_SESSION[$name];
        }
    }

    public function exist($name) {
        return array_key_exists($name, $_SESSION);
    }

    private function init()
    {
        if (session_id() === '' && session_start()) {
            session_name($this->name);
            if(session_get_cookie_params()===null) {
                if ($this->lifetime !== null
                    && $this->cookie !== null
                    && Application::getConfig()['SESSION']['USE_COOKIES']
                ) {
                    session_set_cookie_params(
                        $this->lifetime,
                        $this->cookie['PATH'],
                        $this->cookie['DOMAIN'],
                        $this->cookie['SECURE'],
                        $this->cookie['HTTP_ONLY']
                    );
                }
            }
        }
    }

    private function checkValidity()
    {
        if ($this->useCookies){
            $this->sameDomain();
        }

    }


    private function sameDomain() {
        if ($this->cookie['DOMAIN'] === $_SERVER['HTTP_HOST']) {
            return true;
        }
        else {
            return false;
        }
    }


    public function stop()
    {
        $this->init();
        session_unset();
    }


    private function setToken()
    {
        $size = mcrypt_get_iv_size(MCRYPT_CAST_256, MCRYPT_MODE_CFB);
        $iv = mcrypt_create_iv($size, MCRYPT_DEV_RANDOM);
        return bin2hex($iv);
    }

    public function getToken()
    {
        return $this->token;
    }

    private static function _init(){
        if (session_id()==='' && session_start()){

            if (session_get_cookie_params()===null) {
                $session = Application::getConfig()['SESSION'];
                if ($session['LIFETIME'] !== null
                    && $session['COOKIE'] !== null
                    && $session['USE_COOKIES']
                ) {
                    session_set_cookie_params(
                        $session['LIFETIME'],
                        $session['COOKIE']['PATH'],
                        $session['COOKIE']['DOMAIN'],
                        $session['COOKIE']['SECURE'],
                        $session['COOKIE']['HTTP_ONLY']
                    );
                }
            }
        }
    }

    public static function _setValue($name, $object){
        self::_init();
        $_SESSION[$name] = $object;

    }

    public static function _getValue($name){
        self::_init();
        if (isset($_SESSION[$name])){
            return $_SESSION[$name];
        }
        return false;
    }

    public static function _exist($name) {
        return isset($_SESSION[$name]);
    }

    public static function _stop()
    {
        if(session_id()!=='') {
            session_unset();
        }
    }



}
