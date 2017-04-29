<?php
namespace yuxblank\phackp\core;
/**
 * Session Wrapper class
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
    }


    public function setValue($name, $object)
    {
        $this->init();
        $_SESSION[$name] = $object;
    }

    public function getValue($name)
    {
        $this->init();
        if ($this->exist($name)) { //&& $this->checkValidity($this->token)  ) {
            return $_SESSION[$name];
        }
        return null;
    }

    public function exist($name)
    {
        $this->init();
        return $this->isSession() && isset($_SESSION[$name]);
    }

    public function init()
    {
        if (!$this->isSession()) {
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
    }

    private function checkValidity($token)
    {
        if ($this->useCookies) {
            $this->sameDomain();
        }

    }

    private function sameDomain()
    {
        return $this->cookie['DOMAIN'] === $_SERVER['HTTP_HOST'];
    }


    public function stop()
    {
        $this->init();
        session_unset();
    }

    public function isSession():bool{
        return session_status() !== PHP_SESSION_NONE;
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
