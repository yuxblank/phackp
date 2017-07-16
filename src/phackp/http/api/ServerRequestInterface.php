<?php
namespace yuxblank\phackp\http\api;
/**
 * Extends \Psr\Http\Message\ServerRequestInterface adding getPathParams.
 * Since usually REST uri's are build in a dynamic way, that the Psr interface don't cover.
 * Interface ServerRequestInterface
 * @package yuxblank\phackp\http\api
 */
interface ServerRequestInterface extends \Psr\Http\Message\ServerRequestInterface
{
    public function getPathParams();

}