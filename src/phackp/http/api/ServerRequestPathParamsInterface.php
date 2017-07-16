<?php
/**
 * Created by IntelliJ IDEA.
 * User: TheCo
 * Date: 16/07/2017
 * Time: 21:19
 */

namespace yuxblank\phackp\http\api;


interface ServerRequestPathParamsInterface
{

    public function withPathParams(array $params);
}