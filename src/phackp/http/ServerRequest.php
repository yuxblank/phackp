<?php
/**
 * Created by IntelliJ IDEA.
 * User: TheCo
 * Date: 16/07/2017
 * Time: 12:40
 */

namespace yuxblank\phackp\http;


use yuxblank\phackp\http\api\ServerRequestInterface;

class ServerRequest extends \Zend\Diactoros\ServerRequest implements ServerRequestInterface
{
    private $pathParams;


    public function getPathParams(){
        return $this->pathParams;
    }

    public function withPathParams(array $params){
        $new = clone $this;
        $new->pathParams = $params;
        return $new;
    }


}