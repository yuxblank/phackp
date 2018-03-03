<?php
/**
 * Created by IntelliJ IDEA.
 * User: yux
 * Date: 24/02/18
 * Time: 15.30
 */

namespace yuxblank\phackp\core\api;
use Psr\Http\Message\ResponseInterface;
/**
 * Class LifeCycle
 * @package yuxblank\phackp\core
 */
interface LifeCycleInterface
{
    public function request();

    public function callController(string $method,...$params);

    public function response(ResponseInterface $response);
}