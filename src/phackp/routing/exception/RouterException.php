<?php

/**
 * Created by IntelliJ IDEA.
 * User: yuri.blanc
 * Date: 17/07/2017
 * Time: 14:06
 */
class RouterException extends RuntimeException
{

    const NOT_FOUND = 404;

    /**
     * RouterException constructor.
     * @param string|null $message
     * @param int $code
     * @param Throwable $throwable
     */
    public function __construct(string $message=null, int $code, Throwable $throwable)
    {
        parent::__construct($message,$code,$throwable);
    }
}