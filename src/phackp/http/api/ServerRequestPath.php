<?php
namespace yuxblank\phackp\http\api;
interface ServerRequestPath
{
    public function getPathParams();
    public function withPathParams(array $params);

}