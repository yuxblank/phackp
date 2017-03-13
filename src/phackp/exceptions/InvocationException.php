<?php
/**
 * Created by IntelliJ IDEA.
 * User: yuri.blanc
 * Date: 22/12/2016
 * Time: 14:41
 */

namespace yuxblank\phackp\exceptions;




class InvocationException extends \RuntimeException
{
    const ROUTER = 0;
    const SERVICE = 1;
    public function __construct($message, $code=null, \Exception $previous = null)
    {
        \Exception::__construct($message, $code, $previous);
    }



}