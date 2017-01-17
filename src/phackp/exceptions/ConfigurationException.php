<?php
/**
 * Created by IntelliJ IDEA.
 * User: yuri.blanc
 * Date: 17/01/2017
 * Time: 09:52
 */

namespace yuxblank\phackp\exceptions;



class ConfigurationException extends \Exception
{
    const INVALID_PATH = 1;

    public function __construct($message, $code, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}