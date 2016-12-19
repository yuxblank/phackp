<?php
namespace yuxblank\phackp\services\exceptions;

class ServiceInvocationException extends \Exception
{
    const REQUIRE_UNREGISTERED = 1;

    public function __construct(string $class, $code=null, \Exception $previous=null)
    {
        parent::__construct($class, $code, $previous);
    }

    public function __toString()
    {
       return parent::__toString();
    }


}