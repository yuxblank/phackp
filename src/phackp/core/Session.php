<?php
namespace yuxblank\phackp\core;
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
        if ($this->exist($name)) { //&& $this->checkValidity($this->token)  ) {
            return $_SESSION[$name];
        }
    }

    public function exist($name)
    {
        return isset($_SESSION['$name']);
    }

    private function init()
    {
        session_name($this->name);
        session_start();
        if (Application::getConfig()['SESSION']['USE_COOKIES']) {
            setcookie(
                Application::getConfig()['SESSION']['NAME'],
                session_id(),
                time() + Application::getConfig()['SESSION']['LIFETIME'],
                '/',
                $this->cookie['DOMAIN'],
                $this->cookie['SECURE'],
                $this->cookie['HTTP_ONLY']
            );
        }
    }

    private function checkValidity($token)
    {
        if ($this->useCookies) {
            $this->sameDomain();
        }

    }

    private function sameDomain()
    {
        if ($this->cookie['DOMAIN'] === $_SERVER['HTTP_HOST']) {
            return true;
        } else {
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

    private static function _init()
    {
        if (!isset($_SESSION)) {
            session_name(Application::getConfig()['SESSION']['NAME']);
            session_start();
            if (Application::getConfig()['SESSION']['USE_COOKIES']) {
                setcookie(
                    Application::getConfig()['SESSION']['NAME'],
                    session_id(),
                    time() + Application::getConfig()['SESSION']['LIFETIME'],
                    '/',
                    Application::getConfig()['SESSION']['COOKIE']['DOMAIN'],
                    Application::getConfig()['SESSION']['COOKIE']['SECURE'],
                    Application::getConfig()['SESSION']['COOKIE']['HTTP_ONLY']
                );
            }
        }
    }

    public static function _setValue($name, $object)
    {
        self::_init();
        $_SESSION[$name] = $object;

    }

    public static function _getValue($name)
    {
        self::_init();
        if (self::_exist($name)) {
            return $_SESSION[$name];
        }

    }

    public static function _exist($name)
    {
        return isset($_SESSION[$name]);
    }

    public static function _stop()
    {
        session_unset();
    }

    public static function regenerate()
    {
        session_regenerate_id(true);
    }


}
