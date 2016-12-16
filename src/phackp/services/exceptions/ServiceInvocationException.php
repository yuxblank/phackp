<?php
namespace yuxblank\phackp\services\exceptions;

class ServiceInvocationException extends \Exception
{
    const REQUIRE_UNREGISTERED = "The service was not registered";

    public function __construct(string $class, $code=null, \Exception $previous=null)
    {
        parent::__construct($class, $code, $previous);
    }

    public function __toString()
    {
        parent::__toString();
    }


}