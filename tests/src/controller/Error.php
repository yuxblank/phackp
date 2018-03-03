<?php
/**
 * Created by IntelliJ IDEA.
 * User: yux
 * Date: 03/03/18
 * Time: 1.58
 */

namespace test\controller;


use http\Env\Response;
use yuxblank\phackp\core\Controller;
use yuxblank\phackp\routing\exception\RouterException;
use Zend\Diactoros\Response\JsonResponse;

class Error extends Controller
{
    public function onBefore()
    {
        // TODO: Implement onBefore() method.
    }

    public function onAfter()
    {
        // TODO: Implement onAfter() method.
    }

    public function error(\Throwable $param){

        return new JsonResponse(["error" => "500"]);
    }

    public function notFound(){

        return new JsonResponse(["error" => "not-found"]);

    }


}