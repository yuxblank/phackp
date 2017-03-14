<?php
/**
 * Created by IntelliJ IDEA.
 * User: TheCo
 * Date: 12/03/2017
 * Time: 12:51
 */

namespace yuxblank\phackp\services\exceptions;


use Exception;

/**
 * Class PhackpRuntimeException
 * Runtime exception thrown by pHackp framework
 * @package yuxblank\phackp\services\exceptions
 */
class PhackpRuntimeException extends \RuntimeException
{
    const CONTAINER_ERROR = 999;
    public function __construct($message = "", $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }


}