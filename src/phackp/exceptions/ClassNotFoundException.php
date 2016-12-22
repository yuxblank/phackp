<?php
/**
 * Created by IntelliJ IDEA.
 * User: yuri.blanc
 * Date: 22/12/2016
 * Time: 14:41
 */

namespace yuxblank\phackp\exceptions;


use Herrera\Json\Exception\Exception;

class ClassNotFoundException extends Exception
{
    const CONTROLLER = 1;
    public function __construct($message, $code, \Exception $previous = null)
    {
        \Exception::__construct($message, $code, $previous);
    }

    public function __toString()
    {
        \Exception::__toString(); // TODO: Change the autogenerated stub
    }


}