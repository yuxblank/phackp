<?php
/**
 * Created by IntelliJ IDEA.
 * User: TheCo
 * Date: 20/09/2017
 * Time: 20:12
 */

namespace yuxblank\phackp\view\exception;


use Throwable;

class ViewException extends \Exception
{

    const CONFIGURATION_ERROR = 0;

    /**
     * ViewException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = '', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message,$code,$previous);
    }
}