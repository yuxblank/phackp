<?php
namespace yuxblank\phackp\services\exceptions;

class ServiceProviderException extends \Exception
{
    const REQUIRE_UNREGISTERED = 1;
    const INVALID_CONFIG = 24;
    const NOT_A_PROVIDER = 48;
    const NOT_AUTO_BOOT = 64;

    public function __construct(string $class, $code=null, \Exception $previous=null)
    {
        parent::__construct($class, $code, $previous);
    }

    public function __toString()
    {
       return parent::__toString();
    }


}