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
    public function __construct(array $config)
    {
        $this->lifetime = $config['LIFETIME'];
        if ($config['USE_COOKIES']) {
            $this->useCookies = true;
            $this->cookie = $config['COOKIE'];
        }
        $this->name = $config['NAME'];
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
        return isset($_SESSION[$name]);
    }

    private function init()
    {
        session_name($this->name);
        session_start();
        if ($this->useCookies) {
            setcookie(
                $this->name,
                session_id(),
                time() + $this->lifetime,
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


}
