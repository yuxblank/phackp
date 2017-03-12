<?php
/**
 * Created by IntelliJ IDEA.
 * User: TheCo
 * Date: 12/03/2017
 * Time: 12:51
 */

namespace yuxblank\phackp\services\exceptions;


use Exception;

class PhackpRuntimeException extends \RuntimeException
{
    public function __construct($message = "", $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }


}