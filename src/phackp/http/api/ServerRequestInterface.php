<?php
namespace yuxblank\phackp\http\api;
interface ServerRequestInterface extends \Psr\Http\Message\ServerRequestInterface
{
    public function getPathParams();
    public function withPathParams(array $params);

}