<?php
namespace yuxblank\phackp\services\exceptions;
/**
 * Class ServiceProviderException
 * Exception thrown by pHackp ServiceProvider implementation
 * @package yuxblank\phackp\services\exceptions
 */
class ServiceProviderException extends \Exception
{
    /** trying to get a not registered service */
    const REQUIRE_UNREGISTERED = 2;
    /** ServiceProvider configuration does not satisfy the Provider */
    const INVALID_CONFIG = 24;
    /** The class is not a provider */
    const NOT_A_PROVIDER = 48;
    /** The provider does not support auto-boot */
    const NOT_AUTO_BOOT = 64;
    /** The provider has an invalid status inside the ContainerInterface implementation */
    const DI_ERROR = 128;

    public function __construct(string $class, $code=null, \Exception $previous=null)
    {
        parent::__construct($class, $code, $previous);
    }

    public function __toString()
    {
       return parent::__toString();
    }


}